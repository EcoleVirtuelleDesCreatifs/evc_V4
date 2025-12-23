<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class StudentProfileService
{
    public function loadStudent(?User $user, ?int $id): Student
    {
        if ($id) {
            return Student::findOrFail($id);
        }

        if ($user) {
            // Chercher par user_id (email n'existe PAS dans la table students)
            $student = Student::where('user_id', $user->id)->first();

            // Si toujours pas trouvé, créer un nouveau
            if (!$student) {
                $student = new Student([
                    'user_id' => $user->id,
                    'first_name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                ]);
            }

            return $student;
        }

        return new Student();
    }

    public function loadPreRegistration(Student $student, ?User $user): ?object
    {
        if (!Schema::hasTable('pre_registrations')) {
            return null;
        }
        $email = $student->email ?: ($user->email ?? null);
        if (!$email) return null;
        return DB::table('pre_registrations')->where('email', $email)->orderByDesc('id')->first();
    }

    public function save(Student $student, array $data, ?UploadedFile $photo): Student
    {
        foreach ([
            'first_name','last_name','email','phone','whatsapp','date_of_birth','gender','student_id','program',
            'level','specialization','quartier','city','country','status','gpa','credits_earned','years_experience','industry_sector'
        ] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $student->{$field} = $data[$field];
            }
        }

        if ($photo) {
            $userId = $student->user_id ?: (auth()->user()->id ?? null);
            if ($userId) {
                $oldPath = (string) ($student->profile_photo ?? '');
                if ($oldPath !== '' && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }

                $extension = $photo->getClientOriginalExtension();
                $directory = 'users/' . $userId . '/profile';
                $filename = 'photo.' . $extension;
                $path = $photo->storeAs($directory, $filename, 'public');

                $student->profile_photo = $path;
            }
        }

        if (empty($student->user_id) && $user = auth()->user()) {
            $student->user_id = $user->id;
        }

        // Générer automatiquement un student_id unique si non fourni
        if (empty($student->student_id)) {
            // Format: EVC-ANNÉE-JOUR-MOIS-NUMERO (ex: EVC-2025-141001)
            $year = date('Y');
            $day = date('d');
            $month = date('m');
            $datePrefix = "{$year}-{$day}{$month}";

            $lastStudent = Student::where('student_id', 'LIKE', "EVC-{$datePrefix}%")
                ->orderBy('student_id', 'desc')
                ->first();

            if ($lastStudent && preg_match('/EVC-\\d{4}-\\d{4}(\\d{2})/', $lastStudent->student_id, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1;
            }

            $student->student_id = sprintf('EVC-%s-%s%02d', $year, $day . $month, $nextNumber);
        }

        // Définir des valeurs par défaut pour les champs obligatoires (NOT NULL)
        if (empty($student->first_name)) {
            $student->first_name = auth()->user()->name ?? 'Non spécifié';
        }

        if (empty($student->last_name)) {
            $student->last_name = 'Non spécifié';
        }

        if (empty($student->email)) {
            $student->email = auth()->user()->email ?? 'noemail@evc.com';
        }

        if (empty($student->degree)) {
            $student->degree = 'Non spécifié';
        }

        if (empty($student->Level_education)) {
            $student->Level_education = 'Non spécifié';
        }

        $student->save();
        return $student;
    }
}
