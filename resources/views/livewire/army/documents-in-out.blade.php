<div class="p-6 space-y-6">
    <!-- HEADER & STATS -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-white tracking-widest uppercase">Registre Documents <span class="text-cyan-400">IN/OUT</span></h2>
            <p class="text-xs text-slate-400 tracking-[0.3em] uppercase">Contrôle des flux administratifs militaires</p>
        </div>
        
        <div class="flex gap-3">
            <div class="glass-panel px-4 py-2 rounded-2xl border border-white/5 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                    <i class="bi bi-arrow-down-left-square"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase">Entrants</p>
                    <p class="text-sm font-black text-white">{{ $documents->where('direction', 'IN')->count() }}</p>
                </div>
            </div>
            <div class="glass-panel px-4 py-2 rounded-2xl border border-white/5 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                    <i class="bi bi-arrow-up-right-square"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase">Sortants</p>
                    <p class="text-sm font-black text-white">{{ $documents->where('direction', 'OUT')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- FORMULAIRE DE SAISIE -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-panel rounded-3xl border border-white/10 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/5 blur-3xl"></div>
                
                <h3 class="text-sm font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="bi bi-plus-circle text-cyan-400"></i> Nouvel Enregistrement
                </h3>

                <form wire:submit.prevent="saveDocument" class="space-y-4">
                    <!-- Direction Toggle -->
                    <div class="grid grid-cols-2 gap-2 p-1 bg-black/20 rounded-xl border border-white/5">
                        <button type="button" wire:click="$set('direction', 'IN')" 
                            class="py-2 rounded-lg text-[10px] font-bold transition {{ $direction == 'IN' ? 'bg-cyan-500 text-black' : 'text-slate-400 hover:text-white' }}">ENTRANT</button>
                        <button type="button" wire:click="$set('direction', 'OUT')" 
                            class="py-2 rounded-lg text-[10px] font-bold transition {{ $direction == 'OUT' ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white' }}">SORTANT</button>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Type de Document</label>
                        <select wire:model="doc_type" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition mt-1">
                            <option value="NOTE">Note de Service</option>
                            <option value="LETTRE">Lettre</option>
                            <option value="TELEGRAMME">Télégramme</option>
                            <option value="SITREP">SITREP</option>
                            <option value="COMMUNIQUE">Communiqué</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Titre / Objet</label>
                        <input type="text" wire:model="title" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none mt-1">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Provenance / Destination</label>
                        <input type="text" wire:model="origin_destination" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none mt-1">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Date Action</label>
                            <input type="date" wire:model="action_date" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none mt-1">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Mention</label>
                            <input type="text" wire:model="mention" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none mt-1" placeholder="URGENT...">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 text-black font-black py-4 rounded-xl transition-all shadow-[0_0_20px_rgba(34,211,238,0.2)] uppercase text-xs tracking-widest">
                        Enregistrer au Registre
                    </button>
                </form>
            </div>
        </div>

        <!-- LISTE DES DOCUMENTS -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Search Bar -->
            <div class="glass-panel p-2 rounded-2xl border border-white/5 flex items-center gap-4">
                <div class="pl-4 text-slate-500"><i class="bi bi-search"></i></div>
                <input type="text" wire:model.live="search" placeholder="Rechercher une référence ou un titre..." 
                    class="flex-1 bg-transparent border-none text-sm text-white outline-none py-2">
                <select wire:model.live="filterDirection" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-[10px] font-bold text-white outline-none">
                    <option value="all">TOUS</option>
                    <option value="IN">IN</option>
                    <option value="OUT">OUT</option>
                </select>
            </div>

            <!-- Table -->
            <div class="glass-panel rounded-3xl border border-white/10 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-white/5 border-b border-white/5">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Référence</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Document</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Type</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($documents as $doc)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs {{ $doc->direction == 'IN' ? 'text-emerald-400' : 'text-blue-400' }}">
                                    {{ $doc->reference }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-white leading-none">{{ $doc->title }}</p>
                                <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-tighter">{{ $doc->origin_destination }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $color = match($doc->doc_type) {
                                        'SITREP' => 'rose',
                                        'TELEGRAMME' => 'amber',
                                        'COMMUNIQUE' => 'cyan',
                                        default => 'slate'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-[9px] font-black bg-{{ $color }}-500/10 text-{{ $color }}-400 border border-{{ $color }}-500/20">
                                    {{ $doc->doc_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-xs font-mono text-white">{{ $doc->action_date->format('d M Y') }}</p>
                                @if($doc->mention)
                                <span class="text-[8px] font-black text-rose-500 uppercase tracking-tighter">{{ $doc->mention }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4 border-t border-white/5">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
