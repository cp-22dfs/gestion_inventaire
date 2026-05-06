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
            <img src="{{ asset('back.png') }}" alt="Retour" class="h-10 w-10 object-contain">
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
                            <img src="{{ asset('home.png') }}" alt="Localisation"
                                class="h-8 w-8 object-contain text-black">
                        </div>
                        <span
                            class="text-2xl md:text-3xl font-medium text-black">{{ $item->location ?? 'Non défini' }}</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <img src="{{ asset('boxes.png') }}" alt="Objet" class="h-10 w-10 object-contain">
                        </div>
                        <span class="text-2xl md:text-3xl font-medium text-black">Libre</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <img src="{{ asset('user.png') }}" alt="Utilisateur" class="h-7 w-7 object-contain">
                        </div>
                        <span class="text-2xl md:text-3xl font-medium text-gray-300">Aucun emprunteur</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-6 lg:mt-12">
                <h2 class="text-2xl font-bold text-black flex items-center gap-3">
                    <span class="w-2 h-8 bg-[#89CFF0] rounded-full"></span>
                    Historique
                </h2>
                <button onclick="booking_modal.showModal()"
                    class="btn btn-lg w-full rounded-full border-none bg-[#89CFF0] hover:bg-[#7bc4e6] text-black text-3xl font-bold normal-case h-20 shadow-lg transition-transform active:scale-95">
                    Réserver
                </button>
            </div>
        </div>
    </div>
    <dialog id="booking_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-white rounded-t-3xl sm:rounded-3xl">
            <h3 class="font-bold text-3xl text-black mb-6">Réserver l'objet</h3>
            <form action="{{ route('loan.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-black text-lg">Date de
                                début</span></label>
                        <input type="date" name="start_date"
                            class="input input-bordered h-14 rounded-xl bg-gray-50 border-none text-lg" required
                            min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-black text-lg">Date de fin
                                prévue</span></label>
                        <input type="date" name="end_date_planned"
                            class="input input-bordered h-14 rounded-xl bg-gray-50 border-none text-lg" required>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-black text-lg">Lieu
                            d'utilisation</span></label>
                    <input type="text" name="location" placeholder="ex: Bureau 204"
                        class="input input-bordered h-14 rounded-xl bg-gray-50 border-none text-lg">
                </div>
                <div class="modal-action flex flex-col gap-3">
                    <button type="submit"
                        class="btn btn-lg w-full rounded-full border-none bg-[#89CFF0] text-black font-bold h-16 shadow-lg">
                        Confirmer la réservation
                    </button>
                    <button type="button" onclick="booking_modal.close()"
                        class="btn btn-ghost w-full text-gray-400 font-bold">Annuler</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</body>

</html>