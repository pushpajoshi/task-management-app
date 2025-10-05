<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Tasks') }}
    </h2>

    @if(session('success'))
    <div class="flex justify-end mb-4">
      <div id="toast-success"
          class="fixed top-5 right-5 bg-green-200 text-green-800 px-4 py-2 rounded shadow-lg opacity-0 transform translate-x-20 transition-all duration-500">
          {{ session('success') }}
      </div>
    </div>
    <script>
      const toast = document.getElementById('toast-success');
      toast.classList.remove('opacity-0', 'translate-x-20');
      toast.classList.add('opacity-100', 'translate-x-0');
      setTimeout(() => {
          toast.classList.remove('opacity-100');
          toast.classList.add('opacity-0', 'translate-x-20');
      }, 3000);
    </script>
    @endif

    @if(session('error'))
    <div class="flex justify-end mb-4">
      <div id="toast-error"
          class="fixed top-5 right-5 bg-red-200 text-red-800 px-4 py-2 rounded shadow-lg opacity-0 transform translate-x-20 transition-all duration-500">
          {{ session('error') }}
      </div>
    </div>
    <script>
      const toast = document.getElementById('toast-error');
      toast.classList.remove('opacity-0', 'translate-x-20');
      toast.classList.add('opacity-100', 'translate-x-0');
      setTimeout(() => {
          toast.classList.remove('opacity-100');
          toast.classList.add('opacity-0', 'translate-x-20');
      }, 3000);
    </script>
    @endif
  </x-slot>

  <div class="bg-white shadow-sm sm:rounded-lg p-6">
    @can('create',  \App\Models\Task::class)

    <div class="flex justify-end mb-4">
      <a href="{{ route('tasks.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Add Task</a>
    </div>
        @endcan


    <table id="tasksTable" class="min-w-full mt-4 border">
      <thead>
        <tr class="bg-gray-200 text-left">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Title</th>
          <th class="p-2 border">Priority</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
    </table>
  </div>

  <script>
  $(document).ready(function() {
    $('#tasksTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('tasks.data') }}',
      language: {
        emptyTable: "No data available"
      },
      columns: [
        { data: 'id', name: 'id' },
        { data: 'title', name: 'title' },
        { data: 'priority', name: 'priority' },
        { data: 'status', name: 'status'},
        { data: 'actions', name: 'actions', orderable: false, searchable: false }
      ]
    });
  });
  </script>
  <script>
$(document).on('change', '.status-dropdown', function() {
    var taskId = $(this).data('id');
    var status = $(this).val();

    $.ajax({
        url: '/tasks/' + taskId + '/update-status',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status: status
        },
        success: function(response) {
            if(response.success) {
                // Show success toast
                const toast = document.createElement('div');
                toast.className = 'fixed top-5 right-5 bg-green-200 text-green-800 px-4 py-2 rounded shadow-lg opacity-0 transform translate-x-20 transition-all duration-500';
                toast.innerText = response.message;
                document.body.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('opacity-0', 'translate-x-20');
                    toast.classList.add('opacity-100', 'translate-x-0');
                }, 100);

                // Animate out after 3s and remove
                setTimeout(() => {
                    toast.classList.remove('opacity-100');
                    toast.classList.add('opacity-0', 'translate-x-20');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);

                // Refresh DataTable
                $('#tasksTable').DataTable().ajax.reload(null, false);
            }
        },
        error: function(xhr) {
            // Show error toast
            const toast = document.createElement('div');
            toast.className = 'fixed top-5 right-5 bg-red-200 text-red-800 px-4 py-2 rounded shadow-lg opacity-0 transform translate-x-20 transition-all duration-500';
            toast.innerText = 'Something went wrong!';
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-x-20');
                toast.classList.add('opacity-100', 'translate-x-0');
            }, 100);

            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0', 'translate-x-20');
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    });
});

</script>

</x-app-layout>
