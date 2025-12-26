@extends('layouts.admin')

@section('title', 'Éditer une Pré-inscription')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
@endpush

@section('content')
<form method="POST" action="{{ route('admin.preinscriptions.update', $pre->id) }}" class="interactive-dashboard-form">
    @csrf
    @method('PUT')

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="text-white mb-1" style="font-weight: 800;">
                <i class="fas fa-edit me-2"></i>Éditer la candidature
            </h2>
            <div class="text-white-50">Modifier les informations de la pré-inscription.</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.preinscriptions.show', $pre->id) }}" class="btn btn-light">
                <i class="fas fa-eye me-2"></i>Voir
            </a>
            <a href="{{ route('admin.preinscriptions.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erreur :</strong> veuillez corriger les champs ci-dessous.
        </div>
    @endif

    <div class="row g-4">
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-edit"></i>
                    <h3>Formulaire de modification</h3>
                </div>
                <div class="form-card-body">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Prénom *</label>
                        <input type="text" name="prenom" value="{{ old('prenom', $pre->prenom) }}" class="form-control @error('prenom') is-invalid @enderror" />
                        @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="nom" value="{{ old('nom', $pre->nom) }}" class="form-control @error('nom') is-invalid @enderror" />
                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $pre->email) }}" class="form-control @error('email') is-invalid @enderror" />
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $pre->whatsapp) }}" class="form-control @error('whatsapp') is-invalid @enderror" />
                        @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Âge</label>
                        <input type="number" min="1" max="120" name="age" value="{{ old('age', $pre->age) }}" class="form-control @error('age') is-invalid @enderror" />
                        @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sexe</label>
                        <input type="text" name="sexe" value="{{ old('sexe', $pre->sexe) }}" class="form-control @error('sexe') is-invalid @enderror" />
                        @error('sexe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nationalité</label>
                        <input type="text" name="nationalite" value="{{ old('nationalite', $pre->nationalite) }}" class="form-control @error('nationalite') is-invalid @enderror" />
                        @error('nationalite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pays</label>
                        <input type="text" name="pays" value="{{ old('pays', $pre->pays) }}" class="form-control @error('pays') is-invalid @enderror" />
                        @error('pays')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ville</label>
                        <input type="text" name="ville" value="{{ old('ville', $pre->ville) }}" class="form-control @error('ville') is-invalid @enderror" />
                        @error('ville')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Niveau d'étude</label>
                        <input type="text" name="niveau_etude" value="{{ old('niveau_etude', $pre->niveau_etude) }}" class="form-control @error('niveau_etude') is-invalid @enderror" />
                        @error('niveau_etude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Domaine d'étude</label>
                        <input type="text" name="domaine_etude" value="{{ old('domaine_etude', $pre->domaine_etude) }}" class="form-control @error('domaine_etude') is-invalid @enderror" />
                        @error('domaine_etude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Programme</label>
                        <input type="text" name="programme" value="{{ old('programme', $pre->programme) }}" class="form-control @error('programme') is-invalid @enderror" />
                        @error('programme')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Formation *</label>
                        <select name="choix_formation" class="form-select @error('choix_formation') is-invalid @enderror">
                            @php
                                $formationValue = old('choix_formation', $pre->choix_formation);
                            @endphp
                            <option value="design_graphique" @selected($formationValue==='design_graphique')>Design Graphique</option>
                            <option value="community_management" @selected($formationValue==='community_management')>Community Management</option>
                            <option value="design_graphique_community_manager" @selected($formationValue==='design_graphique_community_manager')>Design Graphique & Community Manager</option>
                            <option value="design_graphique_community_management" @selected($formationValue==='design_graphique_community_management')>Design Graphique & Community Management</option>
                            <option value="gestion_informatique" @selected($formationValue==='gestion_informatique')>Gestion Informatique</option>
                            <option value="intelligence_artificielle" @selected($formationValue==='intelligence_artificielle')>Intelligence Artificielle</option>
                            <option value="design_cm" @selected($formationValue==='design_cm')>Design & Community Management</option>
                        </select>
                        @error('choix_formation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Niveau dans la formation</label>
                        <input type="text" name="niveau_dans_formation" value="{{ old('niveau_dans_formation', $pre->niveau_dans_formation) }}" class="form-control @error('niveau_dans_formation') is-invalid @enderror" />
                        @error('niveau_dans_formation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Comment avez-vous connu ?</label>
                        <input type="text" name="how_known" value="{{ old('how_known', $pre->how_known) }}" class="form-control @error('how_known') is-invalid @enderror" />
                        @error('how_known')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Statut *</label>
                        @php
                            $statusValue = old('status', $pre->status);
                        @endphp
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="pending" @selected($statusValue==='pending')>En cours</option>
                            <option value="en cours" @selected($statusValue==='en cours')>En cours</option>
                            <option value="accepted" @selected($statusValue==='accepted')>Accepté</option>
                            <option value="Validé" @selected($statusValue==='Validé')>Validé</option>
                            <option value="Actif" @selected($statusValue==='Actif')>Actif</option>
                            <option value="rejected" @selected($statusValue==='rejected')>Rejeté</option>
                            <option value="Rejeté" @selected($statusValue==='Rejeté')>Rejeté</option>
                            <option value="En attente" @selected($statusValue==='En attente')>En attente</option>
                            <option value="paid" @selected($statusValue==='paid')>Payé</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Compétences</label>
                        <textarea name="competences" rows="3" class="form-control @error('competences') is-invalid @enderror">{{ old('competences', $pre->competences) }}</textarea>
                        @error('competences')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Disponibilité</label>
                        <input type="text" name="disponibilite" value="{{ old('disponibilite', $pre->disponibilite) }}" class="form-control @error('disponibilite') is-invalid @enderror" />
                        @error('disponibilite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Motivation</label>
                        <textarea name="motivation" rows="4" class="form-control @error('motivation') is-invalid @enderror">{{ old('motivation', $pre->motivation) }}</textarea>
                        @error('motivation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="has_computer" name="has_computer" @checked(old('has_computer', (bool) $pre->has_computer))>
                            <label class="form-check-label" for="has_computer">A un ordinateur</label>
                        </div>
                        @error('has_computer')<div class="text-danger" style="font-size:0.875rem;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="has_smartphone" name="has_smartphone" @checked(old('has_smartphone', (bool) $pre->has_smartphone))>
                            <label class="form-check-label" for="has_smartphone">A un smartphone</label>
                        </div>
                        @error('has_smartphone')<div class="text-danger" style="font-size:0.875rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>

    <div class="form-footer mt-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.preinscriptions.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-2"></i>Retour</a>
        <div>
            <a href="{{ route('admin.preinscriptions.index') }}" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Annuler</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Enregistrer</button>
        </div>
    </div>
</form>
@endsection
