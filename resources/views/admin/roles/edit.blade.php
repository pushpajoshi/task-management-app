<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Role Name -->
            <div class="mb-6">
                <x-label for="name" :value="__('Role Name')" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                         value="{{ old('name', $role->name) }}" required />
                @error('name') 
                    <span class="text-red-600 text-sm">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Permissions -->
            <div class="mb-6">
                <h3 class="font-semibold mb-3">Permissions</h3>
                <div class="flex flex-wrap gap-4">
                    @foreach($permissions as $permission)
                        <label class="flex items-center space-x-2 gap-2 border p-2 rounded">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                   class="form-checkbox h-5 w-5 text-blue-600"
                                   {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                            <span class="text-gray-700">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded shadow">
                    Update Role
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
