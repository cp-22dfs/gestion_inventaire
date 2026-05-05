<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier - {{ $item->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-white min-h-screen">

    <div class="w-full h-16 bg-[#FF8C8C] mb-10 flex items-center px-6 md:px-12">
        <a href="{{ route('admin.items.show', $item->id) }}" class="text-black hover:opacity-70 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>

    <div class="container mx-auto px-6 max-w-2xl">
        <h1 class="text-5xl font-bold text-black mb-2">Modifier l'objet</h1>
        <p class="text-gray-400 text-lg mb-10">Mise à jour des informations</p>

        <form action="{{ route('admin.items.update', $item->id) }}" method="POST" class="space-y-6 pb-20">
            @csrf
            @method('PUT')

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-bold text-black text-xl">Nom</span></label>
                <input type="text" name="name" value="{{ old('name', $item->name) }}"
                    class="input input-bordered w-full h-16 rounded-2xl bg-gray-50 border-none text-lg" required />
            </div>

            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-bold text-black text-xl">Numéro de série</span>
                    <span class="label-text-alt text-gray-400 italic font-medium">Non modifiable</span>
                </label>
                <input type="text" name="serial_number" value="{{ $item->serial_number }}"
                    class="input input-bordered w-full h-16 rounded-2xl bg-gray-200 cursor-not-allowed opacity-70 text-lg"
                    readonly />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-bold text-black text-xl">Localisation</span></label>
                <input type="text" name="location" value="{{ old('location', $item->location) }}"
                    class="input input-bordered w-full h-16 rounded-2xl bg-gray-50 border-none text-lg" />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-bold text-black text-xl">Description</span></label>
                <textarea name="description"
                    class="textarea textarea-bordered w-full h-32 rounded-2xl bg-gray-50 border-none text-lg">{{ old('description', $item->description) }}</textarea>
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="btn btn-lg w-full rounded-full border-none bg-[#FF8C8C] hover:bg-[#f77474] text-black text-2xl font-bold normal-case h-20 shadow-lg transition-transform active:scale-95">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

</body>

</html>