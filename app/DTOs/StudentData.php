<?php

namespace App\DTOs;

/**
 * DTO pour les données d'étudiant
 * Garantit la structure et le typage des données d'étudiant
 */
class StudentData
{
    public int $id;
    public string $nom;
    public string $prenom;
    public string $email;
    public string $formation;
    public ?string $created_at;
    public ?string $photo;
    public int $progression;
    public string $status;
    
    public function __construct(array $data)
    {
        $this->id = (int) ($data['id'] ?? 0);
        $this->nom = (string) ($data['nom'] ?? '');
        $this->prenom = (string) ($data['prenom'] ?? '');
        $this->email = (string) ($data['email'] ?? '');
        $this->formation = (string) ($data['formation'] ?? '');
        $this->created_at = $data['created_at'] ?? null;
        $this->photo = $data['photo'] ?? null;
        $this->progression = (int) ($data['progression'] ?? 0);
        $this->status = (string) ($data['status'] ?? 'Actif');
    }
    
    /**
     * Convertir en tableau pour les vues
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'formation' => $this->formation,
            'created_at' => $this->created_at,
            'photo' => $this->photo,
            'progression' => $this->progression,
            'status' => $this->status
        ];
    }
    
    /**
     * Obtenir le nom complet
     * 
     * @return string
     */
    public function getFullName(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }
    
    /**
     * Valider les données d'étudiant
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->id > 0
            && !empty($this->nom)
            && !empty($this->prenom)
            && !empty($this->email)
            && filter_var($this->email, FILTER_VALIDATE_EMAIL)
            && !empty($this->formation)
            && $this->progression >= 0
            && $this->progression <= 100;
    }
}
