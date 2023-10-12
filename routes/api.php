<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\TicketController;

Route::post('register',[UsuarioController::class,'register']);
Route::post('login',[UsuarioController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']],function(){
    //Rutas Auth
    Route::get('user-profile',[UsuarioController::class,'userProfile']);
    Route::put('user-update/{id}',[UsuarioController::class,'userUpdate']);
    Route::get('logout',[UsuarioController::class,'logout']);
    Route::get('userlist',[UsuarioController::class,'userList']);

    //Rutas Tickets
    Route::post('create-ticket',[TicketController::class,'createTicket']);
    Route::get('list-ticket',[TicketController::class,'listTicket']);
    Route::get('show-ticket/{id}',[TicketController::class,'showTicket']);
    Route::put('update-ticket/{id}',[TicketController::class,'updateTicket']);
    Route::delete('delete-ticket/{id}',[TicketController::class,'deleteTicket']);
    
});
