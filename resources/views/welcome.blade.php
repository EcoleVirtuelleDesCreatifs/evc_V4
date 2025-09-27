@extends('layouts.app')

@section('title', 'EVC - École Virtuelle des Créatifs | Formation en Ligne et en présentiel à Abidjan')
@section('description', 'Devenez un professionnel créatif avec EVC, la première école digitale en Côte d\'Ivoire et en Afrique francophone. Formations certifiantes en design graphique, marketing digital et plus. Basé à Abidjan, nous formons les talents de demain pour toute l\'Afrique.')
@section('keywords', 'école virtuelle, formation en ligne, formation en présentiel, design graphique, marketing digital, Abidjan, Côte d\'Ivoire, formation Afrique, certification professionnelle, créatifs, EVC, Adobe Photoshop, Illustrator, InDesign, web design')

@section('content')
    @include('homepage._hero')
    @include('homepage._presentation')
    @include('homepage._fondateur')
    @include('homepage._international')
    @include('homepage._avantages')
    @include('homepage._formations')
    @include('homepage._travaux')
    @include('homepage._evenements')
    @include('homepage._actualites')
    @include('homepage._laureats')
    @include('homepage._temoignages')
    @include('homepage._chiffres')
    @include('homepage._preinscription')
    @include('homepage._cta-final')
@endsection

