<x-layout>
<div class="bg-white p-8 rounded-lg shadow-md w-96 mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-center">Inscription</h1>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" name="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" placeholder="Entrez votre email">
        @error('email')
        <div class="text-red-500">{{ $message }}</div>
        @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe:</label>
            <input type="password" name="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" placeholder="Entrez votre mot de passe">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded hover:bg-blue-600">S'inscrire</button>
    </form>
</div>
</x-layout>