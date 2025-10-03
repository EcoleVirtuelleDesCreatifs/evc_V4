<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentProfileService
{
    public function loadStudent(?User $user, ?int $id): Student
    {
        if ($id) {
            return Student::findOrFail($id);
        }
        
        if ($user) {
            // Chercher d'abord par user_id
            $student = Student::where('user_id', $user->id)->first();
            
            // Si pas trouvé par user_id, chercher par email (cas des étudiants migrés)
            if (!$student && $user->email) {
                $student = Student::where('email', $user->email)->first();
                
                // Si trouvé par email, lier le user_id
                if ($student) {
                    $student->user_id = $user->id;
                    $student->save();
                }
            }
            
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
            // Stockage public/uploads/photos (peut être migré vers storage/app/public)
            $filename = 'student_' . ($student->id ?? 'new') . '_' . time() . '.' . $photo->getClientOriginalExtension();
            $dest = public_path('uploads/photos');
            if (!is_dir($dest)) {
                @mkdir($dest, 0775, true);
            }
            $photo->move($dest, $filename);
            $student->profile_photo = 'uploads/photos/' . $filename;
        }

        if (empty($student->user_id) && $user = auth()->user()) {
            $student->user_id = $user->id;
        }

        $student->save();
        return $student;
    }
}
