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
            return abort(403);
        }

        $link_id = json_decode($validated['data'])->bill_link_id;
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . config('app.flip_api_key'),
            'Accept' => 'application/json'
        ])->get("https://bigflip.id/big_sandbox_api/v2/pwf/{$link_id}/payment");
        if ($response->failed()) {
            abort($response->getStatusCode());
        }
        $dataFromAPI = $response['data'][0];
        $transactionRecord = TopUpTransaction::where('flip_link_id', '=', $link_id)->first();
        $transactionRecord->method = $dataFromAPI['sender_bank'];

        $status = 0;
        switch ($dataFromAPI['status']) {
            case 'SUCCESSFUL':
                $status = 1;
                break;
            case 'CANCELLED':
                $status = 2;
                break;
            case 'FAILED':
                $status = 3;
                break;
            default:
                $status = 4;
        }
        $transactionRecord->status = $status;

        $transactionRecord->save();

        Cache::flush();

        return response('Callback heard you');
    }

    function createTransaction(Request $request) {
        abort_if(!$request->query('package'), 400);

        $package = TopUpPackage::find($request->query('package'));

        return view('transaction', ['package' => $package]);
    }

    function saveTransaction(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:top_up_packages,id',
            'game_account_id' => 'required'
        ]);

        $package = TopUpPackage::find($validated['package_id']);
        $game = Game::find($package->game_id);

        // GameController::sendItem($game->id, $validated['game_account_id']);

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

        Cache::flush();

        $protocol = config('app.env' == 'production') ? 'https://' : 'http:///';

        return redirect()->away($protocol . $response['link_url']);
    }

    static private function getReport()
    {
        $transactions = TopUpTransaction::where('user_id', '=', Auth::id())->latest()->get();

        foreach ($transactions as $transaction) {
            $package = TopUpPackage::find($transaction->package_id);
            $game = Game::find($package->game_id);

            $transaction->package_title = $package->title;
            $transaction->package_price = $package->price;
            $transaction->package_items = $package->items_count;
            $transaction->game_name = $game->name;

            switch ($transaction->status) {
                case 1:
                    $transaction->status = 'SUCCESSFUL';
                    break;
                case 2:
                    $transaction->status = 'CANCELLED';
                    break;
                case 3:
                    $transaction->status = 'FAILED';
                    break;
                case 4:
                    $transaction->status = 'UNKNOWN';
                    break;
                default:
                    $transaction->status = 'PENDING';
            }
        }

        return $transactions;
    }

    function report()
    {
        $transactions = Cache::remember('transaction_history_' . Auth::id(), now()->addMinutes(10), function () {
            $transactions = TopUpTransactionsController::getReport();

            $json_packages = json_encode($transactions);
            $enc_json_packages = Crypt::encryptString($json_packages);

            return $enc_json_packages;
        });
        $transactions = json_decode(Crypt::decryptString($transactions));

        return view('report', ['transactions' => $transactions]);
    }
}
