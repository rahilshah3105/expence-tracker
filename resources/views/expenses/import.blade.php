@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Bulk Import Expenses</h2>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Paste your expenses below. We'll try to guess the details.
                    <br>
                    <strong>Format example:</strong> "Lunch 500" or "1200 Electricity Bill"
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('expenses.parse') }}" method="POST">
        @csrf
        
        <div class="mb-6">
            <label for="bulk_text" class="block text-gray-700 text-sm font-bold mb-2">Paste Text</label>
            <textarea name="bulk_text" id="bulk_text" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline font-mono" placeholder="Coffee 250&#10;Uber 500&#10;Grocery 1200" required></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Next: Preview & Edit
            </button>
            <a href="{{ route('expenses.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
    </form>
</div>
@endsection
