<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function(){
    Route::get('/me', [AuthController::class, 'me']); //authUser
    Route::get('/logout', [AuthController::class, 'logout']); //logout

    //user
    Route::post('/create-user', [UserController::class, 'createUser']); //createUser
    Route::get('/all-user', [UserController::class, 'allUser'])->name('allUser'); //allUser
    Route::get('/single-user/{id}', [UserController::class, 'singleUser']); //singleUser by id
    Route::post('/update-user/{id}', [UserController::class, 'updateUser']); //updateUser by id
    
    //workspace
    Route::get('/all-workspace', [WorkspaceController::class, 'allWorkspace'])->name('allWorkspace');
    Route::post('/create-workspace', [WorkspaceController::class, 'createWorkspace'])->name('createWorkspace'); //createWorkspace
    Route::get('/single-workspace/{id}', [WorkspaceController::class, 'singleWorkspace'])->name('singleWorkspace'); //singleWorkspace
    Route::post('/update-workspace/{id}', [WorkspaceController::class, 'updateWorkspace'])->name('updateWorkspace'); //updateWorkspace
    Route::get('/delete-workspace/{id}', [WorkspaceController::class, 'deleteWorkspace'])->name('deleteWorkspace'); //deleteWorkspace
});

//auth
Route::post('/login', [AuthController::class, 'login'])->name('login'); //login
Route::post('/register', [AuthController::class, 'register'])->name('register'); //register





