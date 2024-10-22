<div class="flex flex-col mr-auto lg:flex-row">
    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="py-2 text-white hover:text-gray-400 lg:p-4">
        Home
    </x-nav-link>
    <x-nav-link href="{{ route('admin.cars') }}" :active="request()->routeIs('admin.cars')" class="py-2 text-white hover:text-gray-400 lg:p-4">
        Our Cars
    </x-nav-link>
    <x-nav-link href="#" class="py-2 text-white hover:text-gray-400 lg:p-4">
        Terms and Conditions
    </x-nav-link>
    <x-nav-link href="#" class="py-2 text-white hover:text-gray-400 lg:p-4">
        Contact Us
    </x-nav-link>
</div>
