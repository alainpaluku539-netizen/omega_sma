<div class="glass-panel p-4 sm:p-5 rounded-[2rem] flex flex-col h-full border border-white/5" x-data="{
    brightness: @entangle('brightness'),
    activeColor: @entangle('activeColor'),
    selectedRoom: @entangle('selectedRoom')
}">

    <!-- Header adaptatif -->
    <div class="flex justify-between items-start mb-4 sm:mb-6">
        <div class="flex flex-col">
            <h3 class="text-[9px] sm:text-[10px] uppercase font-bold tracking-[0.2em] opacity-50 text-slate-400">Smart
                Lighting</h3>
            <p class="text-xs font-light mt-1 text-white truncate max-w-[120px]" x-text="selectedRoom"></p>
        </div>
        <span class="text-lg sm:text-xl font-extralight text-cyan-400 font-mono" x-text="brightness + '%'"></span>
    </div>

    <!-- Selecteur de Couleur (Interactif + Sync PHP) -->
    <div class="relative h-10 sm:h-12 w-full rounded-xl sm:rounded-2xl mb-6 sm:mb-8 flex items-center px-1.5 shadow-inner overflow-hidden border border-white/5 cursor-crosshair group"
        style="background: linear-gradient(to right, #ff0000, #ffeb00, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000);"
        @click="
            const rect = $el.getBoundingClientRect();
            const x = (($event.clientX - rect.left) / rect.width) * 360;
            activeColor = `hsl(${Math.round(x)}, 100%, 50%)`;
            $wire.set('activeColor', activeColor); {{-- Envoie direct à PHP --}}
         ">

        <!-- Curseur avec lueur (glow) -->
        <div class="w-7 h-7 sm:w-8 sm:h-8 border-[3px] border-white rounded-full shadow-lg transition-transform group-hover:scale-110 pointer-events-none"
            :style="'background-color:' + activeColor + '; box-shadow: 0 0 15px ' + activeColor">
        </div>

        <div
            class="absolute bottom-0.5 w-full hidden sm:flex justify-between text-[6px] uppercase font-black opacity-30 px-3 tracking-widest text-white">
            <span>Warm Spectrum</span>
            <span>Cool Spectrum</span>
        </div>
    </div>

    <!-- Controle d'Intensite -->
    <div class="space-y-3 sm:space-y-4 mb-6 sm:mb-8">
        <div class="flex justify-between items-center px-1">
            <span
                class="text-[8px] sm:text-[9px] uppercase font-bold tracking-widest opacity-40 text-slate-300">Dimmer</span>
            <span class="text-[8px] sm:text-[9px] font-mono text-cyan-400 bg-cyan-400/10 px-1.5 py-0.5 rounded"
                x-text="brightness + '%'"></span>
        </div>
        <input type="range" min="0" max="100" x-model.live="brightness"
            class="w-full h-1.5 bg-white/10 rounded-lg appearance-none cursor-pointer accent-cyan-400 hover:bg-white/20 transition-all">
    </div>

    <!-- Grille des pieces (Responsive optimisée) -->
    <div class="grid grid-cols-2 xs:grid-cols-3 lg:grid-cols-2 gap-2 sm:gap-3 mt-auto">
        <template x-for="room in ['Living Room', 'Kitchen', 'Bedroom', 'Garden', 'Garage', 'Entry']"
            :key="room">
            <button @click="selectedRoom = room; $wire.toggleRoom(room)"
                :class="selectedRoom === room ? 'bg-cyan-500/20 border-cyan-500/40 shadow-[0_0_15px_rgba(34,211,238,0.1)]' :
                    'bg-white/5 border-transparent opacity-50'"
                class="flex flex-col items-center sm:items-start p-2.5 sm:p-3 rounded-xl sm:rounded-2xl border transition-all duration-300 group hover:opacity-100">

                <span class="text-[8px] sm:text-[9px] font-bold uppercase tracking-tight text-center sm:text-left"
                    :class="selectedRoom === room ? 'text-cyan-400' : 'text-slate-300'" x-text="room"></span>

                <div class="flex items-center gap-1.5 mt-1">
                    <div class="w-1 h-1 rounded-full"
                        :class="selectedRoom === room ? 'bg-cyan-400 animate-pulse' : 'bg-white/20'"></div>
                    <span
                        class="hidden xs:block text-[7px] opacity-30 uppercase tracking-tighter group-hover:opacity-60">Status</span>
                </div>
            </button>
        </template>
    </div>

    <!-- Master Controls -->
    <div class="flex flex-row gap-2 sm:gap-3 mt-5 sm:mt-6">
        <button wire:click="allOff"
            class="flex-1 py-2.5 bg-rose-500/10 border border-rose-500/20 rounded-xl text-[8px] uppercase font-bold text-rose-400 hover:bg-rose-500/20 transition-all active:scale-95">
            <i class="fa-solid fa-power-off mr-1 text-[7px]"></i> All Off
        </button>
        <button wire:click="allOn"
            class="flex-1 py-2.5 bg-cyan-500/10 border border-cyan-500/20 rounded-xl text-[8px] uppercase font-bold text-cyan-400 hover:bg-cyan-500/20 transition-all active:scale-95">
            <i class="fa-solid fa-sun mr-1 text-[7px]"></i> All On
        </button>
    </div>
</div>
