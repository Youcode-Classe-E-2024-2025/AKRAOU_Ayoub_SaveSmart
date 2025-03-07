<!-- Navbar -->
<nav class="bg-blue-600 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="/" class="text-white text-lg font-bold">Mon Application</a>
        <div>
            @guest
                <a href="{{ route('login') }}" class="text-white hover:underline">Connexion</a>
                <a href="{{ route('signup') }}" class="ml-4 text-white hover:underline">Inscription</a>
            @endguest

            @auth
                <a href="{{ route('home') }}" class="ml-4 text-white hover:underline">Accueil</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="ml-4 text-white hover:underline">Deconnexion</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
