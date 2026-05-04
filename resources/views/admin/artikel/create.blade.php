@extends('layouts.admin')
@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.artikel.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-primary">Tambah Artikel Baru</h1>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <form id="form-artikel" action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Artikel</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="Masukkan judul artikel">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent bg-white">
                        <option value="">Pilih Kategori</option>
                        <option value="Nutrisi" {{ old('category') === 'Nutrisi' ? 'selected' : '' }}>Nutrisi</option>
                        <option value="Vaksinasi" {{ old('category') === 'Vaksinasi' ? 'selected' : '' }}>Vaksinasi</option>
                        <option value="Tumbuh Kembang" {{ old('category') === 'Tumbuh Kembang' ? 'selected' : '' }}>Tumbuh
                            Kembang</option>
                        <option value="Kesehatan" {{ old('category') === 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
                    <input type="file" name="thumbnail" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-accent/10 file:text-accent hover:file:bg-accent/20">
                    @error('thumbnail')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten Artikel</label>
                    <input type="hidden" name="content" id="content-input" value="{{ old('content') }}">
                    <div id="quill-editor" class="bg-white rounded-xl border border-gray-200 min-h-[250px]">
                        {!! old('content') !!}</div>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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
            // 2. Inisialisasi Editor Quill (Pastikan tidak ada kurung yang tertinggal)
            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Tulis konten artikel di sini...',
                modules: {
                    toolbar: [
                        [{
                            'header': [1, 2, 3, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });

            // 3. Sinkronisasi data ke hidden input saat form di-submit
            const formArtikel = document.getElementById('form-artikel');

            if (formArtikel) {
                formArtikel.addEventListener('submit', function() {
                    // Ambil teks murni
                    const plainText = quill.getText().trim();
                    const contentInput = document.getElementById('content-input');

                    // Cek apakah benar-benar kosong
                    if (plainText.length === 0) {
                        contentInput.value = '';
                    } else {
                        contentInput.value = quill.root.innerHTML;
                    }
                });
            }
        </script>
    @endpush
@endsection
