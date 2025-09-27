<?php

namespace App\DTOs;

/**
 * DTO pour les données de statistiques
 * Garantit la structure et le typage des données
 */
class StatisticsData
{
    public array $main_kpi;
    public array $growth;
    public array $formations;
    public array $students;
    public array $totals;
    public int $total_students;
    public int $active_students;
    public int $new_this_month;
    
    public function __construct(array $data)
    {
        $this->main_kpi = $data['main_kpi'] ?? ['total_students' => 0];
        $this->growth = $data['growth'] ?? ['percentage' => 0.0];
        $this->formations = $data['formations'] ?? [];
        $this->students = $data['students'] ?? [];
        $this->totals = $data['totals'] ?? [];
        $this->total_students = (int) ($data['total_students'] ?? 0);
        $this->active_students = (int) ($data['active_students'] ?? 0);
        $this->new_this_month = (int) ($data['new_this_month'] ?? 0);
    }
    
    /**
     * Convertir en tableau pour les vues
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'main_kpi' => $this->main_kpi,
            'growth' => $this->growth,
            'formations' => array_map(function ($formation) {
                return $formation instanceof FormationData ? $formation->toArray() : $formation;
            }, $this->formations),
            'students' => array_map(function ($student) {
                return $student instanceof StudentData ? $student->toArray() : $student;
            }, $this->students),
            'totals' => $this->totals,
            'total_students' => $this->total_students,
            'active_students' => $this->active_students,
            'new_this_month' => $this->new_this_month
        ];
    }
    
    /**
     * Valider la cohérence des données
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->total_students >= 0 
            && $this->active_students >= 0 
            && $this->new_this_month >= 0
            && is_array($this->main_kpi)
            && is_array($this->growth)
            && is_array($this->formations)
            && is_array($this->students);
    }
}
