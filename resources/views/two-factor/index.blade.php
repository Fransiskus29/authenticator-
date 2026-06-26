<x-layouts.app>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-sm mb-lg animate-fade-in-up">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-base">Your Accounts</h2>
            <p class="text-body-md text-on-surface-variant flex items-center gap-base">
                <span class="material-symbols-outlined text-[16px] text-secondary">lock</span>
                <span>All tokens are end-to-end encrypted</span>
            </p>
        </div>
        <div class="flex items-center gap-xs">
            <button onclick="document.getElementById('import-modal').classList.remove('hidden')" class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-all duration-300 hover:scale-105" title="Import">
                <span class="material-symbols-outlined">upload</span>
            </button>
            <button onclick="exportAccounts()" class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-all duration-300 hover:scale-105" title="Export">
                <span class="material-symbols-outlined">download</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-secondary-container/20 border border-secondary-container/50 rounded-xl p-4 mb-sm flex items-center gap-sm animate-slide-up">
            <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
            <p class="text-label-sm text-secondary">{{ session('success') }}</p>
        </div>
    @endif

    @if (count($accounts) > 2 || request('q'))
        <form method="GET" action="{{ route('two-factor.index') }}" class="relative mb-lg max-w-md animate-fade-in-up" style="animation-delay: 0.1s;">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search accounts..."
                   class="w-full bg-surface-container-low/80 border border-outline-variant/50 rounded-full pl-10 pr-10 py-2.5 text-label-sm text-on-surface focus:outline-none input-glow placeholder-on-surface-variant/50 transition-all duration-300"
                   autocomplete="off">
            @if (request('q'))
                <a href="{{ route('two-factor.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-all duration-200 p-1 hover:scale-110">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </a>
            @endif
        </form>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-sm stagger-in" id="accounts-list">
        @forelse ($accounts as $index => $account)
            <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-md card-hover glow-hover relative group cursor-pointer"
                 onclick="copyCode({{ $account->id }})"
                 data-account-id="{{ $account->id }}"
                 data-url="{{ route('two-factor.code', $account) }}">
                <div class="flex justify-between items-start mb-md">
                    <div class="flex items-center gap-sm">
                        <div class="w-11 h-11 rounded-xl bg-surface-container-low border border-outline-variant/50 flex items-center justify-center p-2 transition-transform duration-300 group-hover:scale-110
                            {{ match(($account->id % 6)) {
                                0 => 'text-[#4285F4]',
                                1 => 'text-on-surface',
                                2 => 'text-[#6e40c9]',
                                3 => 'text-[#FF9900]',
                                4 => 'text-[#ea4335]',
                                5 => 'text-[#00ADEF]',
                                default => 'text-on-surface-variant',
                            } }}">
                            <span class="material-symbols-outlined text-[24px]">
                                {{ match(strtolower($account->issuer ?? '')) {
                                    'google' => 'mail',
                                    'github' => 'code',
                                    'discord' => 'chat',
                                    'microsoft' => 'computer',
                                    'amazon' => 'shopping_bag',
                                    'facebook' => 'group',
                                    'twitter' => 'tag',
                                    default => 'key',
                                } }}
                            </span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-[16px] sm:text-[18px] leading-[24px] font-semibold text-on-surface truncate">{{ $account->label }}</h3>
                            @if ($account->issuer)
                                <p class="text-label-sm text-on-surface-variant">{{ $account->issuer }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="relative w-9 h-9 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <circle class="stroke-surface-container-high/80" cx="18" cy="18" fill="none" r="16" stroke-width="3"></circle>
                            <circle class="progress-ring__circle stroke-secondary" cx="18" cy="18" fill="none" r="16"
                                    stroke-dasharray="100" stroke-dashoffset="0" stroke-width="3"
                                    id="ring-{{ $account->id }}"></circle>
                        </svg>
                        <span class="absolute text-[10px] font-mono font-semibold text-on-surface-variant timer-text"
                              id="timer-{{ $account->id }}">30</span>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-xs">
                    <div class="font-mono text-[28px] sm:text-otp-display text-on-surface tracking-widest code-display transition-colors duration-300"
                         id="code-{{ $account->id }}">{{ $codes[$account->id] ?? '------' }}</div>
                    <button aria-label="Copy code"
                            class="text-on-surface-variant hover:text-primary transition-all duration-200 p-2 rounded-full hover:bg-surface-container-low opacity-0 group-hover:opacity-100 focus:opacity-100 hover:scale-110">
                        <span class="material-symbols-outlined text-[20px]">content_copy</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-xl text-center animate-fade-in-up">
                <span class="material-symbols-outlined text-[64px] text-outline-variant/50 mb-md block">shield</span>
                <h3 class="text-headline-md text-on-surface mb-xs">{{ request('q') ? 'No results found' : 'No accounts yet' }}</h3>
                <p class="text-body-md text-on-surface-variant mb-lg max-w-md mx-auto">
                    {{ request('q') ? 'Try a different search term' : 'Add your first 2FA account to start generating authentication codes' }}
                </p>
                @if (!request('q'))
                    <a href="{{ route('two-factor.create') }}" wire:navigate
                       class="bg-primary text-on-primary text-label-sm px-md py-sm rounded-xl btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 inline-flex items-center gap-xs shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Add Your First Account
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-on-surface/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-surface-container-lowest rounded-2xl max-w-sm w-full p-lg animate-scale-in border border-outline-variant/50 shadow-2xl shadow-black/10 dark:shadow-black/40">
                <div class="w-14 h-14 bg-error-container/30 rounded-full flex items-center justify-center mx-auto mb-sm animate-pulse-glow">
                    <span class="material-symbols-outlined text-error text-[28px]">warning</span>
                </div>
                <h3 class="text-headline-md text-on-surface text-center mb-xs">Delete Account?</h3>
                <p class="text-body-md text-on-surface-variant text-center mb-md">Are you sure you want to delete <span id="delete-name" class="font-semibold text-on-surface"></span>?</p>
                <div class="flex gap-sm">
                    <button onclick="closeDeleteModal()" class="flex-1 py-2.5 text-label-sm font-label-sm text-on-surface-variant bg-surface-container-low border border-outline-variant/50 rounded-xl btn-press hover:bg-surface-container transition-all duration-200">Cancel</button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 text-label-sm font-label-sm text-on-error bg-error rounded-xl btn-press hover:opacity-90 transition-opacity shadow-sm shadow-error/20">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div id="import-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-on-surface/50 backdrop-blur-sm" onclick="document.getElementById('import-modal').classList.add('hidden')"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-lg animate-scale-in border border-outline-variant/50 shadow-2xl shadow-black/10 dark:shadow-black/40">
                <div class="w-14 h-14 bg-primary-container/20 rounded-full flex items-center justify-center mx-auto mb-sm">
                    <span class="material-symbols-outlined text-primary text-[28px]">upload</span>
                </div>
                <h3 class="text-headline-md text-on-surface text-center mb-xs">Import Backup</h3>
                <p class="text-body-md text-on-surface-variant text-center mb-md">Paste your encrypted backup data below</p>
                <form action="{{ route('two-factor.import') }}" method="POST">
                    @csrf
                    <div class="mb-md">
                        <textarea name="backup_data" rows="4" required
                                  class="w-full bg-surface-container-low/80 border border-outline-variant/50 rounded-xl px-sm py-3 font-mono text-code-sm text-on-surface focus:outline-none input-glow placeholder-on-surface-variant/50 transition-all duration-300"
                                  placeholder="Paste backup data here..."></textarea>
                        @error('backup_data') <p class="text-error text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-sm">
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')"
                                class="flex-1 py-2.5 text-label-sm font-label-sm text-on-surface-variant bg-surface-container-low border border-outline-variant/50 rounded-xl btn-press hover:bg-surface-container transition-all duration-200">Cancel</button>
                        <button type="submit" class="flex-1 bg-primary text-on-primary text-label-sm font-label-sm py-2.5 rounded-xl btn-press hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 shadow-sm">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="fixed bottom-lg right-lg bg-inverse-surface text-inverse-on-surface px-md py-xs rounded-xl shadow-xl shadow-black/15 flex items-center gap-xs text-label-sm font-label-sm z-50 hidden">
        <span class="material-symbols-outlined text-secondary-fixed text-[20px]">check_circle</span>
        <span id="toast-text">Done!</span>
    </div>

    <script>
        const accounts = @json($accounts->map(fn($a) => ['id' => $a->id, 'url' => '/authenticator/' . $a->id . '/code']));
        const timerState = {};

        // Initialize timer state
        accounts.forEach(a => {
            timerState[a.id] = { remaining: 30, code: null, formatted: null, fetched: false };
        });

        function updateDOM(account) {
            const state = timerState[account.id];
            const codeEl = document.getElementById('code-' + account.id);
            const timerEl = document.getElementById('timer-' + account.id);
            const ringEl = document.getElementById('ring-' + account.id);

            if (timerEl) timerEl.textContent = state.remaining.toString().padStart(2, '0');
            if (codeEl && state.formatted && codeEl.textContent.trim().replace(/\s/g, '') !== state.code) {
                codeEl.textContent = state.formatted;
                codeEl.classList.add('copy-flash');
                setTimeout(() => codeEl.classList.remove('copy-flash'), 400);
            }
            if (ringEl) {
                const percent = (state.remaining / 30) * 100;
                ringEl.style.strokeDashoffset = 100 - percent;
                const warn = state.remaining <= 7;
                ringEl.classList.toggle('stroke-secondary', !warn);
                ringEl.classList.toggle('stroke-error', warn);
                if (timerEl) {
                    timerEl.classList.toggle('text-on-surface-variant', !warn);
                    timerEl.classList.toggle('text-error', warn);
                }
                if (codeEl) {
                    codeEl.classList.toggle('text-error', warn);
                    codeEl.classList.toggle('animate-pulse', warn);
                }
            }
        }

        function fetchAndStart(account) {
            fetch(account.url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                const state = timerState[account.id];
                state.remaining = data.remaining;
                state.code = data.code;
                state.formatted = data.formatted;
                state.fetched = true;
                updateDOM(account);
            })
            .catch(() => null);
        }

        // Tick: decrement every second, re-fetch at 0
        setInterval(() => {
            accounts.forEach(a => {
                const state = timerState[a.id];
                if (!state || !state.fetched) return;
                state.remaining--;
                if (state.remaining <= 0) {
                    fetchAndStart(a); // new cycle
                } else {
                    updateDOM(a);
                }
            });
        }, 1000);

        // Initial fetch
        accounts.forEach(a => fetchAndStart(a));

        function copyCode(accountId) {
            const state = timerState[accountId];
            if (!state || !state.code) return;
            navigator.clipboard.writeText(state.code).then(() => showToast('Code copied to clipboard!'))
            .catch(() => {
                const ta = document.createElement('textarea');
                ta.value = state.code;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showToast('Code copied to clipboard!');
            });
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function exportAccounts() {
            fetch('{{ route("two-factor.export") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                navigator.clipboard.writeText(data.data).then(() => {
                    showToast('Backup copied to clipboard! (' + data.count + ' accounts)');
                }).catch(() => {
                    const ta = document.createElement('textarea');
                    ta.value = data.data;
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                    showToast('Backup copied to clipboard! (' + data.count + ' accounts)');
                });
            })
            .catch(() => showToast('Export failed'));
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeDeleteModal();
                document.getElementById('import-modal').classList.add('hidden');
            }
        });
    </script>
</x-layouts.app>
