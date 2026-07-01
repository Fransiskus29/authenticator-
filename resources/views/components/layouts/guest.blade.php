<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f8f9ff" id="theme-color-meta">

    <title>{{ config('app.name', 'SecureAuth') }}</title>

    <script>
        (function() {
            var t = localStorage.getItem('theme');
            var d = (!t && window.matchMedia('(prefers-color-scheme: dark)').matches) || t === 'dark';
            document.documentElement.classList.toggle('dark', d);
            document.getElementById('theme-color-meta')?.setAttribute('content', d ? '#161820' : '#f8f9ff');
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap">
    </noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .guest-bg {
            background: linear-gradient(135deg, #e2dfff 0%, #f8f9ff 30%, #d3e4fe 60%, #e8e4ff 100%);
            position: relative;
            overflow: hidden;
        }
        .dark .guest-bg {
            background: linear-gradient(135deg, #1a1535 0%, #0f1115 30%, #151a25 60%, #1a1230 100%);
        }
        .guest-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 30% 40%, rgba(var(--color-primary), 0.06) 0%, transparent 60%),
                        radial-gradient(ellipse at 70% 80%, rgba(var(--color-secondary), 0.04) 0%, transparent 50%);
        }
        .dark .guest-bg::before {
            background: radial-gradient(ellipse at 30% 40%, rgba(var(--color-primary), 0.1) 0%, transparent 60%),
                        radial-gradient(ellipse at 70% 80%, rgba(var(--color-secondary), 0.06) 0%, transparent 50%);
        }
        .guest-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            pointer-events: none;
        }
        .dark .guest-orb { opacity: 0.2; }
        .guest-orb-1 { width: 250px; height: 250px; background: rgba(var(--color-primary), 0.15); top: -5%; right: -3%; animation: orb-drift 22s ease-in-out infinite; }
        .guest-orb-2 { width: 180px; height: 180px; background: rgba(var(--color-secondary), 0.12); bottom: 10%; left: -5%; animation: orb-drift 18s ease-in-out infinite reverse; }
    </style>
</head>
<body class="guest-bg min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 antialiased">
    <div class="guest-orb guest-orb-1"></div>
    <div class="guest-orb guest-orb-2"></div>

    <div class="absolute top-4 left-4 z-10">
        <a href="/" wire:navigate class="flex items-center gap-1.5 text-on-surface-variant hover:text-primary transition-colors text-sm group">
            <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:-translate-x-0.5">arrow_back</span>
            Home
        </a>
    </div>

    <div class="absolute top-4 right-4 z-10">
        <button onclick="toggleTheme()" class="theme-toggle text-on-surface-variant hover:bg-surface-container-low/80 rounded-full p-2 transition-all duration-300 hover:scale-105" title="Toggle dark mode" aria-label="Toggle dark mode">
            <span class="material-symbols-outlined" id="theme-icon">dark_mode</span>
        </button>
    </div>

    <div class="flex items-center gap-xs mb-6 animate-fade-in-up relative z-10" style="animation-delay: 0.1s;">
        <a href="/" wire:navigate class="flex items-center gap-xs group">
            <span class="material-symbols-outlined text-primary text-[40px] font-bold transition-transform duration-300 group-hover:scale-110" style="font-variation-settings: 'FILL' 1;">shield</span>
            <div>
                <h1 class="font-sans text-headline-md font-bold text-primary">SecureAuth</h1>
                <p class="text-label-sm text-on-surface-variant">Your codes, your control</p>
            </div>
        </a>
    </div>

    <div class="w-full sm:max-w-md px-6 py-4 animate-fade-in-up relative z-10" style="animation-delay: 0.2s;">
        {{ $slot }}
    </div>
</body>
</html>
