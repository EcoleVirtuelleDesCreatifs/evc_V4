<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Vérifier que l'admin est connecté
        return session('admin_logged_in', false);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'telephone' => 'required|string|max:20',
            'formation_souhaitee' => 'required|array|min:1',
            'formation_souhaitee.*' => 'required|string|in:design_graphique,community_management,intelligence_artificielle,gestion_informatique',
            'send_welcome_email' => 'sometimes|boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
            'nom.required' => 'Le nom est obligatoire.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'formation_souhaitee.required' => 'Au moins une formation doit être sélectionnée.',
            'formation_souhaitee.array' => 'Les formations doivent être un tableau.',
            'formation_souhaitee.min' => 'Au moins une formation doit être sélectionnée.',
            'formation_souhaitee.*.required' => 'Chaque formation sélectionnée est obligatoire.',
            'formation_souhaitee.*.in' => 'La formation sélectionnée n\'est pas valide.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'prenom' => 'prénom',
            'nom' => 'nom',
            'email' => 'adresse email',
            'telephone' => 'numéro de téléphone',
            'formation_souhaitee' => 'formations souhaitées'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // S'assurer que formation_souhaitee est un tableau
        if ($this->has('formation_souhaitee') && !is_array($this->formation_souhaitee)) {
            $this->merge([
                'formation_souhaitee' => [$this->formation_souhaitee]
            ]);
        }

        // Nettoyer les données
        $this->merge([
            'prenom' => trim($this->prenom ?? ''),
            'nom' => trim($this->nom ?? ''),
            'email' => strtolower(trim($this->email ?? '')),
            'telephone' => trim($this->telephone ?? ''),
            'send_welcome_email' => $this->boolean('send_welcome_email', true)
        ]);
    }

    /**
     * Get validated data with proper formatting (schéma existant)
     */
    public function getValidatedData(): array
    {
        $validated = $this->validated();
        
        return [
            'first_name' => $validated['prenom'],
            'last_name' => $validated['nom'],
            'email' => $validated['email'],
            'phone' => $validated['telephone'],
            'formation_souhaitee' => $validated['formation_souhaitee'][0], // Première formation comme principale
            'password' => bcrypt('password123'), // Mot de passe temporaire
            'status' => 'Actif',
            'country' => 'Côte d\'Ivoire', // Valeur par défaut
            'date_inscription' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * Get send welcome email preference
     */
    public function shouldSendWelcomeEmail(): bool
    {
        return $this->boolean('send_welcome_email', true);
    }

    /**
     * Get formations as array for email
     */
    public function getFormationsArray(): array
    {
        $validated = $this->validated();
        return $validated['formation_souhaitee'] ?? [];
    }
}
