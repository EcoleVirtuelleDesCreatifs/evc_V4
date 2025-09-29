@extends('layouts.admin')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Tester l'envoi d'e-mail SMTP</div>
        <div class="card-body">
          @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
          @endif
          <p>Cette page n'est disponible qu'en environnement local. Elle permet d'envoyer un e-mail de test afin de valider la configuration SMTP.</p>
          <form method="GET" action="{{ route('debug.mail') }}" class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Destinataire</label>
              <input type="email" name="to" class="form-control" placeholder="{{ config('mail.admin_address') ?? config('mail.from.address') }}">
              <div class="form-text">Par défaut: MAIL_ADMIN_ADDRESS ou MAIL_FROM_ADDRESS</div>
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <button class="btn btn-primary w-100" type="submit">Envoyer l'e-mail de test</button>
            </div>
          </form>
          <hr>
          <p class="mb-1"><strong>Rappel configuration .env</strong></p>
<pre class="mb-0"><code>MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@domaine.com
MAIL_FROM_NAME="EVC"
MAIL_ADMIN_ADDRESS=admin@domaine.com</code></pre>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
