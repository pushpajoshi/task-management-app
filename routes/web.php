<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});





Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/data', [UserController::class, 'getData'])->name('users.data');
    Route::resource('users', UserController::class)->except('users.index');
    //roles
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/data', [RoleController::class, 'getData'])->name('roles.data');
    Route::resource('roles', RoleController::class)->except('roles.index');

     //permissions
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('permissions/data', [PermissionController::class, 'getData'])->name('permissions.data');
    Route::resource('permissions', PermissionController::class)->except('permissions.index');
});

Route::middleware(['auth'])->group(function () {
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
//tasks
Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('tasks/data', [TaskController::class, 'getData'])->name('tasks.data');
Route::resource('tasks', TaskController::class)->except('tasks.index');
Route::delete('tasks/documents/{document}', [TaskController::class, 'deleteDocument'])->name('tasks.documents.delete');
Route::get('tasks/documents/{id}/view', [TaskController::class, 'viewDocument'])->name('tasks.documents.view');
Route::post('tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');


});


require __DIR__.'/auth.php';
