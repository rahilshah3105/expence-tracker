@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Review & Import</h2>

    <form action="{{ route('expenses.storeBulk') }}" method="POST">
        @csrf
        
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (₹)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="expenses-list">
                    @foreach($parsedExpenses as $index => $expense)
                    <tr id="row-{{ $index }}">
                        <td class="px-4 py-2">
                            <input type="date" name="expenses[{{ $index }}][date]" value="{{ $expense['date'] }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-1" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="text" name="expenses[{{ $index }}][description]" value="{{ $expense['description'] }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-1" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" step="0.01" name="expenses[{{ $index }}][amount]" value="{{ $expense['amount'] }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-1" required>
                        </td>
                        <td class="px-4 py-2">
                            <select name="expenses[{{ $index }}][category_id]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-1" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $expense['category_id'] == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-2">
                            <button type="button" onclick="removeRow({{ $index }})" class="text-red-600 hover:text-red-900">Remove</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Confirm & Import All
            </button>
            <a href="{{ route('expenses.import') }}" class="text-gray-600 hover:text-gray-800">Back</a>
        </div>
    </form>
</div>

<script>
    function removeRow(index) {
        document.getElementById('row-' + index).remove();
    }
</script>
@endsection
