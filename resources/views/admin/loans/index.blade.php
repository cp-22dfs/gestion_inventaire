<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Flux - Administration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-gray-50 min-h-screen">
    @include('partials.admin-nav')
    <main class="max-w-7xl mx-auto p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h2 class="text-2xl font-bold text-black text-center md:text-left">Toutes les activités</h2>
        </div>
        <div class="overflow-x-auto bg-white rounded-[40px] shadow-sm p-4 md:p-8">
            <table class="table w-full border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-gray-400 border-none uppercase text-xs">
                        <th class="bg-transparent pl-6">Objet</th>
                        <th class="bg-transparent">Utilisateur</th>
                        <th class="bg-transparent text-center">Date de début</th>
                        <th class="bg-transparent">Date de fin prévue</th>
                        <th class="bg-transparent text-center">Statut</th>
                        <th class="bg-transparent text-right pr-6">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                                        <tr class="group">
                                            <td class="bg-gray-50 rounded-l-[25px] pl-6 py-5">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-black text-lg">{{ $loan->item->name }}</span>
                                                    <span
                                                        class="text-xs font-mono text-gray-400 uppercase">{{ $loan->item->serial_number }}</span>
                                                </div>
                                            </td>
                                            <td class="bg-gray-50">
                                                <div class="flex items-center gap-1">
                                                    <span class="font-bold text-black">{{ $loan->user->name }}</span>
                                                    <span class="font-bold text-black">{{ $loan->user->surname }}</span>
                                                </div>
                                            </td>
                                            <td class="bg-gray-50 text-center">
                                                <span class="font-bold text-black text-sm">
                                                    {{ \Carbon\Carbon::parse($loan->start_date)->format('d.m.Y') }}
                                                </span>
                                            </td>
                                            <td class="bg-gray-50">
                                                <span class="font-bold text-black text-sm">
                                                    {{ \Carbon\Carbon::parse($loan->end_date_planned)->format('d.m.Y') }}
                                                </span>
                                            </td>
                                            <td class="bg-gray-50 text-center">
                                                <div class="flex flex-col items-center">
                                                    @if($loan->anomaly)
                                                        <span
                                                            class="px-3 py-1 bg-red-600 text-white rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm">
                                                            {{ $loan->anomaly }}
                                                        </span>
                                                    @else
                                                        @php
                        $statusClasses = [
                            'reserved' => 'bg-[#FF8C8C] text-white',
                            'borrowed' => 'bg-blue-100 text-blue-600',
                            'returned' => 'bg-green-100 text-green-600',
                        ];
                                                        @endphp
                                                        <span
                                                            class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $statusClasses[$loan->status] ?? 'bg-gray-100' }}">
                                                            {{ $loan->status == 'reserved' ? 'Réservé' : ($loan->status == 'borrowed' ? 'Emprunté' : 'Rendu') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="bg-gray-50 rounded-r-[25px] text-right pr-6">
                                                <div class="flex justify-end items-center gap-3">
                                                    <a href="{{ route('admin.loans.edit', $loan) }}">
                                                        <img src="{{ asset('pencil.png') }}" alt="Modifier" class="h-6 w-6 object-contain">
                                                    </a>

                                                    <form action="{{ route('admin.loans.destroy', $loan) }}" method="POST"
                                                        onsubmit="return confirm('Annuler définitivement cette réservation ?');" class="flex items-center">
                                                        @csrf @method('DELETE')
                                                        <button type="submit">
                                                            <img src="{{ asset('delete.png') }}" alt="Supprimer" class="h-6 w-6 object-contain">
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center italic text-gray-400">
                                Aucun flux enregistré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-8 px-6">
                {{ $loans->links() }}
            </div>
        </div>
    </main>
</body>

</html>