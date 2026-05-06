<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-gray-50 min-h-screen">
    @include('partials.admin-nav')
    <main class="max-w-7xl mx-auto p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
            <h2 class="text-2xl font-bold text-black text-center md:text-left">État actuel du stock</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($items as $item)
                <a href="{{ route('admin.items.show', $item->id) }}" class="block hover:opacity-80 transition-opacity">
                    <div
                        class="flex items-center bg-white border border-gray-200 rounded-lg overflow-hidden h-16 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex-grow pl-6 py-2">
                            <p class="text-black font-bold text-lg leading-tight">{{ $item->name }}</p>
                            <p class="text-gray-400 text-xs font-mono uppercase">{{ $item->serial_number }}</p>
                        </div>
                        <div
                            class="w-16 h-full flex items-center justify-center {{ $item->isCurrentlyOccupied() ? 'bg-[#FF8C8C]' : 'bg-[#4ade80]' }}">
                        </div>
                    </div>
            @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-gray-400 italic">Aucun objet dans l'inventaire.</p>
                    </div>
                @endforelse
        </div>
    </main>
    <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 w-full max-w-xs px-6">
        <a href="{{ route('admin.items.create') }}"
            class="btn btn-lg rounded-full border-none text-black font-bold bg-[#FF8C8C] hover:bg-[#f77474] normal-case w-full h-16 md:h-20 text-xl">
            Ajouter un objet
        </a>
    </div>
</body>

</html>