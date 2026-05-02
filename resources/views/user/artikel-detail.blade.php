@extends('layouts.user')
@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Back Button --}}
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-accent transition mb-6 group">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Dashboard
    </a>

    {{-- Article Header --}}
    <div class="mb-6">
        <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider rounded
            @if(strtolower($article->category) === 'kesehatan') bg-red-100 text-red-600
            @elseif(strtolower($article->category) === 'makanan') bg-amber-100 text-amber-600
            @elseif(strtolower($article->category) === 'edukasi') bg-blue-100 text-blue-600
            @else bg-gray-100 text-gray-600
            @endif
        ">{{ $article->category }}</span>

        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mt-3 leading-tight">{{ $article->title }}</h1>

        <div class="flex items-center gap-3 mt-3 text-sm text-gray-400">
            <span>{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
            <span>•</span>
            <span>{{ $article->admin ? $article->admin->name : 'Admin' }}</span>
        </div>
    </div>

    {{-- Thumbnail --}}
    @if($article->thumbnail)
    <div class="rounded-2xl overflow-hidden mb-8 shadow-sm">
        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-64 md:h-80 object-cover">
    </div>
    @endif

    {{-- Article Content --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
        <div class="prose prose-sm md:prose-base max-w-none text-gray-700 leading-relaxed">
            {!! $article->content !!}
        </div>
    </div>

    {{-- Related Articles --}}
    @if($relatedArticles->count() > 0)
    <div class="mt-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Artikel Terkait</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($relatedArticles as $related)
            <a href="{{ route('artikel.show', $related) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow group block">
                <span class="inline-block px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded
                    @if(strtolower($related->category) === 'kesehatan') bg-red-100 text-red-600
                    @elseif(strtolower($related->category) === 'makanan') bg-amber-100 text-amber-600
                    @elseif(strtolower($related->category) === 'edukasi') bg-blue-100 text-blue-600
                    @else bg-gray-100 text-gray-600
                    @endif
                ">{{ $related->category }}</span>
                <h3 class="font-bold text-sm text-gray-900 mt-2 group-hover:text-accent transition line-clamp-2">{{ $related->title }}</h3>
                <p class="text-[11px] text-gray-400 mt-1">{{ $related->published_at ? $related->published_at->format('M d') : $related->created_at->format('M d') }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
