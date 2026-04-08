<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREED | CATALOG</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Animasi untuk efek melayang ke keranjang */
        .fly-item {
            position: fixed;
            z-index: 9999;
            pointer-events: none;
            transition: all 0.8s cubic-bezier(0.42, 0, 0.58, 1);
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-neutral-50 text-neutral-950 min-h-screen overflow-x-hidden">
    
    <nav class="absolute top-8 left-0 w-full z-50 flex justify-between items-center px-6 md:px-12 mt-10">
    <div class="w-32">
        <a href="/">
            <img src="{{ asset('img/logo.svg') }}" alt="GREED LOGO" class="w-20 h-20 hover:scale-105 transition-transform duration-500">
        </a>
    </div>

    <div class="flex gap-8 items-center text-neutral-950 font-bold tracking-widest text-xs">
    
    @auth
        <div class="flex items-center gap-4 border-r border-neutral-950/10 pr-6">
            <span class="uppercase border-b border-neutral-950 pb-0.5 tracking-[0.2em]">
                {{ Str::limit(auth()->user()->username, 10, '') }}
            </span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="opacity-40 hover:opacity-100 italic transition-opacity tracking-widest text-[10px]">
                    LOGOUT
                </button>
            </form>
        </div>
    @else
        <a href="{{ route('login') }}" class="hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </a>
    @endauth

    <button id="cart-icon-btn" onclick="{{ auth()->check() ? 'openCart()' : 'alertLogin()' }}" class="hover:scale-110 transition-transform relative">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
            <path d="M3 6h18"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        <span id="cart-counter" class="absolute -top-1 -right-1 bg-neutral-950 text-white text-[9px] font-black px-1.5 rounded-full min-w-[18px] h-[18px] flex items-center justify-center">
            {{ session('cart') ? count(session('cart')) : 0 }}
        </span>
    </button>

    @auth
        <a href="{{ route('orders.index') }}" class="hover:scale-110 transition-transform relative group">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                <path d="m3.3 7 8.7 5 8.7-5"/>
                <path d="M12 22V12"/>
            </svg>
            <span class="absolute -bottom-10 left-1/2 -translate-x-1/2 bg-neutral-950 text-white text-[8px] px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity uppercase tracking-tighter whitespace-nowrap pointer-events-none">
                My Orders
            </span>
        </a>
    @endauth

</div>
</nav>

    <main class="pt-48 px-6 md:px-12 pb-20">
        <div class="flex justify-between items-end mb-12 border-b border-neutral-950/10 pb-6">
            <h1 class="text-4xl font-black italic tracking-tighter uppercase text-neutral-950">Available Drops</h1>
            <p class="text-[10px] tracking-[0.3em] opacity-60 uppercase text-neutral-950">Total Items: {{ $products->count() }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
            <div class="group">
                <div class="relative aspect-[3/4] overflow-hidden bg-neutral-100 border border-neutral-950/10">
                    <img id="prod-img-{{ $product->id }}" 
                        src="{{ asset('storage/products/' . $product->image) }}" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700"
                        onerror="this.src='https://placehold.co/400x600?text=Image+Not+Found'">
                    
                    <div class="absolute inset-0 bg-neutral-950/80 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500">
                        <a href="{{ route('checkout.view', ['direct_id' => $product->id]) }}" 
                            class="w-3/4 bg-white text-black py-3 text-center text-xs font-black tracking-widest hover:bg-neutral-950 hover:text-white transition-all uppercase">
                            DIRECT CHECKOUT
                            </a>
                        <button onclick="animateToCart({{ $product->id }})" 
                           class="w-3/4 mt-2 border border-white text-white py-3 text-xs font-black tracking-widest hover:bg-white hover:text-black transition-all uppercase">
                           ADD TO BAG
                        </button>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-start">
                    <div>
                        <h3 class="font-bold tracking-tighter text-lg uppercase text-neutral-950">{{ $product->name }}</h3>
                        <p class="text-xs opacity-60 tracking-widest text-neutral-950">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-[9px] border border-neutral-950/20 px-2 py-1 uppercase opacity-60 text-neutral-950">New Drop</span>
                </div>
            </div>
            @endforeach
        </div>
    </main>

    <script>
    function alertLogin() {
        alert("ACCESS DENIED. PLEASE SIGN IN TO YOUR GREED ACCOUNT.");
        window.location.href = "{{ route('login') }}";
    }

    function openCart() {
        window.location.href = "{{ route('cart.view') }}";
    }

    function animateToCart(productId) {
        @if(!auth()->check())
            alertLogin();
            return;
        @endif

        // 1. Inisialisasi Element
        const imgToCopy = document.getElementById(`prod-img-${productId}`);
        const cartBtn = document.getElementById('cart-icon-btn');
        const counter = document.getElementById('cart-counter');

        const imgRect = imgToCopy.getBoundingClientRect();
        const cartRect = cartBtn.getBoundingClientRect();

        // 2. Kirim Data ke Laravel via Fetch (Tanpa Tunggu Animasi)
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Kita simpan jumlah terbaru dari server untuk diupdate nanti
            window.latestCartCount = data.cart_count;
        })
        .catch(error => console.error('Error:', error));

        // 3. Eksekusi Animasi Fly
        const flyImg = imgToCopy.cloneNode();
        flyImg.classList.add('fly-item');
        
        // Start Position
        flyImg.style.top = `${imgRect.top}px`;
        flyImg.style.left = `${imgRect.left}px`;
        flyImg.style.width = `${imgRect.width}px`;
        flyImg.style.height = `${imgRect.height}px`;

        document.body.appendChild(flyImg);

        // 4. Animate to Bag
        setTimeout(() => {
            flyImg.style.top = `${cartRect.top + 10}px`;
            flyImg.style.left = `${cartRect.left + 10}px`;
            flyImg.style.width = '20px';
            flyImg.style.height = '20px';
            flyImg.style.opacity = '0';
            flyImg.style.transform = 'rotate(20deg)'; // Efek miring sedikit biar dinamis
        }, 50);

        // 5. Cleanup & Update UI
        setTimeout(() => {
            // Update counter dengan data asli dari server
            if (window.latestCartCount) {
                counter.innerText = window.latestCartCount;
            }
            
            flyImg.remove();
            
            // Efek feedback visual pada icon bag
            cartBtn.classList.add('scale-150');
            setTimeout(() => {
                cartBtn.classList.remove('scale-150');
            }, 300);
        }, 850);
    }
</script>
</body>
</html>