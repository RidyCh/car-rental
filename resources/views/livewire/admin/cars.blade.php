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
            @scope('cell_image', $car)
                <img src="{{ asset('storage/car-images/' . $car['image']) }}" alt="{{ $car['name'] }}"
                    class="object-cover w-16 h-16">
            @endscope
            @scope('cell_price', $car)
                Rp{{ number_format($car->price, 2, ',', '.') }}
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
    <x-modal wire:model="modalDelete" title="Confirm Deletion">
        <p>Are you sure you want to delete this data?</p>
        <div class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button negative label="Delete" class="btn-error" wire:click="delete" />
        </div>
    </x-modal>

    {{-- MODAL FORM --}}
    <x-modal wire:model="modalForm" class="backdrop-blur" title="{{ $editMode ? 'Edit Car' : 'Add New Car' }}">
        <x-form wire:submit.prevent="save">
            <x-input label="Name" wire:model="form.name" required />
            <x-input label="Brand" wire:model="form.brand" required />
            <x-input label="Type" wire:model="form.type" required />
            <x-input type="number" label="Year" wire:model="form.year" required />
            <x-input type="number" label="Price" wire:model="form.price" step="0.01" required />
            <x-input type="number" label="Seats Total" min="1" wire:model="form.seatsTotal" required />
            <x-input type="number" label="Stock" min="0" wire:model="form.stock" required />
            <x-select label="Status" wire:model="form.status" :options="$statusOptions" option-name="name" option-value="value"
                required />
            <x-file label="Image" wire:model="form.image" accept="image/*" required />

            <div class="flex justify-end gap-x-4">
                <x-button label="Cancel" flat @click="$wire.modalForm = false" />
                <x-button type="submit" class="btn-success" label="{{ $editMode ? 'Update' : 'Create' }}" primary
                    spinner="save" />
            </div>
        </x-form>
    </x-modal>
</div>
