<aside x-cloak
    class="sidebar-transition fixed lg:static top-0 left-0 z-[70] lg:z-auto h-screen lg:h-full glass-panel rounded-r-xl lg:rounded-xl flex flex-col border-r border-white/10 shadow-[0_20px_60px_rgba(0,0,0,0.35)] overflow-hidden"
    :class="{
        'w-80': sidebarOpen,
        'w-24': !sidebarOpen,
        'translate-x-0': mobileSidebar,
        '-translate-x-full lg:translate-x-0': !mobileSidebar
    }">

    <!-- BACKGROUND FX -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-0 w-40 h-40 bg-cyan-500/10 blur-3xl rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-40 h-40 bg-indigo-500/10 blur-3xl rounded-full"></div>
    </div>

    <!-- MOBILE CLOSE -->
    <button @click="mobileSidebar = false"
        class="lg:hidden absolute top-5 right-5 z-20 text-slate-400 hover:text-white transition">
        <i class="bi bi-x-lg text-xl"></i>
    </button>

    <!-- HEADER: LOGO -->
    <div class="relative z-10 shrink-0 px-4 pt-6 pb-4 border-b border-white/5">
        <div class="flex items-center gap-3">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-400/20 to-blue-500/20 flex items-center justify-center text-cyan-400 text-2xl shadow-[0_0_30px_rgba(34,211,238,0.25)]">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>

            <div x-show="sidebarOpen" x-transition.opacity class="min-w-0">
                <h1 class="text-lg font-black tracking-wide truncate">OMEGA CORE</h1>
                <p class="text-[11px] text-slate-400 uppercase tracking-[0.25em] truncate">Smart Home OS</p>
            </div>
        </div>

        <!-- SYSTEM STATUS -->
        <div x-show="sidebarOpen" x-transition.opacity class="mt-4">
            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 px-3 py-2 flex items-center justify-between">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                    <span class="text-xs text-emerald-300 font-semibold truncate">System Online</span>
                </div>
                <span class="text-[10px] text-emerald-400 shrink-0 font-bold">LIVE</span>
            </div>
        </div>
    </div>

    <!-- USER PROFILE -->
    <div class="relative z-10 shrink-0 px-4 py-4 border-b border-white/5">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-11 h-11 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-cyan-400 shrink-0">
                <i class="bi bi-person-fill"></i>
            </div>
            <div x-show="sidebarOpen" x-transition.opacity class="min-w-0 flex-1">
                <p class="text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-xs text-slate-400 truncate">Control Center</p>
            </div>
        </div>
    </div>

    <!-- NAVIGATION -->
    <div class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden custom-scrollbar relative z-10">
        <nav class="px-3 py-4 space-y-2">

            <!-- SECTION: MAIN -->
            <div x-show="sidebarOpen" class="px-3 pb-1 text-[10px] uppercase tracking-[0.35em] text-slate-500 font-bold">
                Main Console
            </div>
            
            <x-nav-link href="{{ route('dashboard') }}" icon="house" label="Dashboard" :active="request()->routeIs('dashboard')" wire:navigate />
            <x-nav-link href="{{ route('devices') }}" icon="cpu" label="Devices" :active="request()->routeIs('devices')" wire:navigate />
            <x-nav-link href="{{ route('lights') }}" icon="lightbulb" label="Lighting" :active="request()->routeIs('lights')" wire:navigate />
            <x-nav-link href="{{ route('sensors') }}" icon="broadcast" label="Sensors" :active="request()->routeIs('sensors')" wire:navigate />

            <!-- SECTION: MILITARY SYSTEM (ARMY) -->
            <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-[0.35em] text-amber-500/70 font-bold">
                Military Module
            </div>
            @can('access-military')
                <x-nav-link 
                    href="{{ route('army.documents') }}" 
                    icon="file-earmark-arrow-up" 
                    label="Documents" 
                    :active="request()->routeIs('army.documents')" 
                    wire:navigate 
                />
            @endcan

            <!-- SECTION: MANAGEMENT -->
            <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-[0.35em] text-slate-500 font-bold">
                Management
            </div>
            <x-nav-link href="{{ route('security') }}" icon="shield-check" label="Security" :active="request()->routeIs('security')" wire:navigate />
            <x-nav-link href="{{ route('cameras') }}" icon="camera-video" label="Cameras" :active="request()->routeIs('cameras')" wire:navigate />
            <x-nav-link href="{{ route('analytics') }}" icon="graph-up-arrow" label="Analytics" :active="request()->routeIs('analytics')" wire:navigate />
            <x-nav-link href="{{ route('settings') }}" icon="gear" label="Settings" :active="request()->routeIs('settings')" wire:navigate />

            <!-- QUICK ACTIONS (Only Visible when Open) -->
            <div x-show="sidebarOpen" x-transition.opacity class="mt-6 rounded-3xl bg-white/5 border border-white/10 p-4">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400 mb-3">Quick Actions</p>
                <div class="grid grid-cols-2 gap-2">
                    <button class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 py-2 text-[10px] font-bold text-emerald-300 hover:bg-emerald-500/20 transition">ALL ON</button>
                    <button class="rounded-2xl bg-rose-500/10 border border-rose-500/20 py-2 text-[10px] font-bold text-rose-300 hover:bg-rose-500/20 transition">ALL OFF</button>
                </div>
            </div>

        </nav>
    </div>

