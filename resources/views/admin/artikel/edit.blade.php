@extends('layouts.admin')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.artikel.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-primary">Edit Artikel</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <form action="{{ route('admin.artikel.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Artikel</label>
                <input type="text" name="title" value="{{ old('title', $article->title) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent bg-white">
                    @foreach(['Nutrisi', 'Vaksinasi', 'Tumbuh Kembang', 'Kesehatan'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $article->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
                @if($article->thumbnail)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="" class="w-24 h-24 rounded-lg object-cover">
                    </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-accent/10 file:text-accent hover:file:bg-accent/20">
                @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konten Artikel</label>
                <input type="hidden" name="content" id="content-input" value="{{ old('content', $article->content) }}">
                <div id="quill-editor" class="bg-white rounded-xl border border-gray-200 min-h-[250px]">{!! old('content', $article->content) !!}</div>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" name="status" value="published"
                    class="px-6 py-2.5 bg-primary text-white rounded-full font-medium text-sm hover:bg-primary-600 transition shadow-md">
                    Publish Artikel
                </button>
                <button type="submit" name="status" value="draft"
                    class="px-6 py-2.5 border border-gray-300 text-gray-600 rounded-full font-medium text-sm hover:bg-gray-50 transition">
                    Simpan sebagai Draft
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endpush
@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Tulis konten artikel di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('content-input').value = quill.root.innerHTML;
    });
</script>
@endpush
@endsection
