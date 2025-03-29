<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ini Catatan Top Up Kamu') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <table class="border border-gray-400">
                <tr class="[&>th]:pl-4 [&>th]:pr-4 [&>th]:border [&>th]:border-gray-400 bg-gray-400">
                    <th>id</th>
                    <th>Game</th>
                    <th>Paket</th>
                    <th>Harga</th>
                    <th>Banyak Item</th>
                    <th>Tanggal</th>
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                </tr>
                @foreach ($transactions as $transaction)
                    <tr class="[&>td]:p-4 [&>td]:border [&>td]:border-gray-300">
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->game_name }}</td>
                        <td>{{ $transaction->package_title }}</td>
                        <td>{{ $transaction->package_price }}</td>
                        <td>{{ $transaction->package_items }}</td>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->method }}</td>
                        <td>{{ $transaction->status }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-app-layout>
