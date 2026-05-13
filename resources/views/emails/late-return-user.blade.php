<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
</head>

<body style="font-family: sans-serif; color: #333; padding: 20px;">
    <h2>Rappel de retour</h2>
    <p>Bonjour {{ $loan->user->name }},</p>
    <p>
        Vous n'avez pas encore rendu l'objet <strong>{{ $loan->item->name }}</strong>
        qui était prévu pour le <strong>{{ \Carbon\Carbon::parse($loan->end_date_planned)->format('d.m.Y') }}</strong>.
    </p>
    <p>Merci de le restituer dès que possible.</p>
    <br>
    <p>Cordialement,<br>L'équipe ICH-OIC</p>
</body>

</html>