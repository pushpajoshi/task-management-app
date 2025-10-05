<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Task Details') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
        <div class="p-6  mt-10">
          {{-- Task Details --}}
          <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ $task->title }}</h3>
            <p class="text-gray-600 mb-3">{{ $task->description }}</p>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
              <div>
                <span class="font-semibold text-gray-800">Priority:</span>
                <span class="text-gray-600">{{ ucfirst($task->priority) }}</span>
              </div>

              <div>
                <span class="font-semibold text-gray-800">Status:</span>
                <span class="text-gray-600">{{ ucfirst($task->status) }}</span>
              </div>

              <div>
                <span class="font-semibold text-gray-800">Deadline:</span>
                <span class="text-gray-600">{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</span>
              </div>

              <div>
                <span class="font-semibold text-gray-800">Created By:</span>
                <span class="text-gray-600">{{ $task->createdBy->name ?? 'N/A' }}</span>
              </div>

              <div>
                <span class="font-semibold text-gray-800">Assigned To:</span>
                <span class="text-gray-600">{{ $task->assignedTo->name ?? 'N/A' }}</span>
              </div>
            </div>
          </div>

          {{-- Documents --}}
          <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Attached Documents</h3>

            @if ($task->documents && $task->documents->count() > 0)
            <table class="min-w-full border border-gray-200">
              <thead>
                <tr class="bg-gray-100 text-gray-700">
                  <th class="text-left px-4 py-2 border-b">File Name</th>
                  <th class="text-left px-4 py-2 border-b">Uploaded At</th>
                  <th class="text-center px-4 py-2 border-b">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($task->documents as $doc)
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-2 border-b">{{ basename($doc->filepath) }}</td>
                  <td class="px-4 py-2 border-b">{{ $doc->created_at->format('d M Y, h:i A') }}</td>
                  <td class="px-4 py-2 border-b text-center space-x-3">
                    {{-- View button --}}
                    <a href="{{ route('tasks.documents.view', $doc->id) }}" class="text-blue-600 ml-2" title="View"
                      target="_blank">
                      <i class="fas fa-eye"></i>
                    </a>

                   

                    <a href="{{ asset('storage/' . $doc->filepath) }}" class="text-green-600 hover:text-green-800" title="Download Document" download="{{ $doc->filename }}">
                      <i class="fas fa-download"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <p class="text-gray-600 italic">No documents uploaded for this task.</p>
            @endif
          </div>

          {{-- Back Button --}}
          <div class="mt-6">
            <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
              Back to Tasks
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>