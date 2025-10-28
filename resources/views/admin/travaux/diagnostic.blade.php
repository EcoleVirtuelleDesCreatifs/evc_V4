@extends('layouts.admin')

@section('title', 'Diagnostic Complet - TP et Fichiers')

@section('content')
<div style="padding: 2rem; background: #0f172a; color: #e2e8f0; min-height: 100vh;">
    <h1 style="margin-bottom: 2rem; color: #38bdf8;">🔍 Diagnostic Complet - TP et Fichiers</h1>
    
    <!-- Structure Table tp_assignments -->
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(51, 65, 85, 0.7); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="color: #38bdf8; margin-bottom: 1rem;">📋 Structure table: tp_assignments</h2>
        <table style="width: 100%; border-collapse: collapse; font-family: monospace; font-size: 0.9rem;">
            <thead>
                <tr style="background: rgba(56, 189, 248, 0.1);">
                    <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Colonne</th>
                    <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Type</th>
                    <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Null</th>
                    <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Key</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tpAssignmentsStructure as $col)
                    <tr>
                        <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7); color: #fbbf24;">{{ $col->Field }}</td>
                        <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $col->Type }}</td>
                        <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $col->Null }}</td>
                        <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $col->Key }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Structure Table tp_assignment_files -->
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(51, 65, 85, 0.7); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="color: #38bdf8; margin-bottom: 1rem;">📋 Structure table: tp_assignment_files</h2>
        <table style="width: 100%; border-collapse: collapse; font-family: monospace; font-size: 0.9rem;">
            <thead>
                <tr style="background: rgba(56, 189, 248, 0.1);">
                    <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Colonne</th>
                    <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Type</th>
                    <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Null</th>
                    <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Key</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tpFilesStructure as $col)
                    <tr>
                        <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7); color: #fbbf24;">{{ $col->Field }}</td>
                        <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $col->Type }}</td>
                        <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $col->Null }}</td>
                        <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $col->Key }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Données récentes -->
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(51, 65, 85, 0.7); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="color: #38bdf8; margin-bottom: 1rem;">📊 10 derniers TP créés</h2>
        @if($recentTps->isEmpty())
            <p style="color: #ef4444;">❌ Aucun TP trouvé</p>
        @else
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: rgba(56, 189, 248, 0.1);">
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">ID</th>
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Titre</th>
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Formation</th>
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Date création</th>
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Fichiers?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTps as $tp)
                        <tr>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $tp->id }}</td>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $tp->title }}</td>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $tp->formation }}</td>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $tp->created_at }}</td>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">
                                @php
                                    $fileCount = DB::table('tp_assignment_files')->where('tp_assignment_id', $tp->id)->count();
                                @endphp
                                @if($fileCount > 0)
                                    <span style="color: #10b981;">✅ {{ $fileCount }} fichier(s)</span>
                                @else
                                    <span style="color: #ef4444;">❌ Aucun</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Tous les fichiers -->
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(51, 65, 85, 0.7); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="color: #38bdf8; margin-bottom: 1rem;">📎 Tous les fichiers enregistrés</h2>
        @if($allFiles->isEmpty())
            <p style="color: #ef4444;">❌ Aucun fichier trouvé dans tp_assignment_files</p>
            <p style="color: #fbbf24; margin-top: 1rem;">💡 Cela confirme que les fichiers ne sont PAS enregistrés en base de données</p>
        @else
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: rgba(56, 189, 248, 0.1);">
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">ID</th>
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">TP ID</th>
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Nom fichier</th>
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Chemin</th>
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Taille</th>
                        <th style="padding: 0.5rem; text-align: left; border: 1px solid rgba(51, 65, 85, 0.7);">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allFiles as $file)
                        <tr>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $file->id }}</td>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $file->tp_assignment_id }}</td>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7); color: #fbbf24;">{{ $file->file_name }}</td>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7); font-size: 0.8rem;">{{ $file->file_path }}</td>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ number_format($file->file_size / 1024, 2) }} Ko</td>
                            <td style="padding: 0.5rem; border: 1px solid rgba(51, 65, 85, 0.7);">{{ $file->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Test d'insertion -->
    <div style="background: rgba(251, 191, 36, 0.2); border: 2px solid #fbbf24; border-radius: 12px; padding: 2rem;">
        <h2 style="color: #fbbf24; margin-bottom: 1rem;">🧪 Test d'insertion manuelle</h2>
        <p style="margin-bottom: 1rem;">Cliquez sur ce bouton pour tester si on peut insérer un fichier test en base :</p>
        <form action="{{ route('admin.test.insert.file') }}" method="POST">
            @csrf
            <button type="submit" style="background: #fbbf24; color: #0f172a; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                Tester l'insertion
            </button>
        </form>
        
        @if(session('test_result'))
            <div style="margin-top: 1rem; padding: 1rem; background: {{ session('test_success') ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' }}; border-radius: 8px;">
                <strong>Résultat du test :</strong><br>
                {{ session('test_result') }}
            </div>
        @endif
    </div>

    <div style="margin-top: 2rem;">
        <a href="{{ route('admin.travaux.to-send') }}" style="background: #38bdf8; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-block;">
            ← Retour au formulaire
        </a>
    </div>
</div>
@endsection
