@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Community Management')

@section('page-title', 'Mon Tableau de Bord')

@section('styles')
<style>
    /* ===================================
       DESIGN DYNAMIQUE INSTAGRAM
       ================================== */

    body {
        background: linear-gradient(135deg, #fdf4f5 0%, #fef9f8 50%, #f8f9fa 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif;
        color: #1a1a1a;
        line-height: 1.6;
        min-height: 100vh;
    }

    /* Container principal */
    .minimal-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1rem 2rem 2rem;
    }

    /* Header dynamique avec dégradé */
    .minimal-header {
        background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        border-radius: 25px;
        padding: 2.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 20px 60px rgba(240, 148, 51, 0.3);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .minimal-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
        color: white;
    }

    .minimal-header h1 strong {
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .minimal-header .date {
        font-size: 1rem;
        opacity: 0.9;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        backdrop-filter: blur(10px);
    }

    /* Layout en grille optimisé */
    .minimal-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 1400px) {
        .minimal-grid {
            grid-template-columns: 320px 1fr;
            gap: 2rem;
        }
    }

    @media (max-width: 1200px) {
        .minimal-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    /* Section profil avec dégradé */
    .minimal-profile {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .minimal-profile::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 120px;
        background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        z-index: 0;
    }

    .minimal-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        border: 5px solid white;
        object-fit: cover;
        position: relative;
        z-index: 1;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .minimal-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 1rem 0 0.5rem;
        background: linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        z-index: 1;
    }

    .minimal-role {
        font-size: 0.95rem;
        color: #666;
        margin: 0 0 2rem;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .minimal-stats {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        text-align: left;
        padding-top: 1.5rem;
        border-top: 2px solid #f0f0f0;
    }

    .minimal-stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: linear-gradient(135deg, #fef5f1 0%, #fce4ec 100%);
        border-radius: 12px;
        transition: transform 0.2s ease;
    }

    .minimal-stat-item:hover {
        transform: translateX(5px);
    }

    .minimal-stat-label {
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }

    .minimal-stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Actions dynamiques avec dégradé */
    .minimal-actions {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 1400px) {
        .minimal-actions {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 1200px) {
        .minimal-actions {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .minimal-actions {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .minimal-action {
        border: 2px solid transparent;
        background-image: linear-gradient(white, white),
                          linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        background-origin: border-box;
        background-clip: padding-box, border-box;
        padding: 1.75rem 1rem;
        border-radius: 18px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }

    .minimal-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 0;
    }

    .minimal-action:hover::before {
        opacity: 1;
    }

    .minimal-action:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(240, 148, 51, 0.3);
    }

    .minimal-action:hover i,
    .minimal-action:hover span {
        color: white;
        position: relative;
        z-index: 1;
    }

    .minimal-action i {
        font-size: 2rem;
        background: linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .minimal-action span {
        font-size: 0.9rem;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    /* Stats cards avec dégradés */
    .minimal-stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1400px) {
        .minimal-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .minimal-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .minimal-stat-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .minimal-stat-card::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #f09433 0%, #dc2743 100%);
    }

    .minimal-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(240, 148, 51, 0.2);
    }

    .minimal-stat-card-value {
        font-size: 3.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem;
        background: linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .minimal-stat-card-label {
        font-size: 0.85rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 600;
    }

    /* Info section avec dégradé */
    .minimal-info {
        background: white;
        padding: 3rem 2rem;
        border-radius: 25px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .minimal-info h3 {
        background: linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
    }

    .minimal-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    }

    .minimal-info h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 2rem;
        background: linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .minimal-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
    }

    .minimal-info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .minimal-info-label {
        font-size: 0.85rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .minimal-info-value {
        font-size: 1rem;
        color: #000;
        font-weight: 500;
    }

    /* Progress bar minimaliste */
    .minimal-progress {
        margin-top: 3rem;
        padding-top: 3rem;
        border-top: 1px solid #e0e0e0;
    }

    .minimal-progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .minimal-progress-label {
        font-size: 0.9rem;
        color: #666;
    }

    .minimal-progress-value {
        font-size: 1.2rem;
        font-weight: 600;
        color: #000;
    }

    .minimal-progress-bar {
        height: 2px;
        background: #e0e0e0;
        position: relative;
    }

    .minimal-progress-fill {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: #000;
        transition: width 0.3s ease;
    }

    /* Animations subtiles */
    * {
        transition: all 0.2s ease;
    }

    /* Accent Instagram sur éléments clés */
    .minimal-header h1 strong {
        background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .minimal-avatar {
        border: 3px solid transparent;
        background-image: linear-gradient(#FAFAFA, #FAFAFA),
                          linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        background-origin: border-box;
        background-clip: padding-box, border-box;
    }

    .minimal-action:hover {
        border-color: #f09433;
        transform: translateY(-2px);
    }

    .minimal-action:hover i {
        background: linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .minimal-progress-fill {
        background: linear-gradient(90deg, #f09433 0%, #dc2743 100%);
    }

    /* Stat value avec accent Instagram */
    .minimal-stat-value {
        background: linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transform: rotate(360deg) scale(0.8);
    }

    /* Progress Card - Transparent glass effect */
    .progress-card-new {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.25rem;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .progress-title-new {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1rem;
        color: white;
    }

    .progress-bar-new {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        height: 10px;
        overflow: hidden;
        margin-bottom: 0.75rem;
    }

    .progress-fill-new {
        background: white;
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .progress-text {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        line-height: 1.4;
    }

    /* Welcome Banner - Instagram */
    .welcome-banner {
        background: linear-gradient(135deg, #fef5f1 0%, #fce4ec 100%);
        border-left: 4px solid #f09433;
        border-radius: 15px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .welcome-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #f09433 0%, #dc2743 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .welcome-content h6 {
        color: #dc2743;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .welcome-content p {
        color: #8e44ad;
        margin: 0;
        font-size: 0.9rem;
    }

    /* Bulles animées de réseaux sociaux */
    .social-bubbles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 999;
        overflow: hidden;
    }

    .bubble {
        position: absolute;
        font-size: 3rem;
        opacity: 0.25;
        animation: float 20s infinite ease-in-out;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.15));
        will-change: transform, opacity;
    }

    .bubble:nth-child(1) {
        left: 10%;
        animation-delay: 0s;
        animation-duration: 25s;
    }

    .bubble:nth-child(2) {
        left: 20%;
        animation-delay: 2s;
        animation-duration: 22s;
    }

    .bubble:nth-child(3) {
        left: 30%;
        animation-delay: 4s;
        animation-duration: 28s;
    }

    .bubble:nth-child(4) {
        left: 40%;
        animation-delay: 1s;
        animation-duration: 24s;
    }

    .bubble:nth-child(5) {
        left: 50%;
        animation-delay: 3s;
        animation-duration: 26s;
    }

    .bubble:nth-child(6) {
        left: 60%;
        animation-delay: 5s;
        animation-duration: 23s;
    }

    .bubble:nth-child(7) {
        left: 70%;
        animation-delay: 2.5s;
        animation-duration: 27s;
    }

    .bubble:nth-child(8) {
        left: 80%;
        animation-delay: 4.5s;
        animation-duration: 21s;
    }

    .bubble:nth-child(9) {
        left: 90%;
        animation-delay: 1.5s;
        animation-duration: 29s;
    }

    .bubble:nth-child(10) {
        left: 15%;
        animation-delay: 6s;
        animation-duration: 25s;
    }

    @keyframes float {
        0% {
            transform: translateY(100vh) rotate(0deg) scale(0.8);
            opacity: 0;
        }
        5% {
            opacity: 0.25;
        }
        50% {
            transform: translateY(50vh) rotate(180deg) scale(1.1);
            opacity: 0.3;
        }
        95% {
            opacity: 0.25;
        }
        100% {
            transform: translateY(-10vh) rotate(360deg) scale(0.8);
            opacity: 0;
        }
    }

    /* Animation de rotation douce */
    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    /* Effet de pulsation pour certaines bulles */
    .bubble.pulse {
        animation: float 20s infinite ease-in-out, pulse 3s infinite ease-in-out;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
</style>
@endsection

@section('content')
{{-- Toile digitale animée --}}
<canvas id="digitalCanvas" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0;"></canvas>

<div class="container-fluid" style="position: relative; z-index: 10;">
    @php
        $student = $student ?? null;
        $user = auth()->user();

        $photoUrl = asset('assets/img/avatar.png');
        if ($student && $student->profile_photo) {
            $photoUrl = asset('uploads/photos/' . basename($student->profile_photo));
        } elseif ($user && $user->profile_photo) {
            $photoUrl = asset('uploads/photos/' . basename($user->profile_photo));
        } elseif (session('user_photo')) {
            $photoUrl = asset('uploads/photos/' . basename(session('user_photo')));
        }

        $fullName = $student ? trim($student->first_name . ' ' . $student->last_name) : ($user->name ?? 'Étudiant');
        $firstName = $student->first_name ?? ($user->first_name ?? 'N/A');
        $lastName = $student->last_name ?? ($user->last_name ?? 'N/A');
        $email = $student->email ?? $user->email ?? 'N/A';
        $phone = $student->phone ?? 'N/A';
        $country = $student->country ?? 'Côte d\'Ivoire';
        $age = $student->age ?? 'N/A';
        $city = $student->city ?? 'N/A';
        $lastDiploma = $student->last_diploma ?? 'N/A';
        $educationLevel = $student->education_level ?? 'N/A';

        // Gérer la date d'inscription
        $registrationDate = 'N/A';
        if ($student && isset($student->created_at)) {
            if (is_object($student->created_at) && method_exists($student->created_at, 'format')) {
                $registrationDate = $student->created_at->format('d/m/Y');
            } elseif (is_string($student->created_at)) {
                $registrationDate = date('d/m/Y', strtotime($student->created_at));
            }
        }

        $remainingBalance = $student->remaining_balance ?? 0;

        $formationsCount = 12;
        $tpRendus = 0;
        $tpTotal = 10;
        $evenementsCount = 4;
        $profileCompletion = 85;
    @endphp


    {{-- DESIGN MINIMALISTE RÉVOLUTIONNAIRE --}}
    <div class="minimal-container">

        {{-- Header --}}
        <div class="minimal-header">
            <h1>Bonjour, <strong>{{ explode(' ', $fullName)[0] }}</strong></h1>
            <div class="date">Inscrit le {{ $registrationDate }}</div>
        </div>

        {{-- Grille Profil + Actions --}}
        <div class="minimal-grid">
            {{-- Profil --}}
            <div class="minimal-profile">
                <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="minimal-avatar">
                <h2 class="minimal-name">{{ $fullName }}</h2>
                <p class="minimal-role">Community Management</p>

                <div class="minimal-stats">
                    <div class="minimal-stat-item">
                        <span class="minimal-stat-label">Formations</span>
                        <span class="minimal-stat-value">{{ $formationsCount }}</span>
                    </div>
                    <div class="minimal-stat-item">
                        <span class="minimal-stat-label">TP Rendus</span>
                        <span class="minimal-stat-value">{{ $tpRendus }}/{{ $tpTotal }}</span>
                    </div>
                    <div class="minimal-stat-item">
                        <span class="minimal-stat-label">Évènements</span>
                        <span class="minimal-stat-value">{{ $evenementsCount }}</span>
                    </div>
                </div>
            </div>

            {{-- Grille Historique + Actualités --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                {{-- HISTORIQUE D'INSCRIPTION --}}
                <div class="minimal-info" style="padding: 2rem;">
                        <h3 style="font-size: 1.1rem; margin-bottom: 1.25rem;">📋 Historique</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            {{-- Statut Paiement --}}
                            <div style="padding: 1.75rem; background: {{ $remainingBalance > 0 ? 'linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%)' : 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)' }}; border-radius: 15px; text-align: center; border: 2px solid {{ $remainingBalance > 0 ? '#dc2743' : '#10b981' }};">
                                <div style="width: 65px; height: 65px; background: {{ $remainingBalance > 0 ? 'linear-gradient(135deg, #dc2743 0%, #cc2366 100%)' : 'linear-gradient(135deg, #10b981 0%, #059669 100%)' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                    <i class="fas {{ $remainingBalance > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }}" style="color: white; font-size: 2rem;"></i>
                                </div>
                                <h4 style="margin: 0 0 0.75rem; font-size: 1.2rem; font-weight: 700; color: {{ $remainingBalance > 0 ? '#dc2743' : '#10b981' }};">
                                    {{ $remainingBalance > 0 ? 'Reste à Payer' : 'À Jour' }}
                                </h4>
                                @if($remainingBalance > 0)
                                    <p style="margin: 0 0 0.5rem; font-size: 1.75rem; font-weight: 700; color: #dc2743;">
                                        {{ number_format($remainingBalance, 0, ',', ' ') }} FCFA
                                    </p>
                                    <small style="color: #666; font-size: 0.85rem;">Montant restant à régler</small>
                                @else
                                    <p style="margin: 0; font-size: 1rem; font-weight: 600; color: #10b981;">
                                        Tous les paiements sont à jour
                                    </p>
                                @endif
                            </div>

                            {{-- Informations d'inscription --}}
                            <div style="padding: 1.5rem; background: white; border-radius: 15px; border: 1px solid #e0e0e0;">
                                <div style="display: flex; flex-direction: column; gap: 1rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid #f0f0f0;">
                                        <span style="color: #666; font-size: 0.9rem;">Date d'inscription</span>
                                        <span style="font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">{{ $registrationDate }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid #f0f0f0;">
                                        <span style="color: #666; font-size: 0.9rem;">Formation</span>
                                        <span style="font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">Community Management</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid #f0f0f0;">
                                        <span style="color: #666; font-size: 0.9rem;">Niveau</span>
                                        <span style="font-weight: 600; color: #1a1a1a; font-size: 0.9rem;">{{ $educationLevel }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="color: #666; font-size: 0.9rem;">Statut</span>
                                        <span style="padding: 0.5rem 1rem; background: linear-gradient(135deg, #f09433 0%, #dc2743 100%); color: white; border-radius: 50px; font-size: 0.85rem; font-weight: 600;">Actif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                {{-- DERNIÈRES ACTUALITÉS EVC --}}
                <div class="minimal-info" style="padding: 2rem;">
                    <h3 style="font-size: 1.1rem; margin-bottom: 1.25rem;">📰 Actualités</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @php
                            // Icônes et couleurs par catégorie
                            $categoryStyles = [
                                'general' => ['icon' => 'fa-info-circle', 'color' => '#6c757d'],
                                'formation' => ['icon' => 'fa-graduation-cap', 'color' => '#f09433'],
                                'evenement' => ['icon' => 'fa-calendar-alt', 'color' => '#dc2743'],
                                'partenariat' => ['icon' => 'fa-handshake', 'color' => '#28a745'],
                                'succes' => ['icon' => 'fa-trophy', 'color' => '#ffc107'],
                            ];
                        @endphp

                        @forelse($actualites as $actualite)
                            @php
                                $style = $categoryStyles[$actualite->category] ?? $categoryStyles['general'];
                                $publishedDate = \Carbon\Carbon::parse($actualite->published_at);
                                $timeAgo = $publishedDate->diffForHumans();
                                $excerpt = \Illuminate\Support\Str::limit(strip_tags($actualite->content), 80);
                            @endphp
                            
                            <div style="padding: 1rem; background: white; border-radius: 12px; border-left: 4px solid {{ $style['color'] }}; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                                <div style="display: flex; gap: 1rem;">
                                    {{-- Miniature de l'image --}}
                                    @if($actualite->cover_image)
                                        <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 8px; overflow: hidden; background: #f5f5f5;">
                                            <img src="{{ asset('storage/' . $actualite->cover_image) }}" 
                                                 alt="{{ $actualite->title }}" 
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 8px; background: linear-gradient(135deg, {{ $style['color'] }}20, {{ $style['color'] }}40); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas {{ $style['icon'] }}" style="color: {{ $style['color'] }}; font-size: 1.5rem;"></i>
                                        </div>
                                    @endif
                                    
                                    {{-- Contenu --}}
                                    <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                                        {{-- Catégorie et date --}}
                                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                            <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.5rem; background: {{ $style['color'] }}15; color: {{ $style['color'] }}; border-radius: 4px; font-size: 0.7rem; font-weight: 600;">
                                                <i class="fas {{ $style['icon'] }}" style="font-size: 0.65rem;"></i>
                                                {{ ucfirst($actualite->category) }}
                                            </span>
                                            <small style="color: #999; font-size: 0.7rem;">{{ $timeAgo }}</small>
                                        </div>
                                        
                                        {{-- Titre --}}
                                        <h4 style="margin: 0; font-size: 0.9rem; font-weight: 600; color: #1a1a1a; line-height: 1.3;">
                                            {{ $actualite->title }}
                                        </h4>
                                        
                                        {{-- Extrait --}}
                                        <p style="margin: 0; font-size: 0.75rem; color: #666; line-height: 1.4;">
                                            {{ $excerpt }}
                                        </p>
                                        
                                        {{-- Bouton Lire la suite --}}
                                        <a href="{{ route(session('user_formation_raw', 'community-management') . '.actualites.show', $actualite->id) }}"
                                           style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.4rem 0.75rem; background: linear-gradient(135deg, {{ $style['color'] }}, {{ $style['color'] }}dd); color: white; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; align-self: flex-start; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
                                            Lire la suite
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="padding: 1.5rem; text-align: center; color: #999;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                <p style="margin: 0; font-size: 0.85rem;">Aucune actualité pour le moment</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions (pleine largeur) --}}
        <div class="minimal-actions">
                    <a href="{{ route('community-management.parametres.index') }}" class="minimal-action">
                        <i class="fas fa-user-edit"></i>
                        <span>Profil</span>
                    </a>
                    <a href="{{ route('community-management.formations.index') }}" class="minimal-action">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Formations</span>
                    </a>
                    <a href="{{ route('community-management.tp.index') }}" class="minimal-action">
                        <i class="fas fa-tasks"></i>
                        <span>TP</span>
                    </a>
                    <a href="{{ route('community-management.events.index') }}" class="minimal-action">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Évènements</span>
                    </a>
                    <a href="{{ route('community-management.documents.index') }}" class="minimal-action">
                        <i class="fas fa-folder-open"></i>
                        <span>Rapports</span>
                    </a>
                    <a href="{{ route('community-management.communaute.index') }}" class="minimal-action">
                        <i class="fas fa-users"></i>
                        <span>Communauté</span>
                    </a>
                    <a href="{{ route('community-management.cvtheque.index') }}" class="minimal-action">
                        <i class="fas fa-file-alt"></i>
                        <span>CVthèque</span>
                    </a>
                    <a href="{{ route('community-management.programme.index') }}" class="minimal-action">
                        <i class="fas fa-book-open"></i>
                        <span>Programme</span>
                    </a>
                    <a href="{{ route('community-management.paiements.index') }}" class="minimal-action">
                        <i class="fas fa-credit-card"></i>
                        <span>Paiement</span>
                    </a>
        </div>





    </div>
    {{-- Fin du container minimaliste --}}
</div>
@endsection

@section('scripts')
<script>
    // Effet de toile digitale animée avec particules connectées
    (function() {
        const canvas = document.getElementById('digitalCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let particles = [];
        let animationId;

        // Redimensionner le canvas
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        // Classe Particule
        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = (Math.random() - 0.5) * 0.5;
                this.radius = Math.random() * 2 + 1;

                // Couleurs Instagram
                const colors = ['#f09433', '#e6683c', '#dc2743', '#cc2366', '#bc1888'];
                this.color = colors[Math.floor(Math.random() * colors.length)];
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                // Rebondir sur les bords
                if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.color;
                ctx.fill();
            }
        }

        // Créer les particules
        function createParticles() {
            const particleCount = Math.min(Math.floor((canvas.width * canvas.height) / 15000), 100);
            particles = [];
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }

        // Dessiner les connexions entre particules proches
        function drawConnections() {
            const maxDistance = 150;

            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < maxDistance) {
                        const opacity = (1 - distance / maxDistance) * 0.3;

                        // Créer un dégradé pour la ligne
                        const gradient = ctx.createLinearGradient(
                            particles[i].x, particles[i].y,
                            particles[j].x, particles[j].y
                        );
                        gradient.addColorStop(0, particles[i].color);
                        gradient.addColorStop(1, particles[j].color);

                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.strokeStyle = gradient;
                        ctx.globalAlpha = opacity;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                        ctx.globalAlpha = 1;
                    }
                }
            }
        }

        // Animation principale
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Mettre à jour et dessiner les particules
            particles.forEach(particle => {
                particle.update();
                particle.draw();
            });

            // Dessiner les connexions
            drawConnections();

            animationId = requestAnimationFrame(animate);
        }

        // Initialisation
        resizeCanvas();
        createParticles();
        animate();

        // Redimensionner au changement de taille de fenêtre
        window.addEventListener('resize', () => {
            resizeCanvas();
            createParticles();
        });

        // Nettoyer à la destruction
        window.addEventListener('beforeunload', () => {
            if (animationId) {
                cancelAnimationFrame(animationId);
            }
        });
    })();
</script>
@endsection
