@props(['href' => '#', 'active' => false])

<li>
    <a href="{{ $href }}" class="nav-link {{ $active ? 'nav-link--active' : '' }}">
        {{ $slot }}
    </a>
</li>