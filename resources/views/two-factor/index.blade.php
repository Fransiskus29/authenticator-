<x-layouts.app>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-sm mb-lg animate-fade-in-up">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-base">Your Accounts</h2>
            <p class="text-body-md text-on-surface-variant flex items-center gap-base">
                <span class="material-symbols-outlined text-[16px] text-secondary">lock</span>
                <span>Secrets encrypted at rest</span>
            </p>
        </div>
        <div class="flex items-center gap-xs">
            <a href="{{ route('two-factor.archived') }}" wire:navigate class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition-all duration-300 hover:scale-105" title="Archived accounts">
                <span class="material-symbols-outlined">archive</span>
            </a>
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
            <input type="hidden" name="category" value="{{ request('category') }}">
        </form>
    @endif

    @if ($categories->isNotEmpty())
        <div class="flex flex-wrap gap-xs mb-md animate-fade-in-up" style="animation-delay: 0.15s;">
            <a href="{{ route('two-factor.index', ['q' => request('q')]) }}" wire:navigate
               class="px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 category-chip {{ !request('category') ? 'active' : '' }}">
                All <span class="ml-0.5 opacity-70">{{ $accounts->count() }}</span>
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('two-factor.index', ['category' => $cat->id, 'q' => request('q')]) }}" wire:navigate
                   class="px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 category-chip {{ request('category') == $cat->id ? 'active' : '' }}"
                   style="--cat-color: {{ $cat->color }};">
                    {{ $cat->name }} <span class="ml-0.5 opacity-70">{{ $cat->accounts_count }}</span>
                </a>
            @endforeach
            <button onclick="openCategoryManager()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-all duration-200">
                <span class="material-symbols-outlined text-[14px] align-middle">edit</span>
            </button>
        </div>
    @else
        <div class="mb-md animate-fade-in-up" style="animation-delay: 0.15s;">
            <button onclick="openCategoryManager()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-all duration-200">
                <span class="material-symbols-outlined text-[14px] align-middle">add</span> Add categories to organize accounts
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-sm stagger-in" id="accounts-list">
        @forelse ($accounts as $index => $account)
            {{-- Swipe container --}}
            <div class="swipe-container relative overflow-hidden rounded-2xl" data-account-id="{{ $account->id }}">
                {{-- Archive action (revealed on swipe) --}}
                <div class="absolute inset-0 flex items-center justify-end px-sm pointer-events-none">
                    <form action="/authenticator/{{ $account->id }}" method="POST" class="pointer-events-auto" onclick="event.stopPropagation()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex flex-col items-center justify-center gap-1 w-20 h-full rounded-2xl bg-error text-on-error btn-press">
                            <span class="material-symbols-outlined text-[24px]">archive</span>
                            <span class="text-[11px] font-medium">Archive</span>
                        </button>
                    </form>
                </div>
                {{-- Card (swipeable) --}}
                <div class="swipe-card bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-md card-hover glow-hover relative group cursor-pointer touch-pan-y"
                     data-account-id="{{ $account->id }}">
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
                             id="code-{{ $account->id }}">------</div>
                        <button aria-label="Copy code"
                                class="text-on-surface-variant hover:text-primary transition-all duration-200 p-2 rounded-full hover:bg-surface-container-low opacity-0 group-hover:opacity-100 focus:opacity-100 hover:scale-110">
                            <span class="material-symbols-outlined text-[20px]">content_copy</span>
                        </button>
                    </div>
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

    {{-- Context Menu (right-click on PC) --}}
    <div id="context-menu" class="fixed z-50 hidden">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 shadow-xl shadow-black/15 dark:shadow-black/40 py-xs min-w-[180px] animate-scale-in">
            <button onclick="contextMenuAction('copy')" class="w-full px-3 py-2 text-left text-label-md text-on-surface hover:bg-surface-container-low flex items-center gap-sm transition-colors duration-150">
                <span class="material-symbols-outlined text-[18px]">content_copy</span>
                Copy Code
            </button>
            <button onclick="contextMenuAction('archive')" class="w-full px-3 py-2 text-left text-label-md text-error hover:bg-error/10 flex items-center gap-sm transition-colors duration-150">
                <span class="material-symbols-outlined text-[18px]">archive</span>
                Archive
            </button>
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
                <form action="/authenticator/import" method="POST">
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

    {{-- Category Manager Modal --}}
    <div id="category-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-on-surface/50 backdrop-bl-sm" onclick="closeCategoryManager()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-lg animate-scale-in border border-outline-variant/50 shadow-2xl shadow-black/10 dark:shadow-black/40 max-h-[80vh] flex flex-col">
                <h3 class="text-headline-md text-on-surface mb-md">Manage Categories</h3>

                {{-- Add new category --}}
                <div class="flex gap-sm mb-md">
                    <input type="text" id="new-cat-name" placeholder="Category name" maxlength="50"
                           class="flex-1 bg-surface-container-low/80 border border-outline-variant/50 rounded-xl px-sm py-2.5 text-body-sm text-on-surface focus:outline-none input-glow">
                    <input type="color" id="new-cat-color" value="#6750A4" class="w-10 h-10 rounded-xl border border-outline-variant/50 cursor-pointer bg-transparent">
                    <button onclick="createCategory()" class="bg-primary text-on-primary px-sm py-2 rounded-xl btn-press text-sm font-medium">Add</button>
                </div>

                {{-- Category list --}}
                <div id="category-list" class="flex-1 overflow-y-auto space-y-xs"></div>

                <div class="mt-md pt-sm border-t border-outline-variant/30 flex justify-end">
                    <button onclick="closeCategoryManager()" class="text-label-sm text-on-surface-variant hover:text-on-surface px-md py-2 rounded-xl transition-colors btn-press">Done</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // === TOTP Timer ===
        const accountIds = @json($accountIds);
        const timerState = {};

        accountIds.forEach(id => {
            timerState[id] = { remaining: 30, code: null, formatted: null, fetched: false };
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
            fetch('/authenticator/' + account.id + '/code', {
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

        setInterval(() => {
            accountIds.forEach(id => {
                const state = timerState[id];
                if (!state || !state.fetched) return;
                state.remaining--;
                if (state.remaining <= 0) {
                    fetchAndStart({id});
                } else {
                    updateDOM({id});
                }
            });
        }, 1000);

        accountIds.forEach(id => fetchAndStart({id}));

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

        // === Swipe (mobile) ===
        let swipeTarget = null;
        let swipeStartX = 0;
        let swipeCurrentX = 0;
        let swiping = false;

        document.querySelectorAll('.swipe-card').forEach(card => {
            card.addEventListener('touchstart', e => {
                swipeTarget = card;
                swipeStartX = e.touches[0].clientX;
                swipeCurrentX = swipeStartX;
                swiping = false;
                card.style.transition = 'none';
            }, { passive: true });

            card.addEventListener('touchmove', e => {
                if (!swipeTarget) return;
                swipeCurrentX = e.touches[0].clientX;
                const dx = swipeCurrentX - swipeStartX;
                // Only allow swipe left (negative dx)
                if (dx < 0) {
                    swiping = true;
                    const offset = Math.max(dx, -100);
                    card.style.transform = `translateX(${offset}px)`;
                }
            }, { passive: true });

            card.addEventListener('touchend', () => {
                if (!swipeTarget) return;
                swipeTarget.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                const dx = swipeCurrentX - swipeStartX;
                if (dx < -50) {
                    // Snap to reveal archive button
                    swipeTarget.style.transform = 'translateX(-80px)';
                } else {
                    // Snap back
                    swipeTarget.style.transform = 'translateX(0)';
                }
                swipeTarget = null;
            });
        });

        // Close swipe when tapping elsewhere
        document.addEventListener('touchstart', e => {
            if (!e.target.closest('.swipe-container')) {
                document.querySelectorAll('.swipe-card').forEach(card => {
                    card.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.transform = 'translateX(0)';
                });
            }
        }, { passive: true });

        // === Context Menu (right-click / long-press) ===
        let contextAccountId = null;
        const contextMenu = document.getElementById('context-menu');

        function showContextMenu(x, y, accountId) {
            contextAccountId = accountId;
            contextMenu.style.left = x + 'px';
            contextMenu.style.top = y + 'px';
            contextMenu.classList.remove('hidden');
            // Prevent going off screen
            requestAnimationFrame(() => {
                const rect = contextMenu.getBoundingClientRect();
                if (rect.right > window.innerWidth) contextMenu.style.left = (x - rect.width) + 'px';
                if (rect.bottom > window.innerHeight) contextMenu.style.top = (y - rect.height) + 'px';
            });
        }

        function hideContextMenu() {
            contextMenu.classList.add('hidden');
            contextAccountId = null;
        }

        document.addEventListener('contextmenu', e => {
            const card = e.target.closest('.swipe-card');
            if (card) {
                e.preventDefault();
                showContextMenu(e.clientX, e.clientY, card.dataset.accountId);
            }
        });

        document.addEventListener('click', e => {
            if (!contextMenu.contains(e.target)) hideContextMenu();
        });

        // Long-press for mobile (alternative to swipe)
        let longPressTimer = null;
        document.querySelectorAll('.swipe-card').forEach(card => {
            card.addEventListener('touchstart', e => {
                longPressTimer = setTimeout(() => {
                    e.preventDefault();
                    const touch = e.touches[0];
                    showContextMenu(touch.clientX, touch.clientY, card.dataset.accountId);
                }, 500);
            }, { passive: false });

            card.addEventListener('touchend', () => clearTimeout(longPressTimer));
            card.addEventListener('touchmove', () => clearTimeout(longPressTimer));
        });

        function contextMenuAction(action) {
            if (!contextAccountId) return;
            if (action === 'copy') {
                copyCode(parseInt(contextAccountId));
            } else if (action === 'archive') {
                // Submit the archive form for this account
                const form = document.querySelector('.swipe-container[data-account-id="' + contextAccountId + '"] form');
                if (form) form.requestSubmit();
            }
            hideContextMenu();
        }

        // Escape closes everything
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                hideContextMenu();
                document.getElementById('import-modal').classList.add('hidden');
            }
        });

        // === Export ===
        function exportAccounts() {
            fetch('/authenticator/export', {
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

        function showToast(text) {
            const t = document.getElementById('toast');
            document.getElementById('toast-text').textContent = text;
            t.classList.remove('hidden');
            setTimeout(() => t.classList.add('hidden'), 2000);
        }

        // === Category Manager ===
        const csrfToken = '{{ csrf_token() }}';

        function openCategoryManager() {
            document.getElementById('category-modal').classList.remove('hidden');
            loadCategories();
        }

        function closeCategoryManager() {
            document.getElementById('category-modal').classList.add('hidden');
        }

        async function loadCategories() {
            const res = await fetch('/authenticator/categories', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const cats = await res.json();
            const list = document.getElementById('category-list');
            list.innerHTML = cats.length ? cats.map(c => `
                <div class="flex items-center gap-sm py-2 px-3 rounded-xl bg-surface-container-low/50 group">
                    <div class="w-4 h-4 rounded-full border border-outline-variant/50" style="background-color: ${c.color}"></div>
                    <span class="flex-1 text-sm text-on-surface truncate">${c.name}</span>
                    <span class="text-xs text-on-surface-variant">${c.accounts_count} accounts</span>
                    <button onclick="deleteCategory(${c.id})" class="opacity-0 group-hover:opacity-100 text-error hover:bg-error/10 rounded-full p-1 transition-all duration-200">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </div>
            `).join('') : '<p class="text-sm text-on-surface-variant text-center py-4">No categories yet</p>';
        }

        async function createCategory() {
            const name = document.getElementById('new-cat-name').value.trim();
            const color = document.getElementById('new-cat-color').value;
            if (!name) return;

            const res = await fetch('/authenticator/categories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ name, color }),
            });

            if (res.ok) {
                document.getElementById('new-cat-name').value = '';
                loadCategories();
                showToast('Category created');
            }
        }

        async function deleteCategory(id) {
            if (!confirm('Delete this category? Accounts will be unassigned.')) return;

            const res = await fetch('/authenticator/categories/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (res.ok) {
                loadCategories();
                showToast('Category deleted');
            }
        }
    </script>
</x-layouts.app>
