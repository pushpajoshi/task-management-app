<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Role
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

        <!-- Role Create Form -->
        <form action="{{ route('roles.store') }}" method="POST" class="bg-white shadow-md rounded p-6">
            @csrf

            <!-- Role Name -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="name">Role Name</label>
                <input type="text" name="name" id="name" 
                       class="w-full px-3 py-2 border rounded"
                       value="{{ old('name') }}" required>
            </div>

            <!-- Permissions -->
            <div class="mb-4">
                <h3 class="font-semibold text-gray-700 mb-2">Assign Permissions</h3>
                <div class="flex flex-wrap gap-4">
                    @foreach($permissions as $permission)
                        <label class="flex items-center gap-2 border p-2 rounded">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                   {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Create Role
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
