<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
</head>

<body style="font-family: sans-serif; color: #333; padding: 20px;">
    <h2>Objet en retard</h2>
    <p>Bonjour,</p>
    <p>
        L'objet <strong>{{ $loan->item->name }}</strong> emprunté par
        <strong>{{ $loan->user->name }} {{ $loan->user->surname }}</strong>
        aurait dû être rendu le <strong>{{ \Carbon\Carbon::parse($loan->end_date_planned)->format('d.m.Y') }}</strong>
        et n'a pas encore été restitué.
    </p>
    <br>
    <p>Cordialement,<br>Système ICH-OIC</p>
</body>

</html>