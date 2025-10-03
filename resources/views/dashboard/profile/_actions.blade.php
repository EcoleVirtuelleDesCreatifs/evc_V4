<div class="row g-3 mt-2">
  <div class="col-12 d-flex flex-wrap gap-2">
    <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-primary">
      <i class="fas fa-user-edit me-1"></i> Modifier mon profil
    </a>
    <a href="{{ route('design-graphique.documents.index') }}" class="btn btn-outline-secondary">
      <i class="fas fa-folder-open me-1"></i> Mes documents
    </a>
    @if(\Illuminate\Support\Facades\Route::has('design-graphique.tp.index'))
      <a href="{{ route('design-graphique.tp.index') }}" class="btn btn-outline-info">
        <i class="fas fa-tasks me-1"></i> Mes TP
      </a>
    @else
      <a href="#" class="btn btn-outline-info disabled" title="Bientôt disponible">
        <i class="fas fa-tasks me-1"></i> Mes TP
      </a>
    @endif
    <a href="{{ route('design-graphique.parametres.index') }}" class="btn btn-outline-dark">
      <i class="fas fa-user-cog me-1"></i> Paramètres
    </a>
  </div>
</div>
