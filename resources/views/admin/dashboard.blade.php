@extends('layouts.admin')
@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-primary">SELAMAT DATANG ADMIN!</h1>
        <p class="text-gray-500 mt-1">Panel administrasi GoKids</p>
    </div>

    <!-- Recent Users -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Pengguna-Terbaru</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @forelse($recentUsers as $u)
            <div class="bg-white rounded-xl shadow-md p-4 text-center">
                <div class="w-14 h-14 rounded-full bg-accent/10 mx-auto mb-2 flex items-center justify-center overflow-hidden">
                    @if($u->avatar)
                        <img src="{{ asset('storage/' . $u->avatar) }}" alt="{{ $u->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xl font-bold text-accent">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                    @endif
                </div>
                <h4 class="text-xs font-medium text-gray-800 truncate">{{ $u->name }}</h4>
                <p class="text-xs text-gray-400 mt-0.5">{{ $u->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <div class="col-span-6 text-center text-gray-500 py-4">Belum ada pengguna terdaftar.</div>
            @endforelse
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-md p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center">
                    <span class="text-lg">👥</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
            <p class="text-xs text-gray-500 mt-1">Total User</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center">
                    <span class="text-lg">👶</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalChildren }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Anak (Balita)</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-lg">📄</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalPublished }}</p>
            <p class="text-xs text-gray-500 mt-1">Artikel Published</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <span class="text-lg">📝</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalDrafts }}</p>
            <p class="text-xs text-gray-500 mt-1">Artikel Draft</p>
        </div>
    </div>

    <!-- Growth Chart -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📈 Grafik Data Pertumbuhan (6 Bulan Terakhir)</h2>
        <canvas id="growthChart" height="100"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    const ctx = document.getElementById('growthChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Jumlah Rekaman Pertumbuhan',
                data: @json($chartValues),
                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 1,
                borderRadius: 8,
                barThickness: 40,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
@endsection
