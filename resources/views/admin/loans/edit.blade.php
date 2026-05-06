<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le prêt - #{{ $loan->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-white min-h-screen">
    <div class="w-full h-16 bg-[#FF8C8C] mb-10 flex items-center px-6 md:px-12">
        <a href="{{ route('admin.loans.index') }}" class="text-black hover:opacity-70 transition-opacity">
            <img src="{{ asset('back.png') }}" alt="Retour" class="h-10 w-10 object-contain">
        </a>
    </div>
    <div class="container mx-auto px-6 max-w-2xl">
        <h1 class="text-5xl font-bold text-black mb-2">Modifier le prêt</h1>
        <p class="text-gray-400 text-lg mb-10">Ajustement de la période et du statut</p>
        @if ($errors->any())
            <div class="alert alert-error bg-red-100 border-none text-red-600 rounded-2xl mb-6 font-bold">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if($loan->anomaly)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-2xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('exclamation.png') }}" alt="Alerte" class="h-6 w-6 object-contain text-red-500">
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-bold">
                            Ce prêt est considéré comme <span class="underline">{{ $loan->anomaly }}</span>.
                            Veuillez régulariser le statut ou la date de retour réelle.
                        </p>
                    </div>
                </div>
            </div>
        @endif
        <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST" class="space-y-6 pb-20">
            @csrf
            @method('PUT')
            <div class="form-control w-full">
                <label class="label"><span class="label-text font-bold text-black text-xl">Objet concerné</span></label>
                <input type="text" value="{{ $loan->item->name }}"
                    class="input input-bordered w-full h-16 rounded-2xl bg-gray-100 cursor-not-allowed border-none text-lg font-bold text-gray-400"
                    readonly />
            </div>
            <div class="form-control w-full">
                <label class="label"><span class="label-text font-bold text-black text-xl">Emprunteur</span></label>
                <input type="text" value="{{ $loan->user->name }}"
                    class="input input-bordered w-full h-16 rounded-2xl bg-gray-100 cursor-not-allowed border-none text-lg font-bold text-gray-400"
                    readonly />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-black text-xl">Date de
                            début</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', $loan->start_date) }}"
                        class="input input-bordered w-full h-16 rounded-2xl bg-gray-50 border-none text-lg" required />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-black text-xl">Fin prévue</span></label>
                    <input type="date" name="end_date_planned"
                        value="{{ old('end_date_planned', $loan->end_date_planned) }}"
                        class="input input-bordered w-full h-16 rounded-2xl bg-gray-50 border-none text-lg" required />
                </div>
            </div>
            <div class="form-control w-full">
                <label class="label"><span class="label-text font-bold text-black text-xl">Lieu
                        d'utilisation</span></label>
                <input type="text" name="location" value="{{ old('location', $loan->location) }}"
                    class="input input-bordered w-full h-16 rounded-2xl bg-gray-50 border-none text-lg"
                    placeholder="Ex: BD12" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 rounded-[30px]">
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-black text-xl">Statut
                            actuel</span></label>
                    <select name="status"
                        class="select select-bordered w-full h-16 rounded-2xl bg-white border-none text-lg font-bold">
                        <option value="reserved" {{ old('status', $loan->status) == 'reserved' ? 'selected' : '' }}>
                            Réservé</option>
                        <option value="borrowed" {{ old('status', $loan->status) == 'borrowed' ? 'selected' : '' }}>
                            Emprunté</option>
                        <option value="returned" {{ old('status', $loan->status) == 'returned' ? 'selected' : '' }}>Rendu
                        </option>
                    </select>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-bold text-black text-xl">Date de retour réelle</span>
                    </label>
                    <input type="date" name="end_date"
                        value="{{ old('end_date', $loan->end_date ? \Carbon\Carbon::parse($loan->end_date)->format('Y-m-d') : '') }}"
                        class="input input-bordered w-full h-16 rounded-2xl bg-white border-none text-lg font-bold" />
                </div>
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