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

    <!-- Preload fonts to prevent FOUT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap">
    </noscript>

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-sans text-body-md min-h-screen antialiased flex">

    {{-- Desktop sidebar --}}
    <aside class="w-[280px] h-screen fixed left-0 top-0 bg-surface/80 backdrop-blur-xl border-r border-outline-variant/30 flex flex-col py-md px-sm z-20 hidden md:flex">
        <div class="mb-xl flex items-center gap-xs px-sm group">
            <span class="material-symbols-outlined text-primary text-[32px] font-bold transition-transform duration-300 group-hover:scale-110" style="font-variation-settings: 'FILL' 1;">shield</span>
            <div>
                <h1 class="font-sans text-headline-md font-bold text-primary">SecureAuth</h1>
                <p class="text-label-sm text-on-surface-variant">Your codes, your control</p>
            </div>
        </div>

        <nav class="flex-1 space-y-xs">
            @php
                $navItems = [
                    ['route' => 'two-factor.index', 'icon' => 'dashboard', 'label' => 'Dashboard'],
                    ['route' => 'two-factor.create', 'icon' => 'add_circle', 'label' => 'Add Account'],
                    ['route' => 'profile', 'icon' => 'security', 'label' => 'Security Settings'],
                ];
            @endphp
            @foreach ($navItems as $nav)
                @php $active = request()->routeIs($nav['route']); @endphp
                <a href="{{ route($nav['route']) }}" wire:navigate
                   class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm relative group
                          {{ $active ? 'bg-primary-container text-on-primary-container font-bold shadow-sm shadow-primary/10' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                    @if ($active)
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-primary rounded-r-full transition-all duration-300"></span>
                    @endif
                    <span class="material-symbols-outlined {{ $active ? 'filled' : '' }} transition-transform duration-200 group-hover:scale-110">{{ $nav['icon'] }}</span>
                    <span>{{ $nav['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="mt-auto pt-lg border-t border-outline-variant/30 space-y-sm">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm text-on-surface-variant hover:bg-error-container/30 hover:text-error group">
                    <span class="material-symbols-outlined transition-transform duration-200 group-hover:scale-110">logout</span>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col md:ml-[280px] w-full min-h-screen">
        <header class="h-16 fixed top-0 right-0 w-full md:w-[calc(100%-280px)] glass border-b border-outline-variant/30 flex justify-between items-center px-sm sm:px-md z-10">
            <div class="flex items-center gap-xs">
                <button onclick="document.getElementById('mobile-sidebar').classList.remove('hidden')" class="md:hidden text-on-surface-variant hover:bg-surface-container-low/80 rounded-full p-2 transition-all duration-300 hover:scale-105">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h2 class="text-headline-md text-primary font-bold hidden sm:block">Authenticator</h2>
                <h2 class="text-headline-md text-primary font-bold sm:hidden">SecureAuth</h2>
            </div>
            <div class="flex items-center gap-xs">
                <button onclick="toggleTheme()" class="theme-toggle text-on-surface-variant hover:bg-surface-container-low/80 rounded-full p-2 transition-all duration-300 hover:scale-105" title="Toggle dark mode" aria-label="Toggle dark mode">
                    <span class="material-symbols-outlined" id="theme-icon">dark_mode</span>
                </button>
                <a href="{{ route('profile') }}" wire:navigate class="text-on-surface-variant hover:bg-surface-container-low/80 rounded-full p-2 transition-all duration-300 hover:scale-105">
                    <span class="material-symbols-outlined">settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="text-on-surface-variant hover:bg-error-container/30 hover:text-error rounded-full p-2 transition-all duration-300 hover:scale-105" title="Log out">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- Mobile sidebar --}}
        <div id="mobile-sidebar" class="hidden fixed inset-0 z-50 md:hidden">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"></div>
            <aside class="w-[280px] h-full bg-surface border-r border-outline-variant/30 flex flex-col py-md px-sm relative z-10 animate-nav-slide">
                <div class="mb-xl flex items-center gap-xs px-sm group">
                    <span class="material-symbols-outlined text-primary text-[32px] font-bold transition-transform duration-300 group-hover:scale-110" style="font-variation-settings: 'FILL' 1;">shield</span>
                    <div>
                        <h1 class="font-sans text-headline-md font-bold text-primary">SecureAuth</h1>
                        <p class="text-label-sm text-on-surface-variant">Your codes, your control</p>
                    </div>
                </div>
                <nav class="flex-1 space-y-xs">
                    @foreach ($navItems as $nav)
                        @php $active = request()->routeIs($nav['route']); @endphp
                        <a href="{{ route($nav['route']) }}" wire:navigate onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"
                           class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm relative group
                                  {{ $active ? 'bg-primary-container text-on-primary-container font-bold shadow-sm shadow-primary/10' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                            @if ($active)
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-primary rounded-r-full"></span>
                            @endif
                            <span class="material-symbols-outlined {{ $active ? 'filled' : '' }} transition-transform duration-200 group-hover:scale-110">{{ $nav['icon'] }}</span>
                            <span>{{ $nav['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="mt-auto pt-lg border-t border-outline-variant/30 space-y-sm">
                    <button onclick="toggleTheme()" class="theme-toggle text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-all duration-300 hover:scale-105" title="Toggle dark mode" aria-label="Toggle dark mode">
                        <span class="material-symbols-outlined" id="theme-icon">dark_mode</span>
                    </button>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm text-on-surface-variant hover:bg-error-container/30 hover:text-error group">
                            <span class="material-symbols-outlined transition-transform duration-200 group-hover:scale-110">logout</span>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            </aside>
        </div>

        <main class="flex-1 mt-16 p-sm sm:p-md md:p-lg overflow-y-auto bg-surface-bright relative">
            <div class="max-w-container-max mx-auto relative z-[1]">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => null);
        }
    </script>
</body>
</html>
