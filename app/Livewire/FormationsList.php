<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Formation;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class FormationsList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $viewMode = 'grid'; // 'grid' or 'list'

    protected $queryString = ['search', 'filterStatus', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function render()
    {
        $formations = Formation::with('category')
            ->where('name', 'like', '%'.$this->search.'%')
            ->when($this->filterStatus !== 'all', function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(12);

        $stats = [
            'active_formations' => Formation::where('status', 'published')->count(),
            'total_students' => 0, // À implémenter
            'avg_satisfaction' => 95.5, // À implémenter
            'growth_rate' => 12, // À implémenter
        ];

        return view('livewire.formations-list', [
            'formations' => $formations,
            'stats' => $stats,
            'categories' => Category::all()
        ]);
    }
}
