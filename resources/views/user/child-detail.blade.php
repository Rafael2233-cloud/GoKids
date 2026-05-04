@extends('layouts.user')

@section('content')
    <div class="max-w-4xl space-y-6 pb-10">

        {{-- Header: Back Button & Title --}}
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ url('/input') }}"
                class="text-blue-600 hover:text-blue-800 transition flex items-center justify-center p-2 rounded-full hover:bg-blue-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl font-extrabold text-blue-900 tracking-tight">Detail Anak</h1>
        </div>

        {{-- 1. Kartu Profil Anak --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-6">
            {{-- Foto / Avatar --}}
            <div
                class="w-24 h-24 rounded-full flex-shrink-0 border-2 border-gray-100 overflow-hidden bg-gray-50 flex items-center justify-center">
                @if (isset($child->photo) && $child->photo)
                    <img src="{{ asset('storage/' . $child->photo) }}" alt="Foto {{ $child->name }}"
                        class="w-full h-full object-cover">
                @else
                    {{-- Default Avatar Icon persis seperti gambar --}}
                    <svg class="w-14 h-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </div>

            {{-- Info Teks --}}
            <div class="flex-1">
                <h2 class="text-2xl font-extrabold text-gray-900">{{ $child->name }}</h2>
                <div class="text-gray-500 mt-1.5 flex items-center gap-2 text-sm font-medium">
                    <span>{{ $child->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    <span>•</span>
                    <span>{{ \Carbon\Carbon::parse($child->birth_date)->diff(\Carbon\Carbon::now())->format('%y tahun %m bulan') }}</span>
                </div>
                <p class="text-gray-400 text-xs mt-1.5">Lahir:
                    {{ \Carbon\Carbon::parse($child->birth_date)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        {{-- 2. Kartu Riwayat Pertumbuhan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                📊 Riwayat Pertumbuhan
            </h3>

            <div class="space-y-4">
                @forelse($child->growthRecords as $record)
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-blue-100 transition">
                        <div>
                            <p class="font-bold text-gray-900 text-base mb-1">
                                {{ \Carbon\Carbon::parse($record->recorded_at)->translatedFormat('d F Y') }}
                            </p>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span>Berat: <strong class="text-gray-800">{{ $record->weight }} kg</strong></span>
                                <span>Tinggi: <strong class="text-gray-800">{{ $record->height }} cm</strong></span>
                                @if ($record->head_circumference)
                                    <span>Lingkar Kepala: <strong class="text-gray-800">{{ $record->head_circumference }}
                                            cm</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            {{-- Logika sederhana untuk status. Nanti sesuaikan dengan kolom database-mu --}}
                            <span
                                class="px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-wider">
                                Data Tersimpan
                            </span>
                        </div>
                    </div>
                @empty
                    {{-- Tampilan saat data kosong (Sesuai Gambar) --}}
                    <div class="py-10 flex flex-col items-center justify-center text-center">
                        <p class="text-gray-500 font-medium text-sm mb-4">Belum ada data pertumbuhan.</p>
                        {{-- Tombol Tambah Data (Opsional, tapi sangat disarankan UX-nya) --}}
                        {{-- <button class="px-5 py-2 bg-blue-600 text-white rounded-full text-xs font-bold hover:bg-blue-700 transition"> + Input Pertumbuhan</button> --}}
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 3. Kartu Jadwal Vaksinasi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                💉 Jadwal Vaksinasi
            </h3>

            <div class="space-y-4">
                @forelse($child->vaccinations as $vaccine)
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-blue-100 transition">
                        <div>
                            <p class="font-bold text-gray-900 text-base mb-1">{{ $vaccine->vaccine_name }}</p>
                            <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Jadwal: {{ \Carbon\Carbon::parse($vaccine->scheduled_date)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            {{-- Ubah pengecekan menjadi 'done' --}}
                            @if ($vaccine->status === 'done')
                                <span
                                    class="px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-wider">Selesai</span>
                            @else
                                <span
                                    class="px-4 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 text-xs font-bold rounded-full uppercase tracking-wider">Menunggu</span>
                            @endif
                        </div>
                    </div>
                @empty
                    {{-- Tampilan saat data kosong (Sesuai Gambar) --}}
                    <div class="py-10 flex flex-col items-center justify-center text-center">
                        <p class="text-gray-500 font-medium text-sm">Belum ada jadwal vaksinasi.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
