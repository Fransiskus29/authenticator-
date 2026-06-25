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
<body class="bg-background text-on-background font-sans text-body-md min-h-screen antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="flex items-center gap-xs mb-6">
            <span class="material-symbols-outlined text-primary text-[40px] font-bold" style="font-variation-settings: 'FILL' 1;">shield</span>
            <div>
                <h1 class="font-sans text-headline-md font-bold text-primary">SecureAuth</h1>
                <p class="text-label-sm text-on-surface-variant">Vigilant &amp; Precise</p>
            </div>
        </div>

        <div class="w-full sm:max-w-md px-6 py-4">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
