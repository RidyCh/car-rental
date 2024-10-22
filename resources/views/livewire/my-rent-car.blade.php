<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Mobil Saya yang Disewa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                @if ($rentedCars->isEmpty())
                    <p class="text-center text-gray-500">Anda belum menyewa mobil apapun.</p>
                @else
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($rentedCars as $transaction)
                            <div class="overflow-hidden bg-white rounded-lg shadow-md">
                                @if ($transaction->car->image)
                                    <img src="{{ asset('storage/car-images/' . $transaction->car->image) }}"
                                        alt="{{ $transaction->car->name }}" class="object-cover w-full h-48">
                                @else
                                    <div class="flex items-center justify-center w-full h-48 bg-gray-200">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold">{{ $transaction->car->name }}</h3>
                                    <p class="text-gray-600">Status: {{ $transaction->status }}</p>
                                    <p class="text-gray-600">Tanggal Sewa:
                                        {{ $transaction->rental_date->format('d M Y') }}</p>
                                    <p class="text-gray-600">Durasi: {{ $transaction->duration }} hari</p>
                                    <p class="text-gray-600">Total Harga: Rp
                                        {{ number_format($transaction->price_total, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $rentedCars->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
