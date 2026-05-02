<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Child;
use App\Models\GrowthRecord;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalChildren = Child::count();
        $totalPublished = Article::published()->count();
        $totalDrafts = Article::draft()->count();

        $recentUsers = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Growth records per month (last 6 months)
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        $growthData = GrowthRecord::select(
                DB::raw('YEAR(recorded_at) as year'),
                DB::raw('MONTH(recorded_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('recorded_at', '>=', $sixMonthsAgo)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $chartLabels = [];
        $chartValues = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $months[$date->month - 1] . ' ' . $date->year;
            $found = $growthData->first(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });
            $chartValues[] = $found ? $found->total : 0;
        }

        return view('admin.dashboard', compact(
            'totalUsers', 'totalChildren', 'totalPublished', 'totalDrafts',
            'recentUsers', 'chartLabels', 'chartValues'
        ));
    }
}
