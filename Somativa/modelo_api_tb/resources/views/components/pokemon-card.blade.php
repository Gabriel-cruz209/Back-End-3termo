@php
    $typeColors = [
        'normal' => '#64748b',
        'fire' => '#ef4444',
        'water' => '#2563eb',
        'electric' => '#facc15',
        'grass' => '#16a34a',
        'ice' => '#38bdf8',
        'fighting' => '#ea580c',
        'poison' => '#9333ea',
        'ground' => '#b45309',
        'flying' => '#0ea5e9',
        'psychic' => '#db2777',
        'bug' => '#65a30d',
        'rock' => '#78716c',
        'ghost' => '#4f46e5',
        'dragon' => '#7c3aed',
        'dark' => '#1f2937',
        'steel' => '#64748b',
        'fairy' => '#ec4899',
    ];

    $primaryType = $pokemon['types'][0] ?? 'normal';
    $accent = $typeColors[$primaryType] ?? $typeColors['normal'];
    $hasLocalImage = ! empty($pokemon['has_local_image']);
    $imageFrame = $hasLocalImage ? 'aspect-[16/10]' : 'aspect-square';
    $imageSize = $hasLocalImage ? 'h-full w-full object-cover' : 'h-32 w-32 object-contain';
@endphp

<article
    class="pokemon-card group relative overflow-hidden rounded-2xl border bg-zinc-900/40 backdrop-blur-md transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] {{ $pokemon['is_registered'] ? 'border-red-500/50 ring-1 ring-red-500/20' : 'border-zinc-800' }}"
    data-pokemon-card
    data-pokemon-id="{{ $pokemon['id'] }}"
    data-detail-url="{{ $pokemon['detail_url'] }}"
>
    <button
        type="button"
        class="flex h-full w-full flex-col p-5 text-left"
        aria-label="Ver detalhes de {{ $pokemon['name'] }}"
    >
        <span class="absolute inset-x-0 top-0 h-1 transition-all duration-300 group-hover:h-1.5" style="background-color: {{ $accent }}"></span>

        @if ($pokemon['is_registered'])
            <span class="absolute right-3 top-3 rounded-full bg-red-600/10 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-red-500 shadow-sm ring-1 ring-red-500/20 backdrop-blur-md">
                CAPTURED
            </span>
        @endif

        <div class="flex items-start justify-between gap-3">
            <span class="rounded-lg bg-zinc-950/50 px-2.5 py-1.5 text-[10px] font-black tracking-widest text-zinc-500 ring-1 ring-zinc-800">
                {{ $pokemon['number'] }}
            </span>
        </div>

        <div class="mt-5 flex {{ $imageFrame }} items-center justify-center overflow-hidden rounded-xl {{ $pokemon['is_registered'] ? 'bg-red-500/5' : 'bg-zinc-950/30' }} transition-colors duration-300 group-hover:bg-zinc-950/50">
            <img
                src="{{ $pokemon['image'] }}"
                alt="{{ $pokemon['name'] }}"
                class="{{ $imageSize }} transition-all duration-500 group-hover:scale-110 group-hover:rotate-2"
                loading="lazy"
            >
        </div>

        <div class="mt-5 min-w-0">
            <h3 class="truncate text-xl font-black tracking-tight text-white group-hover:text-red-500 transition-colors">{{ $pokemon['name'] }}</h3>

            @if (! empty($pokemon['nickname']))
                <p class="mt-1.5 truncate text-xs font-bold italic text-zinc-500">"{{ $pokemon['nickname'] }}"</p>
            @endif
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($pokemon['types'] as $type)
                <span
                    class="rounded-lg px-2.5 py-1.5 text-[9px] font-black uppercase tracking-widest text-white/90 shadow-sm"
                    style="background-color: {{ $typeColors[$type] ?? $typeColors['normal'] }}CC"
                >
                    {{ $type }}
                </span>
            @endforeach
        </div>
    </button>
</article>
