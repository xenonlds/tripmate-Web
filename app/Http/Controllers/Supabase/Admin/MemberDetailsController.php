<?php

namespace App\Http\Controllers\Supabase\Admin;

use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MemberDetailsController extends BaseSupabaseController
{
    protected $table = 'Tourists';
    protected $userTable = 'User';
    protected $communityTable = 'Community';
    protected $bookingTable = 'Booking';
    protected $tripTable = 'TripPlan';

    /**
     * Display detailed member information with posts
     */
    public function show($touristId)
    {
        try {
            // Get tourist data
            $tourist = $this->supabase->get('Tourists', ['tourist_id' => 'eq.' . $touristId]);

            if (empty($tourist)) {
                return redirect()->route('admin.member.index')
                    ->with('error', 'Member not found.');
            }

            $member = $tourist[0];

            // Get user data
            $userId = $member['user_id'] ?? null;
            $userData = ['email' => 'N/A', 'status' => 'inactive', 'created_at' => null, 'last_login' => null];

            if ($userId) {
                try {
                    $userResult = $this->supabase->get('User', ['user_id' => 'eq.' . $userId]);
                    if (is_array($userResult) && !empty($userResult)) {
                        $userData = $userResult[0];
                    }
                } catch (\Exception $e) {
                    Log::error('User data error: ' . $e->getMessage());
                }
            }

            $member['User'] = $userData;

            // Get community posts created by this tourist
            $posts = [];
            try {
                $postsResult = $this->supabase->get('Community', ['tourist_id' => 'eq.' . $touristId]);
                if (is_array($postsResult)) {
                    // Sort by created_at descending
                    usort($postsResult, function($a, $b) {
                        $timeA = strtotime($a['created_at'] ?? '1970-01-01');
                        $timeB = strtotime($b['created_at'] ?? '1970-01-01');
                        return $timeB - $timeA;
                    });
                    $posts = $postsResult;
                }
            } catch (\Exception $e) {
                Log::error('Posts loading error: ' . $e->getMessage());
            }

            // Get booking statistics
            $bookingStats = $this->getBookingStatistics($touristId);

            // Get trip statistics
            $tripStats = $this->getTripStatistics($touristId);

            // Get activity statistics
            $activityStats = $this->getActivityStatistics($touristId);

            $adminName = session('name', 'Admin User');

            return view('admin.MemberDetails', [
                'member' => $member,
                'posts' => $posts,
                'bookingStats' => $bookingStats,
                'tripStats' => $tripStats,
                'activityStats' => $activityStats,
                'adminName' => $adminName
            ]);

        } catch (\Exception $e) {
            Log::error('Member details error: ' . $e->getMessage());
            return redirect()->route('admin.member.index')
                ->with('error', 'Error loading member details: ' . $e->getMessage());
        }
    }

    /**
     * Get booking statistics for a tourist
     */
    private function getBookingStatistics($touristId)
    {
        $stats = [
            'total_bookings' => 0,
            'active_bookings' => 0,
            'completed_bookings' => 0,
            'cancelled_bookings' => 0,
            'total_spent' => 0
        ];

        try {
            $bookings = $this->supabase->get('Booking', ['tourist_id' => 'eq.' . $touristId]);

            if (is_array($bookings)) {
                $stats['total_bookings'] = count($bookings);

                foreach ($bookings as $booking) {
                    $status = strtolower($booking['status'] ?? '');

                    if ($status === 'active' || $status === 'confirmed') {
                        $stats['active_bookings']++;
                    } elseif ($status === 'completed') {
                        $stats['completed_bookings']++;
                    } elseif ($status === 'cancelled') {
                        $stats['cancelled_bookings']++;
                    }

                    $stats['total_spent'] += floatval($booking['totalPrice'] ?? 0);
                }
            }
        } catch (\Exception $e) {
            Log::error('Booking stats error: ' . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Get trip statistics for a tourist
     */
    private function getTripStatistics($touristId)
    {
        $stats = [
            'total_trips' => 0,
            'upcoming_trips' => 0,
            'completed_trips' => 0,
            'shared_trips' => 0
        ];

        try {
            $trips = $this->supabase->get('TripPlan', ['tourist_id' => 'eq.' . $touristId]);

            if (is_array($trips)) {
                $stats['total_trips'] = count($trips);
                $now = time();

                foreach ($trips as $trip) {
                    $startDate = strtotime($trip['startDate'] ?? '');
                    $endDate = strtotime($trip['endDate'] ?? '');

                    if ($startDate > $now) {
                        $stats['upcoming_trips']++;
                    } elseif ($endDate < $now) {
                        $stats['completed_trips']++;
                    }

                    if (isset($trip['isShared']) && $trip['isShared']) {
                        $stats['shared_trips']++;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Trip stats error: ' . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Get activity statistics for a tourist
     */
    private function getActivityStatistics($touristId)
    {
        $stats = [
            'total_posts' => 0,
            'active_posts' => 0,
            'hidden_posts' => 0,
            'total_likes' => 0,
            'total_comments' => 0
        ];

        try {
            // Get posts
            $posts = $this->supabase->get('Community', ['tourist_id' => 'eq.' . $touristId]);

            if (is_array($posts)) {
                $stats['total_posts'] = count($posts);

                foreach ($posts as $post) {
                    // Count as hidden if hidden by user OR by admin
                    $isHidden = (isset($post['is_hidden']) && $post['is_hidden']) ||
                                (isset($post['hidden_by_admin']) && $post['hidden_by_admin']);

                    if ($isHidden) {
                        $stats['hidden_posts']++;
                    } else {
                        $stats['active_posts']++;
                    }

                    $stats['total_likes'] += intval($post['like_count'] ?? 0);
                }
            }

            // Get comments made by tourist
            try {
                $comments = $this->supabase->get('Post_Comments', ['tourist_id' => 'eq.' . $touristId]);
                if (is_array($comments)) {
                    $stats['total_comments'] = count($comments);
                }
            } catch (\Exception $e) {
                Log::error('Comments count error: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error('Activity stats error: ' . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Toggle post visibility (hide/unhide) - Admin control
     */
    public function togglePostVisibility(Request $request, $postId)
    {
        try {
            $post = $this->supabase->get('Community', ['postID' => 'eq.' . $postId]);

            if (empty($post)) {
                return response()->json(['error' => 'Post not found'], 404);
            }

            // Admin toggles hidden_by_admin, not is_hidden (user privacy)
            $currentStatus = $post[0]['hidden_by_admin'] ?? false;
            $newStatus = !$currentStatus;

            $updateData = [
                'hidden_by_admin' => $newStatus
            ];

            // Add/remove reason
            if ($newStatus) {
                $updateData['admin_hide_reason'] = $request->reason ?? 'Hidden by admin';
            } else {
                $updateData['admin_hide_reason'] = null;
            }

            $this->supabase->update('Community',
                ['postID' => 'eq.' . $postId],
                $updateData
            );

            return response()->json([
                'success' => true,
                'hidden_by_admin' => $newStatus,
                'message' => $newStatus ? 'Post hidden by admin successfully' : 'Post unhidden successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a community post
     */
    public function deletePost($postId)
    {
        try {
            $post = $this->supabase->get('Community', ['postID' => 'eq.' . $postId]);

            if (empty($post)) {
                return redirect()->back()->with('error', 'Post not found.');
            }

            // Delete associated comments first
            try {
                $this->supabase->delete('Post_Comments', ['postID' => 'eq.' . $postId]);
            } catch (\Exception $e) {
                Log::error('Error deleting comments: ' . $e->getMessage());
            }

            // Delete post interactions
            try {
                $this->supabase->delete('Post_Interactions', ['postID' => 'eq.' . $postId]);
            } catch (\Exception $e) {
                Log::error('Error deleting interactions: ' . $e->getMessage());
            }

            // Delete the post
            $this->supabase->delete('Community', ['postID' => 'eq.' . $postId]);

            return redirect()->back()->with('success', 'Post deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting post: ' . $e->getMessage());
        }
    }

    /**
     * Export member data as JSON
     */
    public function exportMemberData($touristId)
    {
        try {
            // Get all member data
            $tourist = $this->supabase->get('Tourists', ['tourist_id' => 'eq.' . $touristId]);

            if (empty($tourist)) {
                return response()->json(['error' => 'Member not found'], 404);
            }

            $member = $tourist[0];

            // Get user data
            if (isset($member['user_id'])) {
                $userResult = $this->supabase->get('User', ['user_id' => 'eq.' . $member['user_id']]);
                $member['User'] = !empty($userResult) ? $userResult[0] : null;
            }

            // Get posts
            $posts = $this->supabase->get('Community', ['tourist_id' => 'eq.' . $touristId]);
            $member['Posts'] = $posts ?? [];

            // Get bookings
            $bookings = $this->supabase->get('Booking', ['tourist_id' => 'eq.' . $touristId]);
            $member['Bookings'] = $bookings ?? [];

            // Get trips
            $trips = $this->supabase->get('TripPlan', ['tourist_id' => 'eq.' . $touristId]);
            $member['Trips'] = $trips ?? [];

            $fileName = 'member_' . $touristId . '_' . date('Y-m-d') . '.json';

            return response()->json($member, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
