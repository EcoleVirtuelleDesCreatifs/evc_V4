@extends('layouts.admin')

@section('title', $data['title'] . ' - Détails')

@section('styles')
<link href="{{ asset('css/holographic-stats.css') }}" rel="stylesheet">
<style>
/* Styles spécifiques pour la page de détails */
.stats-detail-container {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.detail-header {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.detail-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(255, 107, 53, 0.1));
    opacity: 0.5;
    z-index: -1;
}

.detail-icon {
    width: 80px;
    height: 80px;
    background: rgba(0, 212, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: rgba(0, 212, 255, 0.9);
    margin-bottom: 1rem;
}

.detail-main-value {
    font-size: 4rem;
    font-weight: 700;
    background: linear-gradient(135deg, #00d4ff, #ff6b35);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
}

.detail-title {
    font-size: 2rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1rem;
}

.detail-description {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
    line-height: 1.6;
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.kpi-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 212, 255, 0.3);
    border-color: rgba(0, 212, 255, 0.3);
}

.kpi-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: rgba(0, 212, 255, 0.9);
    margin-bottom: 0.5rem;
}

.kpi-label {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    font-weight: 500;
}

.chart-container {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
}

.chart-title {
    color: white;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: center;
}

.chart-canvas {
    width: 100%;
    height: 400px;
}

