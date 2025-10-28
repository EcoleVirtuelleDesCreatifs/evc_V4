@extends('layouts.admin')

@section('title', 'Debug - Étudiants')

@section('content')
<div style="padding: 2rem; background: #0f172a; color: #e2e8f0; min-height: 100vh;">
    <h1 style="margin-bottom: 2rem; color: #38bdf8;">🔍 Debug - Étudiants Actifs</h1>
    
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(51, 65, 85, 0.7); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="color: #38bdf8; margin-bottom: 1rem;">📊 Statistiques</h2>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 0.5rem;">✅ Total étudiants actifs : <strong>{{ $totalActifs }}</strong></li>
            <li style="margin-bottom: 0.5rem;">📚 Total étudiants (tous statuts) : <strong>{{ $totalAll }}</strong></li>
        </ul>
    </div>

    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(51, 65, 85, 0.7); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="color: #38bdf8; margin-bottom: 1rem;">📋 Programmes distincts (étudiants actifs)</h2>
        @if($programs->isEmpty())
            <p style="color: #ef4444;">❌ Aucun programme trouvé</p>
        @else
            <div style="display: grid; gap: 0.5rem;">
                @foreach($programs as $program)
                    <div style="background: rgba(56, 189, 248, 0.1); padding: 1rem; border-radius: 8px; border: 1px solid rgba(56, 189, 248, 0.3);">
                        <strong style="color: #fbbf24;">"{{ $program->program }}"</strong>
                        <span style="color: #94a3b8; margin-left: 1rem;">→ {{ $program->count }} étudiant(s)</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(51, 65, 85, 0.7); border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="color: #38bdf8; margin-bottom: 1rem;">👥 Liste des étudiants actifs (20 premiers)</h2>
        @if($students->isEmpty())
            <p style="color: #ef4444;">❌ Aucun étudiant actif trouvé</p>
            <p style="color: #fbbf24; margin-top: 1rem;">💡 Vérifiez que vous avez des étudiants avec status = 'active' dans la table students</p>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: rgba(56, 189, 248, 0.1); border-bottom: 2px solid rgba(56, 189, 248, 0.3);">
                        <th style="padding: 0.75rem; text-align: left;">ID</th>
                        <th style="padding: 0.75rem; text-align: left;">Nom</th>
                        <th style="padding: 0.75rem; text-align: left;">Programme</th>
                        <th style="padding: 0.75rem; text-align: left;">Spécialisation</th>
                        <th style="padding: 0.75rem; text-align: left;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.7);">
                            <td style="padding: 0.75rem;">{{ $student->id }}</td>
                            <td style="padding: 0.75rem;">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td style="padding: 0.75rem;"><strong style="color: #fbbf24;">"{{ $student->program }}"</strong></td>
                            <td style="padding: 0.75rem;">{{ $student->specialization ?? 'N/A' }}</td>
                            <td style="padding: 0.75rem;">
                                <span style="background: #10b981; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">{{ $student->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div style="margin-top: 2rem;">
        <a href="{{ route('admin.travaux.to-send') }}" style="background: #38bdf8; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-block;">
            ← Retour au formulaire
        </a>
    </div>
</div>
@endsection
