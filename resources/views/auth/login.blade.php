<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <style>
        .clip-diagonal { clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%); }
    </style>
</head>
<body class="bg-white">
    <a href="{{ url('/') }}"
        class="absolute top-6 left-6 z-50 flex items-center justify-center w-12 h-12 bg-white/80 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition-colors">
        <img src="{{ asset('back.png') }}" alt="Retour" class="w-6 h-6 object-contain">
    </a>
    @php 
        $isAdmin = request()->query('role') === 'admin'; 
    @endphp
    <div class="relative w-full h-[40vh] overflow-hidden">
        <img src="{{ asset('stock.png') }}" class="w-full h-full object-cover clip-diagonal">
    </div>
    <div class="text-center mt-4">
        <h1 class="text-4xl font-black text-black">
            Connexion {{ $isAdmin ? 'administrateur' : 'utilisateur' }}
        </h1>
        <p class="text-gray-400 text-xl">Login</p>
    </div>
    <form method="POST" action="{{ route('login.post') }}" class="max-w-md mx-auto px-8 mt-6">
        @csrf
        <input type="email" name="email" placeholder="Email" 
            class="input border-0 border-b-2 border-gray-200 rounded-none w-full bg-transparent px-2 mb-8 focus:outline-none {{ $isAdmin ? 'focus:border-[#FF8C8C]' : 'focus:border-[#87CEFA]' }}" 
            required autofocus>
        <input type="password" name="password" placeholder="Mot de passe" 
            class="input border-0 border-b-2 border-gray-200 rounded-none w-full bg-transparent px-2 mb-4 focus:outline-none {{ $isAdmin ? 'focus:border-[#FF8C8C]' : 'focus:border-[#87CEFA]' }}" 
            required>
        @error('email')
            <p class="text-[#FF8C8C] text-sm text-center mb-6 font-medium italic">
                {{ $message }}
            </p>
        @enderror
        <div class="text-center mb-10">
            <a href="#" class="text-gray-400 text-sm">Mot de passe oublié ?</a>
        </div>
        <button type="submit" 
            class="btn btn-lg w-full rounded-full border-none text-black text-2xl h-20 normal-case shadow-lg font-bold 
            {{ $isAdmin ? 'bg-[#FF8C8C] hover:bg-[#f77474]' : 'bg-[#87CEFA] hover:bg-[#70c2f5]' }}">
            Connexion
        </button>
    </form>
</body>
</html>