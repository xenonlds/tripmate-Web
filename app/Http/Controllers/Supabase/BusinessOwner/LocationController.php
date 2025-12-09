<?php

namespace App\Http\Controllers\Supabase\BusinessOwner;

use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;

class LocationController extends BaseSupabaseController
{
    protected $table = 'Address';

    public function getOwnerDetails()
    {
        $userId = session('user_id');
        $result = $this->getTableData('Bussiness_Owner', ['user_id' => 'eq.' . $userId]);
        return $result;
    }

    public function index(Request $request)
    {
        $ownerId = $this->getOwnerDetails()[0]['owner_id'];

        $page = $request->query('page', 1);
        $limit = 4;
        $offset = ($page - 1) * $limit;

        $staffs = $this->getTableData(
            $this->table,
            ['owner_id' => 'eq.' . $ownerId],
            'staff_id,contact_number,department,country,address,user_id,owner_id,registration_date,User(*)',
            $limit,
            $offset,
            'staff_id',
            true
        );

        $allStaffs = $this->getTableData($this->table, ['owner_id' => 'eq.' . $ownerId]);
        $total = count($allStaffs);
        $totalPages = ceil($total / $limit);

        return view('business_owner.staff', compact('staffs', 'ownerId', 'page', 'totalPages', 'total', 'limit'));
    }

    public function show($staffId)
    {
        $staff = $this->getTableData($this->table, ['staff_id' => 'eq.' . $staffId], '*,User(*)');
        if (empty($staff)) {
            return response()->json(['error' => 'Staff not found'], 404);
        }
        return response()->json($staff[0]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'size' => 'nullable|string|max:255',
            'imageUrl' => 'nullable|string|max:255',
            'amenities' => 'nullable|array',
        ]);

        try {
            $address = $this->supabase->get('Address', [], 'address_id');
            $latestAddress = collect($address)->sortByDesc('address_id')->first();
            $newNum = $latestAddress ? str_pad(intval(substr($latestAddress['address_id'], 2)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newAddressId = 'ADRMY' . $newNum;

            $addressData = [
                'address_id'  => $newAddressId,
                'address'     => $validated['address'],
                'street'     => $validated['street'],
                'city'   => $validated['city'],
            ];

            $addressResponse = $this->supabase->insert('Address', $addressData);

            if (isset($addressResponse['error'])) {
                throw new \Exception($addressResponse['error']['message']);
            }

            $ownerDetails = $this->getOwnerDetails();
            $business_type = $ownerDetails[0]['type'] ?? null;
            if (!$business_type) {
                return redirect()->back()->with('error', 'Owner Type not found.');
            }


            if ($business_type == 'Hotel') {
                $hotel = $this->supabase->get('Hotel', [], 'hotel_id');
                $latestHotel = collect($hotel)->sortByDesc('hotel_id')->first();
                $newNums = $latestHotel ? str_pad(intval(substr($latestHotel['hotel_id'], 2)) + 1, 3, '0', STR_PAD_LEFT) : '001';
                $newHotelId = 'HOTMY' . $newNums;

                $busi = $this->supabase->get('Business', [], 'business_id');
                $latestBus = collect($busi)->sortByDesc('business_id')->first();
                $newNumss = $latestBus ? str_pad(intval(substr($latestBus['business_id'], 2)) + 1, 3, '0', STR_PAD_LEFT) : '001';
                $newBusinessId = 'BUS' . $newNumss;

                $hotelData = [
                    'hotel_id'  => $newHotelId,
                    'name'     => $ownerDetails[0]['business_name'],
                    'status'     =>'active',
                    'size'   => $validated['size'],
                    'amenities' => $ $request->amenities,
                    'imageUrl' => $validated['imageUrl'],
                    'created_at' => now(),
                ];

                 $businessData = [
                    'business_id'  => $newBusinessId,
                    'hotel_id'     =>$newHotelId ,
                    'owner_id'   => $ownerDetails[0]['owner_id'],
                ];

                $stateData = [
                    'hotel_id'  => $newHotelId,
                    'owner_id'   => $ownerDetails[0]['owner_id'],
                ];

                $this->supabase->insert('Hotel', $hotelData);
                $this->supabase->insert('Business', $businessData);
                $this->supabase->insert('AddressState', $stateData);


            } else {
                //future business types can be handled here
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', ' Error: ' . $e->getMessage());
        }
    }



    public function update(Request $request, $staffId)
    {
        $data = [
            'contact_number' => $request->input('phone'),
            'department' => $request->input('department'),
            'address' => $request->input('address'),
        ];

        $this->updateRecord($this->table, ['staff_id' => $staffId], $data);

        return redirect()->back()->with('success', 'Staff updated successfully sohai.');
    }


    public function destroy($staffId)
    {
        $this->deleteRecord($this->table, ['staff_id' => $staffId]);
        return redirect()->back()->with('success', 'Staff deleted successfully.');
    }
}
