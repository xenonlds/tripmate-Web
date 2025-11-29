<?php

namespace App\Http\Controllers\Supabase\Admin;

use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;

class AdminStaffController extends BaseSupabaseController
{
    protected $table = 'User';
    protected $staffTable = 'Bussiness_Staff';

    /**
     * Display a listing of staff users with pagination
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = 10; // Items per page
        $offset = ($page - 1) * $limit;

        // Get staff users with pagination
        $staffUsers = $this->getTableData(
            $this->table,
            ['role' => 'eq.staff'],
            '*',
            $limit,
            $offset,
            'created_at',
            true // descending order
        );

        // Get total count for pagination
        $allStaff = $this->getTableData($this->table, ['role' => 'eq.staff']);
        $total = count($allStaff);
        $totalPages = ceil($total / $limit);

        return view('admin.StaffManagement', compact('staffUsers', 'page', 'totalPages', 'total', 'limit'));
    }

    /**
     * Show the form for creating a new staff
     */
    public function create()
    {
        return view('admin.NewStaff');
    }

    /**
     * Get staff details by user_id for editing
     */
    public function edit($userId)
    {
        try {
            // Get user data
            $staffUser = $this->getTableData($this->table, ['user_id' => 'eq.' . $userId, 'role' => 'eq.staff']);

            if (empty($staffUser)) {
                return redirect()->route('admin.staff.index')->with('error', 'Staff not found.');
            }

            $staff = $staffUser[0];

            // Get staff details from Bussiness_Staff table
            $staffDetailsData = $this->getTableData($this->staffTable, ['user_id' => 'eq.' . $userId]);
            $staffDetails = !empty($staffDetailsData) ? $staffDetailsData[0] : [];

            return view('admin.UpdateStaff', compact('staff', 'staffDetails'));
        } catch (\Exception $e) {
            return redirect()->route('admin.staff.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display staff details (for API/AJAX)
     */
    public function show($userId)
    {
        $staff = $this->getTableData($this->table, ['user_id' => 'eq.' . $userId, 'role' => 'eq.staff']);

        if (empty($staff)) {
            return response()->json(['error' => 'Staff not found'], 404);
        }

        // Get staff details
        $staffDetails = $this->getTableData($this->staffTable, ['user_id' => 'eq.' . $userId]);

        return response()->json([
            'user' => $staff[0],
            'details' => !empty($staffDetails) ? $staffDetails[0] : null
        ]);
    }

    /**
     * Create a new staff user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'contact_number' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'IC' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            // Check if email already exists
            $existingUser = $this->getTableData($this->table, ['email' => 'eq.' . $validated['email']]);
            if (!empty($existingUser)) {
                return redirect()->back()->withInput()->with('error', 'Email already exists.');
            }

            // Generate new user_id
            $users = $this->getTableData($this->table, [], 'user_id');
            $latestUser = collect($users)->sortByDesc('user_id')->first();
            $newNum = $latestUser ? str_pad(intval(substr($latestUser['user_id'], 2)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newUserId = 'UU' . $newNum;

            // Create user record
            $userData = [
                'user_id' => $newUserId,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => 'staff',
                'status' => $validated['status'],
                'password' => password_hash($validated['password'], PASSWORD_BCRYPT),
                'created_at' => now()->toIso8601String(),
                'login_attempts' => 0,
            ];

            $userResponse = $this->supabase->insert('User', $userData);

            if (isset($userResponse['error'])) {
                throw new \Exception($userResponse['error']['message'] ?? 'Failed to create user');
            }

            // Generate staff_id
            $allStaff = $this->getTableData($this->staffTable, [], 'staff_id');
            $latestStaff = collect($allStaff)->sortByDesc('staff_id')->first();
            $newStaffNum = $latestStaff ? str_pad(intval(substr($latestStaff['staff_id'], 2)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newStaffId = 'BS' . $newStaffNum;

            // Create staff details record
            $staffData = [
                'staff_id' => $newStaffId,
                'user_id' => $newUserId,
                'contact_number' => $validated['contact_number'] ?? null,
                'department' => $validated['department'] ?? null,
                'IC' => $validated['IC'] ?? null,
                'country' => $validated['country'] ?? null,
                'address' => $validated['address'] ?? null,
                'registration_date' => now()->toIso8601String(),
            ];

            $staffResponse = $this->supabase->insert('Bussiness_Staff', $staffData);

            if (isset($staffResponse['error'])) {
                // Rollback: delete the user if staff creation fails
                $this->deleteRecord($this->table, ['user_id' => $newUserId]);
                throw new \Exception($staffResponse['error']['message'] ?? 'Failed to create staff details');
            }

            return redirect()->route('admin.staff.index')->with('success', "Staff {$newUserId} created successfully!");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update staff user information
     */
    public function update(Request $request, $userId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'nullable|string|min:8|confirmed',
            'contact_number' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'IC' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            // Check if email already exists (excluding current user)
            $existingUser = $this->getTableData($this->table, ['email' => 'eq.' . $validated['email']]);
            if (!empty($existingUser) && $existingUser[0]['user_id'] !== $userId) {
                return redirect()->back()->withInput()->with('error', 'Email already exists.');
            }

            // Update user data
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ];

            // Add password if provided
            if (!empty($validated['password'])) {
                $userData['password'] = password_hash($validated['password'], PASSWORD_BCRYPT);
            }

            $this->updateRecord($this->table, ['user_id' => $userId, 'role' => 'eq.staff'], $userData);

            // Update or create staff details
            $staffData = [
                'contact_number' => $validated['contact_number'] ?? null,
                'department' => $validated['department'] ?? null,
                'IC' => $validated['IC'] ?? null,
                'country' => $validated['country'] ?? null,
                'address' => $validated['address'] ?? null,
            ];

            // Check if staff details exist
            $existingStaff = $this->getTableData($this->staffTable, ['user_id' => 'eq.' . $userId]);

            if (!empty($existingStaff)) {
                // Update existing staff details
                $this->updateRecord($this->staffTable, ['user_id' => $userId], $staffData);
            } else {
                // Create new staff details if not exists
                $allStaff = $this->getTableData($this->staffTable, [], 'staff_id');
                $latestStaff = collect($allStaff)->sortByDesc('staff_id')->first();
                $newStaffNum = $latestStaff ? str_pad(intval(substr($latestStaff['staff_id'], 2)) + 1, 3, '0', STR_PAD_LEFT) : '001';
                $newStaffId = 'BS' . $newStaffNum;

                $staffData['staff_id'] = $newStaffId;
                $staffData['user_id'] = $userId;
                $staffData['registration_date'] = now()->toIso8601String();

                $this->supabase->insert('Bussiness_Staff', $staffData);
            }

            return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Delete staff user (soft delete by changing status)
     */
    public function destroy($userId)
    {
        try {
            // Instead of hard delete, update status to inactive
            $this->updateRecord($this->table, ['user_id' => $userId, 'role' => 'eq.staff'], ['status' => 'inactive']);

            return redirect()->back()->with('success', 'Staff deactivated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete staff user
     */
    public function hardDelete($userId)
    {
        try {
            // Delete staff details first
            $this->deleteRecord($this->staffTable, ['user_id' => $userId]);

            // Then delete user
            $this->deleteRecord($this->table, ['user_id' => $userId, 'role' => 'eq.staff']);

            return redirect()->back()->with('success', 'Staff deleted permanently.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Search and filter staff users
     */
    public function search(Request $request)
    {
        $searchTerm = $request->query('search', '');
        $status = $request->query('status', '');

        $filters = ['role' => 'eq.staff'];

        if ($status) {
            $filters['status'] = 'eq.' . $status;
        }

        $staffUsers = $this->getTableData($this->table, $filters);

        // Filter by search term (name or email)
        if ($searchTerm) {
            $staffUsers = array_filter($staffUsers, function($staff) use ($searchTerm) {
                return stripos($staff['name'], $searchTerm) !== false ||
                       stripos($staff['email'], $searchTerm) !== false;
            });
        }

        return response()->json($staffUsers);
    }

    /**
     * Toggle staff status (active/inactive)
     */
    public function toggleStatus($userId)
    {
        try {
            $staff = $this->getTableData($this->table, ['user_id' => 'eq.' . $userId, 'role' => 'eq.staff']);

            if (empty($staff)) {
                return redirect()->back()->with('error', 'Staff not found.');
            }

            $newStatus = $staff[0]['status'] === 'active' ? 'inactive' : 'active';

            $this->updateRecord($this->table, ['user_id' => $userId], ['status' => $newStatus]);

            return redirect()->back()->with('success', "Staff status updated to {$newStatus}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
