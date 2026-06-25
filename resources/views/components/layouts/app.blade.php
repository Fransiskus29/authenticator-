<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f8f9ff">

    <title>{{ config('app.name', 'SecureAuth') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-sans text-body-md min-h-screen antialiased flex">
    @php
        $user = auth()->user();
        $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
    @endphp
    <aside class="w-[280px] h-screen fixed left-0 top-0 bg-surface border-r border-outline-variant flex flex-col py-md px-sm z-20 hidden md:flex">
        <div class="mb-xl flex items-center gap-xs px-sm">
            <span class="material-symbols-outlined text-primary text-[32px] font-bold" style="font-variation-settings: 'FILL' 1;">shield</span>
            <div>
                <h1 class="font-sans text-headline-md font-bold text-primary">SecureAuth</h1>
                <p class="text-label-sm text-on-surface-variant">Vigilant &amp; Precise</p>
            </div>
        </div>

        <nav class="flex-1 space-y-xs">
            <a href="{{ route('two-factor.index') }}" wire:navigate
               class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                      {{ request()->routeIs('two-factor.index') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('two-factor.index') ? 'filled' : '' }}">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('two-factor.create') }}" wire:navigate
               class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                      {{ request()->routeIs('two-factor.create') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('two-factor.create') ? 'filled' : '' }}">add_circle</span>
                <span>Add Account</span>
            </a>
            <a href="{{ route('profile') }}" wire:navigate
               class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                      {{ request()->routeIs('profile') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('profile') ? 'filled' : '' }}">security</span>
                <span>Security Settings</span>
            </a>
        </nav>

        <div class="mt-auto pt-lg border-t border-outline-variant">
            <a href="{{ route('two-factor.create') }}" wire:navigate
               class="w-full bg-primary-container text-on-primary font-label-sm text-label-sm py-sm px-md rounded-xl hover:opacity-90 transition-opacity flex justify-center items-center gap-xs shadow-sm">
                <span class="material-symbols-outlined text-[20px]">add</span>
                <span>Add New Account</span>
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col md:ml-[280px] w-full min-h-screen">
        <header class="h-16 fixed top-0 right-0 w-full md:w-[calc(100%-280px)] bg-surface border-b border-outline-variant flex justify-between items-center px-md z-10">
            <div class="flex items-center">
                <button onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')" class="md:hidden mr-sm text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h2 class="text-headline-md text-primary font-bold hidden md:block">Authenticator</h2>
                <h2 class="text-headline-md text-primary font-bold md:hidden">SecureAuth</h2>
            </div>
            <div class="flex items-center gap-xs">
                <div class="relative focus-within:ring-2 focus-within:ring-primary rounded-full hidden lg:block">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input class="bg-surface-container-low border-none rounded-full pl-10 pr-4 py-2 text-label-sm text-on-surface focus:outline-none w-64 placeholder-on-surface-variant" placeholder="Search accounts..." type="text"/>
                </div>
                <button class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors">
                    <span class="material-symbols-outlined">lock</span>
                </button>
                <button class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <a href="{{ route('profile') }}" wire:navigate class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors">
                    <span class="material-symbols-outlined">settings</span>
                </a>
                <div class="ml-sm pl-sm border-l border-outline-variant">
                    <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary text-xs font-bold border border-outline-variant">
                        {{ $initial }}
                    </div>
                </div>
            </div>
        </header>

        <div id="mobile-sidebar" class="hidden fixed inset-0 z-50 md:hidden">
            <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"></div>
            <aside class="w-[280px] h-full bg-surface border-r border-outline-variant flex flex-col py-md px-sm relative z-10">
                <div class="mb-xl flex items-center gap-xs px-sm">
                    <span class="material-symbols-outlined text-primary text-[32px] font-bold" style="font-variation-settings: 'FILL' 1;">shield</span>
                    <div>
                        <h1 class="font-sans text-headline-md font-bold text-primary">SecureAuth</h1>
                        <p class="text-label-sm text-on-surface-variant">Vigilant &amp; Precise</p>
                    </div>
                </div>
                <nav class="flex-1 space-y-xs">
                    <a href="{{ route('two-factor.index') }}" wire:navigate onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"
                       class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                              {{ request()->routeIs('two-factor.index') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('two-factor.create') }}" wire:navigate onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"
                       class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                              {{ request()->routeIs('two-factor.create') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">add_circle</span>
                        <span>Add Account</span>
                    </a>
                    <a href="{{ route('profile') }}" wire:navigate onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"
                       class="flex items-center gap-sm px-sm py-xs rounded-xl transition-all duration-200 text-label-sm font-label-sm
                              {{ request()->routeIs('profile') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">security</span>
                        <span>Security Settings</span>
                    </a>
                </nav>
                <div class="mt-auto pt-lg border-t border-outline-variant">
                    <a href="{{ route('two-factor.create') }}" wire:navigate
                       class="w-full bg-primary text-on-primary font-label-sm text-label-sm py-sm px-md rounded-xl hover:opacity-90 transition-opacity flex justify-center items-center gap-xs shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        <span>Add New Account</span>
                    </a>
                </div>
            </aside>
        </div>

        <main class="flex-1 mt-16 p-sm md:p-lg overflow-y-auto bg-surface-bright">
            <div class="max-w-container-max mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
