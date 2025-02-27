<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Crea una cuenta" description="Ingresa tus datos a continuación para crear tu cuenta" />

    <!-- Estatus de Sesión -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Nombre -->
        <flux:input
            wire:model="name"
            id="name"
            label="{{ __('Nombre') }}"
            type="text"
            name="name"
            required
            autofocus
            autocomplete="name"
            placeholder="Nombre completo"
        />

        <!-- Correo Electrónico -->
        <flux:input
            wire:model="email"
            id="email"
            label="{{ __('Correo electrónico') }}"
            type="email"
            name="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Contraseña -->
        <flux:input
            wire:model="password"
            id="password"
            label="{{ __('Contraseña') }}"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Contraseña"
        />

        <!-- Confirmar Contraseña -->
        <flux:input
            wire:model="password_confirmation"
            id="password_confirmation"
            label="{{ __('Confirmar contraseña') }}"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            placeholder="Confirmar contraseña"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Crear cuenta') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        ¿Ya tienes una cuenta?
        <flux:link href="{{ route('login') }}" wire:navigate>Iniciar sesión</flux:link>
    </div>
</div>
