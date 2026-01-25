<p>Bonjour {{ $request->prenoms ?? '' }} {{ $request->nom ?? '' }},</p>

<p>Votre demande de plaquette a été validée.</p>

<p>
    <strong>Plaquette :</strong> {{ $plaquette->title ?? '' }}
</p>

<p>
    Cliquez sur le lien ci-dessous pour télécharger la plaquette :
</p>

<p>
    <a href="{{ $downloadUrl }}">Télécharger la plaquette</a>
</p>

<p>
    Ce lien est temporaire.
</p>

<p>Merci,<br>EVC</p>
