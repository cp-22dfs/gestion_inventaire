<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-white min-h-screen">
    <div class="w-full h-16 bg-[#89CFF0] mb-10 flex items-center px-6 md:px-12">
        <a href="{{ route('user.dashboard') }}" class="hover:opacity-70 transition-opacity">
            <img src="{{ asset('back.png') }}" alt="Retour" class="h-10 w-10 object-contain">
        </a>
    </div>

    <div class="flex flex-col items-center justify-center px-8 mt-20 gap-6">
        <h1 class="text-2xl font-black text-black text-center">Veuillez scanner votre objet</h1>

        <form action="{{ route('scan.post') }}" method="POST" class="w-full max-w-sm flex flex-col gap-4">
            @csrf
            <input type="text" name="serial_number" placeholder="Numéro de série" autofocus
                class="input bg-gray-100 border-none rounded-full px-6 h-14 w-full text-center focus:ring-2 focus:ring-[#89CFF0] @error('serial_number') ring-2 ring-red-400 @enderror">
            @error('serial_number')
                <p class="text-red-500 text-sm text-center font-medium">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="btn btn-lg w-full rounded-full border-none bg-[#89CFF0] hover:bg-[#7bc4e6] text-black font-bold text-xl normal-case h-16 shadow-lg">
                Rechercher
            </button>
        </form>
    </div>
</body>

</html>