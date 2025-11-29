<?php

namespace App\Http\Controllers\Supabase\Admin;

use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;

class AdminMemberController extends BaseSupabaseController
{
    protected $table = 'Tourists';
    protected $userTable = 'User';

    /**
     * Display a listing of tourist members with pagination
     */
    public function index(Request $request)
    {
        // Initialize default values
        $members = [];
        $page = $request->query('page', 1);
        $limit = 10;
        $total = 0;
        $totalPages = 1;
        $adminName = session('name', 'Admin User');

        try {
            $offset = ($page - 1) * $limit;

            // Get all tourists
            $allTourists = $this->supabase->get('Tourists', []);

            if (is_array($allTourists)) {
                $total = count($allTourists);
                $totalPages = $total > 0 ? ceil($total / $limit) : 1;

                // Get paginated slice
                $paginatedTourists = array_slice($allTourists, $offset, $limit);

                // Fetch user data for each tourist
                foreach ($paginatedTourists as $tourist) {
                    $userId = $tourist['user_id'] ?? null;
                    $userData = ['email' => 'N/A', 'status' => 'inactive', 'last_login' => null];

                    if ($userId) {
                        try {
                            $userResult = $this->supabase->get('User', ['user_id' => 'eq.' . $userId]);
                            if (is_array($userResult) && !empty($userResult)) {
                                $userData = $userResult[0];
                            }
                        } catch (\Exception $e) {
                            // Continue with default userData
                        }
                    }

                    $tourist['User'] = $userData;
                    $members[] = $tourist;
                }
            }
        } catch (\Exception $e) {
            // Log the error but continue with empty members
            \Log::error('Member loading error: ' . $e->getMessage());
        }

        return view('admin.MemberManagement', [
            'members' => $members,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'limit' => $limit,
            'adminName' => $adminName
        ]);
    }

    /**
     * Show the form for creating a new member
     */
    public function create()
    {
        return view('admin.NewMember');
    }

    /**
     * Show the form for editing a member
     */
    public function edit($touristId)
    {
        try {
            // Get tourist data
            $tourist = $this->supabase->get('Tourists', ['tourist_id' => 'eq.' . $touristId]);

            if (empty($tourist)) {
                return redirect()->route('admin.member.index')->with('error', 'Member not found.');
            }

            $member = $tourist[0];

            // Get user data
            $userId = $member['user_id'] ?? null;
            if ($userId) {
                $userResult = $this->supabase->get('User', ['user_id' => 'eq.' . $userId]);
                $member['User'] = !empty($userResult) ? $userResult[0] : null;
            }

            return view('admin.UpdateMember', compact('member'));
        } catch (\Exception $e) {
            return redirect()->route('admin.member.index')->with('error', 'Error loading member: ' . $e->getMessage());
        }
    }

    /**
     * Get member details by tourist_id
     */
    public function show($touristId)
    {
        $member = $this->getTableData($this->table, ['tourist_id' => 'eq.' . $touristId], '*,User(*)');

        if (empty($member)) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        return response()->json($member[0]);
    }

