<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire de Stock</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <style>
        .clip-diagonal {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%);
        }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col">
    <div class="relative w-full h-[45vh] overflow-hidden">
        <img src="{{ asset('stock.png') }}" alt="Entrepôt" class="w-full h-full object-cover clip-diagonal">
    </div>
    <div class="flex-1 flex flex-col items-center justify-start pt-8 px-6 text-center">
        <h1 class="text-5xl md:text-5xl font-black text-black leading-tight">
            Gestionnaire de Stock
        </h1>
        <p class="text-xl text-gray-400 mt-2 mb-12">Login</p>
        <div class="flex flex-col gap-6 w-full max-w-xs mx-auto">
            <a href="{{ route('login', ['role' => 'utilisateur']) }}"
                class="btn btn-lg rounded-full border-none text-black text-2xl h-20 bg-[#87CEFA] hover:bg-[#70c2f5] normal-case shadow-md font-bold">
                Utilisateur
            </a>
            <a href="{{ route('login', ['role' => 'admin']) }}"
                class="btn btn-lg rounded-full border-none text-black text-2xl h-20 bg-[#FF8C8C] hover:bg-[#f77474] normal-case shadow-md font-bold">
                Admin
            </a>
        </div>
    </div>
</body>

</html>