<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Recuperar contraseña" description="Ingresa tu correo electrónico para recibir un enlace de restablecimiento" />

    <!-- Estado de Sesión -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Correo Electrónico -->
        <flux:input
            wire:model="email"
            label="{{ __('Correo electrónico') }}"
            type="email"
            name="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Enviar enlace de recuperación') }}</flux:button>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-400">
        O bien, regresa a
        <flux:link href="{{ route('login') }}" wire:navigate>iniciar sesión</flux:link>
    </div>
</div>
