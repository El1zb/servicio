<?php

namespace App\Livewire\Admin;

use App\Models\Campus;
use App\Models\Career;
use App\Models\File;
use App\Models\Period;
use App\Models\Semester;
use App\Services\TrashPurger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Papelera'])]
class Trash extends Component
{
    private const TYPES = [
        'period'   => ['model' => Period::class,   'label' => 'Periodo'],
        'semester' => ['model' => Semester::class,  'label' => 'Semestre'],
        'career'   => ['model' => Career::class,    'label' => 'Carrera'],
        'campus'   => ['model' => Campus::class,    'label' => 'Campus'],
        'file'     => ['model' => File::class,      'label' => 'Documento'],
    ];

    public bool $isPurgeModalOpen = false;
    public ?array $purgeToDelete = null;

    public function render()
    {
        return view('livewire.admin.trash', [
            'items' => $this->trashedItems(),
        ]);
    }

    private function trashedItems(): array
    {
        $items = [];

        foreach (self::TYPES as $type => $cfg) {
            $query = $cfg['model']::onlyTrashed();
            if ($type === 'file') {
                $query->with(['period' => fn ($q) => $q->withTrashed()]);
            }

            foreach ($query->get() as $model) {
                $deletedAt = Carbon::parse($model->deleted_at);

                $items[] = [
                    'type'          => $type,
                    'label'         => $cfg['label'],
                    'id'            => $model->id,
                    'name'          => $model->name,
                    'periodName'    => $type === 'file' ? ($model->period->name ?? null) : null,
                    'deletedAt'     => $deletedAt,
                    'daysLeft'      => max(0, 30 - (int) floor($deletedAt->diffInDays(now()))),
                    'canPurge'      => TrashPurger::canPurge($type, $model),
                    'blockedReason' => TrashPurger::blockedReason($type, $model),
                ];
            }
        }

        usort($items, fn ($a, $b) => $b['deletedAt'] <=> $a['deletedAt']);

        return $items;
    }

    private function findModel(string $type, int $id): ?Model
    {
        $class = self::TYPES[$type]['model'] ?? null;

        return $class ? $class::withTrashed()->find($id) : null;
    }

    public function restore(string $type, int $id): void
    {
        $model = $this->findModel($type, $id);

        if (! $model) return;

        $model->restore();

        $this->dispatch('notify', type: 'success', message: 'Restaurado correctamente');
    }

    public function confirmPurge(string $type, int $id): void
    {
        $model = $this->findModel($type, $id);

        if (! $model || ! TrashPurger::canPurge($type, $model)) {
            return;
        }

        $this->purgeToDelete = [
            'type'  => $type,
            'id'    => $id,
            'name'  => $model->name,
            'label' => self::TYPES[$type]['label'],
        ];
        $this->isPurgeModalOpen = true;
    }

    public function purge(): void
    {
        if (! $this->purgeToDelete) return;

        $type  = $this->purgeToDelete['type'];
        $model = $this->findModel($type, $this->purgeToDelete['id']);

        $this->isPurgeModalOpen = false;
        $this->purgeToDelete    = null;

        if (! $model) return;

        if (! TrashPurger::canPurge($type, $model)) {
            $this->dispatch('notify', type: 'warning', message: 'No se puede eliminar: ' . TrashPurger::blockedReason($type, $model));
            return;
        }

        TrashPurger::purge($type, $model);

        $this->dispatch('notify', type: 'error', message: 'Eliminado permanentemente');
    }

    /**
     * @param array<int, string> $keys Cada elemento en formato "type:id"
     */
    public function restoreSelected(array $keys): void
    {
        $count = 0;

        foreach ($keys as $key) {
            [$type, $id] = explode(':', $key, 2);
            $model = $this->findModel($type, (int) $id);

            if ($model) {
                $model->restore();
                $count++;
            }
        }

        $this->dispatch('notify', type: 'success', message: $count === 1 ? '1 elemento restaurado' : "{$count} elementos restaurados");
    }

    /**
     * @param array<int, string> $keys Cada elemento en formato "type:id"
     */
    public function purgeSelected(array $keys): void
    {
        $purged = 0;
        $blocked = 0;

        foreach ($keys as $key) {
            [$type, $id] = explode(':', $key, 2);
            $model = $this->findModel($type, (int) $id);

            if (! $model) continue;

            if (! TrashPurger::canPurge($type, $model)) {
                $blocked++;
                continue;
            }

            TrashPurger::purge($type, $model);
            $purged++;
        }

        if ($purged === 0) {
            $this->dispatch('notify', type: 'warning', message: 'No se pudo eliminar: los elementos seleccionados están bloqueados');
            return;
        }

        $message = $purged === 1 ? '1 elemento eliminado permanentemente' : "{$purged} elementos eliminados permanentemente";
        if ($blocked > 0) {
            $message .= " · {$blocked} bloqueado(s)";
        }

        $this->dispatch('notify', type: 'error', message: $message);
    }
}
