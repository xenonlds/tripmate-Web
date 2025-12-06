<?php

namespace App\Http\Controllers\Supabase\Admin;

use App\Http\Controllers\Supabase\BaseSupabaseController;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminAnalyticsController extends BaseSupabaseController
{
    protected string $businessOwnerTable = 'Business_Owner';
    protected string $communityTable     = 'Community';
    protected string $reportTable        = 'post_reports';
    protected string $interactionTable   = 'Post_Interactions';
    protected string $commentTable       = 'Post_Comments';

    /**
     * GET /admin/analytics
     * Returns JSON analytics data for the selected month range
     */
    public function index(Request $request)
    {
        $startMonth = $request->query('start');
        $endMonth   = $request->query('end');

        // Default: last 6 months if not provided
        if (!$startMonth || !$endMonth) {
            $end   = now()->startOfMonth();
            $start = (clone $end)->subMonths(5);

            $startMonth = $start->format('Y-m');
            $endMonth   = $end->format('Y-m');
        }

        $months = $this->generateMonths($startMonth, $endMonth);

        // Pull raw data from Supabase
        $businessOwners = $this->getTableData($this->businessOwnerTable);
        $posts          = $this->getTableData($this->communityTable);
        $reports        = $this->getTableData($this->reportTable);
        $interactions   = $this->getTableData($this->interactionTable);
        $comments       = $this->getTableData($this->commentTable);

        // Aggregate
        $businessJoining = $this->countByMonth($businessOwners, 'registration_date', $months);
        $contentRate     = $this->countByMonth($posts, 'created_at', $months);

        $likesCount    = count(array_filter($interactions, fn($x) => !empty($x['isLiked']) && $x['isLiked']));
        $commentsCount = count($comments);
        $sharesCount   = 0; // You can change later when you track shares

        $spamCount = count(array_filter($reports, fn($r) => isset($r['reason']) && strtolower($r['reason']) === 'spam'));
        $harassmentCount = count(array_filter($reports, fn($r) => isset($r['reason']) && strtolower($r['reason']) === 'harassment'));
        $misinfoCount = count(array_filter($reports, fn($r) => isset($r['reason']) && strtolower($r['reason']) === 'misinformation'));
        $otherCount = count(array_filter($reports, function ($r) {
            if (!isset($r['reason'])) return false;
            $reason = strtolower($r['reason']);
            return !in_array($reason, ['spam', 'harassment', 'misinformation']);
        }));

        return response()->json([
            'months' => $months,
            'businessJoining' => $businessJoining,
            'contentRate'     => $contentRate,
            'engagement'      => [
                'likes'    => $likesCount,
                'comments' => $commentsCount,
                'shares'   => $sharesCount,
            ],
            'reported'        => [
                'spam'           => $spamCount,
                'harassment'     => $harassmentCount,
                'misinformation' => $misinfoCount,
                'other'          => $otherCount,
            ],
        ]);
    }

    /**
     * POST /admin/analytics/export-pdf
     * Expects:
     *  - start, end (Y-m)
     *  - analytics (JSON string from /admin/analytics)
     *  - 4 chart images (base64)
     */
    public function exportPdf(Request $request)
    {
        $start     = $request->input('start');
        $end       = $request->input('end');
        $analytics = json_decode($request->input('analytics'), true);

        $charts = [
            'businessChart'   => $request->input('businessChart'),
            'contentChart'    => $request->input('contentChart'),
            'engagementChart' => $request->input('engagementChart'),
            'reportedChart'   => $request->input('reportedChart'),
        ];

        $pdf = Pdf::loadView('admin.pdf.analytics_report', [
            'start'     => $start,
            'end'       => $end,
            'analytics' => $analytics,
            'charts'    => $charts,
            'adminName' => session('name', 'Admin User'),
            'generated' => now()->format('Y-m-d H:i:s'),
        ])->setPaper('a4', 'portrait');

        $filename = "TripMate-Analytics-Report-{$start}-to-{$end}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Helper: generate array of yyyy-mm between start & end inclusive
     */
    private function generateMonths(string $start, string $end): array
    {
        $startDate = new \DateTime("{$start}-01");
        $endDate   = (new \DateTime("{$end}-01"))->modify('+1 month');

        $period = new \DatePeriod($startDate, new \DateInterval('P1M'), $endDate);

        $months = [];
        foreach ($period as $d) {
            $months[] = $d->format('Y-m');
        }

        return $months;
    }

    /**
     * Helper: count rows per month based on timestamp column
     */
    private function countByMonth(array $rows, string $field, array $months): array
    {
        $result = [];

        foreach ($months as $month) {
            $count = 0;

            foreach ($rows as $row) {
                if (!isset($row[$field]) || empty($row[$field])) {
                    continue;
                }

                // Works for ISO timestamp strings from Supabase
                $rowMonth = substr($row[$field], 0, 7); // yyyy-mm

                if ($rowMonth === $month) {
                    $count++;
                }
            }

            $result[] = $count;
        }

        return $result;
    }
}
