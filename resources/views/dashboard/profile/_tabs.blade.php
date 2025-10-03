<div class="card border-0 shadow-sm mt-3">
  <div class="card-header bg-transparent border-0 pb-0">
    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
          <i class="fas fa-home me-1"></i> Aperçu
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#informations" type="button" role="tab" aria-controls="informations" aria-selected="false">
          <i class="fas fa-id-card me-1"></i> Informations
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="docs-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
          <i class="fas fa-folder-open me-1"></i> Documents
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tp-tab" data-bs-toggle="tab" data-bs-target="#tp" type="button" role="tab" aria-controls="tp" aria-selected="false">
          <i class="fas fa-tasks me-1"></i> TP
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab" aria-controls="stats" aria-selected="false">
          <i class="fas fa-chart-pie me-1"></i> Statistiques
        </button>
      </li>
    </ul>
  </div>
  <div class="card-body">
    <div class="tab-content" id="profileTabsContent">
      <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
        @include('dashboard.profile._tab-overview')
      </div>
      <div class="tab-pane fade" id="informations" role="tabpanel" aria-labelledby="info-tab">
        @include('dashboard.profile._tab-informations')
      </div>
      <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="docs-tab">
        <div class="text-muted">Accédez à <a href="{{ route('design-graphique.documents.index') }}">vos documents</a>.</div>
      </div>
      <div class="tab-pane fade" id="tp" role="tabpanel" aria-labelledby="tp-tab">
        <div class="text-muted">Section TP — intégration à venir.</div>
      </div>
      <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
        <div class="text-muted">Statistiques — à activer selon les données.</div>
      </div>
    </div>
  </div>
</div>
