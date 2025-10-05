














<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Permission
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-6">
        <!-- Success / Error Messages -->
        @if(session('success'))
            <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Permission Create Form -->
         <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Permission Name -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="name">Permission Name</label>
                <input type="text" name="name" id="name" 
                       class="w-full px-3 py-2 border rounded"
                        value="{{ old('name', $permission->name) }}" >
            </div>
            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Update Permission
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

