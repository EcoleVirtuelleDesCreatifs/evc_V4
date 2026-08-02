@extends('layouts.app')

@section('title', 'EVC - École Numérique Abidjan | Formation Design Graphique, Motion Design & Community Management')
@section('description', 'École numérique N°1 à Abidjan. EVC, école virtuelle des créatifs : formations certifiées Adobe Photoshop, Motion Design, Community Management, Bureautique. 95% de réussite en Côte d\'Ivoire.')
@section('keywords', 'école numérique Abidjan, ecole numérique, EVC, ecole virtuelle des créatifs, école virtuelle Abidjan, formation design graphique Abidjan, centre de formation Adobe Photoshop Abidjan, formation motion design Abidjan, école community management Abidjan, formation bureautique avancé Abidjan, école de formatique, école de communication visuelle Abidjan, ECV, ECAV, école de formation Abidjan, liste écoles informatique Abidjan')

@section('content')
    @include('homepage._hero')
    @include('homepage._international')
    @include('homepage._chiffres')
    @include('homepage._presentation')
    @include('homepage._webtv_home')
    @include('homepage._travaux')
    @include('homepage._fondateur_homepage')
    @include('homepage._jury_members')
    @include('homepage._partenaires')
    @include('homepage._avantages')
    @include('homepage._formations')
    @include('homepage._processus-inscription')

    @include('homepage._evenements')
    @include('homepage._actualites')
    @include('homepage._laureats')
    @include('homepage._temoignages')
    @include('homepage._faq')
    @include('homepage._preinscription')
    @include('homepage._cta-final')
@endsection

