<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        return view('admin.roles.index');
    }

    public function getData()

      {
        $roles = Role::with('permissions')->get();

        return DataTables::of($roles)
        ->addColumn('permissions', function ($role) {
            // Get permission names and join with comma
            return $role->permissions->pluck('name')->implode(', ');
        })
            ->addColumn('actions', function ($role) {
                return view('admin.roles.actions', compact('role'))->render();
            })
            ->rawColumns(['permissions','actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
{
    $permissions = Permission::all();
    return view('admin.roles.create', compact('permissions'));
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:roles,name',
        'permissions' => 'array'
    ]);

    $role = Role::create(['name' => $request->name]);

    if ($request->has('permissions')) {
        $role->syncPermissions($request->permissions);
    }

    return redirect()->route('roles.index')->with('success', 'Role created successfully.');
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
        $role = Role::findOrFail($id);
        $permissions = Permission::all(); // all available permissions
        $rolePermissions = $role->permissions->pluck('name')->toArray(); // current role permissions

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
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
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'array'
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        // Sync permissions: adds checked, removes unchecked
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findById($id);

        if(!$role) {
            return redirect()->route('roles.index')->with('error', 'Role not found.');
        }

        // Prevent deleting admin role (optional)
        if($role->name === 'admin' || $role->name=== 'user') {
            return redirect()->route('roles.index')->with('error', 'Cannot delete admin or user role.');
        }

        $role->delete();


        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

}
