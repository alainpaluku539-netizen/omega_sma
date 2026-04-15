<div class="p-4 sm:p-6 lg:p-8 animate-fade-in">
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-[0.3em]">Smart Lighting</h1>
            <p class="text-slate-400 text-[10px] uppercase tracking-widest mt-1">Contrôle des luminaires et relais</p>
        </div>

        <div class="flex gap-3">
            <button wire:click="allOff" class="px-4 py-2 bg-rose-500/10 border border-rose-500/20 rounded-xl text-[10px] font-bold text-rose-400 uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all active:scale-95">
                <i class="bi bi-power mr-2"></i>Tout éteindre
            </button>
            <button wire:click="allOn" class="px-4 py-2 bg-cyan-500/10 border border-cyan-500/20 rounded-xl text-[10px] font-bold text-cyan-400 uppercase tracking-widest hover:bg-cyan-500 hover:text-white transition-all active:scale-95">
                <i class="bi bi-lightbulb-fill mr-2"></i>Tout allumer
            </button>
        </div>
    </div>

    <!-- GRILLE DES APPAREILS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($lights as $light)
            <div @click="$wire.toggleLight({{ $light->id }})" 
                 class="relative overflow-hidden cursor-pointer glass-panel p-6 rounded-[2rem] border transition-all duration-500 group {{ $light->is_on ? 'border-cyan-500/50 shadow-[0_0_30px_rgba(34,211,238,0.15)]' : 'border-white/5 opacity-60 hover:opacity-100' }}">
                
                <!-- Effet de lueur en arrière-plan (uniquement si ON) -->
                @if($light->is_on)
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-cyan-500/10 blur-[50px] rounded-full"></div>
                @endif

                <div class="flex justify-between items-start mb-6">
                    <!-- Icône dynamique -->
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-500 {{ $light->is_on ? 'bg-cyan-500 text-slate-900 shadow-[0_0_20px_rgba(34,211,238,0.6)]' : 'bg-white/5 text-slate-400' }}">
                        @if($light->type == 'light')
                            <i class="bi {{ $light->is_on ? 'bi-lightbulb-fill' : 'bi-lightbulb' }} text-2xl"></i>
                        @else
                            <i class="bi bi-toggle-on text-2xl"></i>
                        @endif
                    </div>

                    <!-- Switch visuel -->
                    <div class="w-10 h-5 bg-white/10 rounded-full relative transition-colors {{ $light->is_on ? 'bg-cyan-500/30' : '' }}">
                        <div class="absolute top-1 w-3 h-3 rounded-full transition-all duration-300 {{ $light->is_on ? 'right-1 bg-cyan-400 shadow-[0_0_8px_#22d3ee]' : 'left-1 bg-slate-500' }}"></div>
                    </div>
                </div>

                <!-- Labels -->
                <div class="space-y-1">
                    <h3 class="text-white font-bold uppercase text-sm tracking-wider">{{ $light->name }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] uppercase font-bold tracking-widest text-slate-500">{{ $light->room }}</span>
                        <span class="w-1 h-1 rounded-full bg-white/20"></span>
                        <span class="text-[9px] uppercase font-bold tracking-widest {{ $light->is_on ? 'text-cyan-400' : 'text-slate-600' }}">
                            {{ $light->is_on ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>

                <!-- Intensité (si c'est une lumière) -->
                @if($light->type == 'light' && $light->is_on)
                    <div class="mt-4 pt-4 border-t border-white/5" @click.stop> {{-- .stop empêche le toggle de la carte --}}
                        <div class="flex justify-between text-[8px] uppercase font-black text-slate-500 mb-2 tracking-widest">
                            <span>Intensité</span>
                            <span class="text-cyan-400">{{ $light->value }}%</span>
                        </div>
                        
                        <input type="range" 
                            min="0" max="100" 
                            value="{{ $light->value }}"
                            {{-- On utilise wire:change pour ne pas saturer le serveur, ou wire:model.live pour le temps réel --}}
                            wire:change="updateBrightness({{ $light->id }}, $event.target.value)"
                            class="w-full h-1.5 bg-white/10 rounded-lg appearance-none cursor-pointer accent-cyan-400 hover:accent-cyan-300 transition-all">
                    </div>
                @endif

            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <i class="bi bi-lightbulb-off text-5xl text-white/5"></i>
                <p class="text-slate-500 text-xs uppercase tracking-widest mt-4">Aucun éclairage ou relais configuré</p>
            </div>
        @endforelse
    </div>
</div>
