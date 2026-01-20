@extends('layouts.admin')

@section('title', 'Plaquettes de formation')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1 text-white">Plaquettes de formation</h2>
            <div class="text-muted">Ajoute, consulte ou supprime les plaquettes PDF affichées sur le site public.</div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-5">
            <div class="card border-0" style="background: rgba(255,255,255,0.04); backdrop-filter: blur(12px);">
                <div class="card-body">
                    <h3 class="h6 text-white mb-3">Ajouter une plaquette</h3>

                    <form method="POST" action="{{ route('admin.plaquettes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-white">Titre</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Ex: Design Graphique" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Fichier PDF</label>
                            <input type="file" name="file" class="form-control" accept="application/pdf" required>
                            <div class="form-text text-muted">PDF uniquement. Taille max 20 Mo.</div>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-upload me-2"></i>Uploader
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card border-0" style="background: rgba(255,255,255,0.04); backdrop-filter: blur(12px);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 text-white mb-0">Plaquettes disponibles</h3>
                        <span class="badge text-bg-dark">{{ count($plaquettes) }}</span>
                    </div>

                    @if(empty($plaquettes))
                        <div class="text-muted">Aucune plaquette pour le moment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: rgba(0,0,0,0.15); --bs-table-border-color: rgba(255,255,255,0.08);">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th class="text-nowrap">Taille</th>
                                        <th class="text-nowrap">Màj</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plaquettes as $p)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $p['title'] }}</div>
                                                <div class="small text-muted">{{ $p['filename'] }}</div>
                                            </td>
                                            <td class="text-nowrap">{{ $p['size_label'] ?: '—' }}</td>
                                            <td class="text-nowrap">{{ $p['updated_at'] ?: '—' }}</td>
                                            <td class="text-end">
                                                <a class="btn btn-sm btn-outline-light" href="{{ $p['url'] }}" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <form method="POST" action="{{ route('admin.plaquettes.delete', ['filename' => $p['filename']]) }}" class="d-inline" onsubmit="return confirm('Supprimer cette plaquette ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
