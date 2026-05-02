<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} - GoKids Admin</title>
    <meta name="description" content="GoKids Admin - Panel administrasi platform pemantauan tumbuh kembang anak">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.15);
            border-radius: 0.75rem;
        }
        .sidebar-link.active { background: rgba(255,255,255,0.2); }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-page min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 h-full w-[220px] bg-primary text-white flex flex-col z-50 shadow-xl">
            <!-- Logo -->
            <div class="px-5 py-6 flex items-center gap-3 border-b border-white/10">
                <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M12 14c-6 0-8 3-8 5v1h16v-1c0-2-2-5-8-5z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">GoKids</span>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="/admin/dashboard" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span class="text-lg">📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/artikel" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->is('admin/artikel*') ? 'active' : '' }}">
                    <span class="text-lg">📝</span>
                    <span>Artikel</span>
                </a>
                <a href="/admin/laporan" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                    <span class="text-lg">📋</span>
                    <span>Laporan</span>
                </a>
                <a href="/admin/profil" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->is('admin/profil*') ? 'active' : '' }}">
                    <span class="text-lg">👤</span>
                    <span>Profil</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="px-3 pb-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-white/80 hover:text-white">
                        <span class="text-lg">🚪</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-[220px] flex-1 min-h-screen">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-6 mt-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium flex items-center gap-2" id="flash-success">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-6 mt-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium flex items-center gap-2" id="flash-error">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        setTimeout(() => {
            ['flash-success', 'flash-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
