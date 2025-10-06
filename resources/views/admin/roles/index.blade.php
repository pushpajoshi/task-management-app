<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Roles') }}
    </h2>
  </x-slot>

  <div class="bg-white shadow-sm sm:rounded-lg p-6">
    <div class="flex justify-end mb-4">
      <a href="{{ route('roles.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Add Role</a>
    </div>

    <table id="rolesTable" class="min-w-full mt-4 border">
      <thead>
        <tr class="bg-gray-200 text-left">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Permissions</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <style>
  /* Wrap text in permissions column */
  .text-wrap {
    white-space: normal !important;
    /* allow wrapping */
    word-break: break-word;
    /* break long words */
    max-width: 300px;
    optional: adjust width
  }
  </style>

  <script>
  $(document).ready(function() {
    $('#rolesTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('roles.data') }}',
      columns: [{
          data: 'id',
          name: 'id'
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'permissions',
          name: 'permissions',
          className: 'text-wrap',
        },
        {
          data: 'actions',
          name: 'actions',
          orderable: false,
          searchable: false
        }
      ]
    });
  });
  </script>
</x-app-layout>