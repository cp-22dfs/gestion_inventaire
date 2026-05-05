<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-gray-50 min-h-screen">

    <header class="bg-[#89d2ff] shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-8 flex justify-between items-center">
            <h1 class="text-2xl md:text-4xl font-black text-black">
                Bonjour {{ Auth::user()->name }}
            </h1>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:scale-110 transition-transform p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-8 h-8 md:w-10 md:h-10 text-black">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold text-black text-center md:text-left mb-8">État actuel du stock</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($items as $item)
                <a href="{{ route('items.show', $item->id) }}"
                    class="block hover:opacity-80 transition-opacity no-underline text-current">
                    <div
                        class="flex items-center bg-gray-200 rounded-lg overflow-hidden h-16 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex-grow pl-6 py-2">
                            <p class="text-black font-bold text-lg leading-tight">{{ $item->name }}</p>
                            <p class="text-gray-400 text-xs font-mono uppercase">{{ $item->serial_number }}</p>
                        </div>

                        <div class="w-16 h-full {{ $item->loans_exists ? 'bg-[#FF8C8C]' : 'bg-[#4ade80]' }}">
                        </div>
                    </div>
            @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-gray-400 italic">Aucun objet en stock.</p>
                    </div>
                @endforelse
            </a>
        </div>

        <div class="h-32"></div>
    </main>

    <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 w-full max-w-xs px-6">
        <button
            class="btn btn-lg rounded-full border-none font-black bg-[#89d2ff] hover:bg-[#70c4f5] normal-case w-full h-16 text-xl text-black">
            Scanner
        </button>
    </div>
</body>

</html>