<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ini Catatan Top Up Kamu') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                Halaman
                <nav id="pagination-link" class="inline-flex items-center mb-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    @foreach ($links as $page_number => $link)
                        <a href="{{ $link }}" class="px-4 py-2 rounded-md {{ request()->query('page', 1) == $page_number ? 'bg-gray-400' : 'bg-gray-800' }}">{{ $page_number }}</a>
                    @endforeach
                </nav>
            </div>
            <table class="border border-gray-400z">
                <tr class="[&>th]:pl-0 [&>th]:pr-0 [&>th]:border [&>th]:border-gray-400 bg-gray-400 text-xs md:[&>th]:px-4 md:[&>th]:py-2 md:text-base">
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
                    <tr class="[&>td]:p-0 [&>td]:bg-white [&>td]:border [&>td]:border-gray-300 text-xs md:[&>td]:p-4 md:text-base">
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->game_name }}</td>
                        <td>{{ $transaction->package_title }}</td>
                        <td>{{ $transaction->package_price }}</td>
                        <td>{{ $transaction->package_items }}</td>
                        <td>{{ Illuminate\Support\Carbon::create($transaction->created_at)->format('Y-m-d h:i:s') }}</td>
                        <td>{{ $transaction->method }}</td>
                        <td>{{ $transaction->status }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-app-layout>
