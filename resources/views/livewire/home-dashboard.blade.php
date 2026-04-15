<div class="w-full h-full text-slate-200 p-3 lg:p-6 overflow-y-auto custom-scrollbar">
    
    <!-- GRID PRINCIPALE ADAPTATIVE -->
    <div class="max-w-[1600px] mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 lg:gap-6 items-start animate-fade-in">

        <!-- COLONNE GAUCHE (Contrôles - Compacte) -->
        <div class="col-span-1 lg:col-span-3 space-y-4 lg:space-y-6">
            <div class="glass-panel p-0.5 rounded-2xl overflow-hidden">
                @livewire('temp-control')
            </div>

            <div class="glass-panel p-0.5 rounded-2xl overflow-hidden">
                @livewire('smart-lighting')
            </div>
        </div>

        <!-- COLONNE CENTRE (Données Temps Réel) -->
        <div class="col-span-1 md:col-span-2 lg:col-span-6 space-y-4 lg:space-y-6">
            <!-- SÉCURITÉ -->
            <div class="glass-panel p-3 sm:p-4 rounded-2xl border border-white/5">
                @livewire('security-system')
            </div>

            <!-- CAPTEURS (ESP32) -->
            <div class="glass-panel p-3 sm:p-4 rounded-2xl border border-white/5">
                @livewire('sensor-data')
            </div>
        </div>

        <!-- COLONNE DROITE (Énergie) -->
        <div class="col-span-1 lg:col-span-3 space-y-4 lg:space-y-6">
            <div class="glass-panel p-0.5 rounded-2xl overflow-hidden">
                @livewire('energy-usage')
            </div>
        </div>

    </div>
</div>

<style>
    /* Animation fluide */
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Glassmorphism Ultra-Compact */
    .glass-panel {
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .glass-panel:hover {
        border-color: rgba(34, 211, 238, 0.2);
        background: rgba(255, 255, 255, 0.04);
    }
</style>
