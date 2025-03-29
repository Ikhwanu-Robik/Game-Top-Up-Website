<?php

namespace App\Http\Controllers;

use App\Models\Game;

class HomeController extends Controller
{
    public function __invoke()
    {
        $games = Game::all();
        $packages = TopUpPackagesController::getBills();

        return view('home', ['games' => $games, 'packages' => $packages]);
    }
}
