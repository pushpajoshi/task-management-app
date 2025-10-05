<div class="flex space-x-2">
    @can('view', $task)
        <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 mx-2" title="View">
            <i class="fas fa-eye"></i>
        </a>
    @endcan
    @can('update', $task)
        <a href="{{ route('tasks.edit', $task->id) }}" class="text-green-600 mx-2" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    @endcan
    @can('delete', $task)
        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline" style="margin-left:10px">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 mx-2" onclick="return confirm('Want to delete?')" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endcan
</div>
