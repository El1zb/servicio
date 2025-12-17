<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CreateAdmin extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // 🔹 Verificación de acceso al componente
    public function mount()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No autorizado.');
        }
    }

    public function render()
    {
        return view('livewire.admin.create-admin'); // tu vista reutilizada del registro
    }

    public function createAdmin()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Asignar rol de administrador
        $user->assignRole('admin');

        // Mensaje flash para informar que se creó correctamente
        session()->flash('message', 'Administrador creado correctamente.');

        // Resetear campos del formulario
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
    }
}
