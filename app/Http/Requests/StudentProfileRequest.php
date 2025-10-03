<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'autorisation fine peut être gérée par une Policy; ici on autorise et on laisse le contrôleur/policy décider
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'        => ['nullable','string','max:255'],
            'last_name'         => ['nullable','string','max:255'],
            'email'             => ['nullable','email','max:255'],
            'phone'             => ['nullable','string','max:50'],
            'whatsapp'          => ['nullable','string','max:50'],
            'date_of_birth'     => ['nullable','date'],
            'gender'            => ['nullable','string','max:50'],
            'student_id'        => ['nullable','string','max:100'],
            'program'           => ['nullable','string','max:255'],
            'level'             => ['nullable','string','max:255'],
            'specialization'    => ['nullable','string','max:255'],
            'quartier'          => ['nullable','string','max:500'],
            'city'              => ['nullable','string','max:255'],
            'country'           => ['nullable','string','max:255'],
            'status'            => ['nullable','string','max:100'],
            'gpa'               => ['nullable','numeric'],
            'credits_earned'    => ['nullable','integer'],
            'years_experience'  => ['nullable','integer','min:0'],
            'industry_sector'   => ['nullable','string','max:255'],
            'profile_photo'     => ['nullable','image','max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'profile_photo.image' => 'Le fichier sélectionné doit être une image.',
            'profile_photo.max' => 'La photo ne doit pas dépasser 4 Mo.',
        ];
    }
}
