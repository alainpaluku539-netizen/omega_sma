<div class="glass-panel p-6 rounded-[2rem] relative overflow-hidden flex flex-col items-center" x-data="{
    temp: @entangle('temp'),
    isHeating: @entangle('isHeating'),
    maxTemp: 40,
    circumference: 553,
    get offset() {
        let t = this.temp || 0;
        return this.circumference - (t / this.maxTemp) * this.circumference
    }
}">

    <!-- Header du Widget -->
    <div class="flex justify-between w-full mb-6">
        <div class="flex flex-col">
            <span class="text-[10px] uppercase font-bold tracking-[0.2em] opacity-50 text-slate-400">Temp Control</span>
            <div class="flex gap-2 mt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shadow-[0_0_8px_#22d3ee]"></span>
            </div>
        </div>
        <button class="opacity-30 hover:opacity-100 transition text-white">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
    </div>

    <!-- Conteneur Thermostat avec boutons latéraux -->
    <div class="flex items-center justify-center gap-2 sm:gap-6 w-full">

        <!-- Bouton DECREMENT (Gauche) -->
        <button wire:click="decrement"
            class="w-12 h-12 flex items-center justify-center text-3xl font-extralight text-white/30 hover:text-cyan-400 hover:bg-white/5 rounded-full transition-all duration-200 active:scale-90">
            <i class="fa-solid fa-minus"></i>
        </button>

        <!-- Cercle Thermostat SVG -->
        <div class="relative flex items-center justify-center w-48 h-48 sm:w-52 sm:h-52 shrink-0">
            <svg class="absolute w-full h-full transform -rotate-90" viewBox="0 0 208 208">
                <!-- Cercle de fond (Track) -->
                <circle cx="104" cy="104" r="88" stroke="currentColor" stroke-width="4" fill="transparent"
                    class="text-white/5" />

                <!-- Cercle de progression dynamique -->
                <circle cx="104" cy="104" r="88" stroke="currentColor" stroke-width="6" fill="transparent"
                    class="text-cyan-400 transition-all duration-700 ease-out" stroke-linecap="round"
                    :stroke-dasharray="circumference" :stroke-dashoffset="offset"
                    style="filter: drop-shadow(0 0 12px rgba(34, 211, 238, 0.4));" />
            </svg>

            <!-- Affichage Central -->
            <div class="absolute inset-0 flex flex-col items-center justify-center z-10 pointer-events-none">
                <div class="flex items-start justify-center mt-2 pointer-events-auto">
                    <span class="text-5xl sm:text-6xl font-extralight tracking-tighter text-white leading-none"
                        x-text="temp ? temp.toFixed(1) : '0.0'">
                    </span>
                    <span class="text-lg sm:text-xl font-light text-cyan-400 ml-1 mt-1">°C</span>
                </div>
                <p class="text-[8px] sm:text-[9px] uppercase tracking-[0.2em] opacity-40 text-slate-300 font-bold mt-1">
                    Setpoint
                </p>
            </div>
        </div>

        <!-- Bouton INCREMENT (Droite) -->
        <button wire:click="increment"
            class="w-12 h-12 flex items-center justify-center text-3xl font-extralight text-white/30 hover:text-cyan-400 hover:bg-white/5 rounded-full transition-all duration-200 active:scale-90">
            <i class="fa-solid fa-plus"></i>
        </button>

    </div>

    <!-- État du Chauffage -->
    <div class="mt-6 flex flex-col items-center">
        <span :class="isHeating ? 'text-cyan-400 animate-pulse' : 'text-white/20'"
            class="text-[10px] uppercase font-bold tracking-[0.3em] transition-all duration-1000">
            System {{ $isHeating ? 'Heating' : 'Idle' }}
        </span>
    </div>

    <!-- Sélecteur de pièces (Tabs) -->
    <div class="flex gap-4 sm:gap-6 mt-8 border-t border-white/5 pt-6 w-full justify-center">
        <button
            class="text-[9px] uppercase font-bold tracking-widest text-white border-b border-cyan-400 pb-1">Salon</button>
        <button
            class="text-[9px] uppercase font-bold tracking-widest opacity-30 hover:opacity-60 transition">Chambre</button>
        <button
            class="text-[9px] uppercase font-bold tracking-widest opacity-30 hover:opacity-60 transition">Cuisine</button>
    </div>
</div>
