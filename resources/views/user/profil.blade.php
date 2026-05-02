@extends('layouts.user')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-primary">Profil Saya</h1>

    <!-- Avatar & Info -->
    <div class="bg-white rounded-xl shadow-md p-6 flex items-center gap-5">
        <div class="w-20 h-20 rounded-full bg-accent flex items-center justify-center text-white text-3xl font-bold overflow-hidden flex-shrink-0">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
        </div>
    </div>

    <!-- Edit Profile -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Data Diri</h3>
        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent" placeholder="Contoh: 08123456789">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent resize-none" placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                <input type="file" name="avatar" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-accent/10 file:text-accent hover:file:bg-accent/20">
                @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-full font-medium text-sm hover:bg-primary-600 transition shadow-md">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ganti Password</h3>
        <form action="{{ route('profil.password') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent" placeholder="Minimal 8 karakter">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-transparent" placeholder="Ulangi password baru">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-red-500 text-white rounded-full font-medium text-sm hover:bg-red-600 transition shadow-md">
                Ganti Password
            </button>
        </form>
    </div>
</div>
@endsection
