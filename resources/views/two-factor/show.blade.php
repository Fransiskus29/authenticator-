<x-app-layout>
    <div class="max-w-[800px] mx-auto">
        <header class="mb-lg">
            <h2 class="text-headline-lg text-on-surface mb-base">Setup Complete</h2>
            <p class="text-on-surface-variant">Your account has been added successfully.</p>
        </header>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md animate-slide-up">
            <div class="flex items-center gap-sm mb-md">
                <span class="material-symbols-outlined text-secondary text-[24px]">check_circle</span>
                <h3 class="text-headline-md text-on-surface">{{ $account->label }}</h3>
                @if ($account->issuer)
                    <span class="text-label-sm text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">{{ $account->issuer }}</span>
                @endif
            </div>

            <p class="text-body-md text-on-surface-variant mb-md">Scan this QR code with your authenticator app or enter the secret key manually.</p>

            <div class="flex flex-col md:flex-row gap-lg">
                {{-- QR Code --}}
                <div class="flex-shrink-0">
                    <div class="inline-block p-5 bg-surface-container-low border border-outline-variant rounded-xl">
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&bgcolor=f8f9ff&color=0b1c30&data={{ urlencode($qrCodeUrl) }}"
                            alt="QR Code for {{ $account->label }}"
                            class="block rounded-lg"
                        >
                    </div>
                </div>

                {{-- Secret Key --}}
                <div class="flex-1">
                    <p class="text-on-surface mb-base text-label-sm font-label-sm">Secret Key (Manual Entry)</p>
                    <div class="bg-surface-container-low border border-outline-variant rounded-lg p-sm mb-sm">
                        <code class="text-code-sm font-mono tracking-widest text-primary select-all break-all block">
                            {{ $account->secret }}
                        </code>
                    </div>
                    <button onclick="copySecret()" class="text-primary hover:text-on-primary-fixed-variant text-label-sm font-label-sm flex items-center gap-xs transition-colors">
                        <span class="material-symbols-outlined text-[16px]">content_copy</span>
                        Copy secret key
                    </button>
                </div>
            </div>

            <div class="mt-md pt-md border-t border-outline-variant">
                <a href="{{ route('two-factor.index') }}" wire:navigate
                   class="bg-primary text-on-primary text-label-sm font-label-sm px-md py-sm rounded-lg hover:opacity-90 transition-opacity shadow-sm inline-flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                    Done
                </a>
            </div>
        </div>

        <div class="mt-sm flex items-center justify-center gap-xs text-on-surface-variant opacity-70">
            <span class="material-symbols-outlined text-[16px]">lock</span>
            <span class="text-[12px]">All keys are encrypted locally on this device.</span>
        </div>
    </div>

    <div id="toast" class="fixed bottom-lg right-lg bg-inverse-surface text-inverse-on-surface px-md py-xs rounded-lg shadow-lg flex items-center gap-xs text-label-sm font-label-sm z-50 hidden">
        <span class="material-symbols-outlined text-secondary-fixed text-[20px]">check_circle</span>
        <span id="toast-text">Secret copied!</span>
    </div>

    <script>
        function copySecret() {
            const secret = '{{ $account->secret }}';
            navigator.clipboard.writeText(secret).then(() => {
                const toast = document.getElementById('toast');
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 2000);
            }).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = secret;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                const toast = document.getElementById('toast');
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 2000);
            });
        }
    </script>
</x-app-layout>
