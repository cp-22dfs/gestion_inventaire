<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Que voulez-vous faire ?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-white min-h-screen">
    <div class="w-full h-16 bg-[#89CFF0] mb-10 flex items-center px-6">
        <a href="{{ route('scan') }}" class="hover:opacity-70 transition-opacity">
            <img src="{{ asset('back.png') }}" alt="Retour" class="h-10 w-10 object-contain">
        </a>
    </div>

    <div class="flex flex-col items-center px-8 gap-10 mt-10">
        <h1 class="text-3xl font-black text-black text-center">{{ $item->name }}</h1>
        <p class="text-gray-400 font-medium text-center">Que souhaitez-vous faire avec cet objet ?</p>

        <div class="flex flex-col gap-4 w-full max-w-sm">
            <a href="{{ route('borrow.show', $item->id) }}"
                class="btn btn-lg w-full rounded-full border-none bg-[#89CFF0] hover:bg-[#7bc4e6] text-black font-bold text-xl normal-case h-16 shadow-lg">
                Emprunter
            </a>
            <a href="{{ route('items.show', $item->id) }}?reserve=1"
                class="btn btn-lg w-full rounded-full border-none bg-[#89CFF0] hover:bg-[#7bc4e6] text-black font-bold text-xl normal-case h-16 shadow-lg border-2 border-[#7bc4e6]">
                Réserver
            </a>
        </div>
    </div>
</body>

</html>