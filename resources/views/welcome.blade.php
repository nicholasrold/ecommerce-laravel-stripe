<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREED | First Collection 2026</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/gsap@3.12.5/dist/gsap.min.js"></script>
</head>
<body class="bg-black text-white overflow-hidden uppercase">

    <div id="bg-wrapper" class="fixed inset-0 z-0">
        <div id="bg-1" class="bg-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-100"></div>
        <div id="bg-2" class="bg-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0"></div>
        <div class="absolute inset-0 bg-black/30 z-10"></div>
    </div>

    <div class="fixed top-0 left-0 w-full z-[60] bg-white text-black py-2 overflow-hidden whitespace-nowrap border-b border-white/20">
        <div class="inline-block animate-marquee-slow">
            @for ($i = 0; $i < 15; $i++)
                <span class="text-xs font-black tracking-widest mx-8">GREED SPECIAL DROP 2026 — FREE SHIPPING — NO RESTOCK —</span>
            @endfor
        </div>
    </div>

    <nav class="fixed top-8 left-0 w-full z-50 flex justify-between items-center px-6 md:px-12 mt-10">
    <div class="w-32">
        <a href="/"><img src="{{ asset('img/logo.svg') }}" alt="GREED LOGO" class="w-20 h-20"></a>
    </div>
    <div class="flex gap-8 items-center text-white font-bold tracking-widest text-xs">
        
        @auth
            <span class="uppercase border-b border-white pb-1">{{ Str::limit(auth()->user()->username, 10, '') }}</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="opacity-50 hover:opacity-100 italic">LOGOUT</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
        @endauth


    </div>
</nav>

<script>
    function alertLogin() {
        alert("ACCESS DENIED. PLEASE SIGN IN TO YOUR GREED ACCOUNT TO START SHOPPING.");
    }
</script>

    <main class="relative z-30 flex flex-col items-start justify-end min-h-screen px-6 md:px-12 pb-24 md:pb-32 text-left">
        <h2 class="text-xs md:text-sm tracking-[0.8em] mb-4 opacity-70">STREETWEAR CULTURE</h2>
        <h1 class="text-5xl md:text-[100px] font-black italic tracking-tighter mb-8 leading-[0.9]">
            FIRST COLLECTION <br> <span class="text-outline">2026</span>
        </h1>
        <a href="{{ route('catalog') }}" class="inline-block px-12 py-5 border border-white text-white font-bold text-sm tracking-widest hover:bg-white hover:text-black transition-all duration-500">
            SHOP NOW
        </a>
    </main>



</body>
</html>