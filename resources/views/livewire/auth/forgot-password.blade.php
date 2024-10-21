<div>
    @if (Session::has('message'))
        <span style="color: green;">{{ Session::get('message') }}</span>
    @endif
    @if (Session::has('email'))
        <span style="color: red;">{{ Session::get('email') }}</span>
    @endif

    <div class="flex items-center justify-center min-h-screen bg-gray-100" data-theme="light">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">

            <h2 class="text-2xl font-bold text-center">Forgot Password</h2>

            <x-form wire:submit="sendVerificationMail">
                <x-input label="Email" icon="o-envelope" wire:model="email" hint="Enter your email" />
                <x-slot:actions>
                    <x-button class="border-none bg-primary-500 hover:bg-yellow-600" label="Send" type="submit" spinner="save" />
                </x-slot:actions>
            </x-form>
        </div>
    </div>
</div>