<!-- FOOTER: SYSTEM MONITOR & ACTIONS -->
<div class="relative z-10 shrink-0 border-t border-white/5 p-4 space-y-4 bg-black/20">
    
    <!-- SYSTEM MONITOR (Visible quand ouvert) -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         class="space-y-4 px-1">
        
        <!-- CPU UNIT -->
        <div class="group cursor-help">
            <div class="flex justify-between text-[9px] text-slate-500 mb-1.5 font-black tracking-tighter">
                <span class="group-hover:text-cyan-400 transition-colors">CPU CORE_01</span>
                <span class="text-cyan-400 font-mono">42.8%</span>
            </div>
            <div class="h-1.5 rounded-full bg-slate-800/50 p-[1px] overflow-hidden border border-white/5">
                <div class="h-full w-[42%] bg-gradient-to-r from-cyan-600 to-cyan-400 rounded-full shadow-[0_0_12px_rgba(34,211,238,0.4)] relative">
                    <!-- Animation de balayage -->
                    <div class="absolute inset-0 bg-white/20 w-1/2 -skew-x-12 animate-[shimmer_2s_infinite]"></div>
                </div>
            </div>
        </div>

        <!-- RAM UNIT -->
        <div class="group cursor-help">
            <div class="flex justify-between text-[9px] text-slate-500 mb-1.5 font-black tracking-tighter">
                <span class="group-hover:text-indigo-400 transition-colors">MEMORY STACK</span>
                <span class="text-indigo-400 font-mono">68.2%</span>
            </div>
            <div class="h-1.5 rounded-full bg-slate-800/50 p-[1px] overflow-hidden border border-white/5">
                <div class="h-full w-[68%] bg-gradient-to-r from-indigo-600 to-blue-400 rounded-full shadow-[0_0_12px_rgba(129,140,248,0.4)]"></div>
            </div>
        </div>

        <!-- LOGOUT BUTTON (Fortify) -->
        <form method="POST" action="{{ route('logout') }}" class="pt-2">
            @csrf
            <button type="submit" 
                class="w-full group/logout flex items-center justify-center gap-3 py-2.5 rounded-xl border border-rose-500/20 bg-rose-500/5 hover:bg-rose-500/10 transition-all duration-300">
                <i class="bi bi-power text-rose-500 group-hover/logout:scale-110 transition-transform"></i>
                <span class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em]">Terminate Session</span>
            </button>
        </form>
    </div>

    <!-- COLLAPSE TOGGLE -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="w-full p-3.5 rounded-2xl bg-white/5 hover:bg-cyan-500/10 text-cyan-400 transition-all duration-500 flex items-center justify-center gap-3 border border-white/5 group">
        
        <div class="relative">
            <i class="bi" :class="sidebarOpen ? 'bi-chevron-left' : 'bi-chevron-right'"></i>
            <!-- Petit point d'alerte si sidebar fermée -->
            <span x-show="!sidebarOpen" class="absolute -top-1 -right-1 w-2 h-2 bg-cyan-400 rounded-full animate-ping"></span>
        </div>

        <span x-show="sidebarOpen" 
              x-transition:enter="delay-200"
              class="text-[10px] font-black uppercase tracking-[0.3em]">
            System Collapse
        </span>
    </button>
</div>

<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(200%); }
    }
</style>


</aside>
