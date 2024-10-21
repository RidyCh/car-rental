<div>
    <!-- HEADER -->
    <x-header title="Pembayaran" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Cari..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-plus" class="text-black border-none bg-primary-500" wire:click="openModal()" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$payments" :sort-by="$sortBy" striped with-pagination>
            @scope('actions', $payment)
                <div class="flex gap-x-4">
                    <x-button icon="o-eye" wire:click="view({{ $payment->id }})" class="text-blue-500 btn-ghost btn-sm" />
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
        title="{{ $editMode ? 'Edit Pembayaran' : 'Tambah Pembayaran Baru' }}">
        <x-form wire:submit.prevent="save">
            <x-select label="ID Pengembalian" wire:model="form.returnedId" :options="$returneds" option-label="id"
                option-value="id" required placeholder="Pilih ID Pengembalian" />
            <x-input type="number" label="Jumlah Pembayaran" wire:model="form.paymentAmount" step="0.01" required>
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
