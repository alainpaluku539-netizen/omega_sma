@props(['icon', 'label', 'active' => false])

<div class="relative group/item w-full px-2" x-data="{ showTooltip: false }">
    <a {{ $attributes }} 
       @mouseenter="showTooltip = true"
       @mouseleave="showTooltip = false"
       class="flex items-center rounded-xl transition-all duration-300 group {{ $active ? 'bg-cyan-500/20 text-cyan-400' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
       :class="sidebarOpen ? 'px-4 py-3 justify-start gap-4' : 'p-3 justify-center gap-0'">
        
        <!-- Conteneur Bootstrap Icon -->
        <div class="w-6 h-6 flex items-center justify-center shrink-0">
            <i class="bi bi-{{ $icon }} text-xl transition-transform duration-300 group-hover:scale-110"></i>
        </div>

        <!-- Texte -->
        <span x-show="sidebarOpen" 
              x-cloak
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 -translate-x-2"
              x-transition:enter-end="opacity-100 translate-x-0"
              class="text-[11px] uppercase tracking-widest font-bold whitespace-nowrap">
            {{ $label }}
        </span>
    </a>

    <!-- TOOLTIP -->
    <div x-show="showTooltip && !sidebarOpen"
         x-cloak
         class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-3 py-2 bg-slate-900 text-cyan-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-lg shadow-2xl border border-white/10 whitespace-nowrap z-[100] pointer-events-none">
        {{ $label }}
        <div class="absolute top-1/2 -left-1 -translate-y-1/2 w-2 h-2 bg-slate-900 border-l border-b border-white/10 rotate-45"></div>
    </div>
</div>
