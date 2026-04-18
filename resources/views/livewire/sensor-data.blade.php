{{-- resources/views/livewire/sensor-data.blade.php --}}

<div wire:ignore class="min-h-screen w-full bg-slate-950 text-white p-4 md:p-6">

    <!-- HEADER -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <p class="text-cyan-400 text-xs uppercase tracking-[0.35em] font-bold">
                Smart Home Monitoring
            </p>

            <h1 class="text-2xl md:text-4xl font-black mt-2">
                ESP32 Sensor Dashboard
            </h1>

            <p class="text-slate-400 text-sm mt-2">
                Real-time Telemetry / MQTT / Laravel Reverb
            </p>
        </div>

        <div class="flex items-center gap-3">
            <div class="h-3 w-3 rounded-full bg-emerald-400 animate-pulse"></div>
            <span class="text-sm text-emerald-400 font-semibold">
                LIVE ONLINE
            </span>
        </div>
    </div>

    <!-- METRICS -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

        <!-- DEVICE -->
        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-4">
            <p class="text-slate-400 text-xs uppercase">Device</p>
            <h2 id="val-device" class="text-lg font-bold mt-2">--</h2>
        </div>

        <!-- TEMP -->
        <div class="rounded-3xl border border-cyan-500/20 bg-cyan-500/5 p-4">
            <p class="text-slate-400 text-xs uppercase">Temperature</p>
            <h2 class="text-3xl font-black mt-2 text-cyan-400">
                <span id="val-temp">--</span>
                <span class="text-sm">°C</span>
            </h2>
        </div>

        <!-- HUM -->
        <div class="rounded-3xl border border-blue-500/20 bg-blue-500/5 p-4">
            <p class="text-slate-400 text-xs uppercase">Humidity</p>
            <h2 class="text-3xl font-black mt-2 text-blue-400">
                <span id="val-hum">--</span>
                <span class="text-sm">%</span>
            </h2>
        </div>

        <!-- RSSI -->
        <div class="rounded-3xl border border-yellow-500/20 bg-yellow-500/5 p-4">
            <p class="text-slate-400 text-xs uppercase">Signal</p>
            <h2 class="text-3xl font-black mt-2 text-yellow-400">
                <span id="val-rssi">--</span>
                <span class="text-sm">dBm</span>
            </h2>
        </div>

        <!-- UPTIME -->
        <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/5 p-4">
            <p class="text-slate-400 text-xs uppercase">Uptime</p>
            <h2 id="val-uptime" class="text-xl font-black mt-2 text-emerald-400">
                --
            </h2>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

        <!-- CONTROL PANEL -->
        <div class="xl:col-span-1">

            <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-5">

                <h3 class="text-sm uppercase tracking-widest text-slate-400 mb-4">
                    Relay Control
                </h3>

                <div class="space-y-3">

                    <button onclick="sendRelay('ON', this)"
                        class="w-full rounded-2xl bg-emerald-500 py-3 font-bold hover:scale-105 transition">
                        ON
                    </button>

                    <button onclick="sendRelay('OFF', this)"
                        class="w-full rounded-2xl bg-rose-500 py-3 font-bold hover:scale-105 transition">
                        OFF
                    </button>

                    <button onclick="updateDashboard()"
                        class="w-full rounded-2xl bg-cyan-500 py-3 font-bold hover:scale-105 transition">
                        Refresh
                    </button>

                </div>
            </div>

        </div>

        <!-- CHART -->
        <div class="xl:col-span-3">

            <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-5">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">

                    <div>
                        <h3 class="text-lg font-bold">
                            Live Sensor Analytics
                        </h3>

                        <p class="text-sm text-slate-400">
                            Temperature / Humidity / Signal
                        </p>
                    </div>

                    <select id="timeFilter" onchange="updateDashboard()"
                        class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-sm">

                        <option value="live">LIVE</option>
                        <option value="1h">1 Hour</option>
                        <option value="24h">24 Hours</option>
                    </select>
                </div>

                <div id="chart" class="w-full h-[420px]"></div>

            </div>

        </div>
    </div>

    <!-- APEXCHARTS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        let chart = null;

        function formatUptime(sec) {
            sec = parseInt(sec || 0);

            let h = Math.floor(sec / 3600);
            let m = Math.floor((sec % 3600) / 60);
            let s = sec % 60;

            if (h > 0) return `${h}h ${m}m`;
            if (m > 0) return `${m}m ${s}s`;

            return `${s}s`;
        }

        function initChart() {

            let options = {
                chart: {
                    type: 'line',
                    height: 420,
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 700
                    },
                    background: 'transparent'
                },

                stroke: {
                    curve: 'smooth',
                    width: 3
                },

                dataLabels: {
                    enabled: false
                },

                series: [{
                        name: 'Temperature',
                        data: []
                    },
                    {
                        name: 'Humidity',
                        data: []
                    },
                    {
                        name: 'RSSI',
                        data: []
                    }
                ],

                colors: ['#06b6d4', '#3b82f6', '#f59e0b'],

                xaxis: {
                    categories: [],
                    labels: {
                        style: {
                            colors: '#94a3b8'
                        }
                    }
                },

                yaxis: [{
                        title: {
                            text: 'Temp / Hum'
                        },
                        labels: {
                            style: {
                                colors: '#94a3b8'
                            }
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Signal'
                        },
                        labels: {
                            style: {
                                colors: '#94a3b8'
                            }
                        }
                    }
                ],

                grid: {
                    borderColor: '#1e293b'
                },

                legend: {
                    labels: {
                        colors: '#cbd5e1'
                    }
                },

                tooltip: {
                    theme: 'dark'
                }
            };

            chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }

        async function updateDashboard() {

            const range = document.getElementById('timeFilter').value;

            try {

                const res = await fetch(`/api/sensors/latest?range=${range}`);
                const result = await res.json();

                const history = result.history || [];
                const latest = result.current;

                if (!latest) return;

                // METRICS
                document.getElementById('val-device').innerText = latest.device_id ?? '--';
                document.getElementById('val-temp').innerText = latest.temperature ?? '--';
                document.getElementById('val-hum').innerText = latest.humidity ?? '--';
                document.getElementById('val-rssi').innerText = latest.wifi_rssi ?? '--';
                document.getElementById('val-uptime').innerText = formatUptime(latest.uptime);

                // CHART DATA
                const labels = history.map(i =>
                    new Date(i.measured_at).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    })
                );

                const temp = history.map(i => i.temperature);
                const hum = history.map(i => i.humidity);
                const rssi = history.map(i => i.wifi_rssi);

                chart.updateOptions({
                    xaxis: {
                        categories: labels
                    }
                });

                chart.updateSeries([{
                        name: 'Temperature',
                        data: temp
                    },
                    {
                        name: 'Humidity',
                        data: hum
                    },
                    {
                        name: 'RSSI',
                        data: rssi
                    }
                ]);

            } catch (e) {
                console.log(e);
            }
        }

        async function sendRelay(cmd, btn) {

            const old = btn.innerHTML;
            btn.innerHTML = '...';

            try {
                await fetch('/api/relay', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        command: cmd
                    })
                });

                btn.innerHTML = 'DONE';

            } catch (e) {
                btn.innerHTML = 'ERROR';
            }

            setTimeout(() => btn.innerHTML = old, 1000);
        }

        document.addEventListener('livewire:navigated', () => {

            if (!chart) initChart();

            updateDashboard();

            setInterval(() => {
                updateDashboard();
            }, 2000);
        });

        window.addEventListener('update-sensor-chart', () => {
            updateDashboard();
        });
    </script>

</div>
