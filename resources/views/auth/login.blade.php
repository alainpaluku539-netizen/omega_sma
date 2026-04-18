<x-layouts::app>
    <div class="min-h-[80vh] flex items-center justify-center p-3">

        <!-- Carte Ultra Compacte -->
        <div class="glass-panel w-full max-w-xs p-5 rounded-[2rem] border border-white/10 shadow-xl relative overflow-hidden">

            <!-- Glow l�ger -->
            <div class="absolute -top-16 -right-16 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl"></div>

            <!-- Header -->
            <div class="text-center mb-5 relative z-10">
                <div class="text-cyan-400 flex justify-center mb-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                        <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"></path>
                    </svg>
                </div>

                <h1 class="text-sm font-light tracking-[0.3em] uppercase">
                    Omega <span class="font-bold">Login</span>
                </h1>

                <p class="text-[9px] opacity-40 uppercase tracking-widest mt-1">
                    Secure Access
                </p>
            </div>

            <!-- Formulaire -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4 relative z-10">
                @csrf

                <!-- Email -->
                <div class="space-y-1">
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="Email"
                           class="w-full bg-white/5 border {{ $errors->has('email') ? 'border-red-500/50' : 'border-white/10' }} 
                                  rounded-xl px-4 py-2.5 text-xs text-white placeholder-white/30 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 transition-all">
                    @error('email')
                        <span class="text-[9px] text-red-400 ml-1 italic">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <input type="password" name="password" required autocomplete="current-password"
                           placeholder="Mot de passe"
                           class="w-full bg-white/5 border {{ $errors->has('email') ? 'border-red-500/50' : 'border-white/10' }} 
                                  rounded-xl px-4 py-2.5 text-xs text-white placeholder-white/30 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 transition-all">
                </div>

                <!-- Options : Remember & Forgot -->
                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-3 h-3 rounded border-white/20 bg-white/5 text-cyan-500 focus:ring-0 focus:ring-offset-0">
                        <span class="text-[10px] text-white/40 group-hover:text-white/60 transition">Rester connecté</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[10px] text-cyan-400/70 hover:text-cyan-400 transition">
                            Oublié ?
                        </a>
                    @endif
                </div>

                <!-- Bouton Submit -->
                <button type="submit"
                        class="w-full py-3 bg-cyan-500/10 border border-cyan-500/50 rounded-xl text-cyan-400 text-[10px] font-bold uppercase tracking-widest 
                               hover:bg-cyan-500 hover:text-white hover:shadow-[0_0_15px_rgba(6,182,212,0.4)] transition-all duration-300">
                    Se connecter
                </button>

                <!-- Lien Inscription -->
                <div class="text-center mt-4">
                    <p class="text-[10px] text-white/30">
                        Pas de compte ? 
                        <a href="{{ route('register') }}" class="text-cyan-400/70 hover:text-cyan-400 font-bold ml-1 transition">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center border-t border-white/5 pt-4">
                <p class="text-[9px] opacity-20 uppercase tracking-[0.2em] text-white">
                    &copy; {{ date('Y') }} Omega Cloud Inc.
                </p>
            </div>


        </div>
    </div>
</x-layouts::app>