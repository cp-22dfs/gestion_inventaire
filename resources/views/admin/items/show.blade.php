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
    <div class="w-full h-16 bg-[#FF8C8C] mb-10 flex items-center px-6 md:px-12">
        <a href="{{ route('admin.dashboard') }}" class="text-black hover:opacity-70 transition-opacity">
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
                <p class="text-gray-400 text-lg mt-1 mb-6">Détails de l'objet</p>
                <div class="flex gap-4 mb-10">
                    <a href="{{ route('admin.items.edit', $item->id) }}"
                        class="flex items-center justify-center w-14 h-14 bg-gray-100 rounded-2xl hover:bg-yellow-50 text-gray-500 hover:text-yellow-500 transition-all shadow-sm group">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST"
                        onsubmit="return confirm('Es-tu sûr de vouloir supprimer cet objet définitivement ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center justify-center w-14 h-14 bg-gray-100 rounded-2xl hover:bg-red-50 text-gray-500 hover:text-red-500 transition-all shadow-sm group">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 transition-transform group-hover:scale-110" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
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
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-2xl md:text-3xl font-medium text-gray-300">Aucun historique</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center lg:items-end gap-8 pt-10 lg:pt-20">

                @if($item->qr_code)
                    <div class="p-4 border-4 border-black rounded-3xl bg-white shadow-sm">
                        <img src="{{ asset('storage/' . $item->qr_code) }}" alt="QR Code" class="w-64 h-64 md:w-80 md:h-80">
                    </div>

                    <a href="{{ asset('storage/' . $item->qr_code) }}" download="QR_{{ $item->name }}"
                        class="btn btn-lg w-full max-w-sm rounded-full border-none bg-[#FF8C8C] hover:bg-[#f77474] text-black text-xl font-bold normal-case h-20 shadow-lg">
                        Télécharger QR code
                    </a>
                @else
                    <form action="{{ route('admin.items.qr', $item->id) }}" method="POST"
                        class="w-full flex flex-col items-center lg:items-end">
                        @csrf
                        <button type="submit" class="group w-full max-w-sm flex flex-col items-center">
                            <div
                                class="w-64 h-64 md:w-80 md:h-80 bg-gray-50 border-4 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center group-hover:border-[#FF8C8C] group-hover:bg-[#fffafa] transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-20 w-20 text-gray-300 group-hover:text-[#FF8C8C] transition-colors" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <p
                                    class="text-gray-400 font-bold group-hover:text-[#FF8C8C] transition-colors mt-4 text-center px-4">
                                    Cliquer pour générer <br>le QR Code
                                </p>
                            </div>
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>

</body>

</html>