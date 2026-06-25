<nav class="-mx-3 flex flex-1 justify-end">
    @auth
        <a href="{{ url('/dashboard') }}"
           class="rounded-md px-3 py-2 text-on-surface ring-1 ring-transparent transition hover:text-on-surface-variant focus:outline-none focus-visible:ring-primary">
            Dashboard
        </a>
    @else
        <a href="{{ route('login') }}"
           class="rounded-md px-3 py-2 text-on-surface ring-1 ring-transparent transition hover:text-on-surface-variant focus:outline-none focus-visible:ring-primary">
            Log in
        </a>

        @if (Route::has('register'))
            <a href="{{ route('register') }}"
               class="rounded-md px-3 py-2 text-on-surface ring-1 ring-transparent transition hover:text-on-surface-variant focus:outline-none focus-visible:ring-primary">
                Register
            </a>
        @endif
    @endauth
</nav>