.insights-container {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.insights-title {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.insight-item {
    background: rgba(0, 212, 255, 0.1);
    border-left: 4px solid rgba(0, 212, 255, 0.8);
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
    border-radius: 0 10px 10px 0;
    color: rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
}

.insight-item:hover {
    background: rgba(0, 212, 255, 0.2);
    transform: translateX(5px);
}

.data-table {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    overflow-x: auto;
}

.table {
    color: white;
    margin-bottom: 0;
}

.table th {
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(0, 212, 255, 0.9);
    font-weight: 600;
    background: rgba(0, 212, 255, 0.1);
}

.table td {
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
}

.table tbody tr:hover {
    background: rgba(255, 255, 255, 0.05);
}

.back-button {
    background: rgba(0, 212, 255, 0.2);
    border: 1px solid rgba(0, 212, 255, 0.3);
    color: rgba(0, 212, 255, 0.9);
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    margin-bottom: 2rem;
}

.back-button:hover {
    background: rgba(0, 212, 255, 0.3);
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

.export-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    justify-content: flex-end;
}

.export-btn {
    background: rgba(255, 107, 53, 0.2);
    border: 1px solid rgba(255, 107, 53, 0.3);
    color: rgba(255, 107, 53, 0.9);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.export-btn:hover {
    background: rgba(255, 107, 53, 0.3);
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .detail-main-value {
        font-size: 3rem;
    }
    
    .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .chart-canvas {
        height: 300px;
    }
}
</style>
@endsection

@section('content')
<div class="stats-detail-container">
    <div class="container-fluid">
        <!-- Bouton retour -->
        <a href="{{ route('admin.dashboard') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Retour au Dashboard
        </a>

        <!-- En-tête de la page -->
        <div class="detail-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="detail-icon">
                        <i class="{{ $data['icon'] }}"></i>
                    </div>
                    <div class="detail-main-value">
                        {{ number_format($data['mainValue']) }}{{ $data['unit'] }}
                    </div>
                    <h1 class="detail-title">{{ $data['title'] }}</h1>
                    <p class="detail-description">{{ $data['description'] }}</p>
                </div>
                <div class="col-md-4">
                    <div class="export-buttons">
                        <a href="#" class="export-btn" onclick="exportToPDF()">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="#" class="export-btn" onclick="exportToExcel()">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPIs principaux -->
        @if(isset($data['kpis']))
        <div class="kpi-grid">
            @foreach($data['kpis'] as $label => $value)
            <div class="kpi-card">
                <div class="kpi-value">{{ $value }}</div>
                <div class="kpi-label">{{ $label }}</div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="row">
            <!-- Graphiques -->
            @if(isset($data['charts']))
            <div class="col-lg-8">
                @if(isset($data['charts']['monthly_evolution']))
                <div class="chart-container">
                    <h3 class="chart-title">Évolution Mensuelle</h3>
                    <canvas id="monthlyChart" class="chart-canvas"></canvas>
                </div>
                @endif

                @if(isset($data['charts']['formation_repartition']))
                <div class="chart-container">
                    <h3 class="chart-title">Répartition par Formation</h3>
                    <canvas id="formationChart" class="chart-canvas"></canvas>
                </div>
                @endif
            </div>
            @endif

            <!-- Insights -->
            @if(isset($data['insights']))
            <div class="col-lg-4">
                <div class="insights-container">
                    <h3 class="insights-title">
                        <i class="fas fa-lightbulb"></i>
                        Insights Clés
                    </h3>
                    @foreach($data['insights'] as $insight)
                    <div class="insight-item">
                        {{ $insight }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Tableau de données détaillées -->
        @if(isset($data['formations']))
        <div class="data-table">
            <h3 class="chart-title">Détails par Formation</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Formation</th>
                        <th>Étudiants</th>
                        <th>Taux de Réussite</th>
                        <th>Score Moyen</th>
                        <th>Durée</th>
                        <th>Modules</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['formations'] as $formation)
                    <tr>
                        <td><strong>{{ $formation['name'] }}</strong></td>
                        <td>{{ $formation['students'] }}</td>
                        <td>{{ $formation['completion_rate'] }}%</td>
                        <td>{{ $formation['average_score'] }}/100</td>
                        <td>{{ $formation['duration'] }}</td>
                        <td>{{ $formation['modules'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Top étudiants -->
        @if(isset($data['top_students']))
        <div class="data-table">
            <h3 class="chart-title">Top 5 Étudiants Actifs</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Nom</th>
                        <th>Formation</th>
                        <th>TP Réalisés</th>
                        <th>Score Moyen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['top_students'] as $index => $student)
                    <tr>
                        <td>
                            <span class="badge badge-{{ $index < 3 ? 'warning' : 'info' }}">
                                #{{ $index + 1 }}
                            </span>
                        </td>
                        <td><strong>{{ $student['name'] }}</strong></td>
                        <td>{{ $student['formation'] }}</td>
                        <td>{{ $student['tp_count'] }}</td>
                        <td>{{ $student['score'] }}/100</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Statistiques d'engagement -->
        @if(isset($data['engagement']))
        <div class="kpi-grid">
            @foreach($data['engagement'] as $label => $value)
            <div class="kpi-card">
                <div class="kpi-value">{{ $value }}</div>
                <div class="kpi-label">{{ $label }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution mensuelle
    @if(isset($data['charts']['monthly_evolution']))
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($data['charts']['monthly_evolution'], 'month')) !!},
                datasets: [{
                    label: 'Évolution',
                    data: {!! json_encode(array_column($data['charts']['monthly_evolution'], 'value')) !!},
                    borderColor: 'rgba(0, 212, 255, 0.8)',
                    backgroundColor: 'rgba(0, 212, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: 'white'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    }
    @endif

    // Graphique de répartition par formation
    @if(isset($data['charts']['formation_repartition']))
    const formationCtx = document.getElementById('formationChart');
    if (formationCtx) {
        new Chart(formationCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($data['charts']['formation_repartition'])) !!},
                datasets: [{
                    data: {!! json_encode(array_values($data['charts']['formation_repartition'])) !!},
                    backgroundColor: [
                        'rgba(0, 212, 255, 0.8)',
                        'rgba(179, 71, 255, 0.8)',
                        'rgba(0, 255, 136, 0.8)',
                        'rgba(255, 179, 71, 0.8)'
                    ],
                    borderColor: [
                        'rgba(0, 212, 255, 1)',
                        'rgba(179, 71, 255, 1)',
                        'rgba(0, 255, 136, 1)',
                        'rgba(255, 179, 71, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'white',
                            padding: 20
                        }
                    }
                }
            }
        });
    }
    @endif
});

// Fonctions d'export
function exportToPDF() {
    console.log('Export PDF en cours...');
    // Ici vous pouvez ajouter la logique d'export PDF
    alert('Fonctionnalité d\'export PDF en cours de développement');
}

function exportToExcel() {
    console.log('Export Excel en cours...');
    // Ici vous pouvez ajouter la logique d'export Excel
    alert('Fonctionnalité d\'export Excel en cours de développement');
}
</script>
@endsection
