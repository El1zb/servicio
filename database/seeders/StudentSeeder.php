<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Career;
use App\Models\Period;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $campusIds   = Campus::pluck('id')->all();
        $careerIds   = Career::pluck('id')->all();
        $semesterIds = Semester::pluck('id')->all();
        $statuses    = ['pendiente', 'aprobado', 'rechazado'];
        $systems     = ['Escolarizado', 'Sabatino'];

        if (! $campusIds || ! $careerIds) {
            return;
        }

        $counter = Student::count();

        foreach (Period::all() as $period) {
            $studentsPerPeriod = random_int(5, 10);

            for ($i = 0; $i < $studentsPerPeriod; $i++) {
                $counter++;

                $name           = fake()->firstName();
                $lastNamePat    = fake()->lastName();
                $lastNameMat    = fake()->lastName();
                $controlNumber  = str_pad((string) (20000 + $counter), 8, '0', STR_PAD_LEFT);

                $user = User::firstOrCreate(
                    ['email' => "alumno{$counter}@example.com"],
                    [
                        'name'     => "{$name} {$lastNamePat} {$lastNameMat}",
                        'password' => Hash::make('password'),
                    ]
                );
                $user->assignRole('student');

                Student::firstOrCreate(
                    ['control_number' => $controlNumber],
                    [
                        'user_id'             => $user->id,
                        'campus_id'           => $campusIds[array_rand($campusIds)],
                        'period_id'           => $period->id,
                        'career_id'           => $careerIds[array_rand($careerIds)],
                        'semester_id'         => $semesterIds ? $semesterIds[array_rand($semesterIds)] : null,
                        'system'              => $systems[array_rand($systems)],
                        'curp'                => strtoupper(fake()->bothify('????######??????##')),
                        'rfc'                 => strtoupper(fake()->bothify('????######???')),
                        'last_name_paterno'   => $lastNamePat,
                        'last_name_materno'   => $lastNameMat,
                        'name'                => $name,
                        'institutional_email' => "{$controlNumber}@itsco.edu.mx",
                        'personal_email'      => fake()->unique()->safeEmail(),
                        'phone'               => fake()->numerify('##########'),
                        'reticular_progress'  => random_int(20, 100),
                        'status'              => $statuses[array_rand($statuses)],
                    ]
                );
            }
        }
    }
}
