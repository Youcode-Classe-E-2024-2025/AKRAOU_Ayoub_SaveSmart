@extends('layouts.main')

@section('title', 'profile')

@section('content')


<!-- Contenu Principal -->
<div class="container mx-auto p-6">
    <button onclick="openAddTransactionModal()" class="bg-white mb-4 text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition duration-300">
        + Nouvelle Transaction
    </button>
    <!-- Cartes de Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Solde Total</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $balance }}</p>
            <div class="mt-2 text-green-500 text-sm">+15% ce mois</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Dépenses du Mois</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $expenses }}</p>
            <div class="mt-2 text-red-500 text-sm">-5% vs mois dernier</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">Économies</h3>
            <p class="text-2xl font-bold text-gray-800"></p>
            <div class="mt-2 text-green-500 text-sm">+20% vs objectif</div>
        </div>
    </div>

    <!-- Graphiques et Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Graphique -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Répartition des Dépenses</h2>
            <canvas id="expensesChart" class="w-full"></canvas>
        </div>

        <!-- Transactions Récentes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Transactions Récentes</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="pb-3">Date</th>
                            <th class="pb-3">Catégorie</th>
                            <th class="pb-3">Montant</th>
                            <th class="pb-3">actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">{{ $transaction->created_at }}</td>
                            <td class="py-3" style="color: {{ $transaction->category->color }}">{{ $transaction->category->name }}</td>
                            <td class="py-3 {{$transaction->type == 'expense' ? 'text-red-500' : 'text-green-500'}}">{{ $transaction->amount }}</td>
                            <td class="py-3 flex items-center gap-2">
                                <!-- <button class="text-blue-500 hover:text-blue-700">Voir</button> -->

                                <button onclick='openUpdateTransactionModal(@json($transaction))' class="edit-transaction-btn text-green-500 hover:text-green-700"><i class="fa-solid fa-pen-to-square"></i></button>
                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700"><i class="fa-solid fa-xmark"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Objectifs d'Épargne -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold">Objectifs d'Épargne</h2>
            <button onclick="openAddGoalModal()" class="text-green-600 hover:text-green-700">+ Nouvel Objectif</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($saving_goals as $saving_goal)
            <!-- Objectif 1 -->
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold">{{ $saving_goal->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $saving_goal->saved_amount }} / {{ $saving_goal->target_amount }} €</p>
                    </div>
                    @if($saving_goal->saved_amount < $saving_goal->target_amount)
                        <form action="{{ route('goals.update', $saving_goal->id) }}" method="POST" class="border rounded-lg p-2 bg-white shadow-md">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center space-x-2">
                                <input type="number" name="saved_amount" value="{{ $saving_goal->saved_amount }}" class="w-20 px-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded-lg hover:bg-blue-600 transition">Invest</button>
                            </div>
                            @error('saved_amount')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </form>
                        @else
                        <!-- check btn success -->

                        <a href="{{ route('goals.update', $saving_goal->id) }}" class="block text-green-500 ml-auto text-right block text-xl"><i class="fa-solid fa-check"></i></a>
                        @endif
                </div>
                <span class="text-green-500 ml-auto text-right block">{{ number_format($saving_goal->saved_amount / $saving_goal->target_amount * 100, 2) }}%</span>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $saving_goal->saved_amount / $saving_goal->target_amount * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Update Transaction -->
<div id="updateTransactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h2 class="text-xl font-bold mb-4">Mise à Jour de la Transaction</h2>
        <form action="" method="POST">
            @method('PATCH')
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Type</label>
                <select id='type' class="w-full border rounded-lg p-2" name="type">
                    <option value="expense">Dépense</option>
                    <option value="income">Revenu</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Montant</label>
                <input type="number" id='amount' class="w-full border rounded-lg p-2" name="amount" placeholder="0.00 €">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea id='description' class="w-full border rounded-lg p-2" name="description" placeholder="Description de la transaction"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                <select id='category_id' class="w-full border rounded-lg p-2" name="category_id">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeModal('updateTransactionModal')" class="text-gray-500 hover:text-gray-700">Annuler</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Sauvegarder</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Nouvelle Transaction -->
<div id="transactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h2 class="text-xl font-bold mb-4">Nouvelle Transaction</h2>
        <form action="{{ route('transactions.store') }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Type</label>
                <select class="w-full border rounded-lg p-2" name="type">
                    <option value="expense">Dépense</option>
                    <option value="income">Revenu</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Montant</label>
                <input type="number" class="w-full border rounded-lg p-2" name="amount" placeholder="0.00 €">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea class="w-full border rounded-lg p-2" name="description" placeholder="Description de la transaction"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                <select class="w-full border rounded-lg p-2" name="category_id">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeModal('transactionModal')" class="text-gray-500 hover:text-gray-700">Annuler</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Sauvegarder</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Nouvel Objectif -->
<div id="goalModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h2 class="text-xl font-bold mb-4">Nouvel Objectif d'Épargne</h2>
        <form action="{{ route('goals.store') }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
                <input name="name" type="text" class="w-full border rounded-lg p-2" placeholder="Nom de l'objectif">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Montant Cible</label>
                <input name="target_amount" type="number" class="w-full border rounded-lg p-2" placeholder="0.00 €">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Date Cible</label>
                <input name="target_date" type="date" class="w-full border rounded-lg p-2">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                <select id='category_id' class="w-full border rounded-lg p-2" name="category_id">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeModal('goalModal')" class="text-gray-500 hover:text-gray-700">Annuler</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Créer</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Initialisation du graphique
    const ctx = document.getElementById('expensesChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Alimentation', 'Transport', 'Logement', 'Loisirs', 'Autres'],
            datasets: [{
                data: [300, 150, 500, 200, 50],
                backgroundColor: [
                    '#10B981',
                    '#3B82F6',
                    '#F59E0B',
                    '#EC4899',
                    '#6366F1'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Fonctions pour les modals
    function openAddTransactionModal() {
        document.getElementById('transactionModal').classList.remove('hidden');
    }

    function openUpdateTransactionModal(transaction) {
        document.getElementById('updateTransactionModal').classList.remove('hidden');
        const actionUrl = "{{ route('transactions.update', ':id') }}".replace(':id', transaction['id']);
        document.querySelector('#updateTransactionModal form').setAttribute('action', actionUrl);

        document.getElementById('type').value = transaction['type'];
        document.getElementById('amount').value = transaction['amount'];
        document.getElementById('description').value = transaction['description'];
        document.getElementById('category_id').value = transaction['category_id'];
    }

    function openAddGoalModal() {
        document.getElementById('goalModal').classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection