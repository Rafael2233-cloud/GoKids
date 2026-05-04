<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
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
            'records',
            'users',
            'children',
            'totalRecords',
            'avgWeight',
            'avgHeight'
        ));
    }
    public function exportPdf(Request $request)
    {
        // 1. Ambil data dasar beserta relasinya
        $query = GrowthRecord::with(['child.user']);

        // 2. Terapkan filter yang sama persis seperti halaman index
        if ($request->filled('child_id')) {
            $query->where('child_id', $request->child_id);
        }
        if ($request->filled('month')) {
            $query->whereMonth('recorded_at', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('recorded_at', $request->year);
        }

        // Ambil semua data tanpa pagination
        $records = $query->get();

        // Siapkan variabel untuk dikirim ke view PDF
        $data = [
            'records' => $records,
            'totalRecords' => $records->count(),
            'avgWeight' => $records->avg('weight'),
            'avgHeight' => $records->avg('height'),
            'tanggalCetak' => now()->format('d M Y H:i'),
        ];

        // 3. Load view khusus PDF
        $pdf = Pdf::loadView('admin.laporan.pdf-template', $data);

        // 4. JALUR AMAN VERCEL: Gunakan download() atau stream(), jangan save()!
        return $pdf->download('Laporan_Tumbuh_Kembang_GoKids_' . date('Ymd') . '.pdf');
    }
}
