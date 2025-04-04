<?php

use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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

// Make the TopicController index the homepage
Route::get('/', [TopicController::class, 'index'])->name('topics.index');

// Other Topic routes
Route::get('/topics/create', [TopicController::class, 'create'])->name('topics.create');
Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
Route::get('/topics/{topic}', [TopicController::class, 'show'])->name('topics.show');
Route::get('/topics/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit');
Route::put('/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
Route::delete('/topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
