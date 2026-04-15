{{-- resources/views/livewire/energy-usage.blade.php --}}

<div class="glass-panel p-6 rounded-xl flex flex-col h-full border border-white/5"
     x-data="{
        stats: @entangle('stats'),
        chart: null,

        init() {
            this.chart = new ApexCharts(this.$refs.chart, {
                chart: {
                    type: 'area',
                    height: 160,
                    toolbar: { show: false },
                    sparkline: { enabled: true },
                    animations: { enabled: true, easing: 'easeinout', speed: 500 }
                },
                stroke: { curve: 'smooth', width: 3, colors: ['#22d3ee'] },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0, stops: [0, 90, 100] }
                },
                series: [{ name: 'Usage', data: this.stats }],
                tooltip: { 
                    theme: 'dark', 
                    x: { show: false },
                    y: { formatter: (val) => val.toFixed(2) + ' kW' }
                },
                grid: { padding: { left: 0, right: 0 } }
            });

            this.chart.render();

            // ✅ Livewire v3 compatible
            Livewire.on('statsUpdated', (event) => {
                this.chart.updateSeries([{ data: [...event.data] }]);
            });
        }
     }">

    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h3 class="text-[10px] uppercase font-bold tracking-[0.2em] opacity-50 text-slate-400">
                Energy Usage
            </h3>
            <p class="text-[9px] opacity-40 uppercase tracking-widest mt-1">
                Real-time Telemetry
            </p>
        </div>

        <div class="text-right">
            <span 
                class="text-2xl font-extralight text-cyan-400 transition-all duration-300"
                :class="{'scale-110': ($wire.currentUsage ?? 0) > 0}"
                x-text="($wire.currentUsage ?? 0).toFixed(1) + ' kW'">
            </span>
        </div>
    </div>

    <!-- Graph -->
    <div class="w-full flex-1 min-h-[150px]" x-ref="chart" wire:ignore></div>

    <!-- Statistiques -->
    <div class="mt-4 flex justify-between items-end">
        <div>
            <p class="text-[8px] uppercase font-bold opacity-30 tracking-widest">Instant</p>
            <p class="text-xs font-medium text-slate-200"
               x-text="($wire.currentUsage ?? 0).toFixed(2) + ' kW'"></p>
        </div>

        <div class="text-right">
            <p class="text-[8px] uppercase font-bold opacity-30 tracking-widest">Total Today</p>
            <p class="text-xs font-medium text-cyan-400"
               x-text="($wire.totalToday ?? 0).toFixed(1) + ' kWh'"></p>
        </div>
    </div>

    <!-- Liste des Appareils (inchangée) -->
    <div class="mt-6 space-y-2 border-t border-white/5 pt-4">
        <template x-for="item in [
            { name: 'Climate', val: '0.8kW', icon: 'fa-wind' },
            { name: 'Lighting', val: '0.1kW', icon: 'fa-lightbulb' },
            { name: 'Servers', val: '0.3kW', icon: 'fa-server' }
        ]" :key="item.name">
            <div class="flex justify-between items-center bg-white/5 p-2 px-3 rounded-xl border border-white/5 group hover:bg-cyan-500/5 transition-colors">
                <div class="flex items-center gap-3">
                    <i :class="'fa-solid ' + item.icon"
                       class="text-[10px] text-cyan-400/50 group-hover:text-cyan-400 transition-colors"></i>
                    <span x-text="item.name"
                          class="text-[10px] font-bold opacity-60 uppercase tracking-tighter text-slate-300"></span>
                </div>
                <span x-text="item.val"
                      class="text-[10px] font-mono text-cyan-400 font-bold"></span>
            </div>
        </template>
    </div>
</div>