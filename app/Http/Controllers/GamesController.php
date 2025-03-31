<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

class GamesController extends Controller
{
    function index() {
        Gate::authorize('view-create-game');

        $games = Game::all();

        return view('master.games', ['games' => $games]);
    }

    function create() {
        Gate::authorize('view-create-game');

        return view('master.create-game');
    }

    function store(Request $request) {
        Gate::authorize('create-game');

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

        Cache::flush();

        return redirect()->route('master.games');
    }
}
