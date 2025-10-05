<a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 mx-2" title="Assign Role">
    <i class="fas fa-edit"></i>
</a>
<form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 mx-2" onclick="return confirm('Are you sure?')" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</form>
