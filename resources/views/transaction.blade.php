<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Top Up Buat Game Apa Nih?') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center items-center">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-fit">
                <div class="p-6 text-gray-900 flex flex-col justify-center items-center gap-4 w-fit">
                    <div class="flex flex-col justify-center items-center">
                        <span>Kamu akan membeli paket</span>
                        <span class="text-xl font-bold">{{ $package->title }}</span>
                        <span>IDR {{ $package->price }}</span>
                    </div>
                    <form action="{{ route('transactions.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <label for="game_account_id">ID akun : </label>
                        <input type="text" name="game_account_id" id="game_account_id">

                        <x-primary-button>
                            Beli
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