    /**
     * Create a new tourist member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string',
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|in:Male,Female,Other',
            'nationality' => 'nullable|string|max:50',
            'birthdate' => 'nullable|date',
        ]);

        try {
            // Check if email already exists
            $existingUser = $this->supabase->get('User', ['email' => 'eq.' . $validated['email']]);
            if (!empty($existingUser)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Email already exists in the system.');
            }

            // Generate new user_id
            $users = $this->supabase->get('User', []);
            $latestUser = collect($users)->sortByDesc('user_id')->first();
            $newNum = $latestUser ? str_pad(intval(substr($latestUser['user_id'], 2)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newUserId = 'UU' . $newNum;

            // Create User record
            $userData = [
                'user_id' => $newUserId,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => 'tourist',
                'status' => 'active',
                'password' => bcrypt($validated['password']),
                'created_at' => now()->toIso8601String(),
                'login_attempts' => 0,
            ];

            $userResponse = $this->supabase->insert('User', $userData);

            if (isset($userResponse['error'])) {
                throw new \Exception($userResponse['error']['message'] ?? 'Failed to create user');
            }

            // Generate tourist_id
            $tourists = $this->supabase->get('Tourists', []);
            $latestTourist = collect($tourists)->sortByDesc('tourist_id')->first();
            $newTouristNum = $latestTourist ? str_pad(intval(substr($latestTourist['tourist_id'], 1)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newTouristId = 'T' . $newTouristNum;

            // Create Tourist record
            $touristData = [
                'tourist_id' => $newTouristId,
                'user_id' => $newUserId,
                'name' => $validated['name'],
                'phone_number' => $validated['phone_number'] ?? null,
                'age' => $validated['age'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'nationality' => $validated['nationality'] ?? null,
                'birthdate' => $validated['birthdate'] ? date('Y-m-d', strtotime($validated['birthdate'])) . 'T00:00:00Z' : null,
                'profile_image' => null,
            ];

            $touristResponse = $this->supabase->insert('Tourists', $touristData);

            if (isset($touristResponse['error'])) {
                // Rollback: Delete the user record
                $this->supabase->delete('User', ['user_id' => 'eq.' . $newUserId]);
                throw new \Exception($touristResponse['error']['message'] ?? 'Failed to create tourist');
            }

            return redirect()->route('admin.member.index')->with('success', "Member {$newTouristId} created successfully!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update member information
     */
    public function update(Request $request, $touristId)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'phone_number' => 'sometimes|string',
            'age' => 'sometimes|integer|min:1|max:120',
            'gender' => 'sometimes|in:Male,Female,Other',
            'nationality' => 'sometimes|string|max:50',
            'birthdate' => 'sometimes|date',
        ]);

        try {
            $touristData = array_filter($validated); // Remove null values

            // Update Tourist record
            $this->updateRecord($this->table, ['tourist_id' => $touristId], $touristData);

            // If name is updated, also update in User table
            if (isset($validated['name'])) {
                $tourist = $this->getTableData($this->table, ['tourist_id' => 'eq.' . $touristId]);
                if (!empty($tourist)) {
                    $userId = $tourist[0]['user_id'];
                    $this->updateRecord($this->userTable, ['user_id' => $userId], ['name' => $validated['name']]);
                }
            }

            return redirect()->back()->with('success', 'Member updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete member (update status in User table)
     */
    public function destroy($touristId)
    {
        try {
            // Get tourist to find associated user_id
            $tourist = $this->getTableData($this->table, ['tourist_id' => 'eq.' . $touristId]);

            if (empty($tourist)) {
                return redirect()->back()->with('error', 'Member not found.');
            }

            $userId = $tourist[0]['user_id'];

            // Update user status to inactive
            $this->updateRecord($this->userTable, ['user_id' => $userId], ['status' => 'inactive']);

            return redirect()->back()->with('success', 'Member deactivated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete member
     */
    public function hardDelete($touristId)
    {
        try {
            // Get tourist to find associated user_id
            $tourist = $this->getTableData($this->table, ['tourist_id' => 'eq.' . $touristId]);

            if (empty($tourist)) {
                return redirect()->back()->with('error', 'Member not found.');
            }

            $userId = $tourist[0]['user_id'];

            // Delete tourist record
            $this->deleteRecord($this->table, ['tourist_id' => $touristId]);

            // Delete user record
            $this->deleteRecord($this->userTable, ['user_id' => $userId]);

            return redirect()->back()->with('success', 'Member deleted permanently.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Toggle member status (active/inactive)
     */
    public function toggleStatus($touristId)
    {
        try {
            $tourist = $this->getTableData($this->table, ['tourist_id' => 'eq.' . $touristId], '*,User(*)');

            if (empty($tourist)) {
                return redirect()->back()->with('error', 'Member not found.');
            }

            $userId = $tourist[0]['user_id'];
            $currentStatus = $tourist[0]['User']['status'] ?? 'inactive';
            $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';

            $this->updateRecord($this->userTable, ['user_id' => $userId], ['status' => $newStatus]);

            return redirect()->back()->with('success', "Member status updated to {$newStatus}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Search and filter members
     */
    public function search(Request $request)
    {
        $searchTerm = $request->query('search', '');
        $gender = $request->query('gender', '');
        $nationality = $request->query('nationality', '');

        $filters = [];

        if ($gender) {
            $filters['gender'] = 'eq.' . $gender;
        }

        if ($nationality) {
            $filters['nationality'] = 'eq.' . $nationality;
        }

        $members = $this->getTableData($this->table, $filters, '*,User(*)');

        // Filter by search term (name, phone, email)
        if ($searchTerm) {
            $members = array_filter($members, function($member) use ($searchTerm) {
                return stripos($member['name'], $searchTerm) !== false ||
                       stripos($member['phone_number'], $searchTerm) !== false ||
                       (isset($member['User']['email']) && stripos($member['User']['email'], $searchTerm) !== false);
            });
        }

        return response()->json($members);
    }

    /**
     * Get member statistics
     */
    public function statistics()
    {
        try {
            $allMembers = $this->getTableData($this->table, [], '*,User(*)');

            $stats = [
                'total_members' => count($allMembers),
                'active_members' => count(array_filter($allMembers, function($m) {
                    return isset($m['User']['status']) && $m['User']['status'] === 'active';
                })),
                'inactive_members' => count(array_filter($allMembers, function($m) {
                    return isset($m['User']['status']) && $m['User']['status'] === 'inactive';
                })),
                'by_gender' => [
                    'male' => count(array_filter($allMembers, fn($m) => ($m['gender'] ?? '') === 'Male')),
                    'female' => count(array_filter($allMembers, fn($m) => ($m['gender'] ?? '') === 'Female')),
                    'other' => count(array_filter($allMembers, fn($m) => ($m['gender'] ?? '') === 'Other')),
                ],
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
