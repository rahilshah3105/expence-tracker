@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Top Stats & Chart -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Summary Card -->
        <div class="bg-indigo-600 rounded-lg shadow-xl p-6 text-white">
            <h3 class="text-lg font-semibold mb-2">Total Expenses (Selected Period)</h3>
            <p class="text-4xl font-bold">₹{{ number_format($total_expenses, 2) }}</p>
            <p class="mt-4 text-indigo-200 text-sm">Track your spending habits wisely.</p>
        </div>

        <!-- Chart -->
        <!-- Chart -->
        <div class="bg-white rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Expenses by Category</h3>
            <div class="relative h-64">
                <canvas id="expensesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('expenses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Month Filter -->
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                <input type="month" name="month" id="month" value="{{ request('month') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <div>
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
            </div>

            <!-- Add Button -->
            <div class="md:text-right">
                <a href="{{ route('expenses.create') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    + Add New Expense
                </a>
            </div>
        </form>
    </div>

    <!-- Expense List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($expenses as $expense)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $expense->date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $expense->description }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($expense->category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $expense->category->color }}20; color: {{ $expense->category->color }}">
                                <span class="w-2 h-2 mr-1 rounded-full" style="background-color: {{ $expense->category->color }}"></span>
                                {{ $expense->category->name }}
                            </span>
                        @else
                            <span class="text-gray-400">Uncategorized</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        ₹{{ number_format($expense->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('expenses.edit', $expense->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No expenses found. <a href="{{ route('expenses.create') }}" class="text-indigo-600 hover:underline">Add one now!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Prepare data for Chart.js
    const categories = @json($categories->pluck('name', 'id'));
    const categoryColors = @json($categories->pluck('color', 'id'));
    const expenseData = @json($expenses_by_category);

    const labels = [];
    const data = [];
    const backgroundColor = [];

    // Map fetched expense data to categories to ensure colors match
    for (const [id, total] of Object.entries(expenseData)) {
        if (categories[id]) {
            labels.push(categories[id]);
            data.push(total);
            backgroundColor.push(categoryColors[id] || '#cccccc');
        }
    }

    if (data.length === 0) {
        labels.push('No Data');
        data.push(1);
        backgroundColor.push('#e5e7eb');
    }

    const ctx = document.getElementById('expensesChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColor,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
</script>
@endsection
