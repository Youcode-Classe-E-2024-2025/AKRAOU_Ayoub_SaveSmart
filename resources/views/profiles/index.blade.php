<x-layout>
    <!-- Contenu Principal -->
    <div class="container mx-auto p-6">
        <button onclick="openAddTransactionModal()"
            class="bg-white mb-4 text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition duration-300">
            + Nouvelle Transaction
        </button>
        <button onclick="openAddCategoryModal()"
            class="bg-white mb-4 text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition duration-300">
            + Nouvelle Catégorie
        </button>
        <!-- Cartes de Statistiques -->
        <div class="flex flex-wrap gap-6 mb-8 items-start">
            <div class="bg-white rounded-lg shadow p-6 flex-1">
                <h3 class="text-gray-500 text-sm">Solde Total</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $balance }}$</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 flex-1">
                <h3 class="text-gray-500 text-sm">Dépenses du Mois</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $expenses }}$</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 flex-1">
                <h3 class="text-gray-500 text-sm">Économies</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $profile->savings }}$</p>
                <form action="{{ route('profiles.updateSavings', $profile) }}" method="POST"
                    class="flex items-center gap-4">
                    @method('PATCH')
                    @csrf
                    <div>
                        <input type="number" name="savings" value="{{ old('savings', 0.0) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        @error('savings')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                        <i class="fas fa-plus"></i>
                    </button>
                </form>
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
                            @foreach ($transactions as $transaction)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3">{{ $transaction->created_at }}</td>
                                    @isset($transaction->category)
                                        <td class="py-3"style="color: {{ $transaction->category->color }}">
                                            {{ $transaction->category->name }} </td>
                                    @else
                                        <td class="py-3 text-green-500 font-medium">revenue</td>
                                    @endisset
                                    <td
                                        class="py-3 {{ $transaction->type == 'expense' ? 'text-red-500' : 'text-green-500' }}">
                                        {{ $transaction->amount }}</td>
                                    <td class="py-3 flex items-center gap-2">
                                        <!-- <button class="text-blue-500 hover:text-blue-700">Voir</button> -->

                                        <button onclick='openUpdateTransactionModal(@json($transaction))'
                                            class="edit-transaction-btn text-green-500 hover:text-green-700"><i
                                                class="fa-solid fa-pen-to-square"></i></button>
                                        <form action="{{ route('transactions.destroy', $transaction->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700"><i
                                                    class="fa-solid fa-xmark"></i></button>
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
                <button onclick="openAddGoalModal()" class="text-green-600 hover:text-green-700">+ Nouvel
                    Objectif</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($saving_goals as $saving_goal)
                    <!-- Objectif 1 -->
                    <div class="border rounded-lg p-4 {{ $saving_goal->status == 'fulfilled' ? 'bg-green-100' : '' }}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold">{{ $saving_goal->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $saving_goal->saved_amount }} /
                                    {{ $saving_goal->target_amount }} €</p>
                            </div>
                            @if ($saving_goal->status == 'pending')
                                @if ($saving_goal->saved_amount < $saving_goal->target_amount)
                                    <form action="{{ route('goals.update', $saving_goal->id) }}" method="POST"
                                        class="border rounded-lg p-2 bg-white shadow-md">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-center space-x-2">
                                            <input type="number" name="saved_amount[{{ $saving_goal->id }}]"
                                                value="{{ old('saved_amount.' . $saving_goal->id, $saving_goal->saved_amount) }}"
                                                class="w-20 px-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                placeholder="0.00">
                                            <button
                                                class="bg-blue-500 text-white px-2 py-1 rounded-lg hover:bg-blue-600 transition">Invest</button>
                                        </div>
                                        @error('saved_amount.' . $saving_goal->id)
                                            <div class="text-red-500 text-sm">{{ $message }}</div>
                                        @enderror
                                    </form>
                                @else
                                    <form action="{{ route('goals.convert', $saving_goal->id) }}" method="POST">
                                        @METHOD('PATCH')
                                        @csrf
                                        <button class="block text-green-500 ml-auto text-right block text-xl">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p class="fulfilled text-sm text-gray-500 font-medium">réalisé à
                                    {{ explode(' ', $saving_goal->updated_at)[0] }}</p>
                            @endif
                        </div>
                        <span
                            class="text-green-500 ml-auto text-right block">{{ number_format(($saving_goal->saved_amount / $saving_goal->target_amount) * 100, 2) }}%</span>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-600 h-2.5 rounded-full"
                                style="width: {{ ($saving_goal->saved_amount / $saving_goal->target_amount) * 100 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Update Transaction -->
    <div id="updateTransactionModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h2 class="text-xl font-bold mb-4">Mise à Jour de la Transaction</h2>
            <form action="" method="POST">
                @method('PATCH')
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Type</label>
                    <select id='type' class="select-type w-full border rounded-lg p-2" name="type">
                        <option value="expense">Dépense</option>
                        <option value="income">Revenu</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Montant</label>
                    <input type="number" id='amount' class="w-full border rounded-lg p-2" name="amount"
                        placeholder="0.00 €">
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea id='description' class="w-full border rounded-lg p-2" name="description"
                        placeholder="Description de la transaction"></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4 select-category">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                    <select id='category_id' class="w-full border rounded-lg p-2" name="category_id">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-4 actions">
                    <button type="button" onclick="closeModal('updateTransactionModal')"
                        class="text-gray-500 hover:text-gray-700">Annuler</button>
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Sauvegarder</button>
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
                    <select id='type' class="select-type w-full border rounded-lg p-2" name="type">
                        <option value="expense">Dépense</option>
                        <option value="income">Revenu</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Montant</label>
                    <input type="number" class="w-full border rounded-lg p-2" name="amount" placeholder="0.00 €">
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea class="w-full border rounded-lg p-2" name="description" placeholder="Description de la transaction"></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4 select-category">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                    <select class="w-full border rounded-lg p-2" name="category_id">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-4 actions">
                    <button type="button" onclick="closeModal('transactionModal')"
                        class="text-gray-500 hover:text-gray-700">Annuler</button>
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Sauvegarder</button>
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
                    <input name="name" type="text" class="w-full border rounded-lg p-2"
                        placeholder="Nom de l'objectif">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Montant Cible</label>
                    <input name="target_amount" type="number" class="w-full border rounded-lg p-2"
                        placeholder="0.00 €">
                    @error('target_amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Date Cible</label>
                    <input name="target_date" type="date" class="w-full border rounded-lg p-2">
                    @error('target_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                    <select id='category_id' class="w-full border rounded-lg p-2" name="category_id">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeModal('goalModal')"
                        class="text-gray-500 hover:text-gray-700">Annuler</button>
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Créer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal category -->
    <div id="categoryModal" class="hidden fixed p-4  inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        style="z-index: 9999;max-height: 100vh;">
        <div class="bg-white rounded-lg p-8 max-w-md w-full relative h-full overflow-y-auto">
            <button type="button" onclick="closeModal('categoryModal')"
                class="text-gray-500 hover:text-gray-700 absolute top-4 right-4"><i
                    class="fa-solid fa-xmark"></i></button>
            <h2 class="text-xl font-bold mb-4">Nouvelle Catégorie</h2>
            <form action="{{ route('categories.store') }}" method="POST" class="flex gap-4 items-stretch">
                @method('PUT')
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                    <input name="name" type="text" class="w-full border rounded-lg p-2"
                        placeholder="Nom de la catégorie">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4 h-full">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Couleur</label>
                    <input name="color" type="color" class="w-full border rounded-lg p-2" placeholder="HEX/RGB">
                    @error('color')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="self-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Créer</button>
            </form>
            <ul class="bg-white rounded-xl shadow-lg divide-y divide-gray-100 overflow-hidden border border-gray-100">
                @foreach ($categories as $category)
                    <li class="flex items-center p-4 transition-colors hover:bg-gray-50">
                        <span class="h-3 w-3 rounded-full mr-3"
                            style="background-color: {{ $category->color }}"></span>
                        <span class="text-gray-800 font-medium">{{ $category->name }}</span>
                        <form class="ml-auto" action="{{ route('categories.destroy', $category->id) }}"
                            method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700"><i
                                    class="fa-solid fa-xmark"></i></button>
                        </form>
                    </li>
                @endforeach
            </ul>

        </div>
    </div>
    <!-- ------------------------------------------------------------------------------------- -->
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Optimisation Budgétaire</h1>

                    <!-- Formulaire de saisie du budget -->
                    <div class="mb-10 p-6 bg-gray-50 rounded-lg">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Saisissez votre budget mensuel</h2>
                        <form id="budgetForm" class="space-y-4">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-1">
                                    <label for="totalBudget"
                                        class="block text-sm font-medium text-gray-700 mb-1">Budget
                                        Total (€)</label>
                                    <input type="number" name="totalBudget" id="totalBudget" min="1"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                </div>
                                <div class="md:self-end">
                                    <button type="submit"
                                        class="w-full md:w-auto px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        Calculer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Résultats de l'optimisation 50/30/20 -->
                    <div id="resultsSection" class="hidden mb-10">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Répartition recommandée (Méthode 50/30/20)
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <!-- Besoins (50%) -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-medium text-blue-800">Besoins (50%)</h3>
                                    <span
                                        class="text-sm bg-blue-100 text-blue-800 py-1 px-2 rounded-full">Essentiel</span>
                                </div>
                                <p class="text-3xl font-bold text-blue-600 mb-2" id="needsAmount">€0</p>
                                <p class="text-sm text-gray-600">Loyer, factures, nourriture, transport, assurances...
                                </p>
                            </div>

                            <!-- Envies (30%) -->
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-medium text-purple-800">Envies (30%)</h3>
                                    <span
                                        class="text-sm bg-purple-100 text-purple-800 py-1 px-2 rounded-full">Plaisir</span>
                                </div>
                                <p class="text-3xl font-bold text-purple-600 mb-2" id="wantsAmount">€0</p>
                                <p class="text-sm text-gray-600">Loisirs, restaurants, shopping, abonnements...</p>
                            </div>

                            <!-- Épargne (20%) -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-lg font-medium text-green-800">Épargne (20%)</h3>
                                    <span
                                        class="text-sm bg-green-100 text-green-800 py-1 px-2 rounded-full">Futur</span>
                                </div>
                                <p class="text-3xl font-bold text-green-600 mb-2" id="savingsAmount">€0</p>
                                <p class="text-sm text-gray-600">Économies, investissements, remboursement de dettes...
                                </p>
                            </div>
                        </div>

                        <!-- Graphique -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Répartition visuelle</h3>
                            <div class="w-full h-8 rounded-full overflow-hidden bg-gray-200 mb-4">
                                <div class="flex h-full">
                                    <div id="needsBar" class="bg-blue-500 h-full" style="width: 50%"></div>
                                    <div id="wantsBar" class="bg-purple-500 h-full" style="width: 30%"></div>
                                    <div id="savingsBar" class="bg-green-500 h-full" style="width: 20%"></div>
                                </div>
                            </div>
                            <div class="flex flex-wrap justify-center gap-4">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-gray-600">Besoins (50%)</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-purple-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-gray-600">Envies (30%)</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-gray-600">Épargne (20%)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personnalisation des priorités -->
                    <!-- <div id="customizationSection" class="hidden">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Personnaliser vos priorités</h2>
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <form id="customForm" class="space-y-6">
                                <div class="space-y-4">
                                    <label class="block text-sm font-medium text-gray-700">Ajustez vos pourcentages (total doit être 100%)</label>
                                    
                                    
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <label for="needsPercent" class="text-sm font-medium text-gray-700">Besoins</label>
                                            <span id="needsPercentValue" class="text-sm text-gray-500">50%</span>
                                        </div>
                                        <input type="range" id="needsPercent" name="needsPercent" min="0" max="100" value="50"
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-500">
                                    </div>
                                    
                                    
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <label for="wantsPercent" class="text-sm font-medium text-gray-700">Envies</label>
                                            <span id="wantsPercentValue" class="text-sm text-gray-500">30%</span>
                                        </div>
                                        <input type="range" id="wantsPercent" name="wantsPercent" min="0" max="100" value="30"
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-500">
                                    </div>
                                    
                                
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <label for="savingsPercent" class="text-sm font-medium text-gray-700">Épargne</label>
                                            <span id="savingsPercentValue" class="text-sm text-gray-500">20%</span>
                                        </div>
                                        <input type="range" id="savingsPercent" name="savingsPercent" min="0" max="100" value="20"
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-500">
                                    </div>
                                    
                                    <div class="flex justify-between items-center pt-2">
                                        <div class="text-sm font-medium">
                                            Total: <span id="totalPercent" class="text-blue-600">100%</span>
                                        </div>
                                        <div id="totalWarning" class="hidden text-sm text-red-500">
                                            Le total doit être égal à 100%
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <button type="button" id="resetButton"
                                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        Réinitialiser (50/30/20)
                                    </button>
                                    <button type="submit" id="applyButton"
                                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        Appliquer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const budgetForm = document.getElementById('budgetForm');
            const customForm = document.getElementById('customForm');
            const resultsSection = document.getElementById('resultsSection');
            const customizationSection = document.getElementById('customizationSection');

            // Éléments d'affichage des montants
            const needsAmount = document.getElementById('needsAmount');
            const wantsAmount = document.getElementById('wantsAmount');
            const savingsAmount = document.getElementById('savingsAmount');

            // Éléments de la barre de progression
            const needsBar = document.getElementById('needsBar');
            const wantsBar = document.getElementById('wantsBar');
            const savingsBar = document.getElementById('savingsBar');

            // Éléments de personnalisation
            const needsPercent = document.getElementById('needsPercent');
            const wantsPercent = document.getElementById('wantsPercent');
            const savingsPercent = document.getElementById('savingsPercent');
            const needsPercentValue = document.getElementById('needsPercentValue');
            const wantsPercentValue = document.getElementById('wantsPercentValue');
            const savingsPercentValue = document.getElementById('savingsPercentValue');
            const totalPercent = document.getElementById('totalPercent');
            const totalWarning = document.getElementById('totalWarning');
            const resetButton = document.getElementById('resetButton');

            let totalBudget = 0;

            // Fonction pour calculer et afficher la répartition du budget
            function calculateBudget(needs = 50, wants = 30, savings = 20) {
                // Calcul des montants
                const needsValue = (totalBudget * needs / 100).toFixed(2);
                const wantsValue = (totalBudget * wants / 100).toFixed(2);
                const savingsValue = (totalBudget * savings / 100).toFixed(2);

                // Mise à jour des affichages
                needsAmount.textContent = `€${needsValue}`;
                wantsAmount.textContent = `€${wantsValue}`;
                savingsAmount.textContent = `€${savingsValue}`;

                // Mise à jour de la barre de progression
                needsBar.style.width = `${needs}%`;
                wantsBar.style.width = `${wants}%`;
                savingsBar.style.width = `${savings}%`;

                // Afficher les sections de résultats
                resultsSection.classList.remove('hidden');
                customizationSection.classList.remove('hidden');
            }

            // Soumission du formulaire principal
            budgetForm.addEventListener('submit', function(e) {
                e.preventDefault();
                totalBudget = parseFloat(document.getElementById('totalBudget').value);

                if (totalBudget > 0) {
                    // Réinitialiser les curseurs à 50/30/20
                    // needsPercent.value = 50;
                    // wantsPercent.value = 30;
                    // savingsPercent.value = 20;
                    // needsPercentValue.textContent = '50%';
                    // wantsPercentValue.textContent = '30%';
                    // savingsPercentValue.textContent = '20%';
                    // totalPercent.textContent = '100%';

                    // Calculer et afficher le budget
                    calculateBudget();
                }
            });

            // Mise à jour des pourcentages lors du déplacement des curseurs
            // function updatePercentages() {
            //     const needs = parseInt(needsPercent.value);
            //     const wants = parseInt(wantsPercent.value);
            //     const savings = parseInt(savingsPercent.value);
            //     const total = needs + wants + savings;

            //     needsPercentValue.textContent = `${needs}%`;
            //     wantsPercentValue.textContent = `${wants}%`;
            //     savingsPercentValue.textContent = `${savings}%`;
            //     totalPercent.textContent = `${total}%`;

            //     // Vérifier si le total est égal à 100%
            //     if (total !== 100) {
            //         totalWarning.classList.remove('hidden');
            //         totalPercent.classList.add('text-red-600');
            //         totalPercent.classList.remove('text-blue-600');
            //     } else {
            //         totalWarning.classList.add('hidden');
            //         totalPercent.classList.remove('text-red-600');
            //         totalPercent.classList.add('text-blue-600');
            //     }
            // }

            // Événements pour les curseurs
            // needsPercent.addEventListener('input', updatePercentages);
            // wantsPercent.addEventListener('input', updatePercentages);
            // savingsPercent.addEventListener('input', updatePercentages);

            // // Réinitialiser à 50/30/20
            // resetButton.addEventListener('click', function() {
            //     needsPercent.value = 50;
            //     wantsPercent.value = 30;
            //     savingsPercent.value = 20;
            //     updatePercentages();
            // });

            // Appliquer les pourcentages personnalisés
            // customForm.addEventListener('submit', function(e) {
            //     e.preventDefault();

            //     const needs = parseInt(needsPercent.value);
            //     const wants = parseInt(wantsPercent.value);
            //     const savings = parseInt(savingsPercent.value);
            //     const total = needs + wants + savings;

            //     if (total === 100) {
            //         calculateBudget(needs, wants, savings);
            //     }
            // });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialisation du graphique
        const ctx = document.getElementById('expensesChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($categories->pluck('name')),
                datasets: [{
                    data: @json($categories->map(fn($category) => $category->transactions->where('type', 'expense')->sum('amount'))),
                    backgroundColor: @json($categories->map(fn($category) => $category->color))
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

        function openAddCategoryModal() {
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        const selectCategoryElements = document.querySelectorAll('.select-type');

        selectCategoryElements.forEach(element => {
            element.addEventListener('change', (e) => {
                const form = e.currentTarget.closest('form');
                const selectedCategory = form.querySelector('.select-category');
                if (e.currentTarget.value === 'income') {
                    selectedCategory.remove();
                } else {
                    if (!selectedCategory)
                        form.querySelector('.actions').insertAdjacentHTML('beforebegin', `<div class="mb-4 select-category">
                <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                <select class="w-full border rounded-lg p-2" name="category_id">
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>`);
                }

            });
        })

        // Fonction pour les alertes SweetAlert    
        function showSuccessAlert() {
            Swal.fire({
                title: 'Succès!',
                text: @json(session()->get('success')),
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }

        function showErrorAlert() {
            Swal.fire({
                title: 'Échec!',
                text: @json(session()->get('error')),
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

        @if (session()->has('error'))
            showErrorAlert()
        @endif
        @if (session()->has('success'))
            showSuccessAlert()
        @endif
    </script>
</x-layout>
