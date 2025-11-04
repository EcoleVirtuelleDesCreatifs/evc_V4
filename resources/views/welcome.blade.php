@extends('layouts.app')

@section('title', 'EVC - École Virtuelle des Créatifs | Formation Design Graphique & Digital à Abidjan')
@section('description', 'École N°1 du digital en Côte d\'Ivoire. Formations certifiantes en Design Graphique, Community Management, Bureautique et IA. 95% de réussite. Inscriptions ouvertes à Abidjan.')
@section('keywords', 'école virtuelle, formation design graphique Abidjan, formation community management, école digitale Côte d\'Ivoire, formation certifiante, Adobe Photoshop Abidjan, formation en ligne Afrique, école créatifs, EVC, formation bureautique, intelligence artificielle')

@section('content')
    @include('homepage._hero')
    @include('homepage._webtv_home')
    @include('homepage._chiffres')
    @include('homepage._presentation')
    @include('homepage._fondateur_homepage')
    @include('homepage._international')
    @include('homepage._avantages')
    @include('homepage._formations')
    @include('homepage._travaux')
    
    @include('homepage._evenements')
    @include('homepage._actualites')
    @include('homepage._laureats')
    @include('homepage._temoignages')
    @include('homepage._preinscription')
    @include('homepage._cta-final')
@endsection

