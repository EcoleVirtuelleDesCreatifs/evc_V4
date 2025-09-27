<?php

namespace App\DTOs;

/**
 * DTO pour les données de formation
 * Garantit la structure et le typage des données de formation
 */
class FormationData
{
    public string $name;
    public int $count;
    public string $slug;
    public string $icon;
    public string $gradient;
    public float $percentage;
    
    public function __construct(array $data)
    {
        $this->name = (string) ($data['name'] ?? '');
        $this->count = (int) ($data['count'] ?? 0);
        $this->slug = (string) ($data['slug'] ?? '');
        $this->icon = (string) ($data['icon'] ?? 'fas fa-graduation-cap');
        $this->gradient = (string) ($data['gradient'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)');
        $this->percentage = (float) ($data['percentage'] ?? 0.0);
    }
    
    /**
     * Convertir en tableau pour les vues
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'count' => $this->count,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'gradient' => $this->gradient,
            'percentage' => $this->percentage
        ];
    }
    
    /**
     * Valider les données de formation
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->name) 
            && !empty($this->slug)
            && !empty($this->icon)
            && !empty($this->gradient)
            && $this->count >= 0
            && $this->percentage >= 0.0;
    }
}
