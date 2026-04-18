<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Omega') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://googleapis.com">
    <link href="https://googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="antialiased bg-[#030712] text-slate-200 selection:bg-cyan-500/30">
    
    <!-- Background Design -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <!-- Cercle lumineux Cyan -->
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-cyan-500/10 blur-[120px] animate-pulse"></div>
        <!-- Cercle lumineux Violet -->
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-indigo-500/10 blur-[120px]"></div>
        
        <!-- Texture de grain (Optionnel mais pro) -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://vercel.app')]"></div>
    </div>

    <!-- Contenu Principal -->
    <main class="min-h-screen flex flex-col items-center justify-center relative">
        {{ $slot }}
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>
