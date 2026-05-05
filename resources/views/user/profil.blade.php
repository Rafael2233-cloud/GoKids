@extends('layouts.user')
@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="{ avatarPreview: null }">
    
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi akun dan pengaturan Anda</p>
    </div>

    {{-- Avatar & Quick Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row items-center gap-5">
            <div class="relative">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-3xl font-bold overflow-hidden shadow-lg">
                    <template x-if="avatarPreview">
                        <img :src="avatarPreview" alt="Preview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!avatarPreview">
                        <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </template>
                </div>
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="absolute inset-0 w-24 h-24 rounded-full object-cover" x-show="!avatarPreview">
                @endif
            </div>
            <div class="text-center sm:text-left">
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm mt-0.5">{{ $user->email }}</p>
                @if($user->phone)
                    <p class="text-gray-400 text-xs mt-1">📱 {{ $user->phone }}</p>
                @endif
                <span class="inline-block mt-2 px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded-full">
                    Akun Terdaftar
                </span>
            </div>
        </div>
    </div>

    {{-- Edit Profile Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Data Diri</h3>
        </div>
        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent transition">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent transition">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent transition" placeholder="08123456789">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent resize-none transition" placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto Profil</label>
                <input type="file" name="avatar" accept="image/*" @change="avatarPreview = URL.createObjectURL($event.target.files[0])"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-accent/10 file:text-accent hover:file:bg-accent/20 transition">
                @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-medium text-sm hover:bg-primary-600 transition shadow-sm">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Ganti Password</h3>
        </div>
        <form action="{{ route('profil.password') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent transition" placeholder="Minimal 8 karakter">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent transition" placeholder="Ulangi password baru">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-red-500 text-white rounded-xl font-medium text-sm hover:bg-red-600 transition shadow-sm">
                Ganti Password
            </button>
        </form>
    </div>

    {{-- Account Info --}}
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Akun</h3>
        <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Terdaftar Sejak</span>
                <span class="text-gray-700 font-medium">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-500">Terakhir Diperbarui</span>
                <span class="text-gray-700 font-medium">{{ $user->updated_at ? $user->updated_at->translatedFormat('d F Y H:i') : '-' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
