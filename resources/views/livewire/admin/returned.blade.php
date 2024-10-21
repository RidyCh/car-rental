<div>
    <!-- HEADER -->
    <x-header title="Pengembalian" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Cari..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-plus" class="text-black border-none bg-primary-500" wire:click="openModal()" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$returneds" :sort-by="$sortBy" striped with-pagination>
            @scope('actions', $returned)
                <div class="flex gap-x-4">
                    <x-button icon="o-pencil" wire:click="edit({{ $returned->id }})"
                        class="text-green-500 btn-ghost btn-sm" />
                    <x-button icon="o-trash" wire:click="$set('modalDelete', {{ $returned->id }})" spinner
                        class="text-red-500 btn-ghost btn-sm" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- DIALOG DELETE --}}
    <x-modal wire:model="modalDelete" title="Konfirmasi Penghapusan">
        <p>Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="flex justify-end gap-x-4">
            <x-button flat label="Batal" x-on:click="close" />
            <x-button negative label="Hapus" class="btn-error" wire:click="delete" />
        </div>
    </x-modal>

    {{-- MODAL FORM --}}
    <x-modal wire:model="modalForm" class="backdrop-blur"
        title="{{ $editMode ? 'Edit Pengembalian' : 'Tambah Pengembalian Baru' }}">
        <x-form wire:submit.prevent="save">
            <x-select label="ID Transaksi" wire:model="form.transactionId" :options="$transactions" option-label="id"
                option-value="id" required placeholder="Pilih ID Transaksi" />
            <x-datetime label="Tanggal Kembali" min="{{ now()->toDateString() }}" wire:model="form.returnDate"
                required />
            <x-textarea label="Kondisi Mobil" wire:model="form.conditionCar" />
            <x-input type="number" label="Denda" wire:model="form.pricePenalty" step="0.01" required>
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
