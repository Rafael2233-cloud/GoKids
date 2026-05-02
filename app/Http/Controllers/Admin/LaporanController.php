<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\GrowthRecord;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'user')->with('children')->get();

        $query = GrowthRecord::with(['child.user']);

        // Filter by child
        if ($request->filled('child_id')) {
            $query->where('child_id', $request->child_id);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('recorded_at', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('recorded_at', $request->year);
        }

        $records = $query->orderBy('recorded_at', 'desc')->paginate(15);

        // Statistics
        $totalRecords = $query->count();
        $avgWeight = GrowthRecord::avg('weight');
        $avgHeight = GrowthRecord::avg('height');

        $children = Child::with('user')->get();

        return view('admin.laporan', compact(
            'records', 'users', 'children', 'totalRecords', 'avgWeight', 'avgHeight'
        ));
    }
}
