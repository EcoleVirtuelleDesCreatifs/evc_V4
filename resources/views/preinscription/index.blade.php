@extends('layouts.app')

@section('title', 'Pré‑inscription - EVC')

@section('content')
  <div class="min-h-screen" style="background: linear-gradient(45deg, #0b1e3a 0%, #0e2a54 100%);">
    @if(session('success'))
      <div id="flash-success" class="fixed top-4 left-1/2 -translate-x-1/2 z-[13000] bg-emerald-600 text-white px-4 py-3 rounded-full shadow-lg ring-1 ring-white/10 flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button type="button" onclick="document.getElementById('flash-success').remove()" class="ml-2 text-white/80 hover:text-white">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <script>
        setTimeout(function(){ var el = document.getElementById('flash-success'); if(el) el.remove(); }, 6000);
      </script>
    @endif
    <!-- Toasts -->
    <div id="toast-container" class="fixed top-4 right-4 z-[11000] space-y-3"></div>
    <div id="toast-sr" class="sr-only" aria-live="polite"></div>

    <!-- Overlay d'envoi -->
    <div id="mail-loading-overlay" class="fixed inset-0 z-[12000] bg-black/70 backdrop-blur-sm hidden items-center justify-center" style="display:none;">
      <div class="flex flex-col items-center gap-3 text-white">
        <svg class="animate-spin h-10 w-10 text-evc-orange" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <div class="text-sm">Envoi en cours... Merci de patienter</div>
      </div>
      <span class="sr-only">Envoi des emails en cours</span>
    </div>

    <!-- En-tête -->
    <section class="py-12 pt-28 lg:pt-36">
      <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
        <h1 class="text-3xl lg:text-4xl font-extrabold text-white">Pré‑inscription</h1>
        <p class="mt-3 text-blue-100/90">Merci de compléter soigneusement le formulaire. Tous les champs sont obligatoires.</p>
      </div>
    </section>

    <!-- Formulaire pleine page -->
    <section class="py-10 pb-20">
      <div class="mx-auto max-w-3xl px-6 lg:px-0">
        <div id="preinscription-form-container" class="relative bg-dark-secondary/95 p-8 rounded-2xl shadow-lg ring-1 ring-white/10 w-full max-h-[85vh] overflow-y-auto">
          @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 text-red-200 px-4 py-3">
              <strong class="block mb-1">Veuillez corriger les erreurs suivantes :</strong>
              <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form id="preRegistrationForm" action="/candidature" method="POST" enctype="multipart/form-data" data-ajax="false">
            @csrf
            @include('preinscription._form_fields')
          </form>
        </div>
      </div>
    </section>
  </div>
@endsection
