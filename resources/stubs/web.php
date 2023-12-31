<?php

use App\Http\Controllers\Admin\ExampleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return 'welcome';
});

Route::name('admin.')->prefix('admin')->middleware('auth')->group(function () {
    Route::get('example', ExampleController::class)->name('example');
});

require __DIR__.'/auth.php';
