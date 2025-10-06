<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Users') }}
    </h2>
  </x-slot>

  <div class="bg-white shadow-sm sm:rounded-lg p-6">
     <!-- <div class="flex justify-end mb-4">
          <a href="{{ route('users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Add User</a>

    </div> -->


    <table id="usersTable" class="min-w-full mt-4 border">
      <thead>
        <tr class="bg-gray-200 text-left">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Email</th>
          <th class="p-2 border">Role</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
    </table>
  </div>

  <script>
  $(document).ready(function() {
    $('#usersTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('users.data') }}',
      columns: [{
          data: 'id',
          name: 'id'
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'email',
          name: 'email'
        },
        {
          data: 'role',
          name: 'role'
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