<x-layouts.app>
    <div class="mb-lg animate-fade-in-up">
        <h2 class="text-headline-lg text-on-surface">Security Settings</h2>
        <p class="text-on-surface-variant mt-2 max-w-2xl">Manage your password, browser extensions, and connected devices. Keep your authenticator locked down.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
        {{-- Left Column: Primary Security --}}
        <div class="lg:col-span-7 flex flex-col gap-sm stagger-in">
            {{-- Profile Information --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/50 p-md glow-hover group relative overflow-hidden">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center text-primary group-hover:bg-primary-container group-hover:text-on-primary transition-all duration-300 group-hover:scale-110">
                            <span class="material-symbols-outlined">person</span>
                        </div>
                        <div>
                            <h3 class="text-headline-md text-on-surface">Profile Information</h3>
                            <p class="text-label-sm text-on-surface-variant flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[14px] text-secondary">check_circle</span>
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
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/50 p-md glow-hover transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div class="flex gap-3">
                        <div class="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center text-on-surface-variant">
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
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/50 p-md glow-hover transition-all duration-300 hover:border-error/30">
                <div class="flex items-start justify-between">
                    <div class="flex gap-3">
                        <div class="w-11 h-11 rounded-xl bg-error-container/20 flex items-center justify-center text-error">
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
        <div class="lg:col-span-5 flex flex-col gap-sm stagger-in" style="animation-delay: 0.15s;">
            {{-- Cloud Sync Card --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/50 p-md glow-hover relative overflow-hidden">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-primary-container/20 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">cloud_sync</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface">Cloud Sync</h3>
                </div>
                <div class="space-y-3">
                    <p class="text-body-md text-on-surface-variant">Backup to Cloud (Encrypted)</p>
                    <div class="flex items-center gap-2 text-secondary text-label-sm bg-secondary-container/20 px-3 py-2.5 rounded-xl border border-secondary-container/50">
                        <span class="material-symbols-outlined text-[16px]">sync_saved_locally</span>
                        Last synced: Just now
                    </div>
                    <button class="w-full mt-2 px-4 py-2.5 text-primary text-label-sm font-label-sm hover:bg-surface-container rounded-xl transition-all duration-200 text-left flex justify-between items-center btn-press">
                        Force Sync Now
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
                    </button>
                </div>
            </div>

            {{-- Trusted Devices List --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/50 p-md flex-grow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined">devices</span>
                    </div>
                    <h3 class="text-body-md font-semibold text-on-surface">Trusted Devices</h3>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-outline">laptop_mac</span>
                            <div>
                                <p class="text-body-md text-on-surface text-sm">Current Session</p>
                                <p class="text-label-sm text-on-surface-variant text-xs">{{ request()->userAgent() ? 'Active now' : 'Active' }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-secondary bg-secondary-container/30 px-2.5 py-1 rounded-lg border border-secondary/20">Active</span>
                    </li>
                </ul>
            </div>

            {{-- Browser Extension --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/50 p-md">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-tertiary-container/20 flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined">extension</span>
                    </div>
                    <div>
                        <h3 class="text-body-md font-semibold text-on-surface">Browser Extension</h3>
                        <p class="text-label-sm text-on-surface-variant">Connect Chrome or Firefox for quick access</p>
                    </div>
                </div>
                <div id="token-section">
                    @if (auth()->user()->api_token)
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 text-secondary text-label-sm bg-secondary-container/20 px-3 py-2.5 rounded-xl border border-secondary-container/50">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                Extension connected
                            </div>
                            <button onclick="revokeToken()" class="w-full px-4 py-2.5 text-error text-label-sm font-label-sm hover:bg-error-container/20 rounded-xl transition-all duration-200 text-left btn-press">
                                Disconnect Extension
                            </button>
                        </div>
                    @else
                        <div class="space-y-2">
                            <button onclick="generateToken()" class="w-full px-4 py-2.5 text-primary text-label-sm font-label-sm hover:bg-primary-container/20 rounded-xl transition-all duration-200 text-left flex justify-between items-center btn-press">
                                Generate Connection Token
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </button>
                            <a href="{{ route('extension.download') }}" class="w-full px-4 py-2.5 text-secondary text-label-sm font-label-sm hover:bg-secondary-container/20 rounded-xl transition-all duration-200 text-left flex justify-between items-center btn-press inline-flex items-center justify-center gap-xs">
                                <span class="material-symbols-outlined text-[18px]">download</span>
                                Download Extension (.zip)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';

        async function generateToken() {
            const res = await fetch('/authenticator/api-token', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (res.ok) {
                const data = await res.json();
                const section = document.getElementById('token-section');
                section.innerHTML = `
                    <div class="space-y-3">
                        <div class="bg-surface-container-low rounded-xl p-3 border border-outline-variant/50">
                            <p class="text-label-sm text-on-surface-variant mb-2">Your extension token:</p>
                            <div class="flex items-center gap-2">
                                <code class="flex-1 text-xs font-mono text-on-surface bg-surface-container-lowest/80 px-2 py-1.5 rounded-lg break-all">${data.token}</code>
                                <button onclick="copyToken('${data.token}')" class="shrink-0 text-on-surface-variant hover:text-primary p-1 rounded-lg hover:bg-surface-container-low transition-all">
                                    <span class="material-symbols-outlined text-[18px]">content_copy</span>
                                </button>
                            </div>
                            <p class="text-[11px] text-error mt-2">Copy this now — it won't be shown again.</p>
                        </div>
                        <button onclick="location.reload()" class="w-full px-4 py-2.5 text-primary text-label-sm font-label-sm hover:bg-primary-container/20 rounded-xl transition-all duration-200 text-left btn-press">
                            Done
                        </button>
                    </div>
                `;
            }
        }

        async function revokeToken() {
            if (!confirm('This will disconnect your browser extension. Continue?')) return;

            const res = await fetch('/authenticator/api-token', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (res.ok) location.reload();
        }

        function copyToken(token) {
            navigator.clipboard.writeText(token).then(() => {
                const btn = event.target.closest('button');
                btn.innerHTML = '<span class="material-symbols-outlined text-[18px] text-secondary">check</span>';
                setTimeout(() => btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">content_copy</span>', 1500);
            });
        }
    </script>
</x-layouts.app>
