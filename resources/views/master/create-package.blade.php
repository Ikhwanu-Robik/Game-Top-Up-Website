<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Paket Top Up') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>          
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Isi data paket top up") }}
                            </p>
                        </header>
                    
                        <form method="post" action="{{ route('master.packages.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf
                    
                            <div>
                                <x-input-label for="game_id" :value="__('Game')" />
                                <select name="game_id" id="game_id">
                                    @foreach ($games as $game)
                                        <option value="{{ $game->id }}">{{ $game->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('game_id')" />
                            </div>
                            
                            <div>
                                <x-input-label for="title" :value="__('Nama Paket')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus autocomplete="title" />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>
                            
                            <div>
                                <x-input-label for="item" :value="__('Jumlah item game')" />
                                <x-text-input id="item" name="item" type="number" min="1" class="mt-1 block w-full" :value="old('item')" required autofocus autocomplete="item" />
                                <x-input-error class="mt-2" :messages="$errors->get('item')" />
                            </div>
                            
                            <div>
                                <x-input-label for="price" :value="__('Harga paket (Rp.)')" />
                                <x-text-input id="price" name="price" type="number" min="10000" class="mt-1 block w-full" :value="old('price')" required autofocus autocomplete="price" />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>
                    
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>