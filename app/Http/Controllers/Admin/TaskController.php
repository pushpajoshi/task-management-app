<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tasks.index');
    }
   public function getData()
    {
        $authUser = Auth::user();
        if($authUser->hasRole('admin')){
            $tasks = Task::select(['id', 'title', 'priority', 'status', 'created_by']);
        }else{
            $tasks = Task::select(['id', 'title', 'priority', 'status', 'created_by'])
              ->where('created_by', $authUser->id)
            ->orWhere('assigned_to', $authUser->id);
        }

        return DataTables::of($tasks)
            ->addColumn('priority', function ($task) {
                // $color = match($task->priority) {
                //     'low' => 'gray',
                //     'medium' => 'yellow',
                //     'high' => 'red',
                //     default => 'blue'
                // };
                // return '<span class="px-2 py-1 rounded-full bg-' . $color . '-500 ">' . ucfirst($task->priority) . '</span>';
                return ucfirst($task->priority);
            })
            ->addColumn('status', function($task) {
                $statuses = ['pending', 'completed'];
                $dropdown = '<select class="status-dropdown" data-id="'.$task->id.'">';
                foreach($statuses as $status) {
                    $selected = $task->status == $status ? 'selected' : '';
                    $dropdown .= "<option value='$status' $selected>".ucfirst($status)."</option>";
                }
                $dropdown .= '</select>';
                return $dropdown;
            })

            ->addColumn('actions', function ($task) {
                return view('admin.tasks.actions', compact('task'))->render();
            })
            ->rawColumns(['priority', 'status', 'actions'])
            ->make(true);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
    {
        $users = User::where('id', '!=', Auth::id())
            ->where(function ($query) {
                $query->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'admin');
                })
                ->orDoesntHave('roles'); // include users without any role
            })
            ->get();        
        return view('admin.tasks.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,completed',
            'deadline' => 'nullable|date|after_or_equal:today',
            'assigned_to'=> 'nullable|exists:users,id',
        ]);

        $task = new Task();
        $task->title = $validated['title'];
        $task->description = $validated['description'] ?? null;
        $task->priority = $validated['priority'];
        $task->status = $validated['status'];
        $task->deadline = $validated['deadline'] ?? null;
        $task->created_by = Auth::id();
        $task->assigned_to = $validated['assigned_to'] ?? null;
        $task->save();
           if($request->hasFile('documents')){
                foreach($request->file('documents') as $file){
                    $path = $file->store('task_documents', 'public');
                    $task->documents()->create([
                        'filename' => $file->getClientOriginalName(),
                        'filepath' => $path,
                        'uploaded_by'=> Auth::id(),
                    ]);
                }
            }
        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $task = Task::with(['documents','assignedTo','createdBy'])->findOrFail($id);
      if(!$task){
        return redirect()->route('tasks.index')->with('error', 'You task nor found');
      }

      return view('admin.tasks.show',compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $authId = Auth::user()->id;
        if(Auth::User()->hasRole('admin')){
            $task=Task::with('documents')->findOrFail($id);
        }else{
            $task = Task::with(['documents' => function($query) use ($authId) {
                $query->where('uploaded_by', $authId);
            }])->findOrFail($id);
        }
        

        // Only the creator can edit
        // if ($task->created_by !== auth()->id()) {
        //     return redirect()->route('tasks.index')
        //                     ->with('error', 'You are not authorized to edit this task.');
        // }

       $users = User::where('id', '!=', Auth::id())
        ->where(function ($query) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->orDoesntHave('roles'); // include users without any role
        })
        ->get();

        return view('admin.tasks.edit', compact('task', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        if ($task->created_by !== auth()->id()) {
            return redirect()->route('tasks.index')
                            ->with('error', 'You are not authorized to update this task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pending,completed',
            'assigned_to' => 'nullable|exists:users,id',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:5120',

        ]);

            $task->title = $request->title;
            $task->description = $request->description;
            $task->priority = $request->priority;
            $task->deadline = $request->deadline;
            $task->status = $request->status;
            $task->assigned_to = $request->assigned_to;
            $task->save();
           if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('task_documents', 'public');
                    $task->documents()->create([
                        'filename'    => $file->getClientOriginalName(),
                        'filepath'    => $path, // e.g. "task_documents/abc123.pdf"
                        'uploaded_by' => Auth::id(),
                    ]);
                }
            }


        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


public function destroy($id)
{
    $task = Task::find($id);

    if(!$task) {
        return redirect()->route('tasks.index')->with('error', 'Task not found.');
    }

    // Delete associated documents from storage and database
    $taskDocs = TaskDocument::where('task_id', $id)->get();
    foreach ($taskDocs as $doc) {
        Storage::disk('public')->delete($doc->filepath); // remove file
        $doc->delete(); // remove DB record
    }

    // Delete the task
    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'Task and its documents deleted successfully.');
}


public function deleteDocument(TaskDocument $document)
{
    try {
        Storage::disk('public')->delete($document->filepath);
        $document->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function viewDocument($id)
{
    $document = TaskDocument::findOrFail($id);
    $filePath = $document->filepath;

    if (!Storage::disk('public')->exists($filePath)) {
        abort(404, 'File not found.');
    }

    return redirect(Storage::url($filePath));;
}

public function updateStatus(Request $request, Task $task)
{
    $request->validate([
        'status' => 'required|in:pending,completed',
    ]);

    $task->update([
        'status' => $request->status,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully!',
    ]);
}


}