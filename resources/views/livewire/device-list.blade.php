{{-- resources/views/livewire/device-list.blade.php --}}

<div class="min-h-screen w-full bg-slate-950 text-white p-4 md:p-6">

    <!-- ================= HEADER ================= -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

        <div>
            <p class="text-cyan-400 text-xs uppercase tracking-[0.35em] font-bold">
                Omega Core Network
            </p>

            <h1 class="text-2xl md:text-4xl font-black mt-2">
                Smart Devices Control
            </h1>

            <p class="text-slate-400 text-sm mt-2">
                Real-time ESP32 / MQTT / Reverb Monitoring
            </p>
        </div>

        <div class="flex items-center gap-3">

            <button wire:click="refreshNow"
                class="px-4 py-3 rounded-2xl bg-cyan-500 hover:bg-cyan-600 transition font-bold shadow-lg">
                <i class="fa-solid fa-rotate-right mr-2"></i>
                Refresh
            </button>

        </div>
    </div>

    <!-- ================= STATS ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        <!-- TOTAL -->
        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <p class="text-slate-400 text-xs uppercase tracking-widest">
                Total Devices
            </p>

            <h2 class="text-4xl font-black mt-3">
                {{ $totalDevices }}
            </h2>
        </div>

        <!-- ONLINE -->
        <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-5">
            <p class="text-emerald-300 text-xs uppercase tracking-widest">
                Online
            </p>

            <h2 class="text-4xl font-black mt-3 text-emerald-400">
                {{ $onlineDevices }}
            </h2>
        </div>

        <!-- OFFLINE -->
        <div class="rounded-3xl border border-rose-500/20 bg-rose-500/10 p-5">
            <p class="text-rose-300 text-xs uppercase tracking-widest">
                Offline
            </p>

            <h2 class="text-4xl font-black mt-3 text-rose-400">
                {{ $offlineDevices }}
            </h2>
        </div>

    </div>

    <!-- ================= DEVICE GRID ================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($devices as $device)
            <div
                class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 hover:border-cyan-400/30 transition duration-300 shadow-lg">

                <!-- TOP -->
                <div class="flex items-start justify-between gap-3">

                    <div>
                        <p class="text-slate-400 text-xs uppercase tracking-widest">
                            Device ID
                        </p>

                        <h2 class="text-lg font-black mt-2 break-all">
                            {{ $device['device_id'] }}
                        </h2>
                    </div>

                    <!-- STATUS -->
                    <div
                        class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest
                        {{ $device['status'] === 'online'
                            ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/20'
                            : 'bg-rose-500/15 text-rose-400 border border-rose-500/20' }}">

                        <span class="inline-flex items-center gap-1">
                            <span
                                class="w-2 h-2 rounded-full
                                {{ $device['status'] === 'online' ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}">
                            </span>

                            {{ $device['status'] }}
                        </span>

                    </div>

                </div>

                <!-- METRICS -->
                <div class="grid grid-cols-2 gap-4 mt-5">

                    <!-- TEMP -->
                    <div class="rounded-2xl bg-cyan-500/10 p-4">
                        <p class="text-xs text-slate-400 uppercase">
                            Temp
                        </p>

                        <h3 class="text-2xl font-black text-cyan-400 mt-2">
                            {{ $device['temperature'] }}°
                        </h3>
                    </div>

                    <!-- HUM -->
                    <div class="rounded-2xl bg-blue-500/10 p-4">
                        <p class="text-xs text-slate-400 uppercase">
                            Humidity
                        </p>

                        <h3 class="text-2xl font-black text-blue-400 mt-2">
                            {{ $device['humidity'] }}%
                        </h3>
                    </div>

                    <!-- RSSI -->
                    <div class="rounded-2xl bg-yellow-500/10 p-4">
                        <p class="text-xs text-slate-400 uppercase">
                            Signal
                        </p>

                        <h3 class="text-xl font-black text-yellow-400 mt-2">
                            {{ $device['rssi'] }} dBm
                        </h3>
                    </div>

                    <!-- UPTIME -->
                    <div class="rounded-2xl bg-emerald-500/10 p-4">
                        <p class="text-xs text-slate-400 uppercase">
                            Uptime
                        </p>

                        <h3 class="text-xl font-black text-emerald-400 mt-2">
                            {{ gmdate('H:i:s', $device['uptime']) }}
                        </h3>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="mt-5 pt-4 border-t border-white/5 flex justify-between items-center">

                    <span class="text-xs text-slate-400">
                        Last seen:
                        <span class="text-white">
                            {{ $device['last_seen'] }}
                        </span>
                    </span>

                    <button
                        class="px-3 py-2 rounded-xl bg-white/5 hover:bg-cyan-500/10 text-cyan-400 text-xs font-bold transition">

                        Details

                    </button>

                </div>

            </div>

        @empty

            <div class="col-span-full">

                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 p-12 text-center">

                    <div class="text-5xl text-slate-600 mb-4">
                        <i class="fa-solid fa-microchip"></i>
                    </div>

                    <h2 class="text-xl font-bold">
                        No Devices Found
                    </h2>

                    <p class="text-slate-400 mt-2">
                        Waiting for ESP32 telemetry...
                    </p>

                </div>

            </div>
        @endforelse

    </div>

</div>
