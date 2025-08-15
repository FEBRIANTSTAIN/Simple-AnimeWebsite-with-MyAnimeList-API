@extends('layouts.app')

@section('content')
    <div class="lg:hidden mb-4">
        <button id="toggleFilter" class="px-4 py-2 bg-indigo-600 text-white rounded-xl">Filter</button>
    </div>
    <aside id="filterSidebar" class="hidden lg:block lg:col-span-3">
        <form method="get" action="{{ route('home') }}" class="sticky top-20 space-y-4 bg-neutral-900/60 border border-neutral-800 rounded-2xl p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-neutral-300">Filters</h2>
                <a href="{{ route('home') }}" class="text-xs text-indigo-400 hover:underline">Reset</a>
            </div>

            <input type="hidden" name="category" value="{{ request('category') }}" />
            <div>
                <label class="block text-xs mb-1 text-neutral-400">Keyword</label>
                <input type="text" name="q" value="{{ $q }}" placeholder="e.g. One Piece" class="w-full rounded-xl bg-neutral-950 border border-neutral-800 px-3 py-2 focus:ring-2 ring-indigo-500" />
            </div>

            <div>
                <label class="block text-xs mb-1 text-neutral-400">Genre</label>
                <select name="genre" class="w-full rounded-xl bg-neutral-950 border border-neutral-800 px-3 py-2">
                    <option value="">All</option>
                    @foreach($genres as $g)
                        <option value="{{ $g['mal_id'] }}" @selected($selectedGenre===$g['mal_id'])>{{ $g['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs mb-1 text-neutral-400">Status</label>
                <select name="status" class="w-full rounded-xl bg-neutral-950 border border-neutral-800 px-3 py-2">
                    <option value="">Any</option>
                    @foreach(['airing','complete','upcoming'] as $s)
                        <option value="{{ $s }}" @selected($status===$s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs mb-1 text-neutral-400">Min Score</label>
                <input type="number" min="1" max="9" name="min_score" value="{{ $minScore }}" class="w-full rounded-xl bg-neutral-950 border border-neutral-800 px-3 py-2" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs mb-1 text-neutral-400">Order By</label>
                    <select name="order_by" class="w-full rounded-xl bg-neutral-950 border border-neutral-800 px-3 py-2">
                        @foreach(['score','popularity','rank','favorites'] as $ob)
                            <option value="{{ $ob }}" @selected($orderBy===$ob)>{{ ucfirst($ob) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs mb-1 text-neutral-400">Sort</label>
                    <select name="sort" class="w-full rounded-xl bg-neutral-950 border border-neutral-800 px-3 py-2">
                        @foreach(['desc','asc'] as $st)
                            <option value="{{ $st }}" @selected($sort===$st)>{{ strtoupper($st) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 transition px-4 py-2 font-medium">Apply</button>
        </form>
    </aside>

    <!-- Kanan: Slider + Grid -->
    <section class="lg:col-span-9 space-y-6">
        <!-- Slider Banner -->
        <div class="bg-gradient-to-br from-neutral-900 to-neutral-900/40 border border-neutral-800 rounded-2xl p-4">
            <div class="gonime-slider swiper">
                <div class="swiper-wrapper">
                    @foreach($slider as $item)
                        <div class="swiper-slide">
                            <a href="https://myanimelist.net/anime/{{ $item['mal_id'] }}" target="_blank" class="block group">
                                <div class="relative overflow-hidden rounded-2xl border border-neutral-800">
                                    <img src="{{ $item['images']['jpg']['large_image_url'] ?? $item['images']['jpg']['image_url'] }}" alt="{{ $item['title'] }}" class="w-full h-56 md:h-72 lg:h-80 object-cover transition-transform duration-500 group-hover:scale-[1.03]" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                                    <div class="absolute bottom-0 p-4">
                                        <h3 class="text-base md:text-lg font-semibold">{{ $item['title'] }}</h3>
                                        <p class="text-xs text-neutral-300">Score: {{ $item['score'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <!-- Grid Anime Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @forelse($grid as $anime)
                <a href="https://myanimelist.net/anime/{{ $anime['mal_id'] }}" target="_blank" class="group bg-neutral-900/60 border border-neutral-800 rounded-2xl overflow-hidden hover:border-neutral-700 transition">
                    <div class="relative">
                        <img src="{{ $anime['images']['jpg']['image_url'] }}" alt="{{ $anime['title'] }}" class="w-full h-64 object-cover" />
                        <div class="absolute top-2 left-2 rounded-md bg-black/60 px-2 py-1 text-[10px]">Score {{ $anime['score'] ?? 'N/A' }}</div>
                    </div>
                    <div class="p-3">
                        <h4 class="text-xs font-semibold leading-snug group-hover:text-white line-clamp-2">{{ $anime['title'] }}</h4>
                        <p class="text-[10px] mt-1 text-neutral-400">{{ $anime['type'] ?? 'Anime' }} • {{ $anime['episodes'] ?? '?' }} eps</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center text-neutral-400">No results.</div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(($pagination['current_page'] ?? 1) > 1 || ($pagination['has_next_page'] ?? false))
            <div class="flex items-center justify-center gap-3">
                @php
                    $prev = max(1, (int)($pagination['current_page'] ?? 1) - 1);
                    $next = (int)($pagination['current_page'] ?? 1) + 1;
                @endphp
                @if(($pagination['current_page'] ?? 1) > 1)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $prev]) }}" class="px-4 py-2 rounded-xl border border-neutral-800 hover:border-neutral-700">Prev</a>
                @endif
                @if(($pagination['has_next_page'] ?? false))
                    <a href="{{ request()->fullUrlWithQuery(['page' => $next]) }}" class="px-4 py-2 rounded-xl border border-neutral-800 hover:border-neutral-700">Next</a>
                @endif
            </div>
        @endif
    </section>

    <script>
        document.getElementById('toggleFilter')?.addEventListener('click', function () {
            document.getElementById('filterSidebar').classList.toggle('hidden');
        });
    </script>
@endsection
