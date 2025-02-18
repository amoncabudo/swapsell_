<!DOCTYPE html>
<html>
<head>
    <title>Contacte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Informació de contacte</h2>
            <img src="https://swapsell.cat/images/logo.png" alt="Logo" style="width: 80px; height: 80px;">
        </div>
        <div class="content">
            <p><strong>Nom i Cognom:</strong> {{ $data['name'] }} {{ $data['cognom'] }}</p>
            <p><strong>Email:</strong> {{ $data['email'] }}</p>
            <p><strong>Assumpte:</strong> {{ $data['subject'] }}</p>
            <p><strong>Missatge:</strong></p>
            <p>{{ $data['message'] }}</p>
        </div>
        <div class="footer">
            <p style="color: #777;">Gràcies per contactar amb nosaltres!</p>
        </div>
    </div>
</body>
</html>