@props(['name'])
<svg {{ $attributes->class('size-5 shrink-0') }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('grid') <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/> @break
        @case('menu') <path d="M3 3v5a3 3 0 0 0 6 0V3M6 3v18m12-18a4 4 0 0 0-4 4v5h4m0-9v18"/> @break
        @case('orders') <path d="M6 3h12a2 2 0 0 1 2 2v16l-4-2-4 2-4-2-4 2V5a2 2 0 0 1 2-2Zm2 5h8m-8 4h8m-8 4h4"/> @break
        @case('arrow') <path d="M4 12h15m-6-6 6 6-6 6"/> @break
        @case('back') <path d="M20 12H5m6 6-6-6 6-6"/> @break
        @case('clock') <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/> @break
        @case('wallet') <rect x="3" y="6" width="18" height="14" rx="2"/><path d="M3 8V6l14-3v3m4 5h-6a2 2 0 0 0 0 4h6"/> @break
        @case('plus') <path d="M12 5v14M5 12h14"/> @break
        @case('chef') <path d="M6 14a4 4 0 1 1 2-7 4 4 0 0 1 8 0 4 4 0 1 1 2 7v6H6v-6Zm0 3h12"/> @break
    @endswitch
</svg>
