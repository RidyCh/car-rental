<div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center">Login</h2>

            <x-form wire:submit="authenticate">
                <x-input label="Email" icon="o-envelope" wire:model="email" hint="Enter your email" />
                <x-input label="Password" wire:model="password" icon="o-key" type="password"
                    hint="Enter your password" />

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="w-4 h-4 border-gray-300 rounded text-primary-600 focus:ring-primary-500" />
                        <label for="remember" class="block ml-2 text-sm text-gray-900">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500">Forgot your
                            password?</a>
                    </div>
                </div>

                <x-slot:actions>
                    <x-button label="Login" class="w-full text-white border-none bg-primary-500 hover:bg-primary-600"
                        type="submit" spinner="save" />
                </x-slot:actions>
            </x-form>

            <div class="text-center">
                <p class="text-sm text-gray-600">Don't have an account? <a href="{{ route('register') }}"
                        class="font-medium text-primary-600 hover:text-primary-500">Sign up</a></p>
            </div>
        </div>
    </div>
</div>
