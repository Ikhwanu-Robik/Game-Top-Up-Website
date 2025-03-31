<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\TopUpPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

class TopUpPackagesController extends Controller
{
    static private function getPackagesData()
    {
        $packages = TopUpPackage::all();
        $games = Game::all();

        foreach ($packages as $package) {
            foreach ($games as $game) {
                if ($package->game_id == $game->id) {
                    $package->game_currency = $game->currency;
                }
            }
        }
        
        return $packages;
    }

    function index()
    {
        Gate::authorize('view-create-package');

        $result_packages = TopUpPackagesController::getPackagesData();

        return view('master.packages', ['packages' => $result_packages, 'games' => Game::all()]);
    }

    static function getBills()
    {
        $result_packages = Cache::remember('top-up-packages', now()->addMinutes(10), function () {
            return json_encode(TopUpPackagesController::getPackagesData());
        });
        $result_packages = json_decode($result_packages);

        return $result_packages;
    }

    function create()
    {
        Gate::authorize('view-create-package');
        
        $games = Game::all();
        return view('master.create-package', ['games' => $games]);
    }

    function store(Request $request)
    {
        Gate::authorize('create-package');

        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'title' => 'required',
            'item' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:10000'
        ]);

        TopUpPackage::create([
            'game_id' => $validated['game_id'],
            'title' => $validated['title'],
            'items_count' => $validated['item'],
            'price' => $validated['price']
        ]);

        Cache::flush();

        return redirect()->route('master.packages');
    }
}
