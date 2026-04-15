<div class="glass-panel p-6 rounded-xl flex flex-col h-full border border-white/5"
     x-data="{ isArmed: @entangle('isArmed') }">

    <!-- Header Securite -->
    <header class="flex justify-between items-center mb-6">
        <div class="flex flex-col">
            <h3 class="text-[10px] uppercase font-bold tracking-[0.2em] opacity-50 text-slate-400">Security System</h3>
            <span class="text-[9px] mt-1 transition-colors duration-500 font-bold uppercase tracking-widest"
                  :class="isArmed ? 'text-cyan-400' : 'text-rose-500'"
                  x-text="isArmed ? 'System Armed' : 'System Disarmed'"></span>
        </div>
        
        <!-- Toggle Switch -->
        <button @click="$wire.toggleSecurity()"
                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none"
                :class="isArmed ? 'bg-cyan-500/40' : 'bg-white/10'">
            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-lg transition-transform duration-300"
                  :class="isArmed ? 'translate-x-6' : 'translate-x-1'"></span>
        </button>
    </header>

    <!-- Grille des Cameras -->
    <div class="grid grid-cols-2 gap-4 flex-1">
        @foreach([
            ['name' => 'Front Door', 'id' => '1550009140933-2860d929a730'],
            ['name' => 'Living Room', 'id' => '1558036117-21122b6f265b'],
            ['name' => 'Garage', 'id' => '1532187641029-7521b2999a0a'],
            ['name' => 'Backyard', 'id' => '1595053825316-24e650302061']
        ] as $cam)
        <div class="relative group overflow-hidden rounded-2xl border border-white/5 aspect-video bg-slate-900 shadow-xl">
            <!-- Simulation de flux video avec images Unsplash specifiques -->
            <img src="https://unsplash.com{{ $cam['id'] }}?q=80&w=400" 
                 class="w-full h-full object-cover opacity-30 group-hover:scale-105 group-hover:opacity-50 transition duration-700">

            <!-- Overlay Camera -->
            <div class="absolute inset-0 p-3 flex flex-col justify-between bg-gradient-to-t from-black/90 via-transparent to-black/20">
                <div class="flex justify-between items-start">
                    <span class="text-[8px] font-bold uppercase tracking-widest text-white/80 flex items-center gap-1.5">
                        <i class="fa-solid fa-video text-[7px] text-cyan-400"></i>
                        {{ $cam['name'] }}
                    </span>
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse shadow-[0_0_5px_#ef4444]"></span>
                </div>

                @if($loop->first)
                <div class="bg-rose-500/20 text-rose-400 text-[7px] py-1 px-2 rounded-md border border-rose-500/30 backdrop-blur-md self-start animate-bounce uppercase font-bold tracking-tighter">
                    Activity Detected
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Selecteur de Scenes -->
    <div class="mt-6 border-t border-white/5 pt-5">
        <p class="text-[8px] uppercase font-bold tracking-[0.2em] opacity-30 mb-3 text-slate-300">Active Scenes</p>
        <div class="flex justify-between gap-2 overflow-x-auto pb-1 no-scrollbar">
            @foreach(['Relax', 'Sleep', 'Focus', 'Party'] as $scene)
            <button class="flex-1 py-2 px-1 bg-white/5 border border-white/5 rounded-xl text-[9px] uppercase font-bold text-slate-400 hover:bg-cyan-500/10 hover:text-cyan-400 hover:border-cyan-500/30 transition-all active:scale-95 whitespace-nowrap">
                {{ $scene }}
            </button>
            @endforeach
        </div>
    </div>
</div>
