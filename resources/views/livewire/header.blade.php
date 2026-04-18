
<header
    class="glass-panel rounded-xl p-2 sm:p-5 shadow-2xl mb-4 relative z-[60] overflow-visible border border-white/10">

    <!-- BACKGROUND FX -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden rounded-3xl">
        <div class="absolute -top-10 left-10 w-40 h-40 bg-cyan-500/10 blur-3xl rounded-full"></div>
        <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-500/10 blur-3xl rounded-full"></div>
    </div>

    <div class="relative z-10 flex justify-between items-center gap-4">

        <!-- LEFT SIDE -->
        <div class="flex items-center gap-4 min-w-0">

            <!-- MOBILE MENU -->
            <button @click="mobileSidebar = true"
                class="lg:hidden w-11 h-11 rounded-2xl bg-white/5 border border-white/10 text-cyan-400 hover:bg-cyan-500/10 transition flex items-center justify-center">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- DESKTOP TOGGLE -->
            <button @click="sidebarOpen = !sidebarOpen"
                class="hidden lg:flex w-11 h-11 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 hover:bg-cyan-500/20 transition items-center justify-center">
                <i class="fa-solid" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>

            <!-- TITLE -->
            <div class="min-w-0">
                <p class="text-[10px] uppercase tracking-[0.4em] text-cyan-400 font-bold">
                    Omega Control
                </p>

                <h1 class="text-base sm:text-lg lg:text-xl font-black truncate">
                    Smart Home Terminal
                </h1>

                <p class="text-[11px] text-slate-400 truncate">
                    Real-time IoT Monitoring Center
                </p>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="flex items-center gap-3">

            <!-- CLOCK -->
            <div x-data="{ time: '' }" x-init="const updateClock = () => {
                time = new Date().toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            };
            updateClock();
            setInterval(updateClock, 1000);"
                class="hidden xl:flex flex-col items-end px-4 border-r border-white/10">

                <span class="text-[10px] uppercase tracking-widest text-slate-500">
                    Local Time
                </span>

                <span class="text-sm font-bold text-white" x-text="time"></span>
            </div>

            <!-- SYSTEM STATUS -->
            <div
                class="hidden md:flex items-center gap-3 px-4 py-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/20">

                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>

                <div class="leading-none">
                    <p class="text-[10px] uppercase tracking-widest text-emerald-300 font-bold">
                        System Online
                    </p>
                    <p class="text-[10px] text-slate-400">
                        All Nodes Active
                    </p>
                </div>

            </div>

            <!-- NOTIFICATIONS -->
            <button
                class="relative w-11 h-11 rounded-2xl bg-white/5 border border-white/10 hover:bg-cyan-500/10 transition flex items-center justify-center text-slate-300 hover:text-cyan-400">

                <i class="fa-solid fa-bell"></i>

                <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-400 animate-ping"></span>

                <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-400"></span>

            </button>

            @auth
                <!-- USER MENU -->
                <div class="relative" x-data="{ userMenuOpen: false }" @click.away="userMenuOpen=false">

                    <button @click="userMenuOpen=!userMenuOpen"
                        class="flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full p-1 pr-4 transition shadow-inner">

                        <!-- AVATAR -->
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>

                        <!-- USER INFO -->
                        <div class="hidden sm:block text-left leading-none">
                            <p class="text-[11px] font-bold uppercase text-white">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-[9px] text-slate-400 uppercase tracking-wider">
                                Administrator
                                <i class="fa-solid fa-chevron-down ml-1 text-[8px]"></i>
                            </p>
                        </div>

                    </button>

                    <!-- DROPDOWN -->
                    <div x-show="userMenuOpen" x-cloak x-transition
                        class="absolute right-0 top-full mt-3 w-56 glass-panel rounded-2xl border border-white/10 shadow-[0_25px_60px_rgba(0,0,0,.45)] overflow-hidden">

                        <!-- USER HEADER -->
                        <div class="px-4 py-3 border-b border-white/5 bg-white/5">

                            <p class="text-sm font-bold text-white">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-[11px] text-slate-400">
                                {{ auth()->user()->email }}
                            </p>

                        </div>

                        <!-- LINKS -->
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400 transition">
                            <i class="fa-solid fa-user-gear"></i>
                            Profile
                        </a>

                        <a href="#"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400 transition">
                            <i class="fa-solid fa-sliders"></i>
                            Preferences
                        </a>

                        <a href="#"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400 transition">
                            <i class="fa-solid fa-shield-halved"></i>
                            Security
                        </a>

                        <div class="border-t border-white/5"></div>

                        <!-- LOGOUT -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-rose-400 hover:bg-rose-500/10 transition">
                                <i class="fa-solid fa-power-off"></i>
                                Sign Out
                            </button>
                        </form>

                    </div>

                </div>
            @endauth

        </div>

    </div>

</header>
