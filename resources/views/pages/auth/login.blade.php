<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return redirect()->intended('/dashboard');
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }
};

?>

<x-guest-layout>
    {{-- Brand Header --}}
    <div class="mb-8 text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-400 mb-1">Control Center</p>
        <h1 class="text-3xl font-bold text-white leading-tight">Sign In</h1>
        <p class="mt-2 text-sm text-slate-400">Access your POS dashboard</p>
    </div>

    <form wire:submit="login" class="space-y-5">

        {{-- Email Address --}}
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1.5">
                Email Address
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input wire:model="email" id="email" type="email" required autofocus autocomplete="email"
                    class="block w-full rounded-xl border border-slate-700 bg-slate-900/60 pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500
                           focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 focus:outline-none transition"
                    placeholder="you@example.com">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <label for="password" class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1.5">
                Password
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input wire:model="password" id="password" name="password"
                    :type="show ? 'text' : 'password'" required
                    class="block w-full rounded-xl border border-slate-700 bg-slate-900/60 pl-10 pr-11 py-3 text-sm text-white placeholder-slate-500
                           focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 focus:outline-none transition"
                    placeholder="••••••••">
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-500 hover:text-slate-300 transition focus:outline-none">
                    <svg x-show="!show" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" style="display:none;" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Remember Me & Forgot Password --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <div class="relative">
                    <input wire:model="remember" id="remember_me" type="checkbox"
                        class="sr-only peer">
                    <div class="w-9 h-5 bg-slate-700 peer-checked:bg-cyan-600 rounded-full transition-colors duration-200 peer-focus:ring-2 peer-focus:ring-cyan-500/50"></div>
                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                </div>
                <span class="ml-2.5 text-xs text-slate-400 group-hover:text-slate-300 transition select-none">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-xs font-medium text-cyan-400 hover:text-cyan-300 transition">
                    Forgot password?
                </a>
            @endif
        </div>

        {{-- Submit Button --}}
        <button type="submit"
            class="relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 px-4 py-3 text-sm font-semibold text-white
                   shadow-lg shadow-cyan-900/40 hover:from-cyan-500 hover:to-cyan-400
                   focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900
                   transition-all duration-200 active:scale-[0.98]
                   flex items-center justify-center gap-2">
            <span wire:loading.remove wire:target="login">Sign in to Dashboard</span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Signing in…
            </span>
            {{-- Shimmer effect --}}
            <div class="absolute inset-0 -translate-x-full animate-[shimmer_2s_infinite] bg-gradient-to-r from-transparent via-white/10 to-transparent pointer-events-none"></div>
        </button>

    </form>
</x-guest-layout>