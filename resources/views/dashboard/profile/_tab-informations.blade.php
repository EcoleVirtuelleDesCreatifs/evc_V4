<div class="row g-3">
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-transparent border-0">
        <h6 class="mb-0 text-uppercase">Identité</h6>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Nom complet</div>
            <div class="fw-semibold">{{ $fullName ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Sexe</div>
            <div class="fw-semibold">{{ $gender ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Date de naissance</div>
            <div class="fw-semibold">{{ $dob ? optional($dob)->format('d/m/Y') : '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Âge</div>
            <div class="fw-semibold">{{ $age ? $age.' ans' : '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Matricule</div>
            <div class="fw-semibold">{{ $studentId ?: '—' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-transparent border-0">
        <h6 class="mb-0 text-uppercase">Contact</h6>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Email</div>
            <div class="d-flex align-items-center gap-2">
              <i class="fas fa-envelope text-muted"></i>
              <div class="fw-semibold">{{ $email ?: '—' }}</div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Téléphone</div>
            <div class="d-flex align-items-center gap-2">
              <i class="fas fa-phone text-muted"></i>
              <div class="fw-semibold">{{ $phone ?: '—' }}</div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">WhatsApp</div>
            <div class="d-flex align-items-center gap-2">
              <i class="fab fa-whatsapp text-success"></i>
              <div class="fw-semibold">{{ $whatsapp ?: '—' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-transparent border-0">
        <h6 class="mb-0 text-uppercase">Localisation</h6>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Quartier</div>
            <div class="fw-semibold">{{ $quartier ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Ville</div>
            <div class="fw-semibold">{{ $city ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Pays</div>
            <div class="fw-semibold">{{ $country ?: '—' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-transparent border-0">
        <h6 class="mb-0 text-uppercase">Formation & Statut</h6>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Programme</div>
            <div class="fw-semibold">{{ $program ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Spécialité</div>
            <div class="fw-semibold">{{ $domain ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Niveau</div>
            <div class="fw-semibold">{{ $level ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Statut</div>
            <div class="fw-semibold">{{ $status ?: '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Expérience</div>
            <div class="fw-semibold">{{ $experience !== null ? ($experience.' an'.($experience>1?'s':'')) : '—' }}</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="small text-muted">Secteur</div>
            <div class="fw-semibold">{{ $sector ?: '—' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
