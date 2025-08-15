<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gonime</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>
<body class="min-h-full bg-neutral-950 text-neutral-200">
    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 backdrop-blur supports-[backdrop-filter]:bg-neutral-950/70 bg-neutral-950/90 border-b border-neutral-800">
        <div class="mx-auto max-w-7xl px-4 py-3 flex items-center gap-3">
            <a href="{{ route('home') }}" class="text-xl font-extrabold tracking-wide">
                <span class="text-indigo-400">Go</span><span class="text-neutral-100">nime</span>
            </a>

            <!-- Nav -->
            <nav class="hidden md:flex items-center gap-4 ml-6 text-sm">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <a href="{{ route('top') }}" class="hover:text-white">Top</a>
                <a href="{{ route('season') }}" class="hover:text-white">Season</a>
            </nav>

            <!-- Search -->
            <form action="{{ route('home') }}" method="get" class="ml-auto flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search anime..." class="w-full rounded-2xl bg-neutral-900/80 border border-neutral-800 px-4 py-2 outline-none focus:ring-2 ring-indigo-500" />
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}" />
                    @endif
                </div>
            </form>
        </div>
    </header>

    <!-- Main -->
    <main class="mx-auto max-w-7xl px-4 py-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="border-t border-neutral-800 py-8 text-center text-sm text-neutral-400">
        © {{ date('Y') }} Gonime. Powered by Febriant.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sliders = document.querySelectorAll('.gonime-slider');
            sliders.forEach((el) => {
                new Swiper(el, {
                    slidesPerView: 1.15,
                    spaceBetween: 14,
                    centeredSlides: true,
                    loop: true,
                    autoplay: { delay: 3500, disableOnInteraction: false },
                    breakpoints: {
                        640: { slidesPerView: 1.6 },
                        768: { slidesPerView: 2.2 },
                        1024:{ slidesPerView: 3.2 },
                    },
                    pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
                    navigation: { nextEl: el.querySelector('.swiper-button-next'), prevEl: el.querySelector('.swiper-button-prev') },
                });
            });
        });
    </script>
</body>
</html>
