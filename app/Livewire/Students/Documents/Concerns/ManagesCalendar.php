<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Document;
use Carbon\Carbon;

trait ManagesCalendar
{
    public array $calendarEvents = [];

    public function loadCalendarEvents(): void
    {
        if (! $this->student) {
            $this->calendarEvents = [];
            return;
        }

        $this->calendarEvents = Document::where('student_id', $this->student->id)
            ->with('file')
            ->where('is_active', true)
            ->get()
            ->map(function ($doc) {
                $uploadMode = $doc->file?->upload_mode ?? 'bidirectional';

                if ($uploadMode === 'admin_only') return null;

                $generalDate = $doc->file?->limit_date ? Carbon::parse($doc->file->limit_date) : null;
                $customDate  = $doc->custom_limit_date ? Carbon::parse($doc->custom_limit_date) : null;

                $effectiveDate = ($generalDate && $customDate && $customDate->greaterThan($generalDate))
                    ? $customDate
                    : $generalDate;

                if (! $effectiveDate) return null;

                return [
                    'date'       => $effectiveDate->format('Y-m-d'),
                    'doc'        => ['id' => $doc->id, 'name' => $doc->name],
                    'status'     => $doc->status,
                    'isExpired'  => $effectiveDate->isPast(),
                    'hasFile'    => ! empty($doc->student_file_name),
                    'uploadMode' => $uploadMode,
                ];
            })
            ->filter(fn($e) => $e !== null)
            ->values()
            ->toArray();
    }
}