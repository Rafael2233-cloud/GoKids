@extends('layouts.user')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="/input" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-primary">Detail Anak</h1>
    </div>

    <!-- Child Info -->
    <div class="bg-white rounded-xl shadow-md p-6 flex items-center gap-5">
        <div class="w-20 h-20 rounded-full overflow-hidden bg-accent/10 flex items-center justify-center flex-shrink-0">
            @if($child->photo)
                <img src="{{ asset('storage/' . $child->photo) }}" alt="{{ $child->name }}" class="w-full h-full object-cover">
            @else
                <span class="text-3xl">{{ $child->gender === 'L' ? '👦' : '👧' }}</span>
            @endif
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ $child->name }}</h2>
            <p class="text-gray-500 text-sm">{{ $child->gender === 'L' ? 'Laki-laki' : 'Perempuan' }} • {{ $child->age }}</p>
            <p class="text-gray-400 text-xs mt-1">Lahir: {{ $child->birth_date->format('d F Y') }}</p>
        </div>
    </div>

    <!-- Growth Records -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Riwayat Pertumbuhan</h3>
        @if($child->growthRecords->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Berat (kg)</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Tinggi (cm)</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Lingkar Kepala (cm)</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($child->growthRecords->sortByDesc('recorded_at') as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-gray-600">{{ $record->recorded_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-gray-800 font-medium">{{ $record->weight }}</td>
                        <td class="px-4 py-2 text-gray-800 font-medium">{{ $record->height }}</td>
                        <td class="px-4 py-2 text-gray-800 font-medium">{{ $record->head_circumference ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-500 text-xs">{{ $record->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-center py-8">Belum ada data pertumbuhan.</p>
        @endif
    </div>

    <!-- Vaccination History -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">💉 Jadwal Vaksinasi</h3>
        @if($child->vaccinations->count() > 0)
        <div class="space-y-3">
            @foreach($child->vaccinations->sortBy('scheduled_date') as $vacc)
            <div class="flex items-center justify-between p-3 rounded-lg {{ $vacc->status === 'done' ? 'bg-green-50' : ($vacc->status === 'missed' ? 'bg-red-50' : 'bg-blue-50') }}">
                <div>
                    <h4 class="font-medium text-sm text-gray-800">{{ $vacc->vaccine_name }}</h4>
                    <p class="text-xs text-gray-500">{{ $vacc->scheduled_date->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium
                    {{ $vacc->status === 'done' ? 'bg-green-200 text-green-800' : ($vacc->status === 'missed' ? 'bg-red-200 text-red-800' : 'bg-blue-200 text-blue-800') }}">
                    {{ ucfirst($vacc->status) }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">Belum ada jadwal vaksinasi.</p>
        @endif
    </div>
</div>
@endsection
