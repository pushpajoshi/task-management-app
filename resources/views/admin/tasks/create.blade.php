<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
      {{ __('Create Task') }}
    </h2>
     <div class="flex justify-end mb-4">
      <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Back</a>
    </div>
  </x-slot>

 
  <div class="bg-white shadow-sm sm:rounded-lg p-2">~
    @if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <form action="{{ route('tasks.store') }}" method="POST"  enctype="multipart/form-data" >
      @csrf

      <div class="flex justify-center py-10">
        <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow-lg border border-gray-200">

          <!-- Title -->
          <div class="grid grid-cols-12 items-center mb-6">
            <label for="title" class="col-span-3 text-sm font-medium text-gray-700">Title:</label>
            <div class="col-span-9">
              <input type="text" name="title" id="title" value="{{ old('title') }}"
                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200" required>
            </div>
          </div>

          <!-- Description -->
          <div class="grid grid-cols-12 items-start mb-6">
            <label for="description" class="col-span-3 text-sm font-medium text-gray-700 mt-2">Description:</label>
            <div class="col-span-9">
              <textarea name="description" id="description" rows="3"
                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200"
                placeholder="Enter task details...">{{ old('description') }}</textarea>
            </div>
          </div>

          <!-- Priority -->
          <div class="grid grid-cols-12 items-center mb-6">
            <label for="priority" class="col-span-3 text-sm font-medium text-gray-700">Priority:</label>
            <div class="col-span-9">
              <select name="priority" id="priority"
                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200" required>
                <option value="">Select</option>
                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
              </select>
            </div>
          </div>

          <!-- Status -->
          <div class="grid grid-cols-12 items-center mb-6">
            <label for="status" class="col-span-3 text-sm font-medium text-gray-700">Status:</label>
            <div class="col-span-9">
              <select name="status" id="status"
                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200" required>
                <option value="">Select</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
              </select>
            </div>
          </div>

          <!-- Deadline -->
          <div class="grid grid-cols-12 items-center mb-6">
            <label for="deadline" class="col-span-3 text-sm font-medium text-gray-700">Deadline:</label>
            <div class="col-span-9">
              <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}"
                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200">
            </div>
          </div>

          <!-- Assign To -->
          <div class="grid grid-cols-12 items-center mb-8">
            <label for="assigned_to" class="col-span-3 text-sm font-medium text-gray-700">Assign To:</label>
            <div class="col-span-9">
              <select name="assigned_to" id="assigned_to"
                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200" required>
                <option value="">-- Select User --</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                  {{ $user->name }} ({{ $user->roles->pluck('name')->implode(', ') }})
                </option>
                @endforeach
              </select>
            </div>
          </div>

            <!-- Documents Upload -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="documents">Upload Documents</label>
                <input type="file" name="documents[]" id="documents" multiple class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">You can upload multiple files (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG).</p>
            </div>

          <!-- Submit Button -->

        </div>
      </div>



      <!-- Submit Button -->
      <div class="flex justify-center mt-6">
           <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded mr-2 hover:bg-gray-600">
          Cancel
        </a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          Create Task
        </button>
      </div>
    </form>
  </div>
</x-app-layout>