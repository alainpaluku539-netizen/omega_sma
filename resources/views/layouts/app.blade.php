<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Omega Smart Home' }}</title>

    <!-- Fonts & Icons (FontAwesome + Bootstrap Icons) -->
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="https://jsdelivr.net">

    <!-- Scripts & Styles -->
    <script src="https://jsdelivr.net"></script>
    <script src="https://jsdelivr.net"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; scrollbar-gutter: stable; }
        
        .glass-panel {
            background: linear-gradient(135deg, rgba(255,255,255,0.03), rgba(0,0,0,0.3));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(34,211,238,0.2); border-radius: 10px; }
        
        .sidebar-transition { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>

<body
    x-data="{ sidebarOpen: true, mobileSidebar: false }"
    class="bg-slate-950 text-white antialiased h-screen overflow-hidden custom-scrollbar"
>

    <!-- BACKGROUND LAYER (Ambiance) -->
    <div class="fixed inset-0 bg-[url('https://unsplash.com')] bg-cover bg-center opacity-[0.03] pointer-events-none"></div>
    <div class="fixed inset-0 bg-gradient-to-tr from-cyan-500/5 via-transparent to-blue-500/5 pointer-events-none"></div>

    <!-- APP WRAPPER -->
    <div class="relative z-10 flex flex-col lg:flex-row h-full w-full overflow-hidden p-2 sm:p-4 lg:p-6 gap-4 lg:gap-6">

        <!-- MOBILE TOP BAR -->
        <div class="lg:hidden glass-panel shrink-0 p-4 rounded-2xl flex justify-between items-center shadow-2xl">
            <button @click="mobileSidebar = true" class="w-10 h-10 flex items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-400">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <h1 class="uppercase text-xs tracking-[0.4em] font-black text-white">Omega</h1>
            <div class="text-[10px] font-mono opacity-60 bg-slate-900/50 px-3 py-1 rounded-full border border-white/5">
                {{ now()->format('H:i') }}
            </div>
        </div>

        <!-- BACKDROP MOBILE -->
        <div x-show="mobileSidebar" x-cloak x-transition.opacity 
             class="fixed inset-0 bg-slate-950/90 backdrop-blur-md z-[60] lg:hidden"
             @click="mobileSidebar = false">
        </div>

        <!-- SIDEBAR -->
        @auth
        <livewire:sidebar />
        @endauth
        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0 h-full gap-4 lg:gap-6 overflow-hidden">
            
            <!-- HEADER BAR -->
            @auth
            <livewire:header />
            @endauth

            <!-- PAGE CONTENT -->
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-1">
                {{ $slot }}
            </div>

        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
