<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    @if (session('status'))
        <div class="bg-secondary-container/20 border border-secondary-container/50 rounded-lg p-3 flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
            <p class="text-label-sm text-secondary font-medium">{{ session('status') }}</p>
        </div>
    @endif

    <form wire:submit="login">
        <div>
            <label for="email" class="block text-label-sm font-label-sm text-on-surface mb-base">Email</label>
            <input wire:model="form.email" id="email" name="email" type="email" required autofocus autocomplete="username"
                   class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-sm py-2 text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            @error('form.email') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label for="password" class="block text-label-sm font-label-sm text-on-surface mb-base">Password</label>
            <input wire:model="form.password" id="password" name="password" type="password" required autocomplete="current-password"
                   class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-sm py-2 text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            @error('form.password') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-outline-variant text-primary shadow-sm focus:ring-primary" name="remember">
                <span class="ms-2 text-sm text-on-surface-variant">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-on-surface-variant hover:text-on-surface rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('password.request') }}" wire:navigate>
                    Forgot your password?
                </a>
            @endif

            <button type="submit" class="ms-3 inline-flex items-center px-md py-sm bg-primary text-on-primary border border-transparent rounded-lg font-label-sm text-label-sm tracking-wide hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25">
                Log in
            </button>
        </div>
    </form>
</div>
