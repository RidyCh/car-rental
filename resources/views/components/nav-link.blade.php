@props(['name', 'href', 'active' => '', 'class' => ''])

<a href="{{ $href }}" class="{{ $class }} hover:text-gray-400 lg:p-4 py-2 text-white {{ request()->is($active) ? 'text-primary-500 font-bold' : '' }}"
   :class="{ 'text-primary-500 font-bold': isActive('{{ $active }}') }">
    {{ $name }}
</a>

<script>
    function isActive(route) {
        return window.location.pathname === route;
    }
</script>
