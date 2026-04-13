<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Home Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Contact Form (User Side)
|--------------------------------------------------------------------------
*/

Route::get('/contact', [ContactController::class, 'showForm'])
    ->name('contact.form');

Route::post('/contact', [ContactController::class, 'submitForm'])
    ->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Admin Panel (Contacts Management)
|--------------------------------------------------------------------------
*/

Route::get('/contact/dashboard', [ContactController::class, 'index'])->name('contacts.index');

Route::get('/contact/trash', [ContactController::class, 'trash'])->name('contacts.trash');

Route::get('/contact/delete/{id}', [ContactController::class, 'delete'])->name('contacts.delete');

Route::get('/contact/restore/{id}', [ContactController::class, 'restore'])->name('contacts.restore');

Route::get('/contact/force-delete/{id}', [ContactController::class, 'forceDelete'])->name('contacts.forceDelete');