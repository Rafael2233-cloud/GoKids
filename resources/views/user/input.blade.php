@extends('layouts.user')
@section('content')
    {{-- Tambahkan showVaccineModal pada x-data --}}
    <div class="space-y-8" x-data="{ activeTab: 'register', showGrowthModal: false, showVaccineModal: false, selectedChild: null }">
        <h1 class="text-2xl font-bold text-primary">Input Data Anak</h1>

        <div class="flex gap-2">
            <button @click="activeTab = 'register'"
                :class="activeTab === 'register' ? 'bg-primary text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="px-5 py-2.5 rounded-full text-sm font-medium transition-all shadow-sm">
                ➕ Registrasi Anak Baru
            </button>
            <button @click="activeTab = 'list'"
                :class="activeTab === 'list' ? 'bg-primary text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="px-5 py-2.5 rounded-full text-sm font-medium transition-all shadow-sm">
                📋 Daftar Anak & Pertumbuhan
            </button>
        </div>

        <div x-show="activeTab === 'register'" x-cloak>
            <div class="bg-white rounded-xl shadow-md p-6 max-w-lg">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Registrasi Anak Baru</h2>
                <form action="{{ route('child.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anak</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="Masukkan nama anak">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                        @error('birth_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="L"
                                    {{ old('gender') === 'L' ? 'checked' : '' }} required
                                    class="text-accent focus:ring-accent">
                                <span class="text-sm">👦 Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="P"
                                    {{ old('gender') === 'P' ? 'checked' : '' }} class="text-accent focus:ring-accent">
                                <span class="text-sm">👧 Perempuan</span>
                            </label>
                        </div>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Anak</label>
                        <input type="file" name="photo" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-accent/10 file:text-accent hover:file:bg-accent/20">
                        @error('photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="px-6 py-2.5 bg-primary text-white rounded-full font-medium text-sm hover:bg-primary-600 transition shadow-md">
                        Simpan Data Anak
                    </button>
                </form>
            </div>
        </div>

        <div x-show="activeTab === 'list'" x-cloak>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tgl Lahir</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Jenis Kelamin</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Usia</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($children as $index => $child)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-gray-600">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $child->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ \Carbon\Carbon::parse($child->birth_date)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium {{ $child->gender === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                            {{ $child->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ \Carbon\Carbon::parse($child->birth_date)->diff(\Carbon\Carbon::now())->format('%y tahun %m bulan') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <a href="{{ route('child.show', $child) }}"
                                                class="px-3 py-1.5 bg-accent text-white rounded-lg text-xs font-medium hover:bg-accent/90 transition">Detail</a>

                                            {{-- Tombol Pertumbuhan --}}
                                            <button @click="showGrowthModal = true; selectedChild = {{ $child->id }}"
                                                class="px-3 py-1.5 bg-secondary text-white rounded-lg text-xs font-medium hover:bg-secondary/90 transition">Input
                                                Pertumbuhan</button>

                                            {{-- TOMBOL BARU: Input Vaksin --}}
                                            <button @click="showVaccineModal = true; selectedChild = {{ $child->id }}"
                                                class="px-3 py-1.5 bg-purple-500 text-white rounded-lg text-xs font-medium hover:bg-purple-600 transition shadow-sm">
                                                Input Vaksin
                                            </button>

                                            <form action="{{ route('child.destroy', $child) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data anak ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs font-medium hover:bg-red-600 transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data anak.
                                        Silakan registrasi anak baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="showGrowthModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            @click.self="showGrowthModal = false">
            <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Input Data Pertumbuhan</h3>
                    <button @click="showGrowthModal = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form :action="'/input/' + selectedChild + '/growth'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg)</label>
                        <input type="number" name="weight" step="0.01" min="0.1" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="Contoh: 12.5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan (cm)</label>
                        <input type="number" name="height" step="0.1" min="1" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="Contoh: 85.0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lingkar Kepala (cm)</label>
                        <input type="number" name="head_circumference" step="0.1" min="1"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="Contoh: 46.0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengukuran</label>
                        <input type="date" name="recorded_at" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent resize-none"
                            placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-2.5 bg-primary text-white rounded-full font-medium text-sm hover:bg-primary-600 transition shadow-md">
                        Simpan Data Pertumbuhan
                    </button>
                </form>
            </div>
        </div>

        <div x-show="showVaccineModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            @click.self="showVaccineModal = false">
            <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Input Jadwal Vaksinasi</h3>
                    <button @click="showVaccineModal = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Letakkan ini di dalam div Modal, tepat sebelum tag <form> --}}
                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-3 rounded-xl mb-4 text-xs font-medium">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- Pastikan action URL ini sesuai dengan rute web.php kamu --}}
                <form :action="'/children/' + selectedChild + '/vaccinations'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Vaksin</label>
                        <input type="text" name="vaccine_name" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Contoh: DPT 1, Polio, Campak">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dijadwalkan</label>
                        <input type="date" name="scheduled_date" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white">
                            {{-- GANTI value-nya agar sesuai dengan aturan Controller (upcoming & done) --}}
                            <option value="upcoming">Upcoming (Menunggu)</option>
                            <option value="done">Done (Selesai)</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-purple-600 text-white rounded-full font-medium text-sm hover:bg-purple-700 transition shadow-md">
                        Simpan Jadwal Vaksin
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
