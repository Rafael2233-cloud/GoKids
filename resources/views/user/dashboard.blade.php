@extends('layouts.user')
@section('content')
    <div class="space-y-8">

        {{-- 1. Greeting Header --}}
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 uppercase tracking-tight">SELAMAT DATANG
                {{ strtoupper($user->name) }}!</h1>
            <p class="text-gray-500 mt-2 text-sm md:text-base">Temukan solusi dari permasalahan tumbuh kembang anak anda</p>
        </div>

        {{-- 2. Article Section --}}
        <div>
            {{-- "Lihat lebih banyak" link --}}
            <div class="flex justify-end mb-3">
                <a href="javascript:void(0)" id="btn-lihat-banyak"
                    class="text-sm text-accent hover:underline font-medium">Lihat lebih banyak</a>
            </div>

            {{-- Initial 3 Article Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="article-grid-initial">
                @forelse($latestArticles as $article)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow flex flex-col">
                        {{-- Category Badge --}}
                        <span
                            class="inline-block w-fit px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded
                    @if (strtolower($article->category) === 'kesehatan') bg-red-100 text-red-600
                    @elseif(strtolower($article->category) === 'makanan') bg-amber-100 text-amber-600
                    @elseif(strtolower($article->category) === 'edukasi') bg-blue-100 text-blue-600
                    @else bg-gray-100 text-gray-600 @endif
                ">{{ $article->category }}</span>

                        <div class="flex items-start gap-3 mt-2 flex-1">
                            {{-- Text content --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 leading-snug line-clamp-2">{{ $article->title }}
                                </h3>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    {{ $article->published_at ? $article->published_at->format('M d') : $article->created_at->format('M d') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($article->content), 80) }}</p>
                            </div>
                            {{-- Thumbnail --}}
                            @if ($article->thumbnail)
                                <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                    <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                                        class="w-full h-full object-cover">
                                </div>
                            @else
                                <div
                                    class="w-16 h-16 rounded-lg flex-shrink-0 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                    <span class="text-2xl">📄</span>
                                </div>
                            @endif
                        </div>
                        {{-- Read More --}}
                        <a href="{{ route('artikel.show', $article) }}"
                            class="text-red-500 text-xs font-bold mt-3 hover:text-red-600 transition inline-block">Read
                            More</a>
                    </div>
                @empty
                    <div class="col-span-3 bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                        <span class="text-5xl mb-4 block">📰</span>
                        <h2 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Artikel</h2>
                        <p class="text-gray-500 text-sm">Artikel akan muncul di sini setelah admin mempublikasikannya.</p>
                    </div>
                @endforelse
            </div>

            {{-- All Articles (hidden by default, shown on "Lihat lebih banyak") --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 hidden" id="article-grid-all">
                @foreach ($allArticles->skip(3) as $article)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow flex flex-col">
                        <span
                            class="inline-block w-fit px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded
                    @if (strtolower($article->category) === 'kesehatan') bg-red-100 text-red-600
                    @elseif(strtolower($article->category) === 'makanan') bg-amber-100 text-amber-600
                    @elseif(strtolower($article->category) === 'edukasi') bg-blue-100 text-blue-600
                    @else bg-gray-100 text-gray-600 @endif
                ">{{ $article->category }}</span>

                        <div class="flex items-start gap-3 mt-2 flex-1">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 leading-snug line-clamp-2">{{ $article->title }}
                                </h3>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    {{ $article->published_at ? $article->published_at->format('M d') : $article->created_at->format('M d') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($article->content), 80) }}</p>
                            </div>
                            @if ($article->thumbnail)
                                <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                    <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                                        class="w-full h-full object-cover">
                                </div>
                            @else
                                <div
                                    class="w-16 h-16 rounded-lg flex-shrink-0 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                    <span class="text-2xl">📄</span>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('artikel.show', $article) }}"
                            class="text-red-500 text-xs font-bold mt-3 hover:text-red-600 transition inline-block">Read
                            More</a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 3. Info Tumbuh Kembang + Tata Cara Pengukuran --}}
        <div class="flex flex-col xl:flex-row gap-6 items-start">
            {{-- Left: Info Tumbuh Kembang --}}
            <div class="flex-1 min-w-0 w-full">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Info Tumbuh Kembang</h2>
                    <a href="#" class="text-sm text-blue-600 hover:underline font-medium">View All Milestones</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Motoric --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M13.49 5.48c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-3.6 13.9l1-4.4 2.1 2v6h2v-7.5l-2.1-2 .6-3c1.3 1.5 3.3 2.5 5.5 2.5v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1l-5.2 2.2v4.7h2v-3.4l1.8-.7-1.6 8.1-4.9-1-.4 2 7 1.4z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-3">Motoric</h3>
                        <p class="text-sm text-gray-600 leading-relaxed flex-1 mb-6">Leo is now walking steadily and
                            starting to run with improved balance.</p>
                        <div>
                            <a href="#"
                                class="inline-block px-4 py-1.5 bg-green-300 text-green-800 text-xs font-bold rounded-full hover:bg-green-400 transition uppercase tracking-wide">READ
                                MORE</a>
                        </div>
                    </div>

                    {{-- Cognitive --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-3">Cognitive</h3>
                        <p class="text-sm text-gray-600 leading-relaxed flex-1 mb-6">Sorting shapes and identifying primary
                            colors are key focuses for his age group.</p>
                        <div>
                            <a href="#"
                                class="inline-block px-4 py-1.5 bg-green-300 text-green-800 text-xs font-bold rounded-full hover:bg-green-400 transition uppercase tracking-wide">READ
                                MORE</a>
                        </div>
                    </div>

                    {{-- Social --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-3">Social</h3>
                        <p class="text-sm text-gray-600 leading-relaxed flex-1 mb-6">Showing empathy and playing
                            'make-believe' games with caregivers.</p>
                        <div>
                            <span
                                class="inline-block px-4 py-1.5 bg-green-300 text-green-800 text-xs font-bold rounded-full uppercase tracking-wide">ON
                                TRACK</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Tata Cara Pengukuran --}}
            <div class="w-full xl:w-72 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm">Tata Cara Pengukuran</h3>
                    </div>

                    {{-- Measuring Weight --}}
                    <h4 class="text-xs font-bold text-accent uppercase tracking-wider mb-3">MEASURING WEIGHT</h4>
                    <div class="space-y-3 mb-5">
                        <div class="flex items-start gap-3">
                            <span
                                class="w-5 h-5 bg-accent rounded-full flex items-center justify-center flex-shrink-0 text-white text-[10px] font-bold">1</span>
                            <p class="text-xs text-gray-600 leading-relaxed">Use a digital scale on a flat, hard surface.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span
                                class="w-5 h-5 bg-accent rounded-full flex items-center justify-center flex-shrink-0 text-white text-[10px] font-bold">2</span>
                            <p class="text-xs text-gray-600 leading-relaxed">Remove heavy clothing and shoes before
                                weighing.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span
                                class="w-5 h-5 bg-accent rounded-full flex items-center justify-center flex-shrink-0 text-white text-[10px] font-bold">3</span>
                            <p class="text-xs text-gray-600 leading-relaxed">Ensure the child stands still in the center.
                            </p>
                        </div>
                    </div>

                    {{-- Measuring Height --}}
                    <h4 class="text-xs font-bold text-red-500 uppercase tracking-wider mb-3">MEASURING HEIGHT</h4>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <span
                                class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 text-white text-[10px] font-bold">1</span>
                            <p class="text-xs text-gray-600 leading-relaxed">Stand against a flat wall without baseboards.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span
                                class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 text-white text-[10px] font-bold">2</span>
                            <p class="text-xs text-gray-600 leading-relaxed">Heels, shoulders, and head should touch the
                                wall.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span
                                class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 text-white text-[10px] font-bold">3</span>
                            <p class="text-xs text-gray-600 leading-relaxed">Use a flat object to mark the top of the head.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Upcoming Vaccination --}}
        @if ($upcomingVaccinations->count() > 0)
            <div class="bg-accent rounded-2xl p-6 text-white shadow-lg max-w-2xl">
                <h2 class="text-xl font-bold mb-2">Upcoming Vaccination</h2>
                @php $firstVacc = $upcomingVaccinations->first(); @endphp
                <p class="text-sm text-white/85 leading-relaxed mb-4">
                    {{ $firstVacc->child->name }}'s {{ $firstVacc->vaccine_name }} is due in
                    {{ $firstVacc->scheduled_date->diffForHumans(null, true) }}. Schedule your visit at the nearest
                    pediatric clinic.
                </p>
                <a href="#"
                    class="inline-block bg-white text-accent text-sm font-bold px-5 py-2 rounded-lg hover:bg-blue-50 transition">Remind
                    Me</a>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            // Toggle "Lihat lebih banyak" / "Lihat lebih sedikit"
            const btnLihat = document.getElementById('btn-lihat-banyak');
            const gridAll = document.getElementById('article-grid-all');

            if (btnLihat && gridAll) {
                btnLihat.addEventListener('click', function() {
                    if (gridAll.classList.contains('hidden')) {
                        gridAll.classList.remove('hidden');
                        btnLihat.textContent = 'Lihat lebih sedikit';
                        gridAll.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    } else {
                        gridAll.classList.add('hidden');
                        btnLihat.textContent = 'Lihat lebih banyak';
                    }
                });
            }
        </script>
    @endpush
@endsection
