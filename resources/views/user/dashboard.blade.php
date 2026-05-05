@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-10">
    
    {{-- 1. Dashboard Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-blue-900">Dashboard Orang Tua</h1>
            <p class="text-gray-500 text-sm mt-1">Selamat datang kembali! Pantau tumbuh kembang anak Anda dengan mudah.</p>
        </div>
        <div class="bg-blue-600 text-white px-4 py-2 rounded-xl flex items-center gap-2 shadow-sm w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="text-sm font-semibold">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
        </div>
    </div>

    {{-- 2. Stats Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div>
                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Anak</p>
                <p class="text-2xl font-bold text-gray-800">{{ $childCount ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-4h2v4zm0-6h-2V7h2v3z"/></svg>
            </div>
            <div>
                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Pemeriksaan Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ $checkupsThisMonth ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 11.55C9.64 9.35 6.48 8 3 8v11c3.48 0 6.64 1.35 9 3.55 2.36-2.19 5.52-3.55 9-3.55V8c-3.48 0-6.64 1.35-9 3.55z"/></svg>
            </div>
            <div>
                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Artikel Dibaca</p>
                <p class="text-2xl font-bold text-gray-800">0</p>
            </div>
        </div>
    </div>

    {{-- 3. Ringkasan Anak Section --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Ringkasan Anak
            </h2>
            <a href="{{ route('input') }}" class="text-sm font-semibold text-blue-600 hover:bg-blue-50 px-4 py-1.5 rounded-lg transition">Lihat Detail ></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($children as $child)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative overflow-hidden group">
                @php
                    $status = $child->nutritional_status;
                    $statusClass = match($status) {
                        'Normal' => 'bg-green-100 text-green-600',
                        'Stunting' => 'bg-red-100 text-red-600',
                        'Obesitas' => 'bg-orange-100 text-orange-600',
                        default => 'bg-gray-100 text-gray-500'
                    };
                @endphp
                <span class="absolute top-4 right-4 {{ $statusClass }} text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">{{ $status ?? 'Belum Ada Data' }}</span>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center border border-gray-100">
                        <span class="text-2xl">{{ $child->gender === 'L' ? '👦' : '👧' }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $child->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $child->age }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-dashed border-gray-100 flex items-center justify-between text-gray-400">
                    <div class="flex items-center gap-1.5 text-[11px]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pemeriksaan terakhir: {{ $child->last_checkup ?? 'Belum pernah' }}
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center text-gray-500 italic">
                Belum ada data anak. Klik "Input Data" untuk memulai.
            </div>
            @endforelse
        </div>
    </div>

    {{-- 4. Artikel Section --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"/></svg>
                Artikel Tumbuh Kembang Anak
            </h2>
            <a href="#" class="text-sm font-semibold text-gray-400 hover:text-blue-600 transition">Lihat Semua ↗</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($latestArticles as $article)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden flex flex-col hover:shadow-md transition">
                <div class="relative h-48">
                    @if($article->thumbnail)
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                            <span class="text-6xl">📰</span>
                        </div>
                    @endif
                    <span class="absolute top-4 right-4 bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">{{ $article->category }}</span>
                </div>
                <div class="p-6 space-y-3">
                    <h3 class="font-bold text-gray-900 leading-snug">{{ $article->title }}</h3>
                    <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                        {{ Str::limit(strip_tags($article->content), 100) }}
                    </p>
                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center gap-1.5 text-gray-400 text-[11px]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : '' }}
                        </div>
                        <a href="{{ route('artikel.show', $article->id) }}" class="text-blue-600 text-xs font-bold hover:underline">Baca ></a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-8 text-gray-500">
                <span class="text-4xl mb-2 block">📰</span>
                <p>Belum ada artikel tersedia</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- 5. Tips Hari Ini (Highlight Card) --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 text-white relative overflow-hidden shadow-lg">
        <div class="relative z-10 max-w-2xl space-y-4">
            <div class="flex items-center gap-2">
                <span class="p-2 bg-white/20 rounded-lg">✨</span>
                <h2 class="text-xl font-bold">Tips Hari Ini</h2>
            </div>
            <blockquote class="text-lg font-medium leading-relaxed italic">
                "Berikan anak variasi makanan berwarna-warni setiap hari untuk memastikan asupan nutrisi yang lengkap dan seimbang."
            </blockquote>
            <p class="text-sm text-blue-100 font-semibold">- Dr. Sarah Pediatrician, Spesialis Anak</p>
        </div>
        <div class="absolute right-[-20px] bottom-[-20px] opacity-10">
            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </div>
    </div>

</div>
@endsection