<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Paket Top Up') }}
        </h2>
        <a href="{{ route('master.packages.create') }}"
            class="text-gray-400 hover:text-blue-600 hover:underline hover:decoration-blue-600">Tambah Paket</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('Paket top up yang kami sediakan') }}
                </div>
            </div>
            
            @foreach ($games as $game)
                <div class="m-4 flex flex-wrap flex-col justify-center gap-4" id="{{ Str::slug($game->name) }}">
                    <h2 class="font-bold text-xl">Paket {{ $game->name }}</h2>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($packages as $package)
                            @if ($package->game_id == $game->id)
                                <x-top-up-package>
                                    <span class="text-xl">{{ $package->title }}</span>
                                    <span>IDR {{ $package->price }}</span>
                                    <span class="text-gray-500">{{ $package->items_count }} {{ $package->game_currency }}</span>
                                    <x-primary-button>
                                        <a href="{{ route('transactions.create', ['package' => $package->id]) }}">Beli</a>
                                    </x-primary-button>
                                </x-top-up-package>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
