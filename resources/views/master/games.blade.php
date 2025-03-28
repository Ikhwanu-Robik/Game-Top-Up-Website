<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Game') }}
        </h2>
        <a href="{{ route('master.games.create') }}"
            class="text-gray-400 hover:text-blue-600 hover:underline hover:decoration-blue-600">Tambah Game</a>
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
                    </x-game-card>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
