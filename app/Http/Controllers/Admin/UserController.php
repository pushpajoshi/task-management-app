<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index');
    }
    public function getData()

      {
       $users = User::with('roles')
        ->select(['id', 'name', 'email'])
        ->where('id', '!=', Auth::user()->id);

        return DataTables::of($users)
            ->addColumn('role', function ($user) {
            // Since single role per user
            return $user->roles->pluck('name')->first() ?? 'No Role';
        })
            ->addColumn('actions', function ($user) {
                return view('admin.users.actions', compact('user'))->render();
            })
            ->rawColumns(['role','actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::where('name','!=','admin')->get(); // Get all roles

        return view('admin.users.edit', compact('user', 'roles'));
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
        $user = User::findOrFail($id);
        $user->syncRoles($request->role);
        return redirect()->route('users.index')->with('success', 'User role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        $user = Role::findById($id);

        if(!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
        $user->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

}