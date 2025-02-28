<!-- resources/views/accounts/register.blade.php -->

@extends('layouts.main')

@section('title', 'Inscription')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md w-96 mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6 text-center">Inscription</h1>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nom:</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" placeholder="Entrez votre nom">
        </div>
        @error('name')
        <div class="text-red-500">{{ $message }}</div>
        @enderror
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" placeholder="Entrez votre email">
        </div>
        @error('email')
        <div class="text-red-500">{{ $message }}</div>
        @enderror
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe:</label>
            <input type="password" name="password" value="{{ old('password') }}" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" placeholder="Entrez votre mot de passe">
        </div>
        @error('password')
        <div class="text-red-500">{{ $message }}</div>
        @enderror
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe:</label>
            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" placeholder="Confirmez votre mot de passe">
        </div>
        @error('password_confirmation')
        <div class="text-red-500">{{ $message }}</div>
        @enderror
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Type:</label>
            <select name="type" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                <option {{ old('type') == 'personal' ? 'selected' : '' }} value="personal">Personnel</option>
                <option {{ old('type') == 'family' ? 'selected' : '' }} value="family">Famille</option>
            </select>
        </div>
        @error('type')
        <div class="text-red-500">{{ $message }}</div>
        @enderror
        <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded hover:bg-blue-600">S'inscrire</button>
    </form>
</div>
@endsection