<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Sécurisé - École Virtuelle des Créatifs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(41, 128, 185, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(52, 152, 219, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .payment-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .payment-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.5),
                        0 0 60px rgba(52, 152, 219, 0.2);
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
            position: relative;
            z-index: 1;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .payment-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #3498db 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .payment-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .payment-header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .payment-header p {
            position: relative;
            z-index: 1;
            font-weight: 500;
            opacity: 0.95;
        }

        .header-icon {
            background: rgba(255,255,255,0.15);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255,255,255,0.2);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .payment-body {
            padding: 40px 30px;
        }

        .summary-box {
            background: linear-gradient(135deg, #f7f9fc 0%, #eef2f7 100%);
            border-left: 5px solid #3498db;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid rgba(52, 152, 219, 0.1);
        }

        .summary-box h5 {
            color: #1e3c72;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-row:last-child {
            border-bottom: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 3px solid #3498db;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.05) 0%, rgba(41, 128, 185, 0.05) 100%);
            margin-left: -10px;
            margin-right: -10px;
            padding-left: 10px;
            padding-right: 10px;
            border-radius: 8px;
        }

        .summary-label {
            color: #6c757d;
            font-weight: 500;
        }

        .summary-value {
            font-weight: 600;
            color: #212529;
        }

        .amount-total {
            font-size: 2.2rem;
            background: linear-gradient(135deg, #1e3c72 0%, #3498db 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            text-shadow: 0 2px 10px rgba(52, 152, 219, 0.2);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
            transform: translateY(-2px);
        }

        .btn-pay {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #3498db 100%);
            border: none;
            color: white;
            padding: 18px 50px;
            border-radius: 50px;
            font-size: 19px;
            font-weight: 700;
            width: 100%;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 30px;
            box-shadow: 0 10px 30px rgba(30, 60, 114, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-pay::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-pay:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-pay:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 15px 40px rgba(30, 60, 114, 0.5);
        }

        .btn-pay:active {
            transform: translateY(-2px) scale(0.98);
        }

        .info-box {
            background: linear-gradient(135deg, #e8f4fd 0%, #d6eaf8 100%);
            border-left: 5px solid #2980b9;
            padding: 20px;
            border-radius: 12px;
            margin-top: 25px;
            border: 1px solid rgba(41, 128, 185, 0.2);
            box-shadow: 0 4px 15px rgba(41, 128, 185, 0.1);
        }

        .info-box strong {
            color: #1e3c72;
            font-weight: 700;
        }

        .security-badges {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .security-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 50px;
            font-size: 0.95rem;
            color: #495057;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }

        .security-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .security-badge i {
            font-size: 1.1rem;
        }

        .payment-methods {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .payment-method-icon {
            width: 70px;
            height: 50px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #e1e5e9;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .payment-method-icon:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.2);
            border-color: #3498db;
        }

        .payment-method-icon i {
            font-size: 1.5rem;
        }

        @media (max-width: 768px) {
            .payment-body {
                padding: 30px 20px;
            }

            .summary-box {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-card">
            <!-- Header -->
            <div class="payment-header">
                <div class="header-icon">
                    <i class="fas fa-graduation-cap fa-3x"></i>
                </div>
                <h1>🎓 Concrétisez Votre Avenir Créatif</h1>
                <p class="mb-0">✅ Paiement 100% Sécurisé • Certifié CinetPay</p>
            </div>

            <!-- Body -->
            <div class="payment-body">
                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Récapitulatif -->
                <div class="summary-box">
                    <h5 class="mb-3"><i class="fas fa-file-invoice me-2"></i>Récapitulatif</h5>

                    <div class="summary-row">
                        <span class="summary-label">Candidat :</span>
                        <span class="summary-value">{{ $candidate->prenom }} {{ $candidate->nom }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Email :</span>
                        <span class="summary-value">{{ $candidate->email }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Formation :</span>
                        <span class="summary-value">{{ $candidate->choix_formation }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Référence :</span>
                        <span class="summary-value"><code>{{ $payment->payment_reference }}</code></span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Montant à payer :</span>
                        <span class="amount-total">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</span>
                    </div>
                </div>

                <!-- Moyens de paiement acceptés -->
                <div class="text-center mb-4">
                    <h6 class="mb-3" style="color: #1e3c72; font-weight: 700; font-size: 1.1rem;">
                        💳 Payez Simplement & En Toute Sécurité
                    </h6>
                    <div class="payment-methods">
                        <div class="payment-method-icon" title="Orange Money">
                            <i class="fas fa-mobile-alt" style="color: #FF6600;"></i>
                        </div>
                        <div class="payment-method-icon" title="MTN Money">
                            <i class="fas fa-mobile-alt" style="color: #FFCC00;"></i>
                        </div>
                        <div class="payment-method-icon" title="Moov Money">
                            <i class="fas fa-mobile-alt" style="color: #009ACD;"></i>
                        </div>
                        <div class="payment-method-icon" title="Wave">
                            <i class="fas fa-mobile-alt" style="color: #00A6FB;"></i>
                        </div>
                        <div class="payment-method-icon" title="Carte bancaire">
                            <i class="fas fa-credit-card" style="color: #667eea;"></i>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de paiement -->
                <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                    @csrf
                    <input type="hidden" name="payment_reference" value="{{ $payment->payment_reference }}">

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-phone me-2"></i>Numéro de téléphone pour Mobile Money / Wallet
                        </label>
                        <input type="tel" name="phone_number" class="form-control" required
                               placeholder="Ex: 0758123456 ou +225758123456"
                               value="{{ $candidate->whatsapp ?? '' }}"
                               pattern="[+0-9\s]{10,20}"
                               id="phoneInput">
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1" style="color: #3498db;"></i>
                            <strong>Formats acceptés :</strong><br>
                            • Format ivoirien : 0758123456 ou 07 58 12 34 56<br>
                            • Format international : +225758123456 ou +225 7 58 12 34 56<br>
                            • Utilisé pour Wave, Orange Money, MTN, Moov Money
                        </small>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-rocket me-2" style="color: #3498db;"></i>
                        <small>
                            <strong>🎯 Votre parcours en 4 étapes simples :</strong><br>
                            1️⃣ Cliquez sur "Procéder au Paiement"<br>
                            2️⃣ Choisissez votre moyen de paiement (Mobile Money, Wallet, Carte...)<br>
                            3️⃣ Validez votre paiement en toute confiance<br>
                            4️⃣ Recevez instantanément votre confirmation par email 📧
                        </small>
                    </div>

                    <button type="submit" class="btn-pay" id="btnPay">
                        <i class="fas fa-lock me-2"></i>
                        💳 Procéder au Paiement Sécurisé
                    </button>
                </form>

                @if(app()->environment('local'))
                    <!-- Bouton Test (uniquement en développement) -->
                    <div class="mt-4 p-4" style="background: linear-gradient(135deg, #fff3cd 0%, #fff9e6 100%); border-left: 5px solid #ffc107; border-radius: 12px;">
                        <h6 class="mb-3" style="color: #856404; font-weight: 700;">
                            <i class="fas fa-flask me-2"></i>🧪 Mode Développement
                        </h6>
                        <p class="mb-3" style="color: #856404; font-size: 14px;">
                            Ce bouton simule un paiement réussi sans passer par CinetPay.<br>
                            <strong>Utilisation :</strong> Test du workflow complet (webhook, email, création de compte)
                        </p>
                        <form action="{{ route('payment.test.success') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_reference" value="{{ $payment->payment_reference }}">
                            <button type="submit" class="btn btn-warning w-100" style="border-radius: 30px; font-weight: 600;">
                                <i class="fas fa-check-circle me-2"></i>
                                ⚡ Simuler Paiement Réussi (TEST)
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Badges de sécurité -->
                <div class="security-badges">
                    <div class="security-badge">
                        <i class="fas fa-shield-alt text-success"></i>
                        <span>Paiement 100% sécurisé</span>
                    </div>
                    <div class="security-badge">
                        <i class="fas fa-lock text-primary"></i>
                        <span>Données cryptées</span>
                    </div>
                    <div class="security-badge">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>CinetPay certifié</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Afficher un indicateur visuel du format pendant la saisie
        const phoneInput = document.getElementById('phoneInput');

        phoneInput.addEventListener('input', function(e) {
            const value = e.target.value.trim();
            const digitsOnly = value.replace(/[^0-9]/g, '');

            // Feedback visuel
            if (digitsOnly.length >= 10) {
                phoneInput.style.borderColor = '#28a745'; // Vert
                phoneInput.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
            } else if (digitsOnly.length > 0) {
                phoneInput.style.borderColor = '#ffc107'; // Jaune
                phoneInput.style.boxShadow = '0 0 0 0.2rem rgba(255, 193, 7, 0.25)';
            } else {
                phoneInput.style.borderColor = '#e2e8f0';
                phoneInput.style.boxShadow = 'none';
            }
        });

        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const phoneValue = phoneInput.value.trim();
            const digitsOnly = phoneValue.replace(/[^0-9]/g, '');

            // Validation du numéro
            if (digitsOnly.length < 8) {
                e.preventDefault();
                alert('⚠️ Veuillez entrer un numéro de téléphone valide (minimum 8 chiffres)\n\nExemples:\n• 0758123456\n• +225758123456');
                phoneInput.focus();
                phoneInput.style.borderColor = '#dc3545';
                return false;
            }

            // Afficher le spinner
            const btn = document.getElementById('btnPay');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Redirection vers CinetPay...';
        });
    </script>
</body>
</html>
