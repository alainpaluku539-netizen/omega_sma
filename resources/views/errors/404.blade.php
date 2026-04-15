<x-app::layout>
    <div class="flex flex-col items-center justify-center h-[70vh] text-center">
        <div class="relative mb-8">
            <h1 class="text-9xl font-black opacity-5 tracking-tighter">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-24 h-24 text-red-500/50 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-light uppercase tracking-[0.4em] mb-4">Module Introuvable</h2>
        <p class="text-xs opacity-40 uppercase tracking-widest mb-10 max-w-xs mx-auto">
            Le secteur de la maison que vous tentez d'atteindre est hors ligne ou inexistant.
        </p>

        <a href="{{ route('dashboard') }}"
           class="px-8 py-3 bg-white/5 border border-white/10 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white/10 transition-all">
            Retour au Centre de Commande
        </a>
    </div>
</x-app::layout>
