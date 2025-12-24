@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Category</h2>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Category Name</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="color" class="block text-gray-700 text-sm font-bold mb-2">Color (Hex)</label>
            <input type="color" name="color" id="color" class="shadow appearance-none border rounded w-full h-10 py-0 px-1 leading-tight focus:outline-none focus:shadow-outline" value="#3b82f6">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create Category
            </button>
            <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
    </form>
</div>
@endsection
