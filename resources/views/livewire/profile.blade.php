<div class="container p-6 mx-auto">
    <h1 class="mb-6 text-3xl font-bold">User Profile</h1>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Profile Information -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="mb-4 text-2xl font-semibold">Profile Information</h2>
            <div class="space-y-2">
                @foreach ([
        'NIK' => 'nik',
        'Nama' => 'name',
        'Username' => 'username',
        'Email' => 'email',
        'Jenis Kelamin' => 'gender',
        'Nomor Telepon' => 'phone_number',
        'Alamat' => 'address',
        'Status' => 'role',
    ] as $label => $field)
                    <div class="flex justify-between">
                        <span class="font-medium">{{ $label }}:</span>
                        <span>{{ auth()->user()->$field }}</span>
                    </div>
                @endforeach
            </div>
            <x-button class="mt-4" label="Edit Profile" wire:click="$set('editMode', true)" />
        </div>

        <!-- Edit Profile Form -->
        <div x-data="{ show: @entangle('editMode') }" x-show="show" class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="mb-4 text-2xl font-semibold">Edit Profile</h2>
            <x-form wire:submit="updateProfile">
                <x-input label="NIK" wire:model="nik" />
                <x-input label="Nama" wire:model="name" />
                <x-input label="Username" wire:model="username" />
                <x-input label="Email" wire:model="email" type="email" />
                <x-radio label="Jenis Kelamin" wire:model="gender" :options="$genderOptions" option-label="name"
                    option-value="value" />
                <x-input label="Nomor Telepon" wire:model="phone_number" />
                <x-textarea label="Alamat" wire:model="address" />
                <div class="mt-4">
                    <x-button label="Simpan Perubahan" spinner="updateProfile" type="submit"
                        class="border-none bg-primary-500" />
                    <x-button label="Batal" wire:click="$set('editMode', false)" class="btn-error" />
                </div>
            </x-form>
        </div>

        <!-- Reset Password -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="mb-4 text-2xl font-semibold">Reset Password</h2>
            <livewire:auth.reset-password />
        </div>

        <!-- Email Verification -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="mb-4 text-2xl font-semibold">Email Verification</h2>
            <livewire:auth.verify-email />
        </div>
    </div>
</div>
