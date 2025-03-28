<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    function index() {
        $games = Game::all();

        return view('master.games', ['games' => $games]);
    }

    function create() {
        return view('master.create-game');
    }

    function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'icon' => 'required',
            'currency' => 'required'
        ]);

        $path_name = $request->file('icon')->storePublicly('game_icons');

        Game::create([
            'name' => $validated['name'],
            'icon' => $path_name,
            'currency' => $validated['currency']
        ]);

        return redirect()->route('master.games');
    }
}
