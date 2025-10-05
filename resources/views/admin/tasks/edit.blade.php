@php
$today = date('Y-m-d'); // current date in YYYY-MM-DD format
@endphp
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
      {{ __('Edit Task') }}
    </h2>
     <div class="flex justify-end mb-4">
      <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Back</a>
    </div>
  </x-slot>

  <div class="bg-white shadow-sm sm:rounded-lg p-2">
    <form method="POST" action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="flex justify-center py-10">
        <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow-lg border border-gray-200">

          {{-- Title --}}
          <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
          </div>

          {{-- Description --}}
          <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('description', $task->description) }}</textarea>
          </div>

          {{-- Priority --}}
          <div class="mb-4">
            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
            <select name="priority" id="priority"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
              <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
              <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
              <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
            </select>
          </div>

          {{-- Deadline --}}
          <div class="mb-4">
            <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
            <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $task->deadline) }}"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
              min="{{ $today }}">
          </div>

          {{-- Status --}}
          <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
              <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
          </div>

          {{-- Assigned To --}}
          <div class="mb-4">
            <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign To</label>
            <select name="assigned_to" id="assigned_to"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
              <option value="">-- Select User --</option>
              @foreach($users as $user)
              <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="documents">Upload Documents</label>
            <input type="file" name="documents[]" id="documents" multiple class="w-full border rounded px-3 py-2">
            <p class="text-sm text-gray-500 mt-1">You can upload multiple files (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG).
            </p>
          </div>

          @if($task->documents->count())
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Existing Documents</label>
            <div class="flex flex-wrap gap-2">
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Existing Documents</label>
                <div class="flex flex-wrap gap-2" id="document-list">
                  @foreach($task->documents as $doc)
                  <div class="relative bg-gray-100 border rounded px-3 py-1 flex items-center gap-2"
                    id="doc-{{ $doc->id }}">
                    <!-- Download Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0 0l-4-4m4 4l4-4M12 4v8" />
                    </svg>

                    <a href="{{ asset('storage/' . $doc->filepath) }}" download="{{ $doc->filename }}"
                      class="underline text-blue-600">
                      {{ $doc->filename }}
                    </a>

                    <button type="button" data-url="{{ route('tasks.documents.delete', $doc->id) }}"
                      class="delete-document absolute top-0 right-0 text-red-600 font-bold px-1 py-0.5 rounded hover:bg-red-100">
                      X
                    </button>
                  </div>

                  @endforeach
                </div>
              </div>

            </div>
          </div>
          @endif
        </div>
      </div>


      {{-- Buttons --}}
      <div class="flex justify-center">
        <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded mr-2 hover:bg-gray-600">
          Cancel
        </a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          Update Task
        </button>
      </div>
    </form>
  </div>
</x-app-layout>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const deleteButtons = document.querySelectorAll('.delete-document');

  deleteButtons.forEach(button => {
    button.addEventListener('click', function() {
      const url = this.dataset.url;
      const docId = this.dataset.id;
      const token = "{{ csrf_token() }}";

      if (!confirm('Are you sure you want to delete this document?')) return;

      fetch(url, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const docDiv = document.getElementById(`doc-${docId}`);
            if (docDiv) docDiv.remove();
          } else {
            alert('Failed to delete document.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('An error occurred.');
        });
    });
  });
});
</script>