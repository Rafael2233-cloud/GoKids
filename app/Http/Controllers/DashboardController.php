<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Child;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $children = $user->children()->with('latestGrowth')->get();

        // Fetch published articles for dashboard (3 for initial view)
        $latestArticles = Article::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        // All published articles for "Lihat Lebih Banyak"
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

        return view('user.dashboard', compact('user', 'children', 'latestArticles', 'allArticles', 'upcomingVaccinations'));
    }

    public function showArticle(Article $article)
    {
        // Only show published articles to users
        if ($article->status !== 'published') {
            abort(404);
        }

        $user = Auth::user();

        // Get related articles (same category, exclude current)
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('user.artikel-detail', compact('user', 'article', 'relatedArticles'));
    }
}
