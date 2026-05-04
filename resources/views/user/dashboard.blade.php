@extends('layouts.user')
@section('content')
    <div class="max-w-7xl mx-auto space-y-10">

        {{-- 1. Greeting Header --}}
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-blue-900 uppercase tracking-tight">SELAMAT DATANG
                {{ strtoupper($user->name ?? 'Orang Tua') }}!</h1>
            <p class="text-gray-500 mt-2 text-sm md:text-base">Temukan solusi dari permasalahan tumbuh kembang anak</p>
        </div>

        {{-- 2. Article Section (Info & Tips Kesehatan) --}}
        <div class="w-full">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Info & Tips Kesehatan</h2>
                <a href="javascript:void(0)" id="btn-lihat-banyak"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 transition">
                    Lihat lebih banyak
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Initial 3 Article Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="article-grid-initial">
                @forelse($latestArticles ?? [] as $article)
                    @php
                        $cat = strtolower($article->category ?? 'lainnya');
                        $topBg = 'bg-gray-100';
                        $badgeBg = 'bg-gray-500';
                        if ($cat === 'kesehatan') {
                            $topBg = 'bg-pink-100';
                            $badgeBg = 'bg-red-500';
                        } elseif ($cat === 'makanan') {
                            $topBg = 'bg-orange-100';
                            $badgeBg = 'bg-orange-500';
                        } elseif ($cat === 'edukasi') {
                            $topBg = 'bg-blue-100';
                            $badgeBg = 'bg-blue-500';
                        }
                    @endphp
                    <div
                        class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col hover:shadow-md transition-shadow overflow-hidden">
                        <div
                            class="h-40 w-full relative {{ $topBg }} flex items-center justify-center overflow-hidden">
                            @if (isset($article->thumbnail) && $article->thumbnail)
                                <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl opacity-50">📰</span>
                            @endif
                            <span
                                class="absolute top-4 left-4 inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full text-white shadow-sm {{ $badgeBg }}">
                                {{ $article->category ?? 'Artikel' }}
                            </span>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="font-bold text-base text-gray-900 leading-snug line-clamp-2 mb-2">
                                {{ $article->title ?? 'Judul Artikel' }}</h3>
                            <p class="text-xs text-gray-400 mb-3">
                                {{ isset($article->published_at) ? $article->published_at->format('M d') : (isset($article->created_at) ? $article->created_at->format('M d') : '') }}
                            </p>
                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed flex-1">
                                {{ Str::limit(strip_tags($article->content ?? ''), 70) }}</p>
                            <a href="{{ route('artikel.show', $article->id ?? 1) }}"
                                class="text-red-500 text-sm font-bold mt-4 hover:text-red-600 transition flex items-center gap-1 w-fit">
                                Baca Selengkapnya <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-1 md:col-span-2 lg:col-span-3 bg-white rounded-3xl shadow-sm border border-gray-100 p-10 text-center">
                        <span class="text-5xl mb-4 block">📰</span>
                        <h2 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Artikel</h2>
                        <p class="text-gray-500 text-sm">Artikel Info & Tips Kesehatan akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            {{-- All Articles (hidden by default) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6 hidden" id="article-grid-all">
                @foreach (($allArticles ?? collect())->skip(3) as $article)
                    @php
                        $cat = strtolower($article->category ?? 'lainnya');
                        $topBg = 'bg-gray-100';
                        $badgeBg = 'bg-gray-500';
                        if ($cat === 'kesehatan') {
                            $topBg = 'bg-pink-100';
                            $badgeBg = 'bg-red-500';
                        } elseif ($cat === 'makanan') {
                            $topBg = 'bg-orange-100';
                            $badgeBg = 'bg-orange-500';
                        } elseif ($cat === 'edukasi') {
                            $topBg = 'bg-blue-100';
                            $badgeBg = 'bg-blue-500';
                        }
                    @endphp
                    <div
                        class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col hover:shadow-md transition-shadow overflow-hidden">
                        <div
                            class="h-40 w-full relative {{ $topBg }} flex items-center justify-center overflow-hidden">
                            @if (isset($article->thumbnail) && $article->thumbnail)
                                <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl opacity-50">📰</span>
                            @endif
                            <span
                                class="absolute top-4 left-4 inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full text-white shadow-sm {{ $badgeBg }}">
                                {{ $article->category ?? 'Artikel' }}
                            </span>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="font-bold text-base text-gray-900 leading-snug line-clamp-2 mb-2">
                                {{ $article->title ?? 'Judul Artikel' }}</h3>
                            <p class="text-xs text-gray-400 mb-3">
                                {{ isset($article->published_at) ? $article->published_at->format('M d') : (isset($article->created_at) ? $article->created_at->format('M d') : '') }}
                            </p>
                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed flex-1">
                                {{ Str::limit(strip_tags($article->content ?? ''), 70) }}</p>
                            <a href="{{ route('artikel.show', $article->id ?? 1) }}"
                                class="text-red-500 text-sm font-bold mt-4 hover:text-red-600 transition flex items-center gap-1 w-fit">
                                Baca Selengkapnya <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 3 & 4. Info Tumbuh Kembang, Vaksinasi & Tata Cara --}}
        <div class="flex flex-col lg:flex-row gap-8 items-start w-full">

            {{-- KIRI: Tumbuh Kembang & Card Biru Vaksinasi --}}
            <div class="flex-1 min-w-0 w-full flex flex-col gap-8">

                {{-- Info Tumbuh Kembang --}}
                <div class="w-full">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Info Tumbuh Kembang</h2>
                        <a href="#"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 transition">
                            Lihat Semua Milestone
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                        {{-- Card Motoric --}}
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                            <div
                                class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-5">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M13.49 5.48c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-3.6 13.9l1-4.4 2.1 2v6h2v-7.5l-2.1-2 .6-3c1.3 1.5 3.3 2.5 5.5 2.5v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1l-5.2 2.2v4.7h2v-3.4l1.8-.7-1.6 8.1-4.9-1-.4 2 7 1.4z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-lg mb-2">Motorik</h3>
                            <p class="text-sm text-gray-500 leading-relaxed flex-1 mb-6">Anak sudah bisa berjalan stabil dan
                                mulai berlari dengan keseimbangan yang lebih baik.</p>
                            <div>
                                <a href="#"
                                    class="inline-block px-5 py-2 bg-green-400 text-white text-xs font-bold rounded-full hover:bg-green-500 transition shadow-sm uppercase tracking-wider">BACA
                                    SELENGKAPNYA</a>
                            </div>
                        </div>

                        {{-- Card Cognitive --}}
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                            <div
                                class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-5">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-lg mb-2">Kognitif</h3>
                            <p class="text-sm text-gray-500 leading-relaxed flex-1 mb-6">Menyusun bentuk dan mengenali warna
                                primer jadi fokus utama untuk usia ini.</p>
                            <div>
                                <a href="#"
                                    class="inline-block px-5 py-2 bg-green-400 text-white text-xs font-bold rounded-full hover:bg-green-500 transition shadow-sm uppercase tracking-wider">BACA
                                    SELENGKAPNYA</a>
                            </div>
                        </div>

                        {{-- Card Social --}}
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                            <div
                                class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-5">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-lg mb-2">Sosial</h3>
                            <p class="text-sm text-gray-500 leading-relaxed flex-1 mb-6">Mulai menunjukkan empati dan
                                bermain peran (make-believe) dengan pendamping.</p>
                            <div>
                                <span
                                    class="inline-block px-5 py-2 bg-green-400 text-white text-xs font-bold rounded-full shadow-sm uppercase tracking-wider">SESUAI
                                    TARGET</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upcoming Vaccination --}}
                @if (isset($upcomingVaccinations) && $upcomingVaccinations->count() > 0)
                    @php $firstVacc = $upcomingVaccinations->first(); @endphp
                    <div class="bg-blue-600 rounded-3xl p-8 text-white shadow-md relative overflow-hidden">
                        <div class="relative z-10">
                            <h2 class="text-2xl font-bold mb-3 tracking-wide">Jadwal Vaksinasi Berikutnya</h2>
                            <p class="text-sm text-white/90 leading-relaxed mb-6 max-w-md">
                                Vaksin {{ $firstVacc->vaccine_name ?? 'booster DTaP' }} untuk
                                {{ $firstVacc->child->name ?? 'anak' }} dijadwalkan dalam
                                {{ $firstVacc->scheduled_date ? $firstVacc->scheduled_date->diffForHumans(null, true) : '15 hari' }}.
                                Segera jadwalkan kunjungan ke klinik anak terdekat.
                            </p>
                            <a href="#"
                                class="inline-block bg-white text-blue-600 text-sm font-extrabold px-8 py-3 rounded-xl hover:bg-gray-50 transition shadow-sm w-fit">
                                Ingatkan Nanti
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Dummy Data jika tidak ada jadwal vaksin untuk testing layout --}}
                    <div class="bg-[#1a73e8] rounded-3xl p-8 text-white shadow-md relative overflow-hidden">
                        <div class="relative z-10">
                            <h2 class="text-2xl font-bold mb-3 tracking-wide">Jadwal Vaksinasi Berikutnya</h2>
                            <p class="text-sm text-white/90 leading-relaxed mb-6 max-w-md">
                                Vaksin booster DTaP dijadwalkan dalam 15 hari. Segera jadwalkan kunjungan ke klinik anak
                                terdekat.
                            </p>
                            <a href="#"
                                class="inline-block bg-white text-blue-600 text-sm font-extrabold px-8 py-3 rounded-xl hover:bg-gray-50 transition shadow-sm w-fit">
                                Ingatkan Nanti
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- KANAN: Tata Cara Pengukuran --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-full flex flex-col">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-12 h-12 flex-shrink-0 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg leading-tight">Tata Cara<br>Pengukuran</h3>
                    </div>

                    <div class="flex-1">
                        {{-- Measuring Weight --}}
                        <h4 class="text-xs font-extrabold text-blue-600 uppercase tracking-widest mb-5">MENGUKUR BERAT
                            BADAN</h4>
                        <div class="space-y-6 mb-10">
                            <div class="flex items-start gap-4">
                                <span
                                    class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span>
                                <p class="text-sm text-gray-600 leading-relaxed mt-0.5">Gunakan timbangan digital di
                                    permukaan yang rata dan keras.</p>
                            </div>
                            <div class="flex items-start gap-4">
                                <span
                                    class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span>
                                <p class="text-sm text-gray-600 leading-relaxed mt-0.5">Lepaskan pakaian tebal dan sepatu
                                    sebelum menimbang.</p>
                            </div>
                            <div class="flex items-start gap-4">
                                <span
                                    class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span>
                                <p class="text-sm text-gray-600 leading-relaxed mt-0.5">Pastikan anak berdiri santai dan
                                    diam di tengah timbangan.</p>
                            </div>
                        </div>

                        {{-- Measuring Height --}}
                        <h4 class="text-xs font-extrabold text-blue-600 uppercase tracking-widest mb-5">MENGUKUR TINGGI
                            BADAN</h4>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <span
                                    class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span>
                                <p class="text-sm text-gray-600 leading-relaxed mt-0.5">Berdiri menyandar pada dinding rata
                                    tanpa alas lantai (baseboard).</p>
                            </div>
                            <div class="flex items-start gap-4">
                                <span
                                    class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span>
                                <p class="text-sm text-gray-600 leading-relaxed mt-0.5">Posisikan agar tumit, bahu,
                                    punggung, dan belakang kepala menyentuh dinding.</p>
                            </div>
                            <div class="flex items-start gap-4">
                                <span
                                    class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span>
                                <p class="text-sm text-gray-600 leading-relaxed mt-0.5">Gunakan benda datar (seperti buku)
                                    untuk menandai ujung tertinggi kepala.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const btnLihat = document.getElementById('btn-lihat-banyak');
            const gridAll = document.getElementById('article-grid-all');

            if (btnLihat && gridAll) {
                btnLihat.addEventListener('click', function() {
                    if (gridAll.classList.contains('hidden')) {
                        gridAll.classList.remove('hidden');
                        btnLihat.innerHTML =
                            'Lihat lebih sedikit <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
                        gridAll.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    } else {
                        gridAll.classList.add('hidden');
                        btnLihat.innerHTML =
                            'Lihat lebih banyak <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
                    }
                });
            }
        </script>
    @endpush
@endsection
