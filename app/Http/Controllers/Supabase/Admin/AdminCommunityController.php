<?php

namespace App\Http\Controllers\Supabase\Admin;

use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminCommunityController extends BaseSupabaseController
{
    protected $communityTable = 'Community';
    protected $touristTable = 'Tourists';
    protected $reportTable = 'post_reports';
    protected $commentTable = 'Post_Comments';
    protected $interactionTable = 'Post_Interactions';
    protected $userTable = 'User';
    protected $warningTable = 'content_warnings';
    protected $blockedUsersTable = 'blocked_users';

    /* ------------------------------------------------------
     * FUNCTION 1: CONTENT CRUD
     * ------------------------------------------------------ */

    public function index(Request $request)
    {
        try {
            $search = $request->query('search', '');
            $statusFilter = $request->query('status', ''); // NEW: status filter
            $page   = $request->query('page', 1);
            $limit  = 10;
            $offset = ($page - 1) * $limit;

            // Get ALL posts (including hidden ones for admin)
            $allPosts = $this->getTableData($this->communityTable);

            if (!is_array($allPosts)) {
                $allPosts = [];
            }

            // Apply search filter
            if ($search) {
                $allPosts = array_filter($allPosts, function($post) use ($search) {
                    $searchLower = strtolower($search);
                    return stripos($post['title'] ?? '', $searchLower) !== false ||
                           stripos($post['description'] ?? '', $searchLower) !== false;
                });
            }

            // Apply status filter
            if ($statusFilter) {
                $allPosts = array_filter($allPosts, function($post) use ($statusFilter) {
                    if ($statusFilter === 'hidden') {
                        // Show posts hidden by admin OR by user
                        return (isset($post['hidden_by_admin']) && $post['hidden_by_admin'] === true) ||
                               (isset($post['is_hidden']) && $post['is_hidden'] === true);
                    } elseif ($statusFilter === 'active') {
                        // Show posts that are not hidden by admin AND not hidden by user
                        return (!isset($post['hidden_by_admin']) || $post['hidden_by_admin'] === false) &&
                               (!isset($post['is_hidden']) || $post['is_hidden'] === false);
                    }
                    return true;
                });
            }

            // Sort by created_at descending
            usort($allPosts, function($a, $b) {
                $timeA = strtotime($a['created_at'] ?? '1970-01-01');
                $timeB = strtotime($b['created_at'] ?? '1970-01-01');
                return $timeB - $timeA;
            });

            // Pagination
            $totalPosts = count($allPosts);
            $totalPages = ceil($totalPosts / $limit);

            $posts = array_slice(array_values($allPosts), $offset, $limit);

            // OPTIMIZATION: Fetch all related data in bulk to avoid N+1 queries
            if (!empty($posts)) {
                // Get unique tourist IDs and post IDs
                $touristIds = array_unique(array_column($posts, 'tourist_id'));
                $postIds = array_column($posts, 'postID');

                // Fetch all tourists at once
                $allTourists = $this->getTableData($this->touristTable);
                $touristMap = [];
                foreach ($allTourists as $tourist) {
                    $touristMap[$tourist['tourist_id']] = $tourist;
                }

                // Get all user IDs from tourists
                $userIds = array_filter(array_column($allTourists, 'user_id'));

                // Fetch all users at once
                $allUsers = !empty($userIds) ? $this->getTableData($this->userTable) : [];
                $userMap = [];
                foreach ($allUsers as $user) {
                    $userMap[$user['user_id']] = $user;
                }

                // Fetch all blocked users at once
                $allBlocked = $this->getTableData($this->blockedUsersTable, ['is_active' => 'eq.true']);
                $blockedMap = [];
                foreach ($allBlocked as $blocked) {
                    $blockedMap[$blocked['tourist_id']] = true;
                }

                // Fetch all reports at once
                $allReports = $this->getTableData($this->reportTable);
                $reportCountMap = [];
                foreach ($allReports as $report) {
                    $postId = $report['postid'];
                    $reportCountMap[$postId] = ($reportCountMap[$postId] ?? 0) + 1;
                }

                // Fetch all warnings at once
                $allWarnings = $this->getTableData($this->warningTable);
                $warningCountMap = [];
                foreach ($allWarnings as $warning) {
                    $postId = $warning['post_id'];
                    $warningCountMap[$postId] = ($warningCountMap[$postId] ?? 0) + 1;
                }

                // Now attach the data using the maps (no more queries!)
                foreach ($posts as &$post) {
                    $touristId = $post['tourist_id'];

                    // Get tourist name
                    $post['tourist_name'] = $touristMap[$touristId]['name'] ?? 'Unknown';

                    // Get tourist email
                    $userId = $touristMap[$touristId]['user_id'] ?? null;
                    $post['tourist_email'] = $userId && isset($userMap[$userId]) ? $userMap[$userId]['email'] : '';

                    // Check if blocked
                    $post['is_blocked'] = isset($blockedMap[$touristId]);

                    // Get counts
                    $post['report_count'] = $reportCountMap[$post['postID']] ?? 0;
                    $post['warning_count'] = $warningCountMap[$post['postID']] ?? 0;
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'posts'       => $posts,
                    'currentPage' => $page,
                    'totalPages'  => $totalPages,
                ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
            }

            $adminName = session('name', 'Admin User');
            return view('admin.CommunityManagement', compact('adminName', 'posts', 'page', 'totalPages'));

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@index: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Failed to load posts',
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to load posts');
        }
    }

    public function show(Request $request, $postId)
    {
        try {
            Log::info("Show post called for postId: $postId");
            Log::info("Request expects JSON: " . ($request->expectsJson() ? 'yes' : 'no'));
            Log::info("Request headers: " . json_encode($request->headers->all()));

            $post = $this->getTableData($this->communityTable, ['postID' => "eq.$postId"]);

            Log::info("Post query result: " . json_encode($post));

            if (empty($post)) {
                Log::warning("Post not found: $postId");
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Post not found'], 404);
                }
                return redirect()->route('admin.community.index')->with('error', 'Post not found');
            }
            $post = $post[0];

            // Tourist info
            $tourist = $this->getTableData($this->touristTable, ['tourist_id' => "eq.{$post['tourist_id']}"]);
            $post['tourist_name']  = $tourist[0]['name']  ?? 'Unknown';

            if (!empty($tourist) && isset($tourist[0]['user_id'])) {
                $user = $this->getTableData($this->userTable, ['user_id' => "eq.{$tourist[0]['user_id']}"]);
                $post['tourist_email'] = $user[0]['email'] ?? '';
            } else {
                $post['tourist_email'] = '';
            }

            // Check if blocked
            $blocked = $this->getTableData($this->blockedUsersTable, [
                'tourist_id' => "eq.{$post['tourist_id']}",
                'is_active' => 'eq.true'
            ]);
            $post['is_blocked'] = !empty($blocked);

            // Reports
            $reports = $this->getTableData($this->reportTable, ['postid' => "eq.$postId"]);
            if (!is_array($reports)) {
                $reports = [];
            }

            // Warnings with admin names
            $warnings = $this->getTableData($this->warningTable, ['post_id' => "eq.$postId"]);
            if (!is_array($warnings)) {
                $warnings = [];
            }

            foreach ($warnings as &$warning) {
                $admin = $this->getTableData($this->userTable, ['user_id' => "eq.{$warning['issued_by']}"]);
                $warning['admin_name'] = $admin[0]['name'] ?? 'Admin';
            }

            // Comment count
            $comments = $this->getTableData($this->commentTable, ['postID' => "eq.$postId"]);
            $commentsCount = is_array($comments) ? count($comments) : 0;

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'post'          => $post,
                    'reports'       => $reports,
                    'warnings'      => $warnings,
                    'commentsCount' => $commentsCount
                ]);
            }

            Log::info("Returning view for post: $postId");

            // Return view for browser requests
            return view('admin.CommunityPostDetail', compact('post', 'reports', 'warnings', 'commentsCount'));

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@show: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Failed to load post'], 500);
            }
            return redirect()->route('admin.community.index')->with('error', 'Failed to load post: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $postId)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:100',
                'description' => 'nullable|string|max:255',
            ], [
                'title.required' => 'Post title is required',
                'title.min' => 'Post title must be at least 3 characters',
                'title.max' => 'Post title cannot exceed 100 characters',
                'description.max' => 'Post description cannot exceed 255 characters',
            ]);

            // Check if post exists
            $post = $this->getTableData($this->communityTable, ['postID' => "eq.$postId"]);
            if (empty($post)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found'
                ], 404);
            }

            // Prepare update data
            $update = [
                'title' => trim($validated['title']),
                'description' => isset($validated['description']) ? trim($validated['description']) : null,
                'updated_at' => now()->toIso8601String()
            ];

            // Update the post
            $this->updateRecord($this->communityTable, ['postID' => $postId], $update);

            Log::info("Post updated successfully: $postId by admin " . session('user_id'));

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'post' => array_merge($post[0], $update)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in CommunityController@update: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post. Please try again later.'
            ], 500);
        }
    }

    public function updateStatus(Request $request, $postId)
    {
        try {
            $status = $request->status;

            // Admin controls hidden_by_admin, not is_hidden (which is for user privacy)
            $update = [
                'hidden_by_admin' => $status === 'hidden',
                'status'    => $status === 'removed' ? 'inactive' : 'active',
            ];

            // Optional: Track reason for admin hiding
            if ($status === 'hidden' && $request->has('reason')) {
                $update['admin_hide_reason'] = $request->reason;
            } elseif ($status === 'active') {
                $update['admin_hide_reason'] = null;
            }

            $this->updateRecord($this->communityTable, ['postID' => $postId], $update);

            Log::info("Post $postId status updated by admin: $status");

            return response()->json(['success' => true, 'message' => 'Status updated']);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@updateStatus: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($postId)
    {
        try {
            // Delete main post
            $this->deleteRecord($this->communityTable, ['postID' => $postId]);

            // Delete related items
            $this->deleteRecord($this->interactionTable, ['postID' => $postId]);
            $this->deleteRecord($this->commentTable, ['postID' => $postId]);
            $this->deleteRecord($this->reportTable, ['postid' => $postId]);
            $this->deleteRecord($this->warningTable, ['post_id' => $postId]);

            return response()->json(['success' => true, 'message' => 'Post deleted']);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@destroy: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ------------------------------------------------------
     * NEW: CREATE POST AS ADMIN
     * ------------------------------------------------------ */

    public function create()
    {
        $adminName = session('name', 'Admin User');
        return view('admin.CreateCommunityPost', compact('adminName'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:100',
                'description' => 'nullable|string|max:255',
                'images' => 'nullable|array|max:4',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
            ]);

            // Get or create admin tourist ID
            $adminTouristId = $this->getAdminTouristId();

            // Generate post ID
            $posts = $this->supabase->get('Community', []);
            $latestPost = collect($posts)->sortByDesc('postID')->first();
            $newNum = $latestPost ? str_pad(intval(substr($latestPost['postID'], 1)) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $newPostId = 'P' . $newNum;

            // Handle image uploads
            $imageUrls = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    // Store image in public/storage/community_posts
                    $filename = $newPostId . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('community_posts', $filename, 'public');
                    $imageUrls[] = asset('storage/' . $path);
                }
            }

            // Prepare images JSON
            $images = !empty($imageUrls) ? json_encode($imageUrls) : null;

            // Create post
            $postData = [
                'postID' => $newPostId,
                'title' => $request->title,
                'description' => $request->description,
                'Images' => $images,
                'tourist_id' => $adminTouristId,
                'status' => 'active',
                'like_count' => 0,
                'is_hidden' => false,
                'hidden_by_admin' => false,
                'admin_hide_reason' => null,
                'created_at' => now()->toIso8601String()
            ];

            $response = $this->supabase->insert('Community', $postData);

            if (isset($response['error'])) {
                throw new \Exception($response['error']['message'] ?? 'Failed to create post');
            }

            return redirect()->route('admin.community.index')
                ->with('success', 'Post created successfully as TripMate Admin!');

        } catch (\Exception $e) {
            Log::error('Error creating admin post: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get or create admin tourist ID for posting
     */
    private function getAdminTouristId()
    {
        // Check if admin tourist exists
        $adminTourists = $this->supabase->get('Tourists', ['name' => 'eq.TripMate Admin']);

        if (!empty($adminTourists)) {
            return $adminTourists[0]['tourist_id'];
        }

        // Create admin user if doesn't exist
        $userId = session('user_id', 'ADMIN_USER');

        // Check if user exists in Tourists table
        $existingTourist = $this->supabase->get('Tourists', ['user_id' => 'eq.' . $userId]);

        if (!empty($existingTourist)) {
            return $existingTourist[0]['tourist_id'];
        }

        // Generate new tourist ID for admin
        $tourists = $this->supabase->get('Tourists', []);
        $latestTourist = collect($tourists)->sortByDesc('tourist_id')->first();
        $newNum = $latestTourist ? str_pad(intval(substr($latestTourist['tourist_id'], 1)) + 1, 3, '0', STR_PAD_LEFT) : '999';
        $adminTouristId = 'T' . $newNum;

        // Create admin tourist record
        $touristData = [
            'tourist_id' => $adminTouristId,
            'user_id' => $userId,
            'name' => 'TripMate Admin',
            'phone_number' => null,
            'age' => null,
            'gender' => null,
            'nationality' => 'Malaysia',
            'birthdate' => null,
            'profile_image' => null
        ];

        $this->supabase->insert('Tourists', $touristData);

        return $adminTouristId;
    }

    /* ------------------------------------------------------
     * FUNCTION 2: BLOCKING CONTENT (User Blocking)
     * ------------------------------------------------------ */

    public function blockUser(Request $request)
    {
        try {
            $request->validate([
                'tourist_id' => 'required|string',
                'reason' => 'required|string',
                'duration_days' => 'nullable|integer|min:1',
            ]);

            $touristId = $request->tourist_id;
            $reason = $request->reason;
            $details = $request->details ?? '';
            $durationDays = $request->duration_days ?? null;

            // Check if already blocked
            $existing = $this->getTableData($this->blockedUsersTable, [
                'tourist_id' => "eq.$touristId",
                'is_active' => 'eq.true'
            ]);

            if (!empty($existing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already blocked'
                ], 400);
            }

            // Calculate expiry date
            $expiresAt = $durationDays
                ? now()->addDays($durationDays)->toIso8601String()
                : null;

            // Create block record
            $blockData = [
                'block_id' => $this->generateId('BLK'),
                'tourist_id' => $touristId,
                'blocked_by' => session('user_id'),
                'reason' => $reason,
                'details' => $details,
                'blocked_at' => now()->toIso8601String(),
                'expires_at' => $expiresAt,
                'is_active' => true,
            ];

            $this->insertRecord($this->blockedUsersTable, $blockData);

            // Update user status
            $user = $this->getTableData($this->touristTable, ['tourist_id' => "eq.$touristId"]);
            if (!empty($user) && isset($user[0]['user_id'])) {
                $userId = $user[0]['user_id'];
                $this->updateRecord($this->userTable, ['user_id' => $userId], [
                    'status' => 'blocked'
                ]);
            }

            // Hide all user's posts using admin hide (cannot be unhidden by user)
            $userPosts = $this->getTableData($this->communityTable, ['tourist_id' => "eq.$touristId"]);
            if (is_array($userPosts)) {
                foreach ($userPosts as $post) {
                    $this->updateRecord($this->communityTable, ['postID' => $post['postID']], [
                        'hidden_by_admin' => true,
                        'admin_hide_reason' => 'User blocked by admin',
                        'status' => 'inactive'
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User blocked successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@blockUser: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to block user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function unblockUser(Request $request)
    {
        try {
            $request->validate([
                'tourist_id' => 'required|string',
            ]);

            $touristId = $request->tourist_id;

            // Deactivate block
            $blocks = $this->getTableData($this->blockedUsersTable, [
                'tourist_id' => "eq.$touristId",
                'is_active' => 'eq.true'
            ]);

            if (is_array($blocks)) {
                foreach ($blocks as $block) {
                    $this->updateRecord($this->blockedUsersTable, ['block_id' => $block['block_id']], [
                        'is_active' => false,
                        'unblocked_at' => now()->toIso8601String(),
                        'unblocked_by' => session('user_id')
                    ]);
                }
            }

            // Update user status
            $user = $this->getTableData($this->touristTable, ['tourist_id' => "eq.$touristId"]);
            if (!empty($user) && isset($user[0]['user_id'])) {
                $userId = $user[0]['user_id'];
                $this->updateRecord($this->userTable, ['user_id' => $userId], [
                    'status' => 'active'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User unblocked successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@unblockUser: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to unblock user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBlockedUsers()
    {
        try {
            $blockedUsers = $this->getTableData($this->blockedUsersTable, ['is_active' => 'eq.true']);

            if (!is_array($blockedUsers)) {
                $blockedUsers = [];
            }

            foreach ($blockedUsers as &$block) {
                $tourist = $this->getTableData($this->touristTable, ['tourist_id' => "eq.{$block['tourist_id']}"]);
                $block['tourist_name'] = $tourist[0]['name'] ?? 'Unknown';

                if (!empty($tourist) && isset($tourist[0]['user_id'])) {
                    $user = $this->getTableData($this->userTable, ['user_id' => "eq.{$tourist[0]['user_id']}"]);
                    $block['tourist_email'] = $user[0]['email'] ?? '';
                } else {
                    $block['tourist_email'] = '';
                }
            }

            return response()->json([
                'success' => true,
                'blocked_users' => $blockedUsers
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@getBlockedUsers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load blocked users: ' . $e->getMessage(),
                'blocked_users' => []
            ], 500);
        }
    }

    /* ------------------------------------------------------
     * FUNCTION 3: WARNING CONTENT
     * ------------------------------------------------------ */

    public function issueWarning(Request $request)
    {
        try {
            $request->validate([
                'post_id' => 'required|string',
                'warning_type' => 'required|string|in:minor,moderate,severe',
                'reason' => 'required|string',
                'details' => 'nullable|string',
            ]);

            $postId = $request->post_id;
            $warningType = $request->warning_type;
            $reason = $request->reason;
            $details = $request->details ?? '';

            // Get post info
            $post = $this->getTableData($this->communityTable, ['postID' => "eq.$postId"]);
            if (empty($post)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found'
                ], 404);
            }
            $post = $post[0];
            $touristId = $post['tourist_id'];

            // Create warning record
            $warningData = [
                'warning_id' => $this->generateId('WARN'),
                'post_id' => $postId,
                'tourist_id' => $touristId,
                'warning_type' => $warningType,
                'reason' => $reason,
                'details' => $details,
                'issued_by' => session('user_id'),
                'issued_at' => now()->toIso8601String(),
                'status' => 'active',
            ];

            $this->insertRecord($this->warningTable, $warningData);

            // Check warning count for this user
            $userWarnings = $this->getTableData($this->warningTable, [
                'tourist_id' => "eq.$touristId",
                'status' => 'eq.active'
            ]);
            $warningCount = is_array($userWarnings) ? count($userWarnings) : 0;

            // Auto-action based on warning count
            $action = '';
            if ($warningCount >= 3) {
                // 3+ warnings = Block user
                $blockRequest = new Request([
                    'tourist_id' => $touristId,
                    'reason' => 'Accumulated 3 or more warnings',
                    'details' => 'Automatic block due to multiple violations',
                    'duration_days' => 30
                ]);
                $this->blockUser($blockRequest);
                $action = 'User blocked due to multiple warnings';
            } elseif ($warningCount >= 2) {
                // 2 warnings = Hide post (admin hide, cannot be overridden by user)
                $this->updateRecord($this->communityTable, ['postID' => $postId], [
                    'hidden_by_admin' => true,
                    'admin_hide_reason' => 'Automatically hidden after 2nd warning'
                ]);
                $action = 'Post hidden by admin (user cannot unhide)';
            } else {
                // 1 warning = Just warn
                $action = 'Warning issued';
            }

            return response()->json([
                'success' => true,
                'message' => "Warning issued successfully. {$action}",
                'warning_count' => $warningCount,
                'action_taken' => $action
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@issueWarning: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to issue warning: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPostWarnings($postId)
    {
        try {
            $warnings = $this->getTableData($this->warningTable, ['post_id' => "eq.$postId"]);

            if (!is_array($warnings)) {
                $warnings = [];
            }

            foreach ($warnings as &$warning) {
                $admin = $this->getTableData($this->userTable, ['user_id' => "eq.{$warning['issued_by']}"]);
                $warning['admin_name'] = $admin[0]['name'] ?? 'Admin';
            }

            return response()->json([
                'success' => true,
                'warnings' => $warnings
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@getPostWarnings: ' . $e->getMessage());
            return response()->json(['success' => false, 'warnings' => []], 500);
        }
    }

    public function getUserWarnings($touristId)
    {
        try {
            $warnings = $this->getTableData($this->warningTable, [
                'tourist_id' => "eq.$touristId"
            ]);

            if (!is_array($warnings)) {
                $warnings = [];
            }

            foreach ($warnings as &$warning) {
                // Get post info
                $post = $this->getTableData($this->communityTable, ['postID' => "eq.{$warning['post_id']}"]);
                $warning['post_title'] = $post[0]['title'] ?? 'Deleted Post';

                // Get admin info
                $admin = $this->getTableData($this->userTable, ['user_id' => "eq.{$warning['issued_by']}"]);
                $warning['admin_name'] = $admin[0]['name'] ?? 'Admin';
            }

            return response()->json([
                'success' => true,
                'warnings' => $warnings,
                'total_warnings' => count($warnings)
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@getUserWarnings: ' . $e->getMessage());
            return response()->json(['success' => false, 'warnings' => []], 500);
        }
    }

    public function dismissWarning(Request $request, $warningId)
    {
        try {
            $this->updateRecord($this->warningTable, ['warning_id' => $warningId], [
                'status' => 'dismissed',
                'dismissed_by' => session('user_id'),
                'dismissed_at' => now()->toIso8601String(),
                'dismissal_reason' => $request->reason ?? 'Resolved'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Warning dismissed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@dismissWarning: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ------------------------------------------------------
     * REPORT MANAGEMENT
     * ------------------------------------------------------ */

    public function updateReport(Request $request, $reportId)
    {
        try {
            $status = $request->status;
            $action = $request->action ?? null;

            // Update report status
            $this->updateRecord($this->reportTable, ['report_id' => $reportId], [
                'status'      => $status,
                'reviewed_at' => now()->toIso8601String(),
                'reviewer_id' => session('user_id')
            ]);

            // Get report details
            $report = $this->getTableData($this->reportTable, ['report_id' => "eq.$reportId"]);
            if (empty($report)) {
                return response()->json(['error' => 'Report not found'], 404);
            }
            $report = $report[0];
            $postId = $report['postid'];

            // Take action based on admin decision
            if ($action === 'warn') {
                $warnRequest = new Request([
                    'post_id' => $postId,
                    'warning_type' => 'moderate',
                    'reason' => $report['reason'],
                    'details' => 'Issued from report: ' . ($report['details'] ?? '')
                ]);
                $this->issueWarning($warnRequest);
            } elseif ($action === 'block') {
                $post = $this->getTableData($this->communityTable, ['postID' => "eq.$postId"]);
                if (!empty($post)) {
                    $blockRequest = new Request([
                        'tourist_id' => $post[0]['tourist_id'],
                        'reason' => 'Reported: ' . $report['reason'],
                        'details' => $report['details'] ?? '',
                        'duration_days' => 7
                    ]);
                    $this->blockUser($blockRequest);
                }
            } elseif ($action === 'hide') {
                $this->updateRecord($this->communityTable, ['postID' => $postId], [
                    'is_hidden' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Report processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CommunityController@updateReport: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ------------------------------------------------------
     * HELPER METHODS
     * ------------------------------------------------------ */

    private function generateId($prefix)
    {
        return $prefix . '_' . time() . '_' . rand(1000, 9999);
    }
}
