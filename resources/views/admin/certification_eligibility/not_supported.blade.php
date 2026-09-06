@extends('layouts.admin')

@section('title', 'Éligibilité non prise en charge')

@section('content')
<div class="container-fluid py-5 text-center" style="color:#fff;">
    <h1 class="h3 mb-3"><i class="fas fa-info-circle me-2"></i>Formation non éligible</h1>
    <p class="text-muted">La formation <strong>{{ $student->program ?? '—' }}</strong> de cet étudiant n'est pas encore prise en charge par le module d'éligibilité.</p>
    <a href="{{ route('admin.certification-eligibility.index') }}" class="btn btn-primary mt-3">Retour à la liste</a>
</div>
@endsection
