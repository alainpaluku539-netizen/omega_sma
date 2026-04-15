<div class="p-4 sm:p-6 lg:p-8 animate-fade-in" x-data="{ open: @entangle('showModal') }">
    
    <!-- PREMIUM HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div class="relative">
            <div class="absolute -left-4 top-0 bottom-0 w-1 bg-gradient-to-b from-cyan-500 to-transparent rounded-full"></div>
            <h1 class="text-3xl font-black text-white uppercase tracking-[0.4em] leading-none">
                IoT <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Fleet</span>
            </h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-[0.3em] mt-2 font-bold flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-cyan-500 shadow-[0_0_8px_#22d3ee] animate-pulse"></span>
                Omega Core Management System
            </p>
        </div>
        
        <button @click="open = true" 
                class="group relative px-8 py-3 overflow-hidden rounded-2xl bg-slate-900 border border-white/10 transition-all hover:border-cyan-500/50 shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <span class="relative text-[10px] font-black uppercase tracking-[0.2em] text-cyan-400 group-hover:text-white flex items-center gap-3">
                <i class="bi bi-plus-lg text-lg"></i> Register New Module
            </span>
        </button>
    </div>

    <!-- DEVICE GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($devices as $device)
            <div class="relative group">
                <!-- Glowing Aura (uniquement si Online) -->
                @if($device->is_active)
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-[2.5rem] blur opacity-10 group-hover:opacity-30 transition duration-500"></div>
                @endif

                <div class="glass-panel relative p-6 rounded-[2.5rem] border border-white/5 bg-slate-900/40 backdrop-blur-2xl transition-all duration-500 group-hover:-translate-y-2 group-hover:bg-slate-900/60">
                    
                    <!-- Top Bar -->
                    <div class="flex justify-between items-start mb-8">
                        <div class="relative">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 flex items-center justify-center text-cyan-400 border border-white/10 shadow-2xl transition-transform duration-500 group-hover:rotate-12">
                                <i class="bi bi-{{ $device->type == 'light' ? 'lightbulb-fill' : ($device->type == 'switch' ? 'cpu-fill' : 'thermometer-half') }} text-3xl"></i>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <div class="flex items-center gap-2 justify-end">
                                <span class="text-[8px] font-black uppercase tracking-widest {{ $device->is_active ? 'text-cyan-400' : 'text-slate-600' }}">
                                    {{ $device->computed_status }}
                                </span>
                                <span class="relative flex h-2 w-2">
                                    <span class="{{ $device->is_active ? 'animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75' : '' }}"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 {{ $device->is_active ? 'bg-cyan-500' : 'bg-slate-700' }}"></span>
                                </span>
                            </div>
                            <p class="text-[7px] text-slate-500 uppercase mt-1 tracking-tighter">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'No Signal' }}</p>
                        </div>
                    </div>

                    <!-- Info Area -->
                    <div class="space-y-1 mb-8">
                        <h3 class="text-white font-bold uppercase text-base tracking-widest truncate group-hover:text-cyan-400 transition-colors">{{ $device->name }}</h3>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-md bg-white/5 text-[8px] font-bold text-slate-400 uppercase tracking-widest border border-white/5 italic">{{ $device->room }}</span>
                            <span class="text-[8px] text-slate-600 font-mono tracking-tighter">{{ $device->device_id }}</span>
                        </div>
                    </div>

                    <!-- Real-time Data Display (Premium Look) -->
                    @if($device->type == 'temperature' || $device->type == 'humidity')
                        <div class="relative bg-black/40 rounded-2xl p-4 mb-8 border border-white/5 overflow-hidden">
                            <div class="absolute right-0 top-0 opacity-10 translate-x-4 -translate-y-4">
                                <i class="bi bi-activity text-5xl text-cyan-400"></i>
                            </div>
                            <p class="text-[8px] text-slate-500 uppercase font-black tracking-[0.2em] mb-1">Live Telemetry</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-white font-mono leading-none tracking-tighter">{{ $device->value ?? '00.0' }}</span>
                                <span class="text-cyan-500 font-bold text-xs uppercase">{{ $device->unit ?? '°C' }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Premium Footer Actions -->
                    <div class="flex gap-3 pt-6 border-t border-white/5">
                        @if(in_array($device->type, ['light', 'switch']))
                            <button wire:click="toggleDevice({{ $device->id }})" 
                                    wire:loading.attr="disabled"
                                    class="flex-[2] py-3 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] transition-all relative overflow-hidden group/btn {{ $device->is_on ? 'bg-cyan-500 text-slate-950' : 'bg-white/5 text-white hover:bg-white/10' }}">
                                <span wire:loading.remove wire:target="toggleDevice({{ $device->id }})">
                                    {{ $device->is_on ? 'System Online' : 'Standby Mode' }}
                                </span>
                                <span wire:loading wire:target="toggleDevice({{ $device->id }})">
                                    <i class="bi bi-arrow-repeat animate-spin text-lg"></i>
                                </span>
                            </button>
                        @endif
                        
                        <button onclick="confirm('Destroy this uplink?') || event.stopImmediatePropagation()" 
                                wire:click="deleteDevice({{ $device->id }})"
                                class="w-12 h-12 flex items-center justify-center rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all border border-rose-500/20 active:scale-90">
                            <i class="bi bi-trash3-fill text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- MODALE PREMIUM -->
    <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/95 backdrop-blur-xl" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             @click="open = false">
        </div>

        <div class="relative w-full max-w-lg rounded-[3rem] p-1 bg-gradient-to-b from-white/10 to-transparent shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-12"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="bg-slate-900 rounded-[2.9rem] p-10">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h2 class="text-2xl font-black text-white uppercase tracking-widest leading-none">Register</h2>
                        <p class="text-cyan-500 text-[8px] uppercase font-bold tracking-[0.4em] mt-2">New IoT Uplink Node</p>
                    </div>
                    <button @click="open = false" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-8">
                    <div class="group relative">
                        <label class="absolute -top-2 left-4 px-2 bg-slate-900 text-[8px] uppercase font-black text-cyan-500 tracking-widest z-10">Label</label>
                        <input type="text" wire:model.blur="name" placeholder="E.G. MAIN SERVER LIGHTS" 
                               class="w-full bg-white/5 border {{ $errors->has('name') ? 'border-rose-500' : 'border-white/10' }} rounded-2xl px-6 py-4 text-white placeholder:text-slate-700 focus:border-cyan-500/50 focus:outline-none transition-all font-bold tracking-widest text-xs uppercase">
                        @error('name') <span class="text-rose-500 text-[8px] mt-2 block font-black tracking-widest uppercase italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="group relative text-center">
                            <label class="block text-[8px] uppercase font-black text-slate-500 mb-3 tracking-widest">Uplink ID</label>
                            <input type="text" wire:model.blur="device_id" placeholder="ESP-01" 
                                   class="w-full bg-white/5 border {{ $errors->has('device_id') ? 'border-rose-500' : 'border-white/10' }} rounded-2xl py-4 text-white text-center focus:border-cyan-500/50 focus:outline-none transition-all font-mono font-bold uppercase">
                        </div>
                        <div class="group relative">
                            <label class="block text-[8px] uppercase font-black text-slate-500 mb-3 tracking-widest text-center">Node Type</label>
                            <select wire:model="type" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 text-white focus:border-cyan-500/50 focus:outline-none transition-all appearance-none cursor-pointer text-center font-bold uppercase text-[10px] tracking-widest">
                                <option value="switch">Relay Unit</option>
                                <option value="light">Lighting</option>
                                <option value="temperature">Sensor</option>
                            </select>
                        </div>
                    </div>

                    <div class="group relative">
                        <label class="absolute -top-2 left-4 px-2 bg-slate-900 text-[8px] uppercase font-black text-slate-500 tracking-widest z-10">Location Zone</label>
                        <input type="text" wire:model.blur="room" placeholder="E.G. SECTOR A1" 
                               class="w-full bg-white/5 border {{ $errors->has('room') ? 'border-rose-500' : 'border-white/10' }} rounded-2xl px-6 py-4 text-white focus:border-cyan-500/50 focus:outline-none transition-all font-bold uppercase text-xs tracking-widest">
                    </div>

                    <button type="submit" class="group relative w-full bg-cyan-500 py-6 rounded-2xl overflow-hidden transition-all hover:bg-cyan-400 active:scale-95 shadow-[0_20px_50px_rgba(34,211,238,0.2)]">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        <span wire:loading.remove class="text-slate-950 font-black uppercase tracking-[0.4em] text-xs">Initialize Node</span>
                        <i wire:loading class="bi bi-arrow-repeat animate-spin text-slate-950 text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
