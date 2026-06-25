<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SecureAuth — TOTP authenticator for managing your two-factor authentication codes.">
    <meta name="theme-color" content="#f8f9ff">

    <title>{{ config('app.name', 'SecureAuth') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #e2dfff 0%, #f8f9ff 50%, #d3e4fe 100%);
        }
        @media (prefers-reduced-motion: reduce) {
            .fade-up { opacity: 1 !important; transform: none !important; }
        }
    </style>
</head>
<body class="bg-background text-on-background font-sans text-body-md min-h-screen antialiased">

    {{-- Nav --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-background/80 backdrop-blur-md border-b border-outline-variant/50">
        <div class="max-w-container-max mx-auto px-md flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-xs" wire:navigate>
                <span class="material-symbols-outlined text-primary text-[28px] font-bold" style="font-variation-settings: 'FILL' 1;">shield</span>
                <span class="font-sans text-headline-md font-bold text-primary tracking-tight">SecureAuth</span>
            </a>
            <div class="flex items-center gap-sm">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" wire:navigate class="text-label-sm text-on-surface-variant hover:text-primary transition-colors px-sm py-2">Log in</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" wire:navigate class="text-label-sm text-on-primary bg-primary hover:opacity-90 transition-opacity px-md py-2 rounded-lg">Get started</a>
                @endif
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero-gradient pt-32 pb-20 px-md sm:pt-40 sm:pb-28">
        <div class="max-w-container-max mx-auto text-center">
            <div class="inline-flex items-center gap-xs bg-primary-fixed/60 text-on-primary-fixed text-label-sm font-medium px-sm py-1.5 rounded-full mb-md">
                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                TOTP Authenticator
            </div>

            <h1 class="text-headline-lg sm:text-[clamp(2.5rem,5vw,4rem)] sm:leading-[clamp(3rem,6vw,4.75rem)] font-bold text-on-background max-w-3xl mx-auto" style="text-wrap: balance;">
                Your 2FA codes,<br class="hidden sm:block"> one secure place.
            </h1>

            <p class="mt-md text-body-md sm:text-lg text-on-surface-variant max-w-xl mx-auto" style="text-wrap: balance;">
                Generate time-based one-time passwords for all your accounts. No cloud sync, no vendor lock-in — your secrets stay on your device.
            </p>

            <div class="mt-lg flex flex-col sm:flex-row items-center justify-center gap-sm">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" wire:navigate class="w-full sm:w-auto inline-flex items-center justify-center gap-xs px-lg py-sm bg-primary text-on-primary rounded-lg font-label-sm text-label-sm tracking-wide hover:opacity-90 transition-opacity">
                        <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                        Start for free
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" wire:navigate class="w-full sm:w-auto inline-flex items-center justify-center gap-xs px-lg py-sm bg-surface-container-low text-on-surface border border-outline-variant rounded-lg font-label-sm text-label-sm tracking-wide hover:bg-surface-container transition-colors">
                        I already have an account
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-20 px-md">
        <div class="max-w-container-max mx-auto">
            <div class="text-center mb-xl">
                <h2 class="text-headline-md sm:text-headline-lg font-bold text-on-background" style="text-wrap: balance;">Everything you need,<br class="hidden sm:block"> nothing you don't.</h2>
                <p class="mt-sm text-body-md text-on-surface-variant max-w-lg mx-auto">Built for people who take security seriously but don't want the friction.</p>
            </div>

            <div class="grid gap-md sm:grid-cols-2 lg:grid-cols-3">
                {{-- Feature 1 --}}
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-lg hover:border-primary/30 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center mb-sm">
                        <span class="material-symbols-outlined text-on-primary-container text-[22px]">pin</span>
                    </div>
                    <h3 class="text-body-md font-semibold text-on-surface mb-xs">Live TOTP Codes</h3>
                    <p class="text-body-md text-on-surface-variant" style="text-wrap: pretty;">Real-time code generation with visible countdown. Your codes refresh every 30 seconds, always in sync.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-lg hover:border-primary/30 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-secondary-container flex items-center justify-center mb-sm">
                        <span class="material-symbols-outlined text-on-secondary-container text-[22px]">search</span>
                    </div>
                    <h3 class="text-body-md font-semibold text-on-surface mb-xs">Instant Search</h3>
                    <p class="text-body-md text-on-surface-variant" style="text-wrap: pretty;">Find any account in milliseconds. Search by name or issuer — no scrolling through endless lists.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-lg hover:border-primary/30 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-tertiary-container flex items-center justify-center mb-sm">
                        <span class="material-symbols-outlined text-on-tertiary-container text-[22px]">lock</span>
                    </div>
                    <h3 class="text-body-md font-semibold text-on-surface mb-xs">Encrypted Export</h3>
                    <p class="text-body-md text-on-surface-variant" style="text-wrap: pretty;">Export your accounts encrypted. Move between devices without exposing your secrets in plaintext.</p>
                </div>

                {{-- Feature 4 --}}
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-lg hover:border-primary/30 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center mb-sm">
                        <span class="material-symbols-outlined text-on-primary-container text-[22px]">qr_code_scanner</span>
                    </div>
                    <h3 class="text-body-md font-semibold text-on-surface mb-xs">Easy Setup</h3>
                    <p class="text-body-md text-on-surface-variant" style="text-wrap: pretty;">Add accounts with a label and secret key. Works with any service that supports TOTP — Google, GitHub, Slack, and more.</p>
                </div>

                {{-- Feature 5 --}}
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-lg hover:border-primary/30 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-secondary-container flex items-center justify-center mb-sm">
                        <span class="material-symbols-outlined text-on-secondary-container text-[22px]">download</span>
                    </div>
                    <h3 class="text-body-md font-semibold text-on-surface mb-xs">Import Accounts</h3>
                    <p class="text-body-md text-on-surface-variant" style="text-wrap: pretty;">Migrating from another authenticator? Import your existing accounts in one step.</p>
                </div>

                {{-- Feature 6 --}}
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-lg hover:border-primary/30 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-tertiary-container flex items-center justify-center mb-sm">
                        <span class="material-symbols-outlined text-on-tertiary-container text-[22px]">phonelink_off</span>
                    </div>
                    <h3 class="text-body-md font-semibold text-on-surface mb-xs">No Cloud Dependency</h3>
                    <p class="text-body-md text-on-surface-variant" style="text-wrap: pretty;">Your data lives on this server, under your control. No third-party sync, no surprise lockouts.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Code preview --}}
    <section class="py-20 px-md bg-surface-container-low">
        <div class="max-w-container-max mx-auto">
            <div class="grid lg:grid-cols-2 gap-xl items-center">
                <div>
                    <h2 class="text-headline-md sm:text-headline-lg font-bold text-on-background" style="text-wrap: balance;">Codes at a glance.</h2>
                    <p class="mt-sm text-body-md text-on-surface-variant max-w-md" style="text-wrap: pretty;">See all your OTP codes on one screen. The countdown timer shows exactly how long each code stays valid — no guessing, no rushing.</p>
                    <div class="mt-md flex items-center gap-sm text-label-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span>
                        30-second refresh cycle
                    </div>
                    <div class="mt-xs flex items-center gap-sm text-label-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span>
                        Standard TOTP (RFC 6238)
                    </div>
                    <div class="mt-xs flex items-center gap-sm text-label-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span>
                        Works with any TOTP provider
                    </div>
                </div>

                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-md font-mono">
                    <div class="flex items-center gap-xs mb-sm">
                        <div class="w-3 h-3 rounded-full bg-tertiary/60"></div>
                        <div class="w-3 h-3 rounded-full bg-[#e8a317]/60"></div>
                        <div class="w-3 h-3 rounded-full bg-secondary/60"></div>
                        <span class="ml-xs text-code-sm text-on-surface-variant">authenticator</span>
                    </div>
                    <div class="space-y-sm">
                        <div class="flex items-center justify-between bg-surface-container-low rounded-lg px-sm py-3">
                            <div class="flex items-center gap-xs">
                                <span class="material-symbols-outlined text-primary text-[20px]">shield</span>
                                <div>
                                    <p class="text-label-sm font-semibold text-on-surface">GitHub</p>
                                    <p class="text-code-sm text-on-surface-variant">personal</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-otp-display font-mono text-primary tracking-widest">482 901</p>
                                <div class="w-24 h-1 bg-outline-variant rounded-full mt-1 ml-auto overflow-hidden">
                                    <div class="h-full bg-primary rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-surface-container-low rounded-lg px-sm py-3">
                            <div class="flex items-center gap-xs">
                                <span class="material-symbols-outlined text-secondary text-[20px]">mail</span>
                                <div>
                                    <p class="text-label-sm font-semibold text-on-surface">Google</p>
                                    <p class="text-code-sm text-on-surface-variant">work</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-otp-display font-mono text-primary tracking-widest">739 254</p>
                                <div class="w-24 h-1 bg-outline-variant rounded-full mt-1 ml-auto overflow-hidden">
                                    <div class="h-full bg-primary rounded-full" style="width: 40%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-surface-container-low rounded-lg px-sm py-3">
                            <div class="flex items-center gap-xs">
                                <span class="material-symbols-outlined text-tertiary text-[20px]">chat</span>
                                <div>
                                    <p class="text-label-sm font-semibold text-on-surface">Slack</p>
                                    <p class="text-code-sm text-on-surface-variant">team</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-otp-display font-mono text-primary tracking-widest">516 083</p>
                                <div class="w-24 h-1 bg-outline-variant rounded-full mt-1 ml-auto overflow-hidden">
                                    <div class="h-full bg-primary rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 px-md">
        <div class="max-w-container-max mx-auto text-center">
            <div class="bg-primary-container rounded-2xl px-md py-xl sm:px-xl">
                <span class="material-symbols-outlined text-on-primary-container text-[48px] mb-sm block" style="font-variation-settings: 'FILL' 1;">shield</span>
                <h2 class="text-headline-md sm:text-headline-lg font-bold text-on-primary-container" style="text-wrap: balance;">Secure your accounts today.</h2>
                <p class="mt-sm text-body-md text-on-primary-container/80 max-w-md mx-auto" style="text-wrap: pretty;">Set up two-factor authentication in minutes. Free, self-hosted, and under your control.</p>
                <div class="mt-md flex flex-col sm:flex-row items-center justify-center gap-sm">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" wire:navigate class="w-full sm:w-auto inline-flex items-center justify-center gap-xs px-lg py-sm bg-primary text-on-primary rounded-lg font-label-sm text-label-sm tracking-wide hover:opacity-90 transition-opacity">
                            Create your account
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-lg px-md border-t border-outline-variant/50">
        <div class="max-w-container-max mx-auto flex flex-col sm:flex-row items-center justify-between gap-sm text-label-sm text-on-surface-variant">
            <div class="flex items-center gap-xs">
                <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">shield</span>
                <span class="font-medium text-on-surface">SecureAuth</span>
                <span class="text-outline">·</span>
                <span>Vigilant &amp; Precise</span>
            </div>
            <p>Built with Laravel + Livewire</p>
        </div>
    </footer>

</body>
</html>
