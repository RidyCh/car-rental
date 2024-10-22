<div>
    <!-- HEADER -->
    <x-header title="Mobil" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-plus" class="text-black border-none bg-primary-500" wire:click="openModal()" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$cars" :sort-by="$sortBy" striped with-pagination>
            @scope('cell_name', $car)
                <div class="flex flex-col">
                    <p>{{ $car->name }}</p>
                    <p>{{ $car->slug }}</p>
                </div>
            @endscope
            @scope('cell_image', $car)
                <img src="{{ asset('storage/car-images/' . $car['image']) }}" alt="{{ $car['name'] }}"
                    class="object-cover w-16 h-16">
            @endscope
            @scope('cell_price', $car)
                Rp{{ number_format($car->price, 2, ',', '.') }}
            @endscope
            @scope('cell_status', $car)
                @if ($car->status == 'Available')
                    <x-badge value="{{ $car->status }}" class="badge-success" />
                @endif
                @if ($car->status == 'Unavailable')
                    <x-badge value="{{ $car->status }}" class="badge-warning" />
                @endif
                @if ($car->status == 'Maintenance')
                    <x-badge value="{{ $car->status }}" class="badge-error" />
                @endif
            @endscope
            @scope('actions', $car)
                <div class="flex gap-x-4">
                    <x-button icon="o-pencil" wire:click="edit({{ $car->id }})"
                        class="text-green-500 btn-ghost btn-sm" />
                    <x-button icon="o-trash" wire:click="$set('modalDelete', {{ $car->id }})" spinner
                        class="text-red-500 btn-ghost btn-sm" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- DIALOG DELETE --}}
    <x-modal wire:model="modalDelete" title="Konfirmasi penghapusan" persistent>
        <p>Yakin ingin menghapus data?</p>
        <div class="flex justify-end gap-x-4">
            <x-button flat label="Tidak" @click="$wire.modalDelete = false" />
            <x-button negative label="Hapus" class="btn-error" wire:click="delete" />
        </div>
    </x-modal>

    {{-- MODAL FORM --}}
    <x-modal wire:model="modalForm" class="backdrop-blur" title="{{ $editMode ? 'Edit Mobil' : 'Tambah Mobil Baru' }}">
        <x-form wire:submit="save">
            <div class="flex justify-between">
                <x-input label="Nama" wire:model="form.name" required />
                <x-input label="Merek" wire:model="form.brand" required />
            </div>
            <div class="flex justify-between">
                <x-input label="Tipe" wire:model="form.type" required />
                <x-input type="number" label="Tahun" wire:model="form.year" required />
            </div>
            <div class="flex justify-between">
                <x-input type="number" label="Jumlah Kursi" min="1" wire:model="form.seatsTotal" required />
                <x-input type="number" label="Stok" min="0" wire:model="form.stock" required />
            </div>
            <x-input type="number" label="Harga" wire:model="form.price" step="0.01" required>
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>
            <x-select label="Status" wire:model="form.status" :options="$statusOptions" option-name="name" option-value="value"
                required />
            <x-file label="Gambar" wire:model="form.image" accept="image/*" />

            <div class="flex justify-end gap-x-4">
                <x-button label="Batal" flat @click="$wire.modalForm = false" />
                <x-button type="submit" class="btn-success" label="{{ $editMode ? 'Perbarui' : 'Buat' }}" primary
                    spinner="save" />
            </div>
        </x-form>
    </x-modal>
</div>
