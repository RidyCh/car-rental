<div>
    <!-- HEADER -->
    <x-header title="Pengguna" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-plus" class="text-black border-none bg-primary-500" wire:click="openModal()" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" striped with-pagination>
            @scope('cell_image', $user)
                <img src="{{ asset('storage/car-images/' . $user['image']) }}" alt="{{ $user['name'] }}"
                    class="object-cover w-16 h-16">
            @endscope
            @scope('actions', $user)
                <div class="flex gap-x-4">
                    <x-button icon="o-pencil" wire:click="edit({{ $user->id }})"
                        class="text-green-500 btn-ghost btn-sm" />
                    <x-button icon="o-trash" wire:click="$set('modalDelete', {{ $user->id }})" spinner
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
            <x-button negative class="btn-error" label="Hapus" wire:click="delete" />
        </div>
    </x-modal>

    {{-- MODAL FORM --}}
    <x-modal wire:model="modalForm" class="backdrop-blur" title="{{ $editMode ? 'Edit User' : 'Add New User' }}">
        <x-form wire:submit.prevent="save">
            <div class="flex justify-between">
                <x-input label="NIK" wire:model="form.nik" />
                <x-input label="Nama" wire:model="form.name" required />
            </div>
            <div class="flex justify-between">
                <x-input label="Username" wire:model="form.username" required />
                <x-input label="Email" wire:model="form.email" type="email" required />
            </div>
            @if (!$editMode)
                <x-input label="Password" wire:model="form.password" type="password" required />
            @endif
            <div class="flex justify-between">
                <x-radio label="Jenis Kelamin" wire:model="form.gender" :options="$genderOptions" option-label="name"
                    option-value="value" />
                    <x-input label="Nomor Telepon" wire:model="form.phone_number" />
                </div>
                <x-textarea label="Alamat" wire:model="form.address" />
                <x-select label="Role" wire:model="form.role" :options="$roleOptions" option-label="name"
                    option-value="value" required />

            <div class="flex justify-end gap-x-4">
                <x-button label="Cancel" flat @click="$wire.modalForm = false" />
                <x-button type="submit" class="btn-success" label="{{ $editMode ? 'Update' : 'Create' }}" primary
                    spinner="save" />
            </div>
        </x-form>
    </x-modal>
</div>
