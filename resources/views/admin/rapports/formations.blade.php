@extends('layouts.admin')

@section('title', 'Rapport Formations')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .formations-header {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(86, 171, 47, 0.3);
    }

    .formations-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .formation-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .formation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border-color: #56ab2f;
    }

    .formation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #334155;
    }

    .formation-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #56ab2f;
    }

    .formation-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .stat-mini {
        text-align: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 10px;
    }

    .stat-mini-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-mini-label {
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .progress-custom {
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-bar-custom {
        height: 100%;
        background: linear-gradient(90deg, #56ab2f 0%, #a8e6cf 100%);
        transition: width 0.3s ease;
    }

    .chart-container {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .modules-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .module-item {
        background: rgba(255, 255, 255, 0.03);
        padding: 1rem;
        border-radius: 10px;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .module-item:hover {
        border-color: #56ab2f;
        background: rgba(86, 171, 47, 0.1);
    }

    .module-name {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .module-progress {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .export-btn {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 195, 247, 0.4);
        color: white;
    }

    .badge-level {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-debutant {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .badge-intermediaire {
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        color: white;
    }

    .badge-avance {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="formations-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="formations-title">
                    <i class="fas fa-graduation-cap me-3"></i>Rapport Formations
                </h1>
                <p class="mb-0">Performance et statistiques des formations</p>
            </div>
            <div>
                <button class="export-btn" onclick="exportFormationsReport()">
                    <i class="fas fa-download me-2"></i>Exporter PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Vue d'ensemble -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="formation-card">
                <div class="stat-mini-label">Total Formations</div>
                <div class="stat-mini-value text-success">{{ $overview['total_formations'] }}</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="formation-card">
                <div class="stat-mini-label">Étudiants Inscrits</div>
                <div class="stat-mini-value text-info">{{ $overview['total_students'] }}</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="formation-card">
                <div class="stat-mini-label">Taux de Réussite Moyen</div>
                <div class="stat-mini-value text-warning">{{ number_format($overview['avg_success_rate'], 1) }}%</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="formation-card">
                <div class="stat-mini-label">Modules Actifs</div>
                <div class="stat-mini-value text-primary">{{ $overview['total_modules'] }}</div>
            </div>
        </div>
    </div>

    <!-- Graphique de comparaison -->
    <div class="chart-container mb-4">
        <h5 class="mb-4">
            <i class="fas fa-chart-bar me-2"></i>Comparaison des Formations
        </h5>
        <canvas id="formationsChart" height="80"></canvas>
    </div>

    <!-- Détails par Formation -->
    @foreach($formations as $formation)
    <div class="formation-card">
        <div class="formation-header">
            <div>
                <h3 class="formation-name">{{ $formation->name }}</h3>
                <span class="badge-level badge-{{ strtolower($formation->level) }}">
                    {{ $formation->level }}
                </span>
            </div>
            <div class="text-end">
                <div class="text-muted">Durée: {{ $formation->duration }} mois</div>
                <div class="text-success fw-bold">{{ $formation->students_count }} étudiants</div>
            </div>
        </div>

        <div class="formation-stats">
            <div class="stat-mini">
                <div class="stat-mini-value text-success">{{ $formation->completed_tps }}</div>
                <div class="stat-mini-label">TP Complétés</div>
                <div class="progress-custom">
                    <div class="progress-bar-custom" style="width: {{ $formation->tp_completion_rate }}%"></div>
                </div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-value text-info">{{ $formation->pending_tps }}</div>
                <div class="stat-mini-label">TP En cours</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-value text-warning">{{ number_format($formation->success_rate, 1) }}%</div>
                <div class="stat-mini-label">Taux de Réussite</div>
                <div class="progress-custom">
                    <div class="progress-bar-custom" style="width: {{ $formation->success_rate }}%"></div>
                </div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-value text-primary">{{ $formation->avg_grade }}/20</div>
                <div class="stat-mini-label">Moyenne Générale</div>
            </div>
        </div>

        <!-- Modules -->
        @if(isset($formation->modules) && count($formation->modules) > 0)
        <div class="mt-4">
            <h6 class="text-white mb-3">
                <i class="fas fa-book me-2"></i>Modules ({{ count($formation->modules) }})
            </h6>
            <div class="modules-list">
                @foreach($formation->modules as $module)
                <div class="module-item">
                    <div class="module-name">{{ $module->name }}</div>
                    <div class="module-progress">
                        <span>{{ $module->completed_students }} / {{ $module->total_students }} étudiants</span>
                        <span class="text-success">{{ $module->completion_rate }}%</span>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar-custom" style="width: {{ $module->completion_rate }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Graphique de comparaison des formations
const formationsCtx = document.getElementById('formationsChart').getContext('2d');
new Chart(formationsCtx, {
    type: 'bar',
    data: {
        labels: @json($formationsNames),
        datasets: [{
            label: 'Nombre d\'étudiants',
            data: @json($formationsStudents),
            backgroundColor: 'rgba(86, 171, 47, 0.8)',
            borderColor: '#56ab2f',
            borderWidth: 2,
            borderRadius: 8
        }, {
            label: 'Taux de réussite (%)',
            data: @json($formationsSuccessRates),
            backgroundColor: 'rgba(79, 172, 254, 0.8)',
            borderColor: '#4facfe',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    color: '#94a3b8',
                    font: {
                        size: 12
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#94a3b8'
                },
                grid: {
                    color: '#334155'
                }
            },
            x: {
                ticks: {
                    color: '#94a3b8'
                },
                grid: {
                    color: '#334155'
                }
            }
        }
    }
});

function exportFormationsReport() {
    window.location.href = '{{ route("admin.rapports.download", "formations") }}';
}
</script>
@endpush
