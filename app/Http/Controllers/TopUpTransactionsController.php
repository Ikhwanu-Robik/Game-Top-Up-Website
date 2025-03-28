<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\TopUpPackage;
use Illuminate\Http\Request;
use App\Models\TopUpTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class TopUpTransactionsController extends Controller
{
    function flipCallback(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required|json',
            'token' => 'required'
        ]);

        if ($validated['token'] != config('app.flip_api_token')) {
            return response('Forbidden', 403);
        }

        $data = json_decode($validated['data']);

        $transactionRecord = TopUpTransaction::where('flip_link_id', '=', $data->bill_link_id)->get()->get(0);
        $transactionRecord->method = $data->sender_bank;
        $transactionRecord->status = 1;
        $transactionRecord->save();

        return response('Callback heard you');
    }

    function saveTransaction(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:top_up_packages,id'
        ]);

        $package = TopUpPackage::find($validated['package_id']);
        $game = Game::find($package->game_id);

        // create a Flip bill with data from package
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . config('app.flip_api_key'),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])
            ->asForm()  // Set the request content type to x-www-form-urlencoded
            ->post('https://bigflip.id/big_sandbox_api/v2/pwf/bill', [
                'title' => ($package->title . " - " . $package->items_count . " " . $game->currency),
                'type' => 'SINGLE',
                'amount' => $package->price,
                'expired_date' => '',
                'redirect_url' => config('app.url') . 'home',
                'is_address_required' => '',
                'is_phone_number_required' => '',
                'step' => 1,
                'sender_name' => '',
                'sender_email' => '',
                'sender_phone_number' => '',
                'sender_address' => '',
                'sender_bank' => '',
                'sender_bank_type' => '',
            ]);

        TopUpTransaction::create([
            'user_id' => $validated['user_id'],
            'package_id' => $validated['package_id'],
            'flip_link_id' => $response['link_id'],
            'flip_link_url' => $response['link_url']
        ]);

        $protocol = config('app.env' == 'production') ? 'https://' : 'http:///';

        return redirect()->away($protocol . $response['link_url']);
    }

    static private function getReport() {
        $transactions = TopUpTransaction::where('user_id', '=', Auth::id())->latest()->get();

        foreach ($transactions as $transaction) {
            $package = TopUpPackage::find($transaction->package_id);
            $game = Game::find($package->game_id);

            $transaction->package_title = $package->title;
            $transaction->package_price = $package->price;
            $transaction->package_items = $package->items_count;
            $transaction->game_name = $game->name;

            $transaction->status = ($transaction->status == 1) ? 'Success' : 'Pending';
        }

        return $transactions;
    }

    function report()
    {
        $transactions = null;

        if (Cache::has('transaction_history_' . Auth::id())) {
            $transactions = json_decode(Crypt::decryptString(Cache::get('transaction_history_' . Auth::id())));
        } else {
            $transactions = TopUpTransactionsController::getReport();

            $json_packages = json_encode($transactions);
            $enc_json_packages = Crypt::encryptString($json_packages);
            Cache::put('transaction_history_' . Auth::id(), $enc_json_packages, now()->addMinutes(10));
        }

        return view('report', ['transactions' => $transactions]);
    }

    function refreshCache() {
        Cache::forget('transaction_history_' . Auth::id());
        $json_packages = json_encode(TopUpTransactionsController::getReport());
        $enc_json_packages = Crypt::encryptString($json_packages);
        Cache::put('transaction_history_' . Auth::id(), $enc_json_packages, now()->addMinutes(10));
    }
}
