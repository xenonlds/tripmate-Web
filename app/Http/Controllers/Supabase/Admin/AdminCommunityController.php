<?php

namespace App\Http\Controllers\Supabase\Admin;

use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;

class AdminCommunityController extends BaseSupabaseController
{
    protected $communityTable = 'Community';
    protected $touristTable = 'Tourists';
    protected $reportTable = 'post_reports';
    protected $commentTable = 'Post_Comments';
    protected $interactionTable = 'Post_Interactions';

    /* ------------------------------------------------------
     * INDEX — LIST POSTS (Pagination + Search)
     * ------------------------------------------------------ */
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $page   = $request->query('page', 1);
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        // 1. Get ALL posts (Supabase has no JOIN)
        $allPosts = $this->getTableData($this->communityTable);

        // 2. Apply search filter manually
        if ($search) {
            $allPosts = array_filter($allPosts, function($post) use ($search) {
                return stripos($post['title'], $search) !== false;
            });
        }

        // 3. Pagination
        $totalPosts = count($allPosts);
        $totalPages = ceil($totalPosts / $limit);

        $posts = array_slice(array_values($allPosts), $offset, $limit);

        // 4. Attach extra info (tourist name, report count)
        foreach ($posts as &$post) {
            // Get tourist
            $tourist = $this->getTableData($this->touristTable, ['tourist_id' => "eq.{$post['tourist_id']}"]);
            $post['tourist_name']  = $tourist[0]['name']  ?? 'Unknown';
            $post['tourist_email'] = $tourist[0]['email'] ?? '';

            // Report count
            $reports = $this->getTableData($this->reportTable, ['postid' => "eq.{$post['postID']}"]);
            $post['report_count'] = count($reports);
        }

        if ($request->ajax()) {
            return response()->json([
                'posts'       => $posts,
                'currentPage' => $page,
                'totalPages'  => $totalPages,
            ]);
        }

        $adminName = session('name', 'Admin User');
        return view('admin.CommunityManagement', compact('adminName', 'posts', 'page', 'totalPages'));
    }

    /* ------------------------------------------------------
     * SHOW SINGLE POST (details)
     * ------------------------------------------------------ */
    public function show($postId)
    {
        $post = $this->getTableData($this->communityTable, ['postID' => "eq.$postId"]);
        if (empty($post)) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $post = $post[0];

        // Tourist
        $tourist = $this->getTableData($this->touristTable, ['tourist_id' => "eq.{$post['tourist_id']}"]);

        $post['tourist_name']  = $tourist[0]['name']  ?? 'Unknown';
        $post['tourist_email'] = $tourist[0]['email'] ?? '';

        // Reports
        $reports = $this->getTableData($this->reportTable, ['postid' => "eq.$postId"]);

        // Comment count
        $commentCount = count($this->getTableData($this->commentTable, ['postID' => "eq.$postId"]));

        return response()->json([
            'post'          => $post,
            'reports'       => $reports,
            'commentsCount' => $commentCount
        ]);
    }

    /* ------------------------------------------------------
     * UPDATE POST STATUS (active / hidden / removed)
     * ------------------------------------------------------ */
    public function updateStatus(Request $request, $postId)
    {
        $status = $request->status;

        $update = [
            'is_hidden' => $status === 'hidden',
            'status'    => $status === 'removed' ? 'inactive' : 'active',
        ];

        $this->updateRecord($this->communityTable, ['postID' => $postId], $update);

        return response()->json(['success' => true, 'message' => 'Status updated']);
    }

    /* ------------------------------------------------------
     * DELETE POST
     * ------------------------------------------------------ */
    public function destroy($postId)
    {
        // Delete main post
        $this->deleteRecord($this->communityTable, ['postID' => $postId]);

        // Delete related items
        $this->deleteRecord($this->interactionTable, ['postID' => $postId]);
        $this->deleteRecord($this->commentTable, ['postID' => $postId]);
        $this->deleteRecord($this->reportTable, ['postid' => $postId]);

        return response()->json(['success' => true, 'message' => 'Post deleted']);
    }

    /* ------------------------------------------------------
     * UPDATE REPORT
     * ------------------------------------------------------ */
    public function updateReport(Request $request, $reportId)
    {
        $this->updateRecord($this->reportTable, ['report_id' => $reportId], [
            'status'      => $request->status,
            'reviewed_at' => now()->toIso8601String(),
            'reviewer_id' => session('user_id')
        ]);

        return response()->json(['success' => true, 'message' => 'Report updated']);
    }
}
