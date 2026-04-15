<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>{{ $title ?? 'Omega Smart Home' }}</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cloudflare.com">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; scrollbar-gutter: stable; }
        
        .glass-panel {
            background: linear-gradient(135deg, rgba(255,255,255,0.05), rgba(0,0,0,0.2));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(34,211,238,0.3); border-radius: 10px; }
        
        /* Smooth transitions pour le resize sidebar */
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease; }
    </style>
    <style>
        #sensorDoubleChart {
            filter: drop-shadow(0 0 5px rgba(34, 211, 238, 0.2));
        }
    </style>

</head>

<body
    x-data="{ sidebarOpen: false, mobileSidebar: false }"
    class="bg-slate-950 text-white antialiased h-screen overflow-hidden"
>


<!-- BACKGROUND LAYER -->
<div class="fixed inset-0 bg-[url('https://images.unsplash.com/photo-1497493292307-31c376b6e479')] bg-cover bg-center opacity-10 pointer-events-none"></div>

<!-- APP WRAPPER -->
<div class="relative z-10 flex flex-col lg:flex-row h-full w-full overflow-hidden p-2 sm:p-4 lg:p-6 gap-3 lg:gap-6">

    <!-- MOBILE TOP BAR (Smartphone & Small Tablet) -->
    <div class="lg:hidden glass-panel shrink-0 p-4 rounded-2xl flex justify-between items-center shadow-2xl">
        <button @click="mobileSidebar = true" class="w-10 h-10 flex items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-400">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
        <h1 class="uppercase text-xs tracking-[0.4em] font-bold text-white">Omega</h1>
        <div class="text-[10px] font-mono opacity-60 bg-slate-900/50 px-2 py-1 rounded-md">
            {{ now()->format('H:i') }}
        </div>
    </div>

    <!-- BACKDROP MOBILE -->
    <div x-show="mobileSidebar" x-cloak x-transition.opacity 
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[60] lg:hidden"
         @click="mobileSidebar = false">
    </div>

    <!-- SIDEBAR (Responsive Logic) -->
    @auth
    <aside
        x-cloak
        class="sidebar-transition fixed lg:static top-0 left-0 z-[70] lg:z-auto h-full glass-panel rounded-r-3xl lg:rounded-2xl flex flex-col py-6 shadow-2xl overflow-hidden"
        :class="{
            'w-72': sidebarOpen,
            'w-20': !sidebarOpen,
            'translate-x-0': mobileSidebar,
            '-translate-x-full lg:translate-x-0': !mobileSidebar
        }"
        >
        <!-- CLOSE MOBILE BUTTON -->
        <button @click="mobileSidebar = false" class="lg:hidden absolute top-5 right-5 text-slate-400 hover:text-white">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <!-- LOGO AREA -->
        <div class="flex flex-col items-center mb-10 shrink-0">
            <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-cyan-500/20 shadow-[0_0_20px_rgba(34,211,238,0.2)] text-cyan-400 text-2xl">
                <i class="fa-solid fa-bolt-lightning"></i>
            </div>
            <span x-show="sidebarOpen" x-transition.opacity class="text-[10px] uppercase tracking-[0.5em] text-cyan-400 mt-4 font-black">
                Omega
            </span>
        </div>

        <!-- NAVIGATION -->
        <nav class="flex-1 flex flex-col gap-2 px-3">
            <x-nav-link href="{{ route('dashboard') }}" icon="house" label="Dashboard" :active="request()->routeIs('dashboard')" wire:navigate />
            <x-nav-link href="{{ route('devices') }}" icon="cpu" label="Devices" :active="request()->routeIs('devices')" wire:navigate />
            <x-nav-link href="{{ route('lights') }}" icon="lightbulb" label="Lights" :active="request()->routeIs('lights')" wire:navigate />
            <x-nav-link href="{{ route('sensors') }}" icon="broadcast" label="Sensors" :active="request()->routeIs('sensors')" wire:navigate />
            <x-nav-link href="#" icon="shield-check" label="Security" />
            <x-nav-link href="#" icon="graph-up-arrow" label="Analytics" />
        </nav>




        <!-- DESKTOP TOGGLE -->
        <div class="mt-auto hidden lg:flex justify-center p-4 border-t border-white/5">
            <button @click="sidebarOpen = !sidebarOpen" class="w-full p-3 rounded-xl bg-white/5 hover:bg-cyan-500/10 text-cyan-400 transition-all flex items-center justify-center">
                <i class="fa-solid" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>
        </div>
    </aside>
    @endauth

    <!-- MAIN INTERFACE AREA -->
    <div class="flex-1 flex flex-col min-w-0 h-full gap-4">

    <!-- On ajoute overflow-visible pour laisser sortir le dropdown -->
    <header class="glass-panel rounded-2xl p-4 sm:p-5 flex justify-between items-center shrink-0 shadow-lg mb-4 relative z-[60] overflow-visible">
        <div class="flex items-center gap-4">
            <!-- TOGGLE SIDEBAR BUTTON -->
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="hidden lg:flex w-10 h-10 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500/20 transition-all border border-cyan-500/20">
                <i class="fa-solid" :class="sidebarOpen ? 'fa-bars-staggered' : 'fa-bars'"></i>
            </button>

            <h1 class="hidden sm:block text-sm lg:text-lg font-light tracking-[0.4em] uppercase text-slate-400">
                Control <span class="text-white font-bold">Terminal</span>
            </h1>
        </div>

        <div class="flex items-center gap-4">
            <!-- SYSTEM STATUS -->
            <div class="hidden md:flex flex-col text-right mr-4 border-r border-white/10 pr-4">
                <span class="text-[10px] uppercase text-cyan-400 font-bold tracking-widest">System Status</span>
                <span class="text-[9px] text-emerald-400 animate-pulse">● All Nodes Online</span>
            </div>
            
            @auth
            <!-- USER PROFILE WITH DROPDOWN -->
            <div class="relative" x-data="{ userMenuOpen: false }" @click.away="userMenuOpen = false">
                <button @click="userMenuOpen = !userMenuOpen" 
                        class="flex items-center gap-3 bg-white/5 p-1 pr-4 rounded-full border border-white/5 shadow-inner hover:bg-white/10 transition-all focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-cyan-600 to-blue-600 flex items-center justify-center text-[10px] font-bold shadow-lg text-white">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="text-left leading-none">
                        <p class="text-[10px] font-bold text-white uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] opacity-50 uppercase tracking-tighter">Administrator <i class="fa-solid fa-chevron-down ml-1 text-[7px]"></i></p>
                    </div>
                </button>

                <!-- DROPDOWN MENU - z-[100] et pointer-events-auto pour assurer l'interaction -->
                <div x-show="userMenuOpen" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 top-full mt-2 w-48 glass-panel rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] py-2 z-[100] border border-white/20 pointer-events-auto">
                    
                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-[11px] uppercase tracking-widest text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400 transition-all">
                        <i class="fa-solid fa-user-gear text-[14px]"></i> Profile
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-[11px] uppercase tracking-widest text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400 transition-all">
                        <i class="fa-solid fa-gears text-[14px]"></i> Settings
                    </a>

                    <div class="h-px bg-white/10 my-2 mx-2"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-[11px] uppercase tracking-widest text-rose-400 hover:bg-rose-500/10 transition-all text-left">
                            <i class="fa-solid fa-power-off text-[14px]"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </header>



        <!-- DYNAMIC CONTENT (SLOT) -->
        <main class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar min-h-0 rounded-2xl">
            <div class="pb-10"> <!-- Padding bottom pour pas que le contenu colle au bord -->
                {{ $slot }}
            </div>
        </main>

    </div>
</div>

@livewireScripts
</body>
</html>
