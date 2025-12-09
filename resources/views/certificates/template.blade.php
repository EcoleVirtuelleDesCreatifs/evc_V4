<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Formation</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .certificate-container {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: white;
            border: 20px solid transparent;
            border-image: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%) 1;
            box-sizing: border-box;
        }

        .certificate-inner {
            padding: 40px 60px;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header {
            margin-bottom: 20px;
        }

        .logo {
            font-size: 48px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .certificate-title {
            font-size: 56px;
            font-weight: bold;
            color: #2d3748;
            margin: 20px 0;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .decoration-line {
            width: 200px;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            margin: 20px auto;
            border-radius: 2px;
        }

        .certificate-text {
            font-size: 22px;
            color: #4a5568;
            margin: 20px 0;
            line-height: 1.6;
        }

        .student-name {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            margin: 30px 0;
            text-transform: capitalize;
            letter-spacing: 1px;
        }

        .formation-name {
            font-size: 32px;
            color: #764ba2;
            font-weight: 600;
            margin: 20px 0;
        }

        .completion-text {
            font-size: 18px;
            color: #718096;
            margin: 15px 0;
        }

        .footer {
            margin-top: 40px;
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .signature-block {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            width: 180px;
            height: 2px;
            background: #cbd5e0;
            margin: 10px auto;
        }

        .signature-title {
            font-size: 14px;
            color: #4a5568;
            font-weight: 600;
            margin-top: 5px;
        }

        .certificate-id {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 20px;
        }

        .seal {
            position: absolute;
            bottom: 40px;
            right: 60px;
            width: 100px;
            height: 100px;
            border: 3px solid #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #667eea;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            transform: rotate(-15deg);
        }

        .date {
            font-size: 16px;
            color: #718096;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-inner">
            <!-- Header -->
            <div class="header">
                <div class="logo">EVC</div>
                <div style="font-size: 18px; color: #718096; font-weight: 600;">École Virtuelle des Créatifs</div>
            </div>

            <!-- Title -->
            <div>
                <div class="certificate-title">Certificat</div>
                <div class="decoration-line"></div>
                <div class="certificate-text">Ce certificat est décerné à</div>

                <!-- Student Name -->
                <div class="student-name">
                    {{ $student->first_name }} {{ $student->last_name }}
                </div>

                <!-- Formation -->
                <div class="certificate-text">
                    Pour avoir complété avec succès la formation en
                </div>
                <div class="formation-name">{{ $formation }}</div>

                <div class="completion-text">
                    Cette certification atteste de l'acquisition des compétences<br>
                    et des connaissances requises dans ce domaine.
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="date">Délivré le {{ $date }}</div>

                <div class="signatures">
                    <div class="signature-block">
                        <div class="signature-line"></div>
                        <div class="signature-title">Le Directeur</div>
                    </div>
                    <div class="signature-block">
                        <div class="signature-line"></div>
                        <div class="signature-title">Le Formateur Principal</div>
                    </div>
                </div>

                <div class="certificate-id">
                    N° {{ $student_id }} | {{ date('Y') }}
                </div>
            </div>
        </div>

        <!-- Seal -->
        <div class="seal">
            EVC<br>CERTIFIÉ
        </div>
    </div>
</body>
</html>
