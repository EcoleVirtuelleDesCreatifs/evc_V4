<p>Nouvelle demande de téléchargement de plaquette.</p>

<ul>
    <li><strong>Plaquette :</strong> {{ $data['plaquette_title'] ?? '' }} ({{ $data['plaquette_filename'] ?? '' }})</li>
    <li><strong>Nom :</strong> {{ $data['nom'] ?? '' }}</li>
    <li><strong>Prénoms :</strong> {{ $data['prenoms'] ?? '' }}</li>
    <li><strong>Type de formation :</strong> {{ $data['type_formation'] ?? '' }}</li>
    <li><strong>Pays :</strong> {{ $data['pays'] ?? '' }}</li>
    <li><strong>Ville :</strong> {{ $data['ville'] ?? '' }}</li>
    <li><strong>Whatsapp :</strong> {{ $data['whatsapp'] ?? '' }}</li>
    <li><strong>Email :</strong> {{ $data['email'] ?? '' }}</li>
    <li><strong>Niveau d'étude :</strong> {{ $data['niveau_etude'] ?? '' }}</li>
</ul>

<p><strong>Pourquoi rejoindre EVC :</strong></p>
<p>{{ $data['motivation'] ?? '' }}</p>

<p>Date : {{ $data['submitted_at'] ?? '' }}</p>
