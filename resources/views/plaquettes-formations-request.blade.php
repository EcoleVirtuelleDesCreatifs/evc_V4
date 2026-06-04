@extends('layouts.app')

@section('title', 'Télécharger une plaquette')

@section('content')
    <div class="relative bg-gradient-to-b from-[#000033] to-[#000066] pt-[200px] pb-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <nav class="flex justify-center mb-8" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('homepage') }}" class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-orange-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Accueil
                            </a>
                        </li>
                        <li class="inline-flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ $backUrl }}" class="ml-1 text-sm font-medium text-gray-300 hover:text-orange-500 md:ml-2">Plaquettes</a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Demande de téléchargement</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">Télécharger la plaquette</h1>
                <p class="text-xl text-gray-300">Merci de renseigner le formulaire pour accéder au document.</p>

                <div class="mt-8 inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                    <span class="text-white font-semibold">{{ $formationLabel ?? $title }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-black py-16">
        <div class="mx-auto max-w-3xl px-6 lg:px-8">
            <div class="rounded-2xl bg-gray-800/80 p-8 ring-1 ring-white/10">
                @if(session('success'))
                    <div class="mb-6 rounded-xl bg-green-500/10 border border-green-500/30 p-4 text-green-200">
                        <div class="font-semibold">{{ session('success') }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ $actionUrl }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full rounded-xl bg-black/30 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Prénoms</label>
                            <input type="text" name="prenoms" value="{{ old('prenoms') }}" required class="w-full rounded-xl bg-black/30 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Type de formation</label>
                            <input type="text" name="type_formation" value="{{ old('type_formation') }}" required class="w-full rounded-xl bg-black/30 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Ex: Design Graphique" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Niveau d'étude</label>
                            <input type="text" name="niveau_etude" value="{{ old('niveau_etude') }}" required class="w-full rounded-xl bg-black/30 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Ex: Licence" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Pays</label>
                            <input type="text" name="pays" value="{{ old('pays') }}" required class="w-full rounded-xl bg-black/30 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville') }}" required class="w-full rounded-xl bg-black/30 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Whatsapp</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" required class="w-full rounded-xl bg-black/30 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Ex: +225 01 02 03 04 05" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Adresse mail</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl bg-black/30 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">Pourquoi rejoindre EVC</label>
                        <textarea name="motivation" rows="5" required class="w-full rounded-xl bg-black/30 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('motivation') }}</textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-orange-500 text-white font-semibold rounded-full hover:bg-orange-600 transition duration-300">
                            Envoyer ma demande
                        </button>
                        <a href="{{ $backUrl }}" class="inline-flex items-center justify-center px-6 py-3 bg-white/10 text-white font-semibold rounded-full hover:bg-white/15 transition duration-300">
                            Retour
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('homepage._cta-final')
@endsection
