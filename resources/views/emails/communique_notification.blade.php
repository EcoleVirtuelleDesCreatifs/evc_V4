<!DOCTYPE html>
<html>
<head>
    <title>Flash Info EVC</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(to right, #c2410c, #f97316);
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .message-box {
            background-color: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 15px;
            margin: 20px 0;
            font-weight: bold;
            color: #7c2d12;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666666;
            margin-top: 20px;
            border-top: 1px solid #eeeeee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>FLASH INFO EVC</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Une nouvelle information importante est disponible :</p>

            <div class="message-box">
                {{ $communique->content }}
            </div>

            <p>Restez connectés pour ne rien manquer de l'actualité de l'école.</p>

            <p>Cordialement,<br>L'équipe EVC</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} École Virtuelle des Créatifs. Tous droits réservés.
        </div>
    </div>
</body>
</html>
