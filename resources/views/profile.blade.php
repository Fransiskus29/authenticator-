<x-app-layout>
    <div class="mb-lg">
        <h2 class="text-headline-lg text-on-surface">Security Settings</h2>
        <p class="text-on-surface-variant mt-2 max-w-2xl">Manage your master password, multi-device access, and cloud backups. Maintaining these settings ensures your authenticator codes remain strictly under your control.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
        {{-- Left Column: Primary Security --}}
        <div class="lg:col-span-7 flex flex-col gap-sm">
            {{-- Profile Information --}}
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md hover:border-primary transition-colors duration-200 group relative overflow-hidden shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary group-hover:bg-primary-container group-hover:text-on-primary transition-colors">
                            <span class="material-symbols-outlined">person</span>
                        </div>
                        <div>
                            <h3 class="text-headline-md text-on-surface">Profile Information</h3>
                            <p class="text-label-sm text-on-surface-variant flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                Account active
                            </p>
                        </div>
                    </div>
                </div>
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            {{-- Update Password --}}
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md hover:border-primary transition-colors duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined">password</span>
                        </div>
                        <div>
                            <h3 class="text-body-md font-semibold text-on-surface">Update Password</h3>
                            <p class="text-label-sm text-on-surface-variant mt-1">
                                Ensure your account is using a long, random password to stay secure.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="max-w-xl mt-4">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md hover:border-error transition-colors duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full bg-error-container/30 flex items-center justify-center text-error">
                            <span class="material-symbols-outlined">delete_forever</span>
                        </div>
                        <div>
                            <h3 class="text-body-md font-semibold text-on-surface">Delete Account</h3>
                            <p class="text-label-sm text-on-surface-variant mt-1">
                                Permanently delete your account and all associated data.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="max-w-xl mt-4">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>

        {{-- Right Column: Sync & Devices --}}
        <div class="lg:col-span-5 flex flex-col gap-sm">
            {{-- Cloud Sync Card --}}
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md hover:border-primary transition-colors duration-200 relative overflow-hidden">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">cloud_sync</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface">Cloud Sync</h3>
                </div>
                <div class="space-y-3">
                    <p class="text-body-md text-on-surface-variant">Backup to Cloud (Encrypted)</p>
                    <div class="flex items-center gap-2 text-secondary text-label-sm bg-secondary-container/20 px-3 py-2 rounded-lg border border-secondary-container/50">
                        <span class="material-symbols-outlined text-[16px]">sync_saved_locally</span>
                        Last synced: Just now
                    </div>
                    <button class="w-full mt-2 px-4 py-2 text-primary text-label-sm font-label-sm hover:bg-surface-container rounded-lg transition-colors text-left flex justify-between items-center">
                        Force Sync Now
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>
                </div>
            </div>

            {{-- Trusted Devices List --}}
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md flex-grow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined">devices</span>
                    </div>
                    <h3 class="text-body-md font-semibold text-on-surface">Trusted Devices</h3>
                </div>
                <ul class="space-y-4">
                    <li class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-outline">laptop_mac</span>
                            <div>
                                <p class="text-body-md text-on-surface text-sm">Current Session</p>
                                <p class="text-label-sm text-on-surface-variant text-xs">{{ request()->userAgent() ? 'Active now' : 'Active' }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-secondary bg-secondary-container/30 px-2 py-1 rounded border border-secondary/20">Active</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
