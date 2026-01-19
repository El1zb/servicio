<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CreateAdmin extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $editingId = null; // null = crear, id = editar
    public $isDeleteModalOpen = false;
    public $adminToDelete;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public $search = ''; // Para búsqueda de admins

    protected $paginationTheme = 'tailwind'; // Paginación estilo Tailwind

    // 🔹 Administradores a excluir (ejemplo: creado en AdminSeeder)
    protected $excludedEmails = ['admin@example.com'];

    // Verificación de acceso
    public function mount()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No autorizado.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reinicia la página cuando se filtra
    }

    public function render()
    {
        $admins = User::role('admin')
            ->whereNotIn('email', $this->excludedEmails) // Excluir emails protegidos
            ->where(function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.create-admin', compact('admins'));
    }

    // Abrir modal de creación
    public function create()
    {
        $this->resetFields();
        $this->editingId = null;
        $this->isModalOpen = true;
    }

    // Abrir modal de edición
    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $this->editingId = $id;
        $this->name = $admin->name;
        $this->email = $admin->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->isModalOpen = true;
    }

    // Guardar cambios o crear nuevo admin
    public function save()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ];

        if ($this->editingId) {
            $rules['email'][] = 'unique:users,email,' . $this->editingId;
        } else {
            $rules['email'][] = 'unique:users,email';
            $rules['password'] = ['required', 'string', 'confirmed', Rules\Password::defaults()];
        }

        if (!$this->editingId || $this->password) {
            $rules['password'] = ['required', 'string', 'confirmed', Rules\Password::defaults()];
        }

        $validated = $this->validate($rules);

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            $this->dispatch('notify', type: 'info', message: "Administrador actualizado correctamente");
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole('admin');
            $this->dispatch('notify', type: 'success', message: "Administrador creado correctamente");
        }

        $this->isModalOpen = false;
        $this->resetFields();
    }

    // Confirmar eliminación
    public function confirmDelete($id)
    {
        $this->adminToDelete = User::findOrFail($id);
        $this->isDeleteModalOpen = true;
    }

    // Eliminar admin
    public function delete()
    {
        if ($this->adminToDelete) {
            $this->adminToDelete->delete();
            $this->dispatch('notify', type: 'error', message: "Administrador eliminado correctamente");
        }
        $this->isDeleteModalOpen = false;
    }

    private function resetFields()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
    }
}
