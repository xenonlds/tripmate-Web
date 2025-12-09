<?php

namespace App\Http\Controllers\Supabase\BusinessOwner;


use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromotionController extends BaseSupabaseController
{
    protected $table = 'Promotion';

    public function getOwnerDetails()
    {
        $userId = session('user_id');
        $result = $this->getTableData('Bussiness_Owner', ['user_id' => 'eq.' . $userId]);
        return $result;
    }

    public function generateCode()
    {
        do {
            $code = strtoupper(Str::random(8));

            $existing = $this->getTableData(
                $this->table,
                ['code' => 'eq.' . $code],
                'code'
            );
        } while (!empty($existing));

        return response()->json(['code' => $code]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'promotionTitle' => 'required|string|max:255',
            'promotionType' => 'required|string|in:seasonal,spending,points,new_customer',
            'discountPercent' => 'required|numeric|min:0',
            'promotionCode' => 'required|string|max:50',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $promotion = $this->supabase->get('Promotion', [], 'promotion_id');
        $latestPromotion = collect($promotion)->sortByDesc('promotion_id')->first();
        $newNum = $latestPromotion ? str_pad(intval(substr($latestPromotion['promotion_id'], 3)) + 1, 5, '0', STR_PAD_LEFT) : '10000';
        $newPromotionId = 'PRO' . $newNum;

        $data = [
            'promotion_id' => $newPromotionId,
            'title' => $validated['promotionTitle'],
            'description' => $request->description,
            'type' => $validated['promotionType'],
            'discount_percent' => $validated['discountPercent'],
            'code' => $validated['promotionCode'],
            'start_date' => $validated['startDate'],
            'end_date' => $validated['endDate'],
            'status' => 'active',
            'owner_id' => $this->getOwnerDetails()[0]['owner_id'] ?? null,
        ];

        if ($request->promotionType === 'spending') {
            $data['min_spending'] = $request->minSpending ?? 0;
        } elseif ($request->promotionType === 'points') {
            $data['min_points'] = $request->minPoints ?? 0;
        } elseif ($request->promotionType === 'seasonal') {
            $data['season_name'] = $request->seasonName;
        }

        $result = $this->createRecord($this->table, $data);

        if (isset($result['error'])) {
            return redirect()->back()->with('error', 'Failed to create promotion: ' . $result['error']);
        }

        return redirect()->back()->with('success', 'Promotion created successfully!');
    }

    public function index()
    {
        $ownerId = $this->getOwnerDetails()[0]['owner_id'] ?? null;
        $promotions = $this->getTableData($this->table, ['owner_id' => 'eq.' . $ownerId], '*', null, null, 'promotion_id', true);
        return view('business_owner.promotion', compact('promotions'));
    }
}
