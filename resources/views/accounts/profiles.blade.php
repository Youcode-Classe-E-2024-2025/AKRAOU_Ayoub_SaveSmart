<x-layout>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Mes Profils</h1>

    <div class="grid grid-cols-4 gap-4">
        <!-- Affichage des profils -->
        @foreach($profiles as $profile)
        <a href="{{ route('profiles.show', $profile->id) }}" class="bg-white block p-4 rounded-lg shadow-md text-center transition-transform transform hover:scale-105 relative">
            <form action="{{ route('profiles.destroy', $profile->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="absolute top-0 right-0 bg-red-500 text-white block w-6 h-6 flex justify-center items-center rounded-full"><i class="fa-solid fa-xmark"></i></button>
            </form>
            <img src="{{ asset('images/' . $profile->avatar) }}" alt="Avatar de {{ $profile->name }}" class="object-cover rounded-full w-32 h-32 mx-auto mb-2">
            <h2 class="text-lg font-semibold">{{ $profile->name }}</h2>
        </a>
        @endforeach

        <!-- Bouton pour ajouter un nouveau profil -->
        <a href="{{ route('profiles.create') }}" class="text-blue-500 text-lg font-medium hover:underline bg-gray-200 p-4 rounded-lg shadow-md text-center flex flex-col justify-center items-center transition-transform transform hover:scale-105">
            Ajouter un Nouveau Profil
        </a>
    </div>
</div>
</div>

<style>
    /* Ajout de styles personnalisés pour un effet de survol */
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
</style>
</x-layout>
