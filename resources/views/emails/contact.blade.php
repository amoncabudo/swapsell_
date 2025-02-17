<!DOCTYPE html>
<html>
<head>
    <title>Contacte</title>
</head>
<body>
    <h4>Informació de contacte</h4>
    <div style="display: flex; justify-content: space-between;">
        <p><strong>Nom:</strong> {{ $data['name'] }}</p>
        <p style="margin-left: 10px;"><strong>Email:</strong> {{ $data['email'] }}</p>
    </div>

    <p><strong>Assumpte:</strong> {{ $data['subject'] }}</p> 
    <p><strong>Missatge:</strong></p> <p>{{ $data['message'] }}</p>
</body>
</html>