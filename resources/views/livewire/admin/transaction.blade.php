<div>
    <!-- HEADER -->
    <x-header title="Transaksi" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-plus" class="text-black border-none bg-primary-500" wire:click="openModal()" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$transactions" :sort-by="$sortBy" striped with-pagination>
            @scope('actions', $transaction)
                <div class="flex gap-x-4">
                    <x-button icon="o-pencil" wire:click="edit({{ $transaction->id }})"
                        class="text-green-500 btn-ghost btn-sm" />
                    <x-button icon="o-trash" wire:click="$set('modalDelete', {{ $transaction->id }})" spinner
                        class="text-red-500 btn-ghost btn-sm" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- DIALOG DELETE --}}
    <x-modal wire:model="modalDelete" title="Confirm Deletion">
        <p>Are you sure you want to delete this data?</p>
        <div class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button negative label="Delete" class="btn-error" wire:click="delete" />
        </div>
    </x-modal>

    {{-- MODAL FORM --}}
    <x-modal wire:model="modalForm" class="backdrop-blur"
        title="{{ $editMode ? 'Edit Transaksi' : 'Tambah Transaksi Baru' }}">
        <x-form wire:submit.prevent="save">
            <x-select label="Member" wire:model="form.nik" :options="$users" option-label="name" option-value="nik" required placeholder="Pilih Member" />
            <x-select label="Mobil" wire:model="form.carId" :options="$cars" option-label="name" option-value="id"
                placeholder="Pilih Mobil" wire:change="updateCarPrice" required />
            <x-input type="number" label="Jumlah Mobil" wire:model="form.amountCar" min="1" required />
            <x-datetime label="Tanggal Sewa" min="{{ now()->toDateString() }}" wire:model="form.rentalDate" required />
            <x-datetime label="Waktu Pengambilan" wire:model="form.pickUpAt" type="time" required />
            <x-input type="number" label="Durasi (hari)" wire:model="form.duration" min="1" required />
            <x-toggle label="Supir" wire:model="form.driver" right />
            <x-input type="number" label="Total Harga" wire:model="form.priceTotal" step="0.01" readonly>
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>
            <x-input type="number" label="DP" wire:model="form.dp" step="0.01" required>
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>
            <x-input type="number" label="Kekurangan Harga" wire:model="form.priceFinal" step="0.01" readonly>
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>

            <div class="flex justify-end gap-x-4">
                <x-button label="Batal" flat @click="$wire.modalForm = false" />
                <x-button type="submit" class="btn-success" label="{{ $editMode ? 'Perbarui' : 'Buat' }}" primary
                    spinner="save" />
            </div>
        </x-form>
    </x-modal>
</div>
