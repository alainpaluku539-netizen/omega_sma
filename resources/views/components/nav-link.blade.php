{{-- resources/views/components/nav-link.blade.php --}}

@props(['icon', 'label', 'active' => false])

<div class="relative group/item w-full px-2" x-data="{ showTooltip: false }">

    <!-- LINK -->
    <a {{ $attributes }} @mouseenter="showTooltip = true" @mouseleave="showTooltip = false"
        class="relative overflow-hidden flex items-center rounded-2xl transition-all duration-300 group border border-transparent

        {{ $active
            ? 'bg-gradient-to-r from-cyan-500/20 to-blue-500/10 text-cyan-400 border-cyan-400/20 shadow-[0_0_20px_rgba(34,211,238,0.08)]'
            : 'text-slate-400 hover:text-white hover:bg-white/5 hover:border-white/10' }}"
        :class="sidebarOpen
            ?
            'px-4 py-3.5 justify-start gap-4' :
            'p-3.5 justify-center gap-0'">

        <!-- ACTIVE GLOW -->
        @if ($active)
            <div class="absolute inset-0 bg-cyan-400/5 blur-xl"></div>
        @endif

        <!-- LEFT BAR -->
        @if ($active)
            <div
                class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-cyan-400 rounded-r-full shadow-[0_0_15px_#22d3ee]">
            </div>
        @endif

        <!-- ICON -->
        <div class="relative z-10 w-6 flex items-center justify-center shrink-0">

            {{-- Bootstrap Icons --}}
            <i
                class="bi bi-{{ $icon }} text-lg transition-all duration-300
                {{ $active ? 'scale-110 drop-shadow-[0_0_8px_rgba(34,211,238,0.8)]' : 'group-hover:scale-110' }}">
            </i>

        </div>

        <!-- LABEL -->
        <span x-show="sidebarOpen" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
            class="relative z-10 text-[10px] uppercase tracking-[0.22em] font-black whitespace-nowrap">

            {{ $label }}

        </span>

        <!-- RIGHT ARROW -->
        <div x-show="sidebarOpen"
            class="ml-auto relative z-10 opacity-0 group-hover:opacity-100 transition duration-300">

            <i class="bi bi-chevron-right text-[10px]"></i>

        </div>

    </a>

    <!-- TOOLTIP -->
    <div x-show="showTooltip && !sidebarOpen" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-x-0" x-transition:enter-end="opacity-100 translate-x-2"
        class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-4 py-2
        bg-slate-900 text-cyan-400 text-[9px] font-black uppercase tracking-[0.25em]
        rounded-xl shadow-[0_20px_40px_rgba(0,0,0,0.5)]
        border border-white/10 whitespace-nowrap z-[100] pointer-events-none">

        {{ $label }}

        <!-- ARROW -->
        <div
            class="absolute top-1/2 -left-1 -translate-y-1/2 w-2 h-2 bg-slate-900 border-l border-b border-white/10 rotate-45">
        </div>

    </div>

</div>
