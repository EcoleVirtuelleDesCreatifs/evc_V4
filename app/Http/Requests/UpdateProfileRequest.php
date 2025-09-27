<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Vérifier que l'utilisateur est connecté
        return session('logged_in', false) && session('user_id');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'firstName' => 'nullable|string|max:255|min:2',
            'lastName' => 'nullable|string|max:255|min:2',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'whatsapp' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'age' => 'nullable|integer|min:16|max:100',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'biography' => 'nullable|string|max:1000',
            'educationLevel' => 'nullable|string|max:255',
            'lastDiploma' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'firstName.required' => 'Le prénom est obligatoire.',
            'firstName.min' => 'Le prénom doit contenir au moins 2 caractères.',
            'firstName.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
            
            'lastName.required' => 'Le nom est obligatoire.',
            'lastName.min' => 'Le nom doit contenir au moins 2 caractères.',
            'lastName.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            
            'phone.regex' => 'Le numéro de téléphone n\'est pas valide.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            
            'whatsapp.regex' => 'Le numéro WhatsApp n\'est pas valide.',
            'whatsapp.max' => 'Le numéro WhatsApp ne peut pas dépasser 20 caractères.',
            
            'age.integer' => 'L\'âge doit être un nombre entier.',
            'age.min' => 'L\'âge minimum est de 16 ans.',
            'age.max' => 'L\'âge maximum est de 100 ans.',
            
            'country.required' => 'Le pays est obligatoire.',
            'country.max' => 'Le pays ne peut pas dépasser 255 caractères.',
            
            'city.required' => 'La ville est obligatoire.',
            'city.max' => 'La ville ne peut pas dépasser 255 caractères.',
            
            'district.max' => 'Le quartier ne peut pas dépasser 255 caractères.',
            'address.max' => 'L\'adresse ne peut pas dépasser 1000 caractères.',
            'biography.max' => 'La biographie ne peut pas dépasser 1000 caractères.',
            'educationLevel.max' => 'Le niveau d\'éducation ne peut pas dépasser 255 caractères.',
            'lastDiploma.max' => 'Le dernier diplôme ne peut pas dépasser 255 caractères.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'firstName' => 'prénom',
            'lastName' => 'nom',
            'email' => 'adresse email',
            'phone' => 'numéro de téléphone',
            'whatsapp' => 'numéro WhatsApp',
            'age' => 'âge',
            'country' => 'pays',
            'city' => 'ville',
            'district' => 'quartier',
            'address' => 'adresse',
            'biography' => 'biographie',
            'educationLevel' => 'niveau d\'éducation',
            'lastDiploma' => 'dernier diplôme',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation des données.',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour effectuer cette action.'
                ], 401)
            );
        }

        parent::failedAuthorization();
    }
}
