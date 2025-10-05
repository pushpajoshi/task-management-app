<div class="flex items-center">
    <a href="{{ route('roles.edit', $role->id) }}" class="text-blue-600 mx-2" title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline" style="margin-left:10px">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 mx-2" onclick="return confirm('Want to delete ?')" title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>

