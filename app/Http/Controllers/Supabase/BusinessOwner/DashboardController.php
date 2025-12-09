<?php

namespace App\Http\Controllers\Supabase\BusinessOwner;
use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;

class DashboardController extends BaseSupabaseController
{
    protected $table = 'Bussiness_Owner';

    public function getOwnerDetails()
    {
        $userId = session('user_id');

        $owners = $this->getTableData($this->table, ['user_id' => 'eq.' . $userId]);

        $name = $owners[0]['business_name'] ?? '';

        session(['business_name' => $name]);

        return view('business_owner.dashboard');
    }



}
