@extends('layouts.ki-admin')

@section('title', 'Test de connaissance')
@section('page-title', 'Test de connaissance')

@section('content')
<style>
    .cert-list-card{background:linear-gradient(145deg,#1e293b,#334155);border-radius:16px;border:1px solid rgba(255,255,255,.1);padding:1.5rem;margin-bottom:1rem;transition:all .3s ease}.cert-list-card:hover{transform:translateY(-3px);box-shadow:0 8px 30px rgba(0,0,0,.3);border-color:rgba(99,102,241,.3)}.empty-state{text-align:center;padding:3rem;color:#94a3b8}.empty-state i{font-size:3rem;margin-bottom:1rem;opacity:.5}.cert-status{display:inline-block;padding:5px 14px;border-radius:20px;font-size:.78rem;font-weight:600;background:rgba(59,130,246,.15);color:#60a5fa}.cert-info{display:flex;gap:1.5rem;flex-wrap:wrap;margin-top:.5rem}.cert-info span{color:#94a3b8;font-size:.85rem}.cert-info i{margin-right:4px}
</style>

<div class="container-fluid">
    <div class="cert-list-card">
        <div class="empty-state">
            <i class="fas fa-brain d-block"></i>
            <h5 class="text-white">Test de connaissance</h5>
            <p class="mb-3">Pour le moment, aucun test de connaissance n’est disponible pour votre compte.</p>
            <div class="text-start" style="max-width:720px;margin:0 auto;">
                <p class="mb-2"><strong>Comment ça fonctionne ?</strong></p>
                <ol class="mb-3" style="color:#94a3b8;line-height:1.7;text-align:left;padding-left:1.2rem;">
                    <li>Votre formateur prépare un test lié à votre progression.</li>
                    <li>Le test s’affiche ici dès qu’il est activé et assigné.</li>
                    <li>Vous répondez aux questions directement depuis votre espace étudiant.</li>
                    <li>Votre résultat permet d’évaluer votre niveau avant la certification.</li>
                </ol>
                <p class="mb-0" style="color:#94a3b8;"><strong>Si rien ne s’affiche :</strong><br>cela signifie qu’aucun test de connaissance n’a encore été assigné à votre compte.</p>
            </div>
        </div>
    </div>
</div>
@endsection
