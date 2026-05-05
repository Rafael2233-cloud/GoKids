@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-10" x-data="{ selectedChild: null, showAddModal: false, showGrowthModal: false, showVaccineModal: false, showEditModal: false, selectedChildData: {} }">
    
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-400 hover:text-blue-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-blue-900">Input Data Tumbuh Kembang</h1>
    </div>

    {{-- Pemilihan Anak --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Pilih profil anak:</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Card Anak --}}
            @forelse($children as $child)
            <div @click="selectedChild = {{ $child->id }}" 
                 :class="selectedChild === {{ $child->id }} ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-100' : 'border-gray-100 bg-gray-50'"
                 class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-lg">
                    {{ $child->gender === 'L' ? '👦' : '👧' }}
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-800 text-sm">{{ $child->name }}</p>
                    <p class="text-[10px] text-gray-500">{{ $child->age }}</p>
                </div>
                <div x-show="selectedChild === {{ $child->id }}" class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            @empty
            @endforelse
            
            {{-- Tombol Tambah Anak --}}
            <div @click="showAddModal = true" class="p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-blue-400 transition-colors cursor-pointer flex items-center justify-center gap-2 text-blue-600 font-bold text-sm">
                <span>+</span> Tambah Anak Baru
            </div>
        </div>
    </div>

    {{-- Form Input (shown when child selected) --}}
    <template x-if="selectedChild">
        <div>
            {{-- Tanggal --}}
            <div class="md:col-span-3 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <label class="text-xs font-bold text-gray-400 uppercase block mb-2">Tanggal Pengukuran</label>
                <input type="date" class="w-full bg-gray-50 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500" value="{{ date('Y-m-d') }}">
            </div>

            {{-- Berat, Tinggi, L.Kepala --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Berat Badan (kg)</label>
                    <input type="number" step="0.1" class="text-3xl font-bold w-full border-none focus:ring-0 p-0" placeholder="0.0" x-model="weight">
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" class="text-3xl font-bold w-full border-none focus:ring-0 p-0" placeholder="0.0" x-model="height">
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Lingkar Kepala (cm)</label>
                    <input type="number" step="0.1" class="text-3xl font-bold w-full border-none focus:ring-0 p-0" placeholder="0.0" x-model="head">
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <form :action="`{{ url('/input') }}/${selectedChild}/growth`" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="weight" x-model="weight">
                <input type="hidden" name="height" x-model="height">
                <input type="hidden" name="head_circumference" x-model="head">
                <input type="hidden" name="recorded_at" value="{{ date('Y-m-d') }}">
                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg transition-all transform hover:-translate-y-1">
                    Simpan Data Tumbuh Kembang
                </button>
            </form>
        </div>
    </template>

    {{-- Quick Action Buttons --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <button @click="showAddModal = true" class="flex items-center justify-center gap-2 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-blue-400 transition">
            <span class="text-2xl">➕</span>
            <span class="font-bold text-gray-700">Registrasi Anak Baru</span>
        </button>
        <a href="{{ route('input') }}" class="flex items-center justify-center gap-2 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-blue-400 transition">
            <span class="text-2xl">📋</span>
            <span class="font-bold text-gray-700">Lihat Semua Data</span>
        </a>
    </div>

    {{-- Add Child Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="showAddModal = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Registrasi Anak Baru</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('child.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Anak</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="birth_date" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <div class="flex gap-4">
                        <label class="flex-1 flex items-center justify-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-accent has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                            <input type="radio" name="gender" value="L" required class="text-accent"> 👦 Laki-laki
                        </label>
                        <label class="flex-1 flex items-center justify-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-accent has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                            <input type="radio" name="gender" value="P" class="text-accent"> 👧 Perempuan
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto (opsional)</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-sm">
                </div>
                <button type="submit" class="w-full py-2.5 bg-primary text-white rounded-xl font-medium text-sm hover:bg-primary-600">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection