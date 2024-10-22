<div>
    <div class="flex items-start justify-center w-full gap-12 py-10 text-white bg-gray-900 shadow-lg p-screen h-sreen">
        <div class="flex items-center justify-center mb-4">
            <div class="relative">
                <img src="{{ asset('storage/car-images/' . $car->image) }}" alt="{{ $car->name }}"
                    class="w-full rounded-lg shadow-md shadow-black h-96">
                <span
                    class="absolute px-2 py-1 text-sm rounded-lg bg-primary-500 top-2 left-2">{{ $car->brand }}</span>
            </div>
        </div>
        <div>
            <h1 class="mb-4 text-2xl font-bold">{{ $car->name }}</h1>
            <div class="flex items-center justify-between mb-4 text-sm">
                <div class="flex items-center space-x-2">
                    <span class="flex gap-1"><x-heroicon-s-cog-8-tooth class="w-4 h-4 mt-0.5" />
                        {{ $car->type }}</span>
                    <span class="flex gap-1"><x-heroicon-s-calendar-days class="w-4 h-4 mt-0.5" />
                        {{ $car->year }}</span>
                    <span>👥 6 Kursi</span>
                </div>
            </div>

            <div class="p-4 mb-4 text-black bg-white rounded-lg">
                <p class="text-xl font-bold">Rp{{ number_format($car->price, 0, ',', '.') }} <span
                        class="text-sm font-normal">/hari</span></p>
                <p class="mt-2 text-xs">Order now at the lowest price today! The prices above may change at any time
                    without prior notice.</p>
            </div>

            <div class="flex items-center justify-between mb-4">
                <div>
                    <label class="block mb-2">Jumlah</label>
                    <div class="flex items-center space-x-2">
                        <x-button icon="o-minus" class="px-3 py-1 text-white border-none rounded-lg bg-primary-500"
                            wire:click="decrementAmount" />
                        <x-input type="number" wire:model.live="form.amountCar" min="1" max="50"
                            class="w-24 text-black bg-white rounded-lg text-start input-md"
                            x-on:input="$event.target.value = $event.target.value.slice(0, 12)" />
                        <x-button icon="o-plus" class="px-3 py-1 text-white border-none rounded-lg bg-primary-500"
                            wire:click="incrementAmount" />
                    </div>
                </div>

                <div class="flex gap-2">
                    <div>
                        <label class="block mb-2">Tanggal Sewa</label>
                        <x-datetime wire:model.defer="form.rentalDate" value="{{ now()->toDateString() }}"
                            min="{{ now()->toDateString() }}" class="px-3 py-1 text-black" data-theme="light" />
                    </div>
                    <div>
                        <label class="block mb-2">Durasi</label>
                        <x-select :options="$durationOptions" wire:model.live="form.duration" option-value="value"
                            option-name="name" data-theme="light" />
                    </div>
                </div>
            </div>

            <x-button icon="o-check-circle" label="Booking Sekarang" @click="$wire.modalBooking = true"
                class="w-full py-3 font-bold text-center text-white border-none rounded-lg bg-primary-500 hover:bg-primary-700" />

            {{-- MODAL BOOKING --}}
            <x-modal wire:model="modalBooking" class="text-black" title="Detail Booking" separator
                box-class="max-w-2xl">
                <x-form wire:submit="saveBooking">
                    <!-- Detail Booking Header -->
                    <div class="flex items-start justify-between mb-6">
                        <!-- Gambar Mobil -->
                        <div class="w-1/4">
                            <img src="{{ asset('storage/car-images/' . $car->image) }}" alt="{{ $car->name }}"
                                class="rounded-md" />
                            <x-badge value="Rp{{ number_format($car->price, 0, ',', '.') }} /hari"
                                class="relative mt-2 font-semibold" />
                        </div>
                        <!-- Informasi Mobil -->
                        <div class="flex-1 ml-4 font-medium text-gray-600">
                            <p class="text-lg font-semibold text-black">{{ $car->name }}</p>
                            <p>Tanggal Sewa: {{ Carbon\Carbon::parse($form->rentalDate)->format('d F Y') }}</p>
                            <p>Durasi Sewa: {{ $form->duration }} hari</p>
                            <p>Jumlah Sewa: {{ $form->amountCar }} unit</p>
                        </div>
                    </div>

                    <x-input type="number" label="Harga Total" wire:model.live="form.priceTotal" step="0.01"
                        readonly>
                        <x-slot:prefix>Rp</x-slot:prefix>
                    </x-input>
                    <x-input type="number" label="DP" wire:model.live="form.dp" step="0.01" min="0">
                        <x-slot:prefix>Rp</x-slot:prefix>
                    </x-input>
                    <x-datetime type="time" label="Waktu Pengambilan" wire:model="form.pickUpAt" />
                    <x-toggle label="Dengan Supir" wire:model.live="form.driver" right
                        hint="Harga bertambah Rp{{ number_format($driverCost, 2, ',', '.') }}" />

                    <!-- Detail Customer Section -->
                    <x-card title="Detail Customer" class="mb-6">
                        <div class="flex-1 ml-4">
                            <p>Nama: {{ auth()->user()->name }}</p>
                            <p>NIK: {{ auth()->user()->nik }}</p>
                            <p>Nomor Telepon: {{ auth()->user()->phone_number }}</p>
                        </div>
                    </x-card>

                    <!-- Grand Total Section -->
                    <div class="text-right">
                        <p>Grand Total: </p>
                        <span class="text-xl font-bold text-primary-500">Rp{{ $this->formattedPriceFinal }}</span>
                    </div>

                    <!-- Actions -->
                    <x-slot:actions>
                        <x-button label="Batal" @click="$wire.modalBooking = false" />
                        <x-button icon="o-check-circle" label="Konfirmasi" class="border-none bg-primary-500"
                            type="submit" spinner="saveBooking" />
                    </x-slot:actions>
                </x-form>
            </x-modal>

        </div>
    </div>
</div>
