@php
    // Définir toutes les variables localement pour autonomie complète
    $sf = optional($student ?? null);
    $pr = optional($preReg ?? null);
    $userObj = isset($user) ? $user : (auth()->check() ? auth()->user() : null);

    // Photo
    $studentPhoto = $sf->profile_photo;
    $prePhoto = $pr->profile_photo ?? $pr->photo ?? $pr->image ?? $pr->image_url ?? $pr->avatar;
    $rawPhoto = $studentPhoto ?: $prePhoto;
    $photoUrl = $rawPhoto ? (preg_match('/^https?:\/\//', $rawPhoto) ? $rawPhoto : asset($rawPhoto)) : asset('assets/img/avatar.png');

    // Nom
    $fullName = trim(($sf->first_name ?? '') . ' ' . ($sf->last_name ?? ''));
    if ($fullName === '') {
        $fullName = ($userObj->name ?? '') ?: trim(($pr->first_name ?? '') . ' ' . ($pr->last_name ?? ''));
    }

    // Contact
    $email = ($sf->email ?? '') ?: (($userObj->email ?? '') ?: ($pr->email ?? ''));
    $phone = ($sf->phone ?? '') ?: ($pr->phone ?? '');
    $whatsapp = ($sf->whatsapp ?? '') ?: ($pr->whatsapp ?? '');

    // Formation
    $level = ($sf->level ?? '') ?: ($pr->level ?? '');
    $domain = ($sf->specialization ?? '') ?: ($pr->specialization ?? '');

    // KPIs
    $gpa = $sf->gpa ?? null;
    $credits = $sf->credits_earned ?? null;
@endphp

<div class="row align-items-center g-3">
  <div class="col-md-8 d-flex align-items-center gap-3">
    <img class="rounded-circle shadow-sm" src="{{ $photoUrl }}" alt="Avatar" style="height:96px;width:96px;object-fit:cover;border:3px solid #FF6B35;">
    <div>
      <div class="d-flex flex-wrap align-items-center gap-2">
        <h2 class="h4 fw-bold mb-0">{{ $fullName ?: '—' }}</h2>
        @if(!empty($level))
          <span class="badge rounded-pill bg-primary-subtle text-primary"><i class="fas fa-graduation-cap me-1"></i>{{ $level }}</span>
        @endif
        @if(!empty($domain))
          <span class="badge rounded-pill bg-warning-subtle text-warning"><i class="fas fa-shapes me-1"></i>{{ $domain }}</span>
        @endif
      </div>
      <div class="mt-1">
        @if($email)
          <a href="mailto:{{ $email }}" class="text-muted text-decoration-none me-3"><i class="fas fa-envelope me-1"></i>{{ $email }}</a>
        @endif
        @if($phone)
          <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="text-muted text-decoration-none me-3"><i class="fas fa-phone me-1"></i>{{ $phone }}</a>
        @endif
        @if($whatsapp)
          <a href="https://wa.me/{{ preg_replace('/\D+/', '', $whatsapp) }}" target="_blank" rel="noopener" class="text-muted text-decoration-none"><i class="fab fa-whatsapp me-1"></i>WhatsApp</a>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-4 text-md-end">
    <div class="d-inline-flex flex-wrap gap-3 align-items-center">
      @if(!empty($credits))
        <div class="d-flex align-items-center gap-2">
          <div class="avatar avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center"><i class="fas fa-layer-group text-primary"></i></div>
          <div class="small">
            <div class="fw-semibold">{{ number_format($credits) }}</div>
            <div class="text-muted">Crédits</div>
          </div>
        </div>
      @endif
      @if(!empty($gpa))
        <div class="d-flex align-items-center gap-2">
          <div class="avatar avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center"><i class="fas fa-chart-line text-success"></i></div>
          <div class="small">
            <div class="fw-semibold">{{ number_format((float)$gpa, 2) }}</div>
            <div class="text-muted">GPA</div>
          </div>
        </div>
      @endif
      <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-primary"><i class="fas fa-user-edit me-1"></i>Modifier</a>
      <a href="{{ route('design-graphique.documents.index') }}" class="btn btn-outline-secondary"><i class="fas fa-folder-open me-1"></i>Documents</a>
    </div>
  </div>
</div>
