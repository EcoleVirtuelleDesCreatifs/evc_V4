@extends('layouts.ki-admin')

@section('title', 'Diagnostic TP - EVC')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-bug me-2"></i>
                        Diagnostic TP et Statistiques - 100% Laravel
                    </h4>
                </div>
                <div class="card-body">
                    
                    @if(isset($diagnostic['error']))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Erreur:</strong> {{ $diagnostic['error'] }}
                        </div>
                    @endif

                    {{-- 1. Informations Session --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">
                                <i class="fas fa-user-circle me-2"></i>
                                1. Informations Session Laravel
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Clé Session</th>
                                            <th>Valeur</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($diagnostic['session'] as $key => $value)
                                            <tr>
                                                <td><code>{{ $key }}</code></td>
                                                <td>
                                                    @if($value)
                                                        <span class="text-success">{{ $value }}</span>
                                                    @else
                                                        <span class="text-muted">NON DÉFINI</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($key === 'user_id' && $value)
                                                        <span class="badge bg-success">✅ OK</span>
                                                    @elseif($key === 'logged_in' && $value)
                                                        <span class="badge bg-success">✅ CONNECTÉ</span>
                                                    @elseif($key === 'user_id' && !$value)
                                                        <span class="badge bg-danger">❌ MANQUANT</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Structure Table TP --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">
                                <i class="fas fa-database me-2"></i>
                                2. Structure Table TP
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Colonne</th>
                                            <th>Type</th>
                                            <th>Null</th>
                                            <th>Défaut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($diagnostic['table_structure'] as $column)
                                            <tr>
                                                <td>
                                                    <code>{{ $column->Field }}</code>
                                                    @if($column->Field === 'status')
                                                        <span class="badge bg-success ms-1">✅</span>
                                                    @endif
                                                </td>
                                                <td>{{ $column->Type }}</td>
                                                <td>{{ $column->Null }}</td>
                                                <td>{{ $column->Default ?? 'NULL' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- 3. TP de l'Utilisateur --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">
                                <i class="fas fa-file-alt me-2"></i>
                                3. TP de l'Utilisateur (ID: {{ $diagnostic['session']['user_id'] }})
                            </h5>
                            
                            @if($diagnostic['tps']['total'] == 0)
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Aucun TP trouvé</strong> pour cet utilisateur.
                                    <br>
                                    <a href="{{ route('design-graphique.tp.ajouter') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i>
                                        Créer un TP
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <strong>Total TP trouvés:</strong> {{ $diagnostic['tps']['total'] }}
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Titre</th>
                                                <th>Statut</th>
                                                <th>Créé le</th>
                                                <th>Modifié le</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($diagnostic['tps']['list'] as $tp)
                                                <tr>
                                                    <td>{{ $tp->id }}</td>
                                                    <td>{{ $tp->title }}</td>
                                                    <td>
                                                        @switch($tp->status)
                                                            @case('pending')
                                                                <span class="badge bg-warning">🕐 PENDING</span>
                                                                @break
                                                            @case('validate')
                                                                <span class="badge bg-success">✅ VALIDATE</span>
                                                                @break
                                                            @case('rejected')
                                                                <span class="badge bg-danger">❌ REJECTED</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ $tp->status }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $tp->created_at }}</td>
                                                    <td>{{ $tp->updated_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 4. Statistiques par Statut --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">
                                <i class="fas fa-chart-pie me-2"></i>
                                4. Répartition par Statut
                            </h5>
                            
                            @if(empty($diagnostic['status_stats']))
                                <div class="alert alert-warning">Aucune statistique de statut disponible</div>
                            @else
                                <div class="row">
                                    @foreach($diagnostic['status_stats'] as $status => $count)
                                        <div class="col-md-4 mb-3">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body text-center">
                                                    @switch($status)
                                                        @case('pending')
                                                            <div class="text-warning fs-1">🕐</div>
                                                            <h4 class="text-warning">{{ $count }}</h4>
                                                            <p class="text-muted mb-0">En Attente</p>
                                                            @break
                                                        @case('validate')
                                                            <div class="text-success fs-1">✅</div>
                                                            <h4 class="text-success">{{ $count }}</h4>
                                                            <p class="text-muted mb-0">Validés</p>
                                                            @break
                                                        @case('rejected')
                                                            <div class="text-danger fs-1">❌</div>
                                                            <h4 class="text-danger">{{ $count }}</h4>
                                                            <p class="text-muted mb-0">Rejetés</p>
                                                            @break
                                                        @default
                                                            <div class="text-secondary fs-1">❓</div>
                                                            <h4 class="text-secondary">{{ $count }}</h4>
                                                            <p class="text-muted mb-0">{{ $status }}</p>
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 5. Test Requêtes Contrôleur --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">
                                <i class="fas fa-search me-2"></i>
                                5. Test Requêtes Contrôleur
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-warning text-white">
                                            <h6 class="mb-0">TP Status = 'pending'</h6>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Nombre trouvé:</strong> {{ $diagnostic['controller_queries']['pending_count'] }}</p>
                                            @if($diagnostic['controller_queries']['pending_count'] > 0)
                                                <ul class="list-unstyled">
                                                    @foreach($diagnostic['controller_queries']['pending_list'] as $tp)
                                                        <li>
                                                            <i class="fas fa-file-alt me-1"></i>
                                                            {{ $tp->title }} (ID: {{ $tp->id }})
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0">TP Status = 'validate'</h6>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Nombre trouvé:</strong> {{ $diagnostic['controller_queries']['validate_count'] }}</p>
                                            @if($diagnostic['controller_queries']['validate_count'] > 0)
                                                <ul class="list-unstyled">
                                                    @foreach($diagnostic['controller_queries']['validate_list'] as $tp)
                                                        <li>
                                                            <i class="fas fa-file-alt me-1"></i>
                                                            {{ $tp->title }} (ID: {{ $tp->id }})
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 6. Statistiques Contrôleur --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">
                                <i class="fas fa-calculator me-2"></i>
                                6. Statistiques Calculées par le Contrôleur
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Statistiques de Validation</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                @foreach($diagnostic['validation_stats'] as $key => $value)
                                                    <tr>
                                                        <td><code>{{ $key }}</code></td>
                                                        <td>
                                                            @if(is_array($value))
                                                                <span class="badge bg-info">{{ count($value) }} éléments</span>
                                                            @else
                                                                <strong>{{ $value }}</strong>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Statistiques TP Générales</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                @foreach($diagnostic['tp_stats'] as $key => $value)
                                                    <tr>
                                                        <td><code>{{ $key }}</code></td>
                                                        <td><strong>{{ $value }}</strong></td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 7. Actions Recommandées --}}
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary">
                                <i class="fas fa-tools me-2"></i>
                                7. Actions Recommandées
                            </h5>
                            
                            <div class="alert alert-info">
                                @if(!$diagnostic['session']['user_id'])
                                    <p><strong>🔑 Session:</strong> Connectez-vous via l'interface Laravel</p>
                                @endif
                                
                                @if($diagnostic['tps']['total'] == 0)
                                    <p><strong>📝 TP:</strong> Créez des TP via l'interface d'ajout</p>
                                @endif
                                
                                @if($diagnostic['validation_stats']['tp_en_validation'] == 0 && $diagnostic['tps']['total'] > 0)
                                    <p><strong>⚠️ Statuts:</strong> Vos TP n'ont pas le statut 'pending'. Vérifiez la création.</p>
                                @endif
                                
                                @if($diagnostic['validation_stats']['tp_en_validation'] > 0)
                                    <p><strong>✅ Succès:</strong> Les statistiques devraient s'afficher sur le dashboard!</p>
                                @endif
                            </div>
                            
                            <div class="text-center">
                                <a href="{{ route('dashboard.design-graphique') }}" class="btn btn-primary">
                                    <i class="fas fa-tachometer-alt me-1"></i>
                                    Retour au Dashboard
                                </a>
                                <a href="{{ route('design-graphique.tp.ajouter') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>
                                    Créer un TP
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
