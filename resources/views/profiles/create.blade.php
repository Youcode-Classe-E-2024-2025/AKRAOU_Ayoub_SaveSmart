<x-layout>
<div class="bg-white p-8 rounded-lg shadow-md w-96 mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6 text-center">Ajouter un Profil</h1>
    <form action="{{ route('profiles.store') }}" method="POST" >
        @method('PUT')
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nom:</label>
            <input type="text" name="name" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" placeholder="Entrez votre nom">
        </div>
        @error('name')
        <div class="text-red-500">{{ $message }}</div>
        @enderror
        
        <!-- Ajoutez d'autres champs si nécessaire -->
        
        <!-- <div class="mb-4">
            <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar:</label>
            <input type="file" name="avatar" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
        </div> -->

        <button type="submit" class="w-full bg-blue-500 text-white font-semibold py-2 rounded-md hover:bg-blue-600 transition duration-200">Ajouter Profil</button>
    </form>
</div>
</x-layout>