<?php

namespace App\Http\Controllers\Supabase;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;

class BaseSupabaseController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    protected function getTableData($table, $filters = [], $select = '*', $limit = null, $offset = null, $orderBy = null, $desc = false)
    {
        return $this->supabase->get($table, $filters, $select, $limit, $offset, $orderBy, $desc);
    }

    protected function createRecord($table, $data)
    {
        return $this->supabase->insert($table, $data);
    }

// ✅ ADD THIS METHOD
    protected function insertRecord($table, $data)
    {
        return $this->createRecord($table, $data);
    }
    // ✅ END OF NEW METHOD

    protected function updateRecord($table, $filters, $data)
    {
        return $this->supabase->update($table, $filters, $data);
    }

    protected function deleteRecord($table, $filters)
    {
        return $this->supabase->delete($table, $filters);
    }
}
