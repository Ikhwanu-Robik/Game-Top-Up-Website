<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Top Up Buat Game Apa Nih?') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('Ini game-game yang kami support!') }}
                </div>
            </div>
            <div class="m-4 flex justify-center gap-4">
                @foreach ($games as $game)
                    <x-game-card>
                        <img src="{{ asset('storage/' . $game->icon) }}" alt="" class="w-32 h-32">
                        <span>{{ $game->name }}</span>
                        <a href="{{ route('home') }}#{{ Str::slug($game->name) }}"
                            class="text-sm text-gray-400 hover:text-black">Lihat Paket</a>
                    </x-game-card>
                @endforeach
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
            <x-input-error class="mt-2" :messages="$errors->get('package_id')" />
            <x-input-error class="mt-2" :messages="$errors->get('link_url')" />

            @foreach ($games as $game)
                <div class="m-4 flex flex-col justify-center gap-4" id="{{ Str::slug($game->name) }}">
                    <h2 class="font-bold text-xl">Paket {{ $game->name }}</h2>

                    <div class="flex gap-2">
                        @foreach ($packages as $package)
                            @if ($package->game_id == $game->id)
                                <x-top-up-package>
                                    <span class="text-xl">{{ $package->title }}</span>
                                    <span>IDR {{ $package->price }}</span>
                                    <span class="text-gray-500">{{ $package->items_count }}
                                        {{ $package->game_currency }}</span>
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
