<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Child;
use App\Models\Vaccination;
use App\Models\GrowthRecord;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $children = $user->children()->with('latestGrowth')->get();

        $latestArticles = Article::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $allArticles = Article::published()
            ->latest('published_at')
            ->get();

        $upcomingVaccinations = Vaccination::whereIn('child_id', $children->pluck('id'))
            ->where('status', 'upcoming')
            ->where('scheduled_date', '>=', now())
            ->orderBy('scheduled_date')
            ->with('child')
            ->take(5)
            ->get();

        // Stats
        $childCount = $children->count();
        
        // Pemeriksaan bulan ini
        $checkupsThisMonth = GrowthRecord::whereIn('child_id', $children->pluck('id'))
            ->whereMonth('recorded_at', now()->month)
            ->whereYear('recorded_at', now()->year)
            ->count();
        
        // Artikel dibaca (dummy - perlu fitur tracking)
        $articlesRead = 0;
        
        // Growth data untuk chart
        $growthData = [];
        if ($children->count() > 0) {
            $firstChild = $children->first();
            $records = GrowthRecord::where('child_id', $firstChild->id)
                ->orderBy('recorded_at')
                ->take(10)
                ->get();
            
            $growthData = [
                'labels' => $records->pluck('recorded_at')->map(fn($d) => $d->format('d M'))->toArray(),
                'weight' => $records->pluck('weight')->map(fn($w) => (float) $w)->toArray(),
                'height' => $records->pluck('height')->map(fn($h) => (float) $h)->toArray(),
                'child_name' => $firstChild->name,
            ];
        }

        $totalMilestones = Milestone::whereIn('child_id', $children->pluck('id'))->count();
        $achievedMilestones = Milestone::whereIn('child_id', $children->pluck('id'))->where('is_achieved', true)->count();

        $totalGrowthRecords = GrowthRecord::whereIn('child_id', $children->pluck('id'))->count();
        $avgWeight = $totalGrowthRecords > 0 ? GrowthRecord::whereIn('child_id', $children->pluck('id'))->avg('weight') : 0;
        $avgHeight = $totalGrowthRecords > 0 ? GrowthRecord::whereIn('child_id', $children->pluck('id'))->avg('height') : 0;
        $vaccineUpcomingCount = $upcomingVaccinations->count();

        return view('user.dashboard', compact(
            'user', 
            'children', 
            'latestArticles', 
            'allArticles', 
            'upcomingVaccinations',
            'growthData',
            'totalMilestones',
            'achievedMilestones',
            'childCount',
            'checkupsThisMonth',
            'articlesRead',
            'totalGrowthRecords',
            'avgWeight',
            'avgHeight',
            'vaccineUpcomingCount'
        ));
    }

    public function showArticle(Article $article)
    {
        if ($article->status !== 'published') {
            abort(404);
        }

        $user = Auth::user();

        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedArticles->count() < 3) {
            $moreArticles = Article::published()
                ->where('id', '!=', $article->id)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->latest('published_at')
                ->take(3 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->merge($moreArticles);
        }

        return view('user.artikel-detail', compact('user', 'article', 'relatedArticles'));
    }
}
