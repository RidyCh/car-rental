<div>
    {{-- <livewire: forgot-password /> --}}
    {{-- <livewire: reset-password /> --}}
    {{-- <livewire: verify-email /> --}}
<div class="profile-info">
    <h2 class="text-2xl font-bold">Profile Information</h2>
    <ul class="mt-4">
        <li><strong>NIK:</strong> {{ auth()->user()->nik }}</li>
        <li><strong>Name:</strong> {{ auth()->user()->name }}</li>
        <li><strong>Username:</strong> {{ auth()->user()->username }}</li>
        <li><strong>Email:</strong> {{ auth()->user()->email }}</li>
        <li><strong>Gender:</strong> {{ auth()->user()->gender }}</li>
        <li><strong>Phone Number:</strong> {{ auth()->user()->phone_number }}</li>
        <li><strong>Address:</strong> {{ auth()->user()->address }}</li>
        <li><strong>Role:</strong> {{ auth()->user()->role }}</li>
    </ul>
</div>
</div>
