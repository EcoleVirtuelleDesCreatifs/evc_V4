<div class="row g-3">
  <div class="col-12 col-xl-8">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between">
        <h6 class="mb-0 text-uppercase">Résumé</h6>
        <div class="d-none d-md-flex align-items-center gap-2">
          @if(!empty($level))
            <span class="badge rounded-pill bg-primary-subtle text-primary"><i class="fas fa-graduation-cap me-1"></i>{{ $level }}</span>
          @endif
          @if(!empty($domain))
            <span class="badge rounded-pill bg-warning-subtle text-warning"><i class="fas fa-shapes me-1"></i>{{ $domain }}</span>
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Nom complet</div>
            <div class="fw-semibold">{{ $fullName ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Programme</div>
            <div class="fw-semibold">{{ $program ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Email</div>
            <div class="fw-semibold">{{ $email ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Téléphone</div>
            <div class="fw-semibold">{{ $phone ?: '—' }}</div>
          </div>
        </div>
        <hr>
        <div class="d-flex flex-wrap gap-3">
          <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-primary">
            <i class="fas fa-user-edit me-1"></i> Modifier mon profil
          </a>
          <a href="{{ route('design-graphique.documents.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-folder-open me-1"></i> Mes documents
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-xl-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-transparent border-0">
        <h6 class="mb-0 text-uppercase">Indicateurs</h6>
      </div>
      <div class="card-body">
        <div class="d-flex flex-column gap-3">
          <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center"><i class="fas fa-layer-group text-primary"></i></div>
            <div class="small">
              <div class="text-muted">Crédits</div>
              <div class="fw-semibold">{{ !is_null($credits) ? number_format($credits) : '—' }}</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center"><i class="fas fa-chart-line text-success"></i></div>
            <div class="small">
              <div class="text-muted">GPA</div>
              <div class="fw-semibold">{{ !is_null($gpa) ? number_format((float)$gpa, 2) : '—' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
