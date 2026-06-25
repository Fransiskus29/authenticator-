<x-app-layout>
    <div class="flex justify-between items-end mb-lg">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-base">Dashboard</h2>
            <p class="text-body-md text-on-surface-variant">Welcome back, {{ auth()->user()->name }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-sm mb-lg">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md card-hover">
            <div class="flex items-center gap-sm mb-sm">
                <span class="material-symbols-outlined text-primary text-[24px]">shield</span>
                <h3 class="text-headline-md text-on-surface">Your 2FA Hub</h3>
            </div>
            <p class="text-body-md text-on-surface-variant mb-md">Manage all your two-factor authentication codes in one place.</p>
            <a href="{{ route('two-factor.index') }}" wire:navigate
               class="bg-primary text-on-primary text-label-sm font-label-sm px-md py-sm rounded-lg hover:opacity-90 transition-opacity shadow-sm inline-flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                Go to Authenticator
            </a>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md card-hover">
            <div class="flex items-center gap-sm mb-sm">
                <span class="material-symbols-outlined text-secondary text-[24px]">add_circle</span>
                <h3 class="text-headline-md text-on-surface">Add Account</h3>
            </div>
            <p class="text-body-md text-on-surface-variant mb-md">Add a new service to generate authentication codes.</p>
            <a href="{{ route('two-factor.create') }}" wire:navigate
               class="bg-primary-container text-on-primary text-label-sm font-label-sm px-md py-sm rounded-lg hover:opacity-90 transition-opacity shadow-sm inline-flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Add New Account
            </a>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md card-hover">
            <div class="flex items-center gap-sm mb-sm">
                <span class="material-symbols-outlined text-on-surface-variant text-[24px]">security</span>
                <h3 class="text-headline-md text-on-surface">Security</h3>
            </div>
            <p class="text-body-md text-on-surface-variant mb-md">Manage your security settings and trusted devices.</p>
            <a href="{{ route('profile') }}" wire:navigate
               class="bg-surface-container border border-outline-variant text-on-surface text-label-sm font-label-sm px-md py-sm rounded-lg hover:bg-surface-container-high transition-colors inline-flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">settings</span>
                Settings
            </a>
        </div>
    </div>
</x-app-layout>
