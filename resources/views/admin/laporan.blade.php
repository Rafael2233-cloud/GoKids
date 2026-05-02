@extends('layouts.admin')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary">RIWAYAT & LAPORAN</h1>
            <p class="text-gray-500 mt-1">Data pertumbuhan seluruh anak</p>
        </div>
        <button class="px-5 py-2.5 bg-accent text-white rounded-full font-medium text-sm hover:bg-accent/90 transition shadow-md">Ekspor PDF/Excel</button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-md p-4">
        <form action="{{ route('admin.laporan') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Pilih Anak</label>
                <select name="child_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white">
                    <option value="">Semua</option>
                    @foreach($children as $child)
                        <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>{{ $child->name }} ({{ $child->user->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
                <select name="month" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white">
                    <option value="">Semua</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-28">
                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                <select name="year" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white">
                    <option value="">Semua</option>
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-primary text-white rounded-full text-sm font-medium">Filter</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Anak</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Orang Tua</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Berat</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tinggi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">L. Kepala</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status Gizi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $i => $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $records->firstItem() + $i }}</td>
                        <td class="px-4 py-3 font-medium">{{ $r->child->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $r->child->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $r->weight }}</td>
                        <td class="px-4 py-3">{{ $r->height }}</td>
                        <td class="px-4 py-3">{{ $r->head_circumference ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs">{{ $r->recorded_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            @php $s = $r->nutritional_status; @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $s === 'Baik' ? 'bg-green-100 text-green-700' : ($s === 'Kurang' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">{{ $s }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="px-4 py-3 border-t bg-gray-50">{{ $records->appends(request()->query())->links() }}</div>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-md p-5 text-center">
            <p class="text-3xl font-bold text-primary">{{ $totalRecords }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Rekaman</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 text-center">
            <p class="text-3xl font-bold text-accent">{{ number_format($avgWeight ?? 0, 1) }} kg</p>
            <p class="text-sm text-gray-500 mt-1">Rata-rata Berat</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 text-center">
            <p class="text-3xl font-bold text-secondary">{{ number_format($avgHeight ?? 0, 1) }} cm</p>
            <p class="text-sm text-gray-500 mt-1">Rata-rata Tinggi</p>
        </div>
    </div>
</div>
@endsection
