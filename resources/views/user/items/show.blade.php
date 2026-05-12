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
                @if($item->description)
                    <p class="text-gray-400 text-xl mt-2 max-w-md font-medium">{{ $item->description }}</p>
                @endif
                <br>

                @php $currentLoan = $item->currentLoan(); @endphp

                <div class="space-y-6 md:space-y-8">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <img src="{{ asset('home.png') }}" alt="Localisation" class="h-8 w-8 object-contain">
                        </div>
                        <span
                            class="text-2xl md:text-3xl font-medium text-black">{{ $item->location ?? 'Non défini' }}</span>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <img src="{{ asset('boxes.png') }}" alt="Statut" class="h-10 w-10 object-contain">
                        </div>
                        <span
                            class="text-2xl md:text-3xl font-medium {{ $currentLoan ? 'text-[#FF8C8C]' : 'text-black' }}">
                            {{ $currentLoan ? 'Occupé' : 'Libre' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <img src="{{ asset('user.png') }}" alt="Utilisateur" class="h-7 w-7 object-contain">
                        </div>
                        <span
                            class="text-2xl md:text-3xl font-medium {{ $currentLoan ? 'text-black' : 'text-gray-300' }}">
                            {{ $currentLoan ? $currentLoan->user->name . ' ' . $currentLoan->user->surname : 'Aucun emprunteur' }}
                        </span>
                    </div>

                    @if($currentLoan)
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                                <img src="{{ asset('calendar.png') }}" alt="Date" class="h-8 w-8 object-contain">
                            </div>
                            <span class="text-2xl md:text-3xl font-medium text-black">
                                Jusqu'au {{ \Carbon\Carbon::parse($currentLoan->end_date_planned)->format('d.m.Y') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-6 lg:mt-12">
                <h2 class="text-2xl font-bold text-black flex items-center gap-3">
                    <span class="w-2 h-8 bg-[#89CFF0] rounded-full"></span>
                    Historique
                </h2>
                <div class="space-y-3 mb-4">
                    @php
                        $loans = $item->loans()->orderBy('start_date', 'asc')->get()->sortBy(function ($loan) {
                            if ($loan->anomaly === 'En retard')
                                return 0;
                            $today = now()->format('Y-m-d');
                            $isCurrent = $loan->start_date <= $today && $loan->end_date_planned >= $today && !$loan->end_date;
                            $isFuture = $loan->start_date > $today;
                            if ($isCurrent)
                                return 1;
                            if ($isFuture)
                                return 2;
                            return 3;
                        })->take(4);
                    @endphp
                    @forelse($loans as $loan)
                        @php
                            $today = now()->format('Y-m-d');
                            $isCurrent = $loan->start_date <= $today && $loan->end_date_planned >= $today && !$loan->end_date;
                            $isFuture = $loan->start_date > $today;
                            $anomaly = $loan->anomaly;
                        @endphp
                        <div
                            class="p-4 bg-gray-50 rounded-[20px] border-2 {{ $anomaly === 'En retard' ? 'border-red-400' : ($isCurrent ? 'border-[#89CFF0]' : 'border-transparent') }}">
                            <div class="flex justify-between items-center">
                                <p class="font-bold text-black">{{ $loan->user->name }} {{ $loan->user->surname }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($loan->start_date)->format('d.m') }} —
                                    {{ \Carbon\Carbon::parse($loan->end_date_planned)->format('d.m') }}
                                </p>
                                @if($anomaly === 'En retard')
                                    <span class="badge badge-sm border-none font-black bg-red-500 text-white">
                                        EN RETARD
                                    </span>
                                @else
                                    <span
                                        class="badge badge-sm border-none font-black {{ $isCurrent ? 'bg-[#89CFF0] text-white' : ($isFuture ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-400') }}">
                                        {{ $isCurrent ? 'ACTUEL' : ($isFuture ? 'À VENIR' : 'PASSÉ') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 italic text-sm">Aucune réservation pour cet objet.</p>
                    @endforelse
                </div>
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
                @if($errors->has('conflict'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-xl">
                        <p class="text-red-600 font-bold text-sm">{{ $errors->first('conflict') }}</p>
                    </div>
                @endif
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
                    <input type="text" name="location" placeholder="ex: BD12"
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

    @if($errors->has('conflict') || $errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                booking_modal.showModal();
            });
        </script>
    @endif
</body>

</html>