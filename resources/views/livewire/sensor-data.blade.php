<div wire:ignore class="w-full space-y-4">

    <!-- HEADER -->
    <div class="text-center">
        <p class="text-cyan-400 uppercase tracking-widest text-[10px] font-bold">
            Terminal ID: {{ $device ?? 'N/A' }}
        </p>
    </div>
 
    <!-- GRID METRICS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- TEMP -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-3 text-center">
            <p class="text-slate-400 text-[10px] uppercase tracking-widest">Temp</p>
            <p class="text-xl font-bold text-white mt-1">
                <span id="val-temp">{{ $temperature ?? '--' }}</span>
                <span class="text-sm text-slate-400">°C</span>
            </p>
        </div>

        <!-- HUM -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-3 text-center">
            <p class="text-slate-400 text-[10px] uppercase tracking-widest">Humidity</p>
            <p class="text-xl font-bold text-white mt-1">
                <span id="val-hum">{{ $humidity ?? '--' }}</span>
                <span class="text-sm text-slate-400">%</span>
            </p>
        </div>

        <!-- RSSI -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-3 text-center">
            <p class="text-slate-400 text-[10px] uppercase tracking-widest">Signal</p>
            <p class="text-xl font-bold text-white mt-1">
                <span id="val-rssi">--</span>
                <span class="text-sm text-slate-400">dBm</span>
            </p>
        </div>

        <!-- UPTIME -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-3 text-center">
            <p class="text-slate-400 text-[10px] uppercase tracking-widest">Uptime</p>
            <p class="text-sm font-bold text-white mt-1" id="val-uptime">--</p>
        </div>
    </div>

    <!-- CONTROLS + CHART -->
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- CONTROL LED (Cible Python Gateway Port 5000) -->
        <div class="w-full lg:w-64 shrink-0">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-md h-full flex flex-col justify-center">
                <p class="text-center text-slate-400 text-[10px] uppercase mb-4 tracking-widest font-bold">
                    Relay Control
                </p>
                <div class="flex flex-row lg:flex-col gap-3">
                    <button class="flex-1 py-3 rounded-xl text-xs font-bold uppercase bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500 hover:text-white transition active:scale-95"
                        onclick="sendRelayCommand(0, 'ON', this)">
                        <i class="bi bi-power mr-2"></i> ON
                    </button>
                    <button class="flex-1 py-3 rounded-xl text-xs font-bold uppercase bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500 hover:text-white transition active:scale-95"
                        onclick="sendRelayCommand(0, 'OFF', this)">
                        <i class="bi bi-power mr-2"></i> OFF
                    </button>
                </div>
            </div>
        </div>

        <!-- ANALYTICS CHART (Responsive Fix) -->
        <div class="flex-1 min-w-0 max-w-full">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3 sm:p-4 backdrop-blur-md overflow-hidden">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-slate-400 text-[10px] uppercase font-bold tracking-widest">Analytics</p>
                    <select id="timeFilter" onchange="updateDashboard()" class="bg-slate-900 text-white text-[10px] border border-slate-700 rounded-lg px-2 py-1 outline-none">
                        <option value="live">LIVE</option>
                        <option value="1h">1H</option>
                        <option value="24h">24H</option>
                    </select>
                </div>
                <div class="h-[180px] sm:h-[220px] md:h-[260px] w-full relative">
                    <canvas id="sensorChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        let myChart;

        // Fonction pour envoyer les commandes à la Gateway Python (app.py)
        async function sendRelayCommand(id, action, btn) {
            const oldHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i>';

            try {
                // On cible le port 5000 de Flask
                await fetch('http://127.0.0', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ relay: id, action: action })
                });
            } catch (e) {
                console.error("Gateway Python injoignable", e);
            }

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = oldHTML;
            }, 500);
        }

        async function updateDashboard() {
            const range = document.getElementById('timeFilter').value;
            try {
                const res = await fetch(`/api/sensors/latest?range=${range}`);
                const result = await res.json();
                const data = result.history || [];
                const latest = result.current;

                if (!latest) return;

                // Mise à jour des cartes
                document.getElementById('val-temp').innerText = latest.temperature;
                document.getElementById('val-hum').innerText = latest.humidity;
                document.getElementById('val-rssi').innerText = latest.wifi_rssi || '--';
                document.getElementById('val-uptime').innerText = latest.uptime ? latest.uptime + 's' : '--';

                const labels = data.map(d => new Date(d.measured_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}));
                const tempValues = data.map(d => d.temperature);
                const humValues = data.map(d => d.humidity);

                if (myChart) {
                    myChart.data.labels = labels;
                    myChart.data.datasets[0].data = tempValues;
                    myChart.data.datasets[1].data = humValues;
                    myChart.update('none');
                } else {
                    initChart(labels, tempValues, humValues);
                }
            } catch (e) { console.log("Update failed", e); }
        }

        function initChart(labels, temp, hum) {
            const ctx = document.getElementById('sensorChart').getContext('2d');
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        { label: 'Temp', data: temp, borderColor: '#22d3ee', tension: 0.4, pointRadius: 0, fill: true, backgroundColor: 'rgba(34, 211, 238, 0.05)' },
                        { label: 'Hum', data: hum, borderColor: '#3b82f6', tension: 0.4, pointRadius: 0 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 9 }, maxTicksLimit: 5 } },
                        y: { position: 'right', ticks: { color: '#94a3b8', font: { size: 9 } } }
                    }
                }
            });
        }

        // Ecoute l'événement dispatché par Livewire lors d'un message Reverb
        window.addEventListener('update-sensor-chart', event => {
            updateDashboard(); // On force le rafraîchissement global lors d'une mise à jour temps réel
        });

        document.addEventListener('livewire:navigated', () => {
            updateDashboard();
            const interval = setInterval(updateDashboard, 5000);
            // Nettoyage de l'intervalle si on quitte la page
            document.addEventListener('livewire:navigating', () => clearInterval(interval), {once: true});
        });
    </script>
</div>
