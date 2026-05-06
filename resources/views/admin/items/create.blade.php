<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel objet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-white min-h-screen">
    <header class="bg-[#FF8C8C] h-20 w-full flex items-center px-6 relative shadow-sm">
        <a href="{{ route('admin.dashboard') }}" class="hover:opacity-70 transition-opacity">
            <img src="{{ asset('back.png') }}" alt="Retour" class="w-8 h-8 object-contain">
        </a>
    </header>
    <main class="max-w-2xl mx-auto p-8">
        <h1 class="text-4xl font-black text-black mb-10">Nouvel objet</h1>
        <form method="POST" action="{{ route('admin.items.store') }}" class="space-y-6"
            onkeydown="return event.key != 'Enter';">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-control w-full">
                    <input type="text" name="name" placeholder="Nom" value="{{ old('name') }}"
                        class="input bg-gray-200 border-none rounded-full px-6 focus:ring-2 focus:ring-[#FF8C8C] placeholder-gray-500 @error('name') ring-2 ring-[#FF8C8C] @enderror">
                    @error('name')
                        <span class="text-[#FF8C8C] text-xs mt-2 ml-4 font-bold italic">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control w-full">
                    <input type="text" name="serial_number" placeholder="Numéro de série"
                        value="{{ old('serial_number') }}"
                        class="input bg-gray-200 border-none rounded-full px-6 focus:ring-2 focus:ring-[#FF8C8C] placeholder-gray-500 @error('serial_number') ring-2 ring-[#FF8C8C] @enderror">
                    @error('serial_number')
                        <span class="text-[#FF8C8C] text-xs mt-2 ml-4 font-bold italic">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-control">
                <input type="text" name="manufacturer" placeholder="Fabricant" value="{{ old('manufacturer') }}"
                    class="input bg-gray-200 border-none rounded-full px-6 focus:ring-2 focus:ring-[#FF8C8C] placeholder-gray-500 @error('manufacturer') ring-2 ring-[#FF8C8C] @enderror">
                @error('manufacturer')
                    <span class="text-[#FF8C8C] text-xs mt-2 ml-4 font-bold italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-control">
                <input type="text" name="location" placeholder="Lieu de stockage (ex: Armoire A, Local 102)"
                    value="{{ old('location') }}"
                    class="input bg-gray-200 border-none rounded-full px-6 focus:ring-2 focus:ring-[#FF8C8C] placeholder-gray-500 @error('location') ring-2 ring-[#FF8C8C] @enderror">
                @error('location')
                    <span class="text-[#FF8C8C] text-xs mt-2 ml-4 font-bold italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-control">
                <textarea name="description" placeholder="Description"
                    class="textarea bg-gray-200 border-none rounded-2xl px-6 py-3 focus:ring-2 focus:ring-[#FF8C8C] placeholder-gray-500 min-h-[100px] @error('description') ring-2 ring-[#FF8C8C] @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-[#FF8C8C] text-xs mt-2 ml-4 font-bold italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="pt-6">
                <button type="submit"
                    class="btn btn-lg w-full rounded-full border-none text-black text-xl font-black bg-[#FF8C8C] hover:bg-[#f77474] normal-case shadow-lg transition-all active:scale-95">
                    Ajouter
                </button>
            </div>
        </form>
    </main>
</body>

</html>