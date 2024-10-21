<div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center">Register</h2>

            <x-form wire:submit="register">
                <x-input label="Name" icon="o-user" wire:model="name" hint="Enter your Name" />

                <x-input label="Username" icon="o-user" wire:model="username" hint="Enter your username" />

                <x-input label="Email" icon="o-envelope" wire:model="email" hint="Enter your email" />

                <x-input label="Password" wire:model="password" icon="o-key" type="password"
                    hint="Enter your password" />

                <x-slot:actions>
                    <x-button class="w-full text-white border-none bg-primary-500 hover:bg-primary-600" label="Register"
                        type="submit" spinner="save" />
                </x-slot:actions>
            </x-form>

            <div class="text-center">
                <p class="text-sm text-gray-600">Already have an account? <a href="{{ route('login') }}"
                        class="font-medium text-primary-600 hover:text-primary-500">Sign in</a></p>
            </div>
        </div>
    </div>
</div>
