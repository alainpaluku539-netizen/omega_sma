<x-layouts::app>
    <div class="min-h-[80vh] flex items-center justify-center p-3">

        <!-- Carte Ultra Compacte -->
        <div class="glass-panel w-full max-w-xs p-5 rounded-[2rem] border border-white/10 shadow-xl relative overflow-hidden">

            <!-- Glow léger -->
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

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-3 relative z-10">
                @csrf

                <!-- Email -->
                <div class="space-y-1">
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="Email"
                           class="w-full bg-white/5 border {{ $errors->has('email') ? 'border-red-500/50' : 'border-white/10' }}
                                  rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-cyan-500/50 transition">
                    @error('email')
                        <span class="text-[8px] text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <input type="password" name="password" required
                           placeholder="Password"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-cyan-500/50 transition">
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between text-[9px]">
                    <label class="flex items-center gap-1 opacity-50">
                        <input type="checkbox" name="remember" class="scale-75">
                        Keep
                    </label>

                    <a href="{{ route('password.request') }}" class="text-cyan-400/70 hover:text-cyan-400">
                        Reset
                    </a>
                </div>

                <!-- Button -->
                <button type="submit"
                        class="w-full py-2.5 bg-cyan-500/10 border border-cyan-500/50 rounded-xl text-cyan-400 text-[10px] font-bold uppercase tracking-widest hover:bg-cyan-500 hover:text-white transition">
                    Login
                </button>

                <!-- Register -->
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}"
                       class="text-[9px] text-cyan-400/70 hover:text-cyan-400">
                        Create account
                    </a>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-4 text-center border-t border-white/5 pt-3">
                <p class="text-[8px] opacity-30 uppercase tracking-widest">
                    © {{ date('Y') }}
                </p>
            </div>

        </div>
    </div>
</x-layouts::app>