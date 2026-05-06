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
    @php
        $currentLoan = $item->currentLoan();
    @endphp
    @include('partials.admin-nav')
    <div class="container mx-auto px-6 max-w-6xl pb-20 mt-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-5xl md:text-7xl font-black text-black leading-tight">{{ $item->name }}</h1>
                <p class="text-gray-400 text-lg md:text-xl font-medium mt-1">Référence : {{ $item->serial_number }}</p>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('admin.items.edit', $item->id) }}"
                    class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-3xl shadow-sm">
                    <img src="{{ asset('pencil.png') }}" alt="Modifier" class="h-8 w-8 object-contain">
                </a>
                <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST"
                    onsubmit="return confirm('Supprimer définitivement ?');">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-3xl shadow-sm">
                        <img src="{{ asset('delete.png') }}" alt="Supprimer" class="h-8 w-8 object-contain">
                    </button>
                </form>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="space-y-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                        <img src="{{ asset('home.png') }}" alt="Localisation" class="h-8 w-8 object-contain">
                    </div>
                    <span class="text-2xl md:text-4xl font-bold text-black">{{ $item->location ?? 'Non défini' }}</span>
                </div>
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                        <img src="{{ asset('boxes.png') }}" alt="Statut" class="h-10 w-10 object-contain">
                    </div>
                    <span
                        class="text-2xl md:text-4xl font-bold {{ $currentLoan ? 'text-[#FF8C8C]' : 'text-green-500' }}">
                        {{ $currentLoan ? 'Occupé' : 'Libre' }}
                    </span>
                </div>
                @if($currentLoan)
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center">
                            <img src="{{ asset('user.png') }}" alt="Utilisateur" class="h-7 w-7 object-contain">
                        </div>
                        <span class="text-2xl md:text-4xl font-bold text-black">{{ $currentLoan->user->name }}</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <div
                            class="w-16 h-16 bg-gray-100 rounded-full flex-none flex items-center justify-center text-black">
                            <img src="{{ asset('calendar.png') }}" alt="Date" class="h-8 w-8 object-contain">
                        </div>
                        <span class="text-2xl md:text-4xl font-bold text-black">Jusqu'au
                            {{ \Carbon\Carbon::parse($currentLoan->end_date_planned)->format('d.m.Y') }}</span>
                    </div>
                @endif
                <div class="pt-10">
                    <h2 class="text-2xl font-bold text-black flex items-center gap-3 mb-6">
                        <span class="w-2 h-8 bg-[#FF8C8C] rounded-full"></span> Planning
                    </h2>
                    <div class="space-y-4">
                        @foreach($item->loans()->orderBy('start_date', 'asc')->take(4)->get() as $loan)
                            @php
                                $today = now()->format('Y-m-d');
                                $isCurrent = $loan->start_date <= $today && ($loan->end_date_planned >= $today && !$loan->end_date);
                                $isFuture = $loan->start_date > $today;
                            @endphp
                            <div
                                class="p-6 bg-gray-50 rounded-[30px] border-2 {{ $isCurrent ? 'border-[#FF8C8C] shadow-sm' : 'border-transparent' }}">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-1">
                                        <p class="text-xl font-bold text-black">{{ $loan->user->name }}</p>
                                        <p class="text-xl font-bold text-black">{{ $loan->user->surname }}</p>
                                    </div>
                                    <p class="font-bold text-black">
                                        {{ \Carbon\Carbon::parse($loan->start_date)->format('d.m') }} —
                                        {{ \Carbon\Carbon::parse($loan->end_date_planned)->format('d.m') }}
                                    </p>
                                    <div class="text-right">
                                        <span
                                            class="badge badge-sm border-none font-black mt-1 {{ $isCurrent ? 'bg-[#FF8C8C] text-white' : ($isFuture ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-400') }}">
                                            {{ $isCurrent ? 'ACTUEL' : ($isFuture ? 'À VENIR' : 'PASSÉ') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center lg:items-end gap-8 pt-10">
                @if($item->qr_code)
                    <div class="p-6 border-4 border-black rounded-[40px] bg-white shadow-lg">
                        <img src="{{ asset('storage/' . $item->qr_code) }}" alt="QR Code" class="w-64 h-64 md:w-80 md:h-80">
                    </div>
                    <a href="{{ asset('storage/' . $item->qr_code) }}" download="QR_{{ $item->name }}"
                        class="btn btn-lg w-full max-w-sm rounded-full border-none bg-[#FF8C8C] hover:bg-[#f77474] text-black text-xl font-bold normal-case h-20 shadow-xl transition-all active:scale-95">
                        Télécharger QR code
                    </a>
                @endif
            </div>
        </div>
    </div>
</body>

</html>