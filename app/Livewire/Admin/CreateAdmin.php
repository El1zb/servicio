<?php

namespace App\Livewire\Admin;

use App\Actions\Admin\CreateAdminAccount;
use App\Actions\Admin\DeleteAdminAccount;
use App\Actions\Admin\UpdateAdminAccount;
use App\Models\User;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['title' => 'Administradores'])]
class CreateAdmin extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    protected array $excludedEmails = ['admin@example.com'];

    public bool $isModalOpen = false;
    public ?int $editingId = null;
    public bool $isDeleteModalOpen = false;
    public ?User $adminToDelete = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public string $search = '';

    public function mount(): void
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'No autorizado.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updated(string $propertyName): void
    {
        $this->resetValidation($propertyName);
    }

    public function render()
    {
        $admins = User::role('admin')
            ->whereNotIn('email', $this->excludedEmails)
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.create-admin', compact('admins'));
    }

    public function create(): void
    {
        $this->resetFields();
        $this->editingId = null;
        $this->isModalOpen = true;
    }

    public function edit(int $id): void
    {
        $admin = User::findOrFail($id);
        $this->editingId = $id;
        $this->name = $admin->name;
        $this->email = $admin->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->isModalOpen = true;
    }

    public function save(CreateAdminAccount $create, UpdateAdminAccount $update): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email' . ($this->editingId ? ",{$this->editingId}" : '')],
        ];

        if (! $this->editingId || $this->password) {
            $rules['password'] = ['required', 'string', 'confirmed', Rules\Password::defaults()];
        }

        $validated = $this->validate($rules, [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Ya existe un administrador con este correo.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if ($this->editingId) {
            $update(User::findOrFail($this->editingId), $validated);
            $this->dispatch('notify', type: 'info', message: 'Administrador actualizado correctamente');
        } else {
            $create($validated);
            $this->dispatch('notify', type: 'success', message: 'Administrador creado correctamente');
        }

        $this->isModalOpen = false;
        $this->resetFields();
    }

    public function confirmDelete(int $id): void
    {
        $this->isModalOpen = false;
        $this->adminToDelete = User::findOrFail($id);
        $this->isDeleteModalOpen = true;
    }

    public function delete(DeleteAdminAccount $delete): void
    {
        if ($this->adminToDelete) {
            $delete($this->adminToDelete);
            $this->dispatch('notify', type: 'error', message: 'Administrador eliminado correctamente');
        }

        $this->isDeleteModalOpen = false;
    }

    private function resetFields(): void
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
    }
}
