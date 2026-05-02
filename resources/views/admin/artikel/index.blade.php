@extends('layouts.admin')
@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-primary">Daftar Artikel</h1>
        <a href="{{ route('admin.artikel.create') }}" class="px-5 py-2.5 bg-accent text-white rounded-full font-medium text-sm hover:bg-accent/90 transition shadow-md flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Artikel
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Thumbnail</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Judul</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($articles as $index => $article)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600">{{ $articles->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            @if($article->thumbnail)
                                <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $article->title }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 bg-accent/10 text-accent rounded-full text-xs font-medium">{{ $article->category }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($article->status === 'published')
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Published</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">Draft</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $article->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.artikel.edit', $article) }}" class="px-3 py-1.5 bg-accent text-white rounded-lg text-xs font-medium hover:bg-accent/90 transition">Edit</a>
                                <form action="{{ route('admin.artikel.destroy', $article) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs font-medium hover:bg-red-600 transition">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada artikel.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($articles->hasPages())
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $articles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
