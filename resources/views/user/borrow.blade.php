<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emprunter - {{ $item->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-white min-h-screen">
    <div class="w-full h-16 bg-[#89CFF0] mb-10 flex items-center px-6">
        <a href="{{ route('scan') }}" class="hover:opacity-70 transition-opacity">
            <img src="{{ asset('back.png') }}" alt="Retour" class="h-10 w-10 object-contain">
        </a>
    </div>

    <div class="flex flex-col items-center px-8 gap-8">
        <h1 class="text-3xl font-black text-black text-center">{{ $item->name }}</h1>

        @if($currentLoan && $currentLoan->status === 'borrowed' && $currentLoan->user_id !== Auth::id())
            <div class="w-full max-w-sm bg-red-50 border-l-4 border-red-400 p-4 rounded-xl">
                <p class="text-red-600 font-bold text-sm">Cet objet est déjà emprunté par quelqu'un d'autre.</p>
            </div>
        @elseif($currentLoan && $currentLoan->status === 'borrowed' && $currentLoan->user_id === Auth::id())
            <div class="w-full max-w-sm bg-orange-50 border-l-4 border-orange-400 p-4 rounded-xl">
                <p class="text-orange-600 font-bold text-sm">Vous avez déjà cet objet, scannez-le à nouveau pour le rendre.
                </p>
            </div>
        @else
            <form action="{{ route('loan.store') }}" method="POST" class="w-full max-w-sm flex flex-col gap-4">
                @csrf
                @if($errors->has('conflict'))
                    <div class="w-full bg-red-50 border-l-4 border-red-400 p-4 rounded-xl">
                        <p class="text-red-600 font-bold text-sm">{{ $errors->first('conflict') }}</p>
                    </div>
                @endif
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="status" value="borrowed">
                <input type="hidden" name="start_date" value="{{ date('Y-m-d') }}">

                <input type="text" name="location" placeholder="Atelier" value="{{ $currentLoan?->location ?? '' }}"
                    class="input bg-gray-100 border-none rounded-full px-6 h-14 w-full focus:ring-2 focus:ring-[#89CFF0]">

                <input type="text" value="{{ Auth::user()->name }} {{ Auth::user()->surname }}"
                    class="input bg-gray-100 border-none rounded-full px-6 h-14 w-full opacity-60 cursor-not-allowed"
                    readonly>

                <div class="flex flex-col gap-1 w-full">
                    <label class="text-sm font-bold text-gray-400 ml-4">Date de retour prévue</label>
                    <input type="date" name="end_date_planned" id="end_date_planned_borrow"
                        value="{{ $currentLoan ? \Carbon\Carbon::parse($currentLoan->end_date_planned)->format('Y-m-d') : '' }}"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" {{ $currentLoan ? 'readonly' : '' }}
                        class="input bg-gray-100 border-none rounded-full px-6 h-14 w-full focus:ring-2 focus:ring-[#89CFF0]"
                        required>
                    <div class="flex gap-2 mt-2">
                        <button type="button" onclick="addDays('end_date_planned_borrow', 1)"
                            class="btn btn-sm rounded-full border-none bg-gray-100 text-black font-bold normal-case flex-1">
                            +1 jour
                        </button>
                        <button type="button" onclick="addDays('end_date_planned_borrow', 7)"
                            class="btn btn-sm rounded-full border-none bg-gray-100 text-black font-bold normal-case flex-1">
                            +1 semaine
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="btn btn-lg w-full rounded-full border-none bg-[#89CFF0] hover:bg-[#7bc4e6] text-black font-bold text-2xl normal-case h-16 shadow-lg mt-4">
                    Valider
                </button>
            </form>
        @endif
    </div>
    <script>
        function addDays(fieldId, days) {
            const field = document.getElementById(fieldId);
            const today = field.value ? new Date(field.value) : new Date();
            today.setDate(today.getDate() + days);
            field.value = today.toISOString().split('T')[0];
        }
    </script>
</body>

</html>