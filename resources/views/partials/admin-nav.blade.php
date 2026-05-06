<nav class="w-full h-20 bg-[#FF8C8C] flex items-center justify-between px-6 md:px-12 shadow-sm">
    <div class="text-3xl font-black text-black">
        Admin
    </div>
    <div class="flex items-center gap-6">
        <div class="flex items-center bg-white/20 p-1 rounded-full">
            <a href="{{ route('admin.dashboard') }}"
                class="px-6 py-2 rounded-full text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white' : 'text-black/60 hover:text-black' }}">
                Inventaire
            </a>
            <a href="{{ route('admin.loans.index') }}"
                class="px-6 py-2 rounded-full text-sm font-bold transition-all {{ request()->routeIs('admin.loans.index') ? 'bg-black text-white' : 'text-black/60 hover:text-black' }}">
                Flux
            </a>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="flex items-center">
            @csrf
            <button type="submit" class="p-2">
                <img src="{{ asset('logout.png') }}" alt="Déconnexion" class="w-8 h-8 object-contain">
            </button>
        </form>
    </div>
</nav>