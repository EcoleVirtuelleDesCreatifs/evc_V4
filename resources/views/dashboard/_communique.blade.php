@php
$coms = \App\Models\Communique::active()->with(['actualite:id,slug','evenement:id,slug'])->orderBy('order')->orderBy('created_at','desc')->get();
$flashItems = [];
foreach ($coms as $c) {
    $url = null; $label = null;
    if (!empty($c->actualite) && !empty($c->actualite->slug)) { $url = route('actualite.show', $c->actualite->slug); $label = 'Lire'; }
    elseif (!empty($c->evenement) && !empty($c->evenement->slug)) { $url = route('evenement.show', $c->evenement->slug); $label = 'Lire'; }
    $flashItems[] = ['content' => $c->content, 'url' => $url, 'label' => $label];
}
if (empty($flashItems)) {
    $flashItems = [
        ['content' => 'Bienvenue sur votre espace. Bonne formation !', 'url' => null, 'label' => null],
    ];
}
@endphp
