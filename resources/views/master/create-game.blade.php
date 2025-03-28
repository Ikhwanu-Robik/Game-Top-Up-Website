<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Game Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>          
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Isi data game baru") }}
                            </p>
                        </header>
                    
                        <form method="post" action="{{ route('master.games.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf
                    
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            
                            <div>
                                <x-input-label for="currency" :value="__('Mata Uang')" />
                                <x-text-input id="currency" name="currency" type="text" class="mt-1 block w-full" :value="old('currency')" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                            </div>
                    
                            <div>
                                <x-input-label for="icon" :value="__('Icon')" />
                                <input type="file" name="icon" id="icon">
                                <x-input-error class="mt-2" :messages="$errors->get('icon')" />
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