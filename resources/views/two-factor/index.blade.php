<x-app-layout>
    <div class="flex justify-between items-end mb-lg">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-base">Your Accounts</h2>
            <p class="text-body-md text-on-surface-variant flex items-center gap-base">
                <span class="material-symbols-outlined text-[16px] text-secondary">lock</span>
                <span>All tokens are end-to-end encrypted</span>
            </p>
        </div>
        <div class="flex items-center gap-sm">
            <button onclick="document.getElementById('import-modal').classList.remove('hidden')" class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors" title="Import">
                <span class="material-symbols-outlined">upload</span>
            </button>
            <button onclick="exportAccounts()" class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-colors" title="Export">
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
        <form method="GET" action="{{ route('two-factor.index') }}" class="relative mb-lg max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search accounts..."
                   class="w-full bg-surface-container-low border border-outline-variant rounded-full pl-10 pr-10 py-2 text-label-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary placeholder-on-surface-variant"
                   autocomplete="off">
            @if (request('q'))
                <a href="{{ route('two-factor.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition p-1">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </a>
            @endif
        </form>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-sm" id="accounts-list">
        @forelse ($accounts as $index => $account)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md card-hover relative group cursor-pointer"
                 onclick="copyCode({{ $account->id }})"
                 data-account-id="{{ $account->id }}"
                 data-url="{{ route('two-factor.code', $account) }}">
                <div class="flex justify-between items-start mb-md">
                    <div class="flex items-center gap-sm">
                        <div class="w-10 h-10 rounded-lg bg-surface-container-low border border-outline-variant flex items-center justify-center p-2
                            {{ match(($account->id % 6)) {
                                0 => 'text-[#4285F4]',
                                1 => 'text-[#181717]',
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
                        <div>
                            <h3 class="text-[18px] leading-[24px] font-semibold text-on-surface">{{ $account->label }}</h3>
                            @if ($account->issuer)
                                <p class="text-label-sm text-on-surface-variant">{{ $account->issuer }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="relative w-8 h-8 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <circle class="stroke-surface-container-high" cx="18" cy="18" fill="none" r="16" stroke-width="3"></circle>
                            <circle class="progress-ring__circle stroke-secondary" cx="18" cy="18" fill="none" r="16"
                                    stroke-dasharray="100" stroke-dashoffset="0" stroke-width="3"
                                    id="ring-{{ $account->id }}"></circle>
                        </svg>
                        <span class="absolute text-[10px] font-mono font-semibold text-on-surface-variant timer-text"
                              id="timer-{{ $account->id }}">30</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="font-mono text-otp-display text-on-surface tracking-widest code-display"
                         id="code-{{ $account->id }}">{{ $codes[$account->id] ?? '------' }}</div>
                    <button aria-label="Copy code"
                            class="text-on-surface-variant hover:text-primary transition-colors p-2 rounded-full hover:bg-surface-container-low opacity-0 group-hover:opacity-100 focus:opacity-100">
                        <span class="material-symbols-outlined text-[20px]">content_copy</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center animate-slide-up">
                <span class="material-symbols-outlined text-[64px] text-outline-variant mb-md block">shield</span>
                <h3 class="text-headline-md text-on-surface mb-xs">{{ request('q') ? 'No results found' : 'No accounts yet' }}</h3>
                <p class="text-body-md text-on-surface-variant mb-lg max-w-md mx-auto">
                    {{ request('q') ? 'Try a different search term' : 'Add your first 2FA account to start generating authentication codes' }}
                </p>
                @if (!request('q'))
                    <a href="{{ route('two-factor.create') }}" wire:navigate
                       class="bg-primary text-on-primary text-label-sm px-md py-sm rounded-xl hover:opacity-90 transition-opacity inline-flex items-center gap-xs shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Add Your First Account
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-on-surface/50" onclick="closeDeleteModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-surface-container-lowest rounded-xl max-w-sm w-full p-md animate-slide-up border border-outline-variant">
                <div class="w-14 h-14 bg-error-container rounded-full flex items-center justify-center mx-auto mb-sm">
                    <span class="material-symbols-outlined text-error text-[28px]">warning</span>
                </div>
                <h3 class="text-headline-md text-on-surface text-center mb-xs">Delete Account?</h3>
                <p class="text-body-md text-on-surface-variant text-center mb-md">Are you sure you want to delete <span id="delete-name" class="font-semibold text-on-surface"></span>?</p>
                <div class="flex gap-sm">
                    <button onclick="closeDeleteModal()" class="flex-1 py-2 text-label-sm font-label-sm text-on-surface-variant bg-surface-container-low border border-outline-variant rounded-lg hover:bg-surface-container transition-colors">Cancel</button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 text-label-sm font-label-sm text-on-error bg-error rounded-lg hover:opacity-90 transition-opacity">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div id="import-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-on-surface/50" onclick="document.getElementById('import-modal').classList.add('hidden')"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-surface-container-lowest rounded-xl max-w-md w-full p-md animate-slide-up border border-outline-variant">
                <div class="w-14 h-14 bg-primary-container/20 rounded-full flex items-center justify-center mx-auto mb-sm">
                    <span class="material-symbols-outlined text-primary text-[28px]">upload</span>
                </div>
                <h3 class="text-headline-md text-on-surface text-center mb-xs">Import Backup</h3>
                <p class="text-body-md text-on-surface-variant text-center mb-md">Paste your encrypted backup data below</p>
                <form action="{{ route('two-factor.import') }}" method="POST">
                    @csrf
                    <div class="mb-sm">
                        <textarea name="backup_data" rows="4" required
                                  class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-sm py-2 font-mono text-code-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary placeholder-on-surface-variant"
                                  placeholder="Paste backup data here..."></textarea>
                        @error('backup_data') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-sm">
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')"
                                class="flex-1 py-2 text-label-sm font-label-sm text-on-surface-variant bg-surface-container-low border border-outline-variant rounded-lg hover:bg-surface-container transition-colors">Cancel</button>
                        <button type="submit" class="flex-1 bg-primary text-on-primary text-label-sm font-label-sm py-2 rounded-lg hover:opacity-90 transition-opacity shadow-sm">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="fixed bottom-lg right-lg bg-inverse-surface text-inverse-on-surface px-md py-xs rounded-lg shadow-lg flex items-center gap-xs text-label-sm font-label-sm z-50 hidden">
        <span class="material-symbols-outlined text-secondary-fixed text-[20px]">check_circle</span>
        <span id="toast-text">Done!</span>
    </div>

    <script>
        const accounts = @json($accounts->map(fn($a) => ['id' => $a->id, 'url' => route('two-factor.code', $a)]));

        function refreshCode(account) {
            fetch(account.url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                const codeEl = document.getElementById('code-' + account.id);
                const timerEl = document.getElementById('timer-' + account.id);
                const ringEl = document.getElementById('ring-' + account.id);

                if (codeEl) {
                    const oldCode = codeEl.textContent.trim().replace(/\s/g, '');
                    if (oldCode !== data.code) {
                        codeEl.textContent = data.formatted;
                    }
                }
                if (timerEl) {
                    timerEl.textContent = data.remaining.toString().padStart(2, '0');
                }
                if (ringEl) {
                    const percent = (data.remaining / 30) * 100;
                    const offset = 100 - percent;
                    ringEl.style.strokeDashoffset = offset;

                    if (data.remaining <= 7) {
                        ringEl.classList.remove('stroke-secondary');
                        ringEl.classList.add('stroke-error');
                        timerEl.classList.remove('text-on-surface-variant');
                        timerEl.classList.add('text-error');
                        codeEl.classList.add('text-error', 'animate-pulse');
                    } else {
                        ringEl.classList.add('stroke-secondary');
                        ringEl.classList.remove('stroke-error');
                        timerEl.classList.add('text-on-surface-variant');
                        timerEl.classList.remove('text-error');
                        codeEl.classList.remove('text-error', 'animate-pulse');
                    }
                }
            })
            .catch(() => {});
        }

        function refreshAll() { accounts.forEach(a => refreshCode(a)); }

        function copyCode(accountId) {
            const codeEl = document.getElementById('code-' + accountId);
            if (codeEl) {
                const code = codeEl.textContent.trim().replace(/\s/g, '');
                navigator.clipboard.writeText(code).then(() => showToast('Code copied to clipboard!'))
                .catch(() => {
                    const ta = document.createElement('textarea');
                    ta.value = code;
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                    showToast('Code copied to clipboard!');
                });
            }
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-text').textContent = msg;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2000);
        }

        function confirmDelete(id, name) {
            document.getElementById('delete-name').textContent = name;
            document.getElementById('delete-form').action = '{{ url("authenticator") }}/' + id;
            document.getElementById('delete-modal').classList.remove('hidden');
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

        refreshAll();
        setInterval(refreshAll, 1000);
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeDeleteModal();
                document.getElementById('import-modal').classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
