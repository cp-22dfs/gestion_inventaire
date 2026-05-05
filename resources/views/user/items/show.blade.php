<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails - {{ $item->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-white min-h-screen">
    <div class="w-full h-16 bg-[#89CFF0] mb-10 flex items-center px-6 md:px-12">
        <a href="{{ route('user.dashboard') }}" class="text-black hover:opacity-70 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>

    <div class="container mx-auto px-6 max-w-6xl pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            <div class="flex flex-col">
                <h1 class="text-5xl md:text-6xl font-bold text-black">{{ $item->name }}</h1>
                <p class="text-gray-400 text-lg mt-1 mb-10">Détails de l'objet</p>

                <div class="space-y-6 md:space-y-8">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span
                            class="text-2xl md:text-3xl font-medium text-black">{{ $item->location ?? 'Non défini' }}</span>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="text-2xl md:text-3xl font-medium text-black">Libre</span>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-2xl md:text-3xl font-medium text-gray-300">Aucun emprunteur</span>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-2xl md:text-3xl font-medium text-gray-300">Aucune date de prévue</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6 mt-12">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-black flex items-center gap-3">
                        <span class="w-2 h-8 bg-[#89CFF0] rounded-full"></span>
                        Historique
                    </h2>
                </div>

                <div class="space-y-4">
                    <div
                        class="flex items-center justify-between p-5 bg-gray-50 rounded-3xl border border-transparent hover:border-gray-100 transition-all">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-[#89CFF0]/20 text-[#5da9ce] rounded-full flex items-center justify-center font-bold">
                                DS
                            </div>
                            <div>
                                <p class="text-black font-bold text-lg leading-none">Diogo Soares</p>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-black font-semibold">01.01.26 — 05.01.26</p>
                            <span
                                class="badge badge-sm bg-green-100 text-green-600 border-none font-bold py-3 px-3 mt-1">Rendu</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-5 bg-gray-50 rounded-3xl border border-transparent">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-bold">
                                JD
                            </div>
                            <div>
                                <p class="text-black font-bold text-lg leading-none">Jean Dupont</p>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-black font-semibold">12.03.26 — 15.03.26</p>
                            <span
                                class="badge badge-sm bg-blue-100 text-blue-600 border-none font-bold py-3 px-3 mt-1">À
                                venir</span>
                        </div>
                    </div>
                </div>
            </div>
            <button
                class="btn btn-lg w-full rounded-full border-none bg-[#89CFF0] hover:bg-[#7bc4e6] text-black text-3xl font-bold normal-case h-20 shadow-lg transition-transform active:scale-95">
                Réserver
            </button>
        </div>
    </div>
    </div>

</body>

</html>