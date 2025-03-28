<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\TopUpPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TopUpPackagesController extends Controller
{
    private function callFlipApi()
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
        $result_packages = TopUpPackagesController::callFlipApi();

        return view('master.packages', ['packages' => $result_packages, 'games' => Game::all()]);
    }

    function getBills()
    {
        $result_packages = null;
        if (!Cache::has('top-up-packages')) {
            $json_packages = json_encode(TopUpPackagesController::callFlipApi());
            Cache::put('top-up-packages', $json_packages, now()->addMinutes(10));

            $result_packages = json_decode(Cache::get('top-up-packages'));
        }
        else {
            $result_packages = json_decode(Cache::get('top-up-packages'));
        }

        return $result_packages;
    }

    function refreshCache() {
        Cache::forget('top-up-packages');
        $json_packages = json_encode(TopUpPackagesController::callFlipApi());
        Cache::put('top-up-packages', $json_packages, now()->addMinutes(10));
    }

    function create()
    {
        $games = Game::all();
        return view('master.create-package', ['games' => $games]);
    }

    function store(Request $request)
    {
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

        return redirect()->route('master.packages');
    }
}
