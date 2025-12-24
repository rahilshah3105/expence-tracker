@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Expense</h2>

    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <input type="text" name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="e.g. Weekly Groceries">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount (₹)</label>
                <input type="number" step="0.01" name="amount" id="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="0.00">
            </div>
            <div>
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
        </div>

        <div class="mb-6">
            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
            <div class="relative">
                <select name="category_id" id="category_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Select a Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-1">Don't see your category? <a href="{{ route('categories.create') }}" class="text-indigo-600 hover:underline">Create a new one</a>.</p>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Save Expense
            </button>
            <a href="{{ route('expenses.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
    </form>
</div>
@endsection
