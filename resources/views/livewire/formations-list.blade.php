<div>
    {{-- Stop trying to control. --}}
    @push('styles')
    <style>
        /* Styles de l'interface révolutionnaire (condensés) */
        .revolutionary-container { padding: 25px; }
        .revolutionary-header { background: rgba(255,255,255,0.08); backdrop-filter: blur(18px); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 28px; padding: 28px; margin: 0 auto 28px auto; max-width: 1100px; }
        .header-content { display: flex; justify-content: space-between; align-items: center; }
        .header-main { display: flex; align-items: center; gap: 20px; }
        .header-icon { font-size: 2rem; width: 60px; height: 60px; border-radius: 14px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; display: flex; align-items: center; justify-content: center; }
        .header-title { color: #fff; font-size: 2rem; margin: 0; }
        .header-subtitle { color: rgba(255,255,255,0.8); margin: 0; }
        .header-actions { display: flex; gap: 15px; }
        .rev-btn { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .rev-btn.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .rev-btn.secondary { background: rgba(255,255,255,0.1); }
        .rev-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .dynamic-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 25px; }
        .stat-card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 15px; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.1); }
        .stat-icon { font-size: 1.5rem; color: #a7b3c4; }
        .stat-value { font-size: 1.75rem; font-weight: 700; color: #fff; }
        .stat-label { font-size: 0.9rem; color: #a7b3c4; }
        .smart-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; max-width: 1100px; margin: 0 auto 25px auto; }
        .search-input-wrapper { position: relative; }
        .smart-search { background: rgba(31,41,55,0.85); border: 1px solid rgba(255, 255, 255, 0.25); color: #fff; border-radius: 10px; padding: 10px 15px 10px 40px; width: 300px; }
        .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a7b3c4; }
        .filter-group { display: flex; align-items: center; background: rgba(255,255,255,0.08); border-radius: 12px; padding: 5px; gap: 5px; }
        .filter-btn { background: transparent; border: none; color: #a7b3c4; padding: 8px 15px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; }
        .filter-btn.active { background: #2563eb; color: #fff; }
        .formations-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; max-width: 1100px; margin: 0 auto; }
        .formation-card { background: rgba(255,255,255,0.08); border-radius: 16px; overflow: hidden; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.2); }
        .formation-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .card-header { padding: 15px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.2); }
        .formation-name { font-size: 1.2rem; color: #fff; margin: 0; }
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
        .status-badge.published { background-color: #10b981; color: #fff; }
        .status-badge.draft { background-color: #f59e0b; color: #fff; }
        .card-body { padding: 15px; }
        .card-actions { padding: 15px; text-align: right; }
    </style>
    @endpush

    <div class="revolutionary-container">
        <!-- Header Dynamique -->
        <div class="revolutionary-header">
            <div class="header-content">
                <div class="header-main">
                    <div class="header-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div>
                        <h1 class="header-title">Gestion des Formations</h1>
                        <p class="header-subtitle">Interface dynamique avec Livewire</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.formations.create') }}" class="rev-btn primary">
                        <i class="fas fa-plus"></i>
                        <span>Nouvelle Formation</span>
                    </a>
                </div>
            </div>
            
            <!-- Stats Dynamiques -->
            <div class="dynamic-stats">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-play-circle"></i></div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['active_formations'] }}</div>
                        <div class="stat-label">Formations Actives</div>
                    </div>
                </div>
                 <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['total_students'] }}</div>
                        <div class="stat-label">Étudiants (Total)</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['avg_satisfaction'] }}%</div>
                        <div class="stat-label">Satisfaction</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-trending-up"></i></div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['growth_rate'] }}%</div>
                        <div class="stat-label">Croissance</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contrôles Intelligents -->
        <div class="smart-controls">
            <div class="search-zone">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="smart-search" placeholder="Rechercher..." wire:model.live.debounce.300ms="search">
                </div>
            </div>
            
            <div class="filter-group">
                <button class="filter-btn {{ $filterStatus === 'all' ? 'active' : '' }}" wire:click="$set('filterStatus', 'all')">Toutes</button>
                <button class="filter-btn {{ $filterStatus === 'published' ? 'active' : '' }}" wire:click="$set('filterStatus', 'published')">Actives</button>
                <button class="filter-btn {{ $filterStatus === 'draft' ? 'active' : '' }}" wire:click="$set('filterStatus', 'draft')">Brouillons</button>
            </div>
        </div>

        <!-- Zone de Contenu Adaptative -->
        <div class="formations-grid">
            @forelse($formations as $formation)
                <div class="formation-card">
                    <div class="card-header">
                        <h3 class="formation-name">{{ $formation->name }}</h3>
                        <span class="status-badge {{ $formation->status }}">{{ ucfirst($formation->status) }}</span>
                    </div>
                    <div class="card-body">
                        <p>{{ Str::limit($formation->short_description, 100) }}</p>
                        <p><strong>Catégorie:</strong> {{ $formation->category->name ?? 'N/A' }}</p>
                    </div>
                    <div class="card-actions">
                        <a href="#" class="rev-btn secondary">Voir</a>
                        <a href="#" class="rev-btn">Éditer</a>
                    </div>
                </div>
            @empty
                <p>Aucune formation trouvée.</p>
            @endforelse
        </div>
        
        <div style="margin-top: 25px; max-width: 1100px; margin: 25px auto 0 auto;">
            {{ $formations->links() }}
        </div>
    </div>
</div>
