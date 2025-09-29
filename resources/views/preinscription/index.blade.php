@extends('layouts.app')

@section('title', 'Pré‑inscription - EVC')

@section('content')
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
  <section class="bg-gray-900 py-12 border-b border-gray-800 pt-28 lg:pt-36">
    <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
      <h1 class="text-3xl lg:text-4xl font-extrabold text-white">Pré‑inscription</h1>
      <p class="mt-3 text-gray-300">Merci de compléter soigneusement le formulaire. Tous les champs sont obligatoires.</p>
    </div>
  </section>

  <!-- Formulaire pleine page -->
  <section class="py-10">
    <div class="mx-auto max-w-3xl px-6 lg:px-0">
      <div id="preinscription-form-container" class="relative bg-dark-secondary p-8 rounded-2xl shadow-lg border border-gray-700 w-full max-h-[85vh] overflow-y-auto">
        <form id="preRegistrationForm" action="/candidature" method="POST" enctype="multipart/form-data" novalidate>
          @csrf
          @include('preinscription._form_fields')
        </form>
      </div>
    </div>
  </section>
@endsection
