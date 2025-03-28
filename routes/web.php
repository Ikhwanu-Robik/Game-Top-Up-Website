<?php

use App\Http\Controllers\TopUpPackagesController;
use App\Http\Controllers\TopUpTransactionsController;
use App\Models\Game;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamesController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', function () {
    $topUpPackage = new TopUpPackagesController();
    $packages = $topUpPackage->getBills();
    return view('home', ['games' => Game::all(), 'packages' => $packages]);
})->name('home');

Route::post('/flipcallback', [TopUpTransactionsController::class, 'flipCallback']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home/refresh', function () {
        $topUpPackage = new TopUpPackagesController();
        $topUpPackage->refreshCache();
        return redirect()->route('home');
    })->name('home.refresh');

    Route::get('/report', [TopUpTransactionsController::class, 'report'])->name('report');

    Route::post('/transactions/store', [TopUpTransactionsController::class, 'saveTransaction'])->name('transactions.store');
});

Route::middleware(['auth', 'verified', EnsureUserIsAdmin::class])->group(function () {
    Route::get('/master/games', [GamesController::class, 'index'])->name('master.games');
    
    Route::get('/master/games/create', [GamesController::class, 'create'])->name('master.games.create');
    
    Route::post('/master/games/store', [GamesController::class, 'store'])->name('master.games.store');

    Route::get('/master/packages', [TopUpPackagesController::class, 'index'])->name('master.packages');

    Route::get('/master/packages/create', [TopUpPackagesController::class, 'create'])->name('master.packages.create');
    
    Route::post('/master/packages/store', [TopUpPackagesController::class, 'store'])->name('master.packages.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
