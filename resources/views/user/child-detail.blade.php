@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 pb-10" x-data="{ showGrowthModal: false, showVaccineModal: false }">
    
    {{-- Kiri: Sidebar Stats & BMI --}}
    <div class="lg:col-span-4 space-y-6">
        {{-- Ringkasan Parameter --}}
        <div class="space-y-4">
            @php
                $latest = $child->latestGrowth;
                $bmi = $child->bmi;
                $status = $child->nutritional_status;
                $statusColor = match($status) {
                    'Normal' => 'green',
                    'Stunting' => 'red',
                    'Obesitas' => 'orange',
                    default => 'gray'
                };
            @endphp
            
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl">⚖️</div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase">Berat Badan</p>
                        <p class="text-xl font-extrabold text-gray-800">{{ $latest ? $latest->weight : '-' }} <span class="text-sm font-medium">kg</span></p>
                    </div>
                </div>
                @if($latest)
                <span class="text-green-500 font-bold text-xs">+{{ $latest->weight - ($child->growthRecords->skip(1)->first()?->weight ?? $latest->weight) }}</span>
                @endif
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-2xl">📏</div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase">Tinggi Badan</p>
                        <p class="text-xl font-extrabold text-gray-800">{{ $latest ? $latest->height : '-' }} <span class="text-sm font-medium">cm</span></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-2xl">🧠</div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase">Lingkar Kepala</p>
                        <p class="text-xl font-extrabold text-gray-800">{{ $latest && $latest->head_circumference ? $latest->head_circumference : '-' }} <span class="text-sm font-medium">cm</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Indeks Massa Tubuh (BMI) --}}
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Indeks Massa Tubuh (BMI)</h3>
                <span class="bg-{{ $statusColor }}-100 text-{{ $statusColor }}-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                    {{ $status ?? 'Belum Ada Data' }}
                </span>
            </div>
            <div class="flex items-end gap-3">
                <span class="text-5xl font-black text-{{ $statusColor }}-600">{{ $bmi ?? '-' }}</span>
                <span class="text-gray-400 text-sm mb-1 font-medium">kg/m²</span>
            </div>
            <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                <p class="text-xs text-blue-700 leading-relaxed font-medium">
                    @if($status === 'Normal')
                        ✨ <strong>Rekomendasi:</strong> Pertahankan pola makan sehat dan aktivitas fisik yang seimbang.
                    @elseif($status === 'Stunting')
                        ⚠️ <strong>Rekomendasi:</strong> Konsultasikan dengan dokter untuk penanganan nutrisi yang tepat.
                    @elseif($status === 'Obesitas')
                        ⚠️ <strong>Rekomendasi:</strong> Kurangi makanan tinggi gula dan lemak, tambah aktivitas fisik.
                    @else
                        📝 <strong>Catatan:</strong> Input data pertumbuhan untuk melihat status BMI.
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Kanan: Grafik Pertumbuhan --}}
    <div class="lg:col-span-8 space-y-6">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-bold text-gray-800">Grafik Berat Badan - {{ $child->name }}</h3>
                <div class="flex gap-2">
                    <button class="px-4 py-1.5 rounded-lg text-xs font-bold bg-blue-600 text-white shadow-md">6 Bulan</button>
                    <button class="px-4 py-1.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-400">1 Tahun</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-2 gap-4">
            <button @click="showGrowthModal = true" class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-blue-400 transition flex items-center justify-center gap-2">
                <span class="text-xl">📊</span>
                <span class="font-bold text-gray-700">Input Pertumbuhan</span>
            </button>
            <button @click="showVaccineModal = true" class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-purple-400 transition flex items-center justify-center gap-2">
                <span class="text-xl">💉</span>
                <span class="font-bold text-gray-700">Input Vaksin</span>
            </button>
        </div>

        {{-- Riwayat Pertumbuhan --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Riwayat Pertumbuhan</h3>
            <div class="space-y-3">
                @forelse($child->growthRecords->take(5) as $record)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $record->recorded_at->translatedFormat('d F Y') }}</p>
                        <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                            <span>⚖️ {{ $record->weight }} kg</span>
                            <span>📏 {{ $record->height }} cm</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                        {{ $record->nutritional_status }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada data pertumbuhan</p>
                @endforelse
            </div>
        </div>

        {{-- Riwayat Vaksinasi --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Riwayat Vaksinasi</h3>
            <div class="space-y-3">
                @forelse($child->vaccinations->take(5) as $vaccine)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $vaccine->vaccine_name }}</p>
                        <p class="text-sm text-gray-500">📅 {{ $vaccine->scheduled_date->translatedFormat('d F Y') }}</p>
                    </div>
                    @php
                        $badgeClass = match($vaccine->status) {
                            'done' => 'bg-green-100 text-green-700',
                            'upcoming' => 'bg-blue-100 text-blue-700',
                            'missed' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                        $label = match($vaccine->status) {
                            'done' => 'Selesai',
                            'upcoming' => 'Akan Datang',
                            'missed' => 'Terlewat',
                            default => $vaccine->status
                        };
                    @endphp
                    <span class="px-3 py-1 {{ $badgeClass }} text-xs font-medium rounded-full">
                        {{ $label }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada jadwal vaccinations</p>
                @endforelse
            </div>
        </div>

        {{-- Status Kesehatan Card --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-3xl p-8 text-white flex items-center justify-between shadow-xl">
            <div class="space-y-4">
                <h3 class="text-xl font-bold">Status Kesehatan {{ $child->name }}</h3>
                <p class="text-blue-100 text-sm max-w-sm">
                    @if($status === 'Normal')
                        Pertumbuhan {{ $child->name }} sangat baik! Semua indikator dalam rentang yang sesuai.
                    @elseif($status === 'Stunting')
                        {{ $child->name }} memerlukan perhatian lebih pada aspek nutrisi. Segera konsultasikan dengan dokter.
                    @elseif($status === 'Obesitas')
                        {{ $child->name }} memiliki risiko kesehatan. Perbaiki pola makan dan tingkatkan aktivitas fisik.
                    @else
                        Belum ada data pertumbuhan untuk анализ. Input data pertama untuk melihat status kesehatan.
                    @endif
                </p>
                <div class="flex gap-3">
                    <a href="{{ route('input') }}" class="bg-white text-blue-600 px-6 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-gray-50">Lihat Detail</a>
                    <button class="bg-blue-400/30 text-white px-6 py-2 rounded-xl text-xs font-bold backdrop-blur-sm">Unduh Laporan</button>
                </div>
            </div>
            <div class="text-6xl hidden sm:block opacity-20">⭐</div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const growthRecords = @json($child->growthRecords->sortBy('recorded_at')->map(fn($r) => [
    'date' => $r->recorded_at->format('d M'),
    'weight' => (float) $r->weight,
    'height' => (float) $r->height
]));

if (growthRecords.length > 0) {
    const ctx = document.getElementById('growthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: growthRecords.map(r => r.date),
            datasets: [{
                label: 'Berat (kg)',
                data: growthRecords.map(r => r.weight),
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1E3A5F', padding: 10, cornerRadius: 8 }
            },
            scales: {
                y: { beginAtZero: false, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
} else {
    const ctx = document.getElementById('growthChart').getContext('2d');
    ctx.font = '14px Poppins';
    ctx.fillStyle = '#9CA3AF';
    ctx.textAlign = 'center';
    ctx.fillText('Belum ada data pertumbuhan', ctx.canvas.width / 2, ctx.canvas.height / 2);
}
</script>

{{-- Growth Modal --}}
<div x-show="showGrowthModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="showGrowthModal = false">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" @click.stop>
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Input Data Pertumbuhan</h3>
            <button @click="showGrowthModal = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('child.growth.store', $child) }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Berat (kg)</label>
                    <input type="number" name="weight" step="0.01" min="0.1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tinggi (cm)</label>
                    <input type="number" name="height" step="0.1" min="1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Lingkar Kepala (cm)</label>
                <input type="number" name="head_circumference" step="0.1" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                <input type="date" name="recorded_at" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
            </div>
            <button type="submit" class="w-full py-2.5 bg-primary text-white rounded-xl font-medium text-sm">Simpan</button>
        </form>
    </div>
</div>

{{-- Vaccine Modal --}}
<div x-show="showVaccineModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="showVaccineModal = false">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" @click.stop>
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Input Jadwal Vaksinasi</h3>
            <button @click="showVaccineModal = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('vaccinations.store', $child) }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Vaksin</label>
                <input type="text" name="vaccine_name" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                <input type="date" name="scheduled_date" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white">
                    <option value="upcoming">Akan Datang</option>
                    <option value="done">Selesai</option>
                    <option value="missed">Terlewat</option>
                </select>
            </div>
            <button type="submit" class="w-full py-2.5 bg-purple-600 text-white rounded-xl font-medium text-sm">Simpan</button>
        </form>
    </div>
</div>
@endpush
@endsection