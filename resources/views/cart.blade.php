<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREED | YOUR BAG</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-50 text-neutral-950 min-h-screen font-sans overflow-x-hidden">
    
    <nav class="p-8 md:p-12 flex justify-between items-center">
        <a href="{{ route('catalog') }}" class="text-[10px] tracking-[0.4em] font-bold opacity-50 hover:opacity-100 transition-all uppercase flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Continue Shopping
        </a>
        <a href="/"><img src="{{ asset('img/logo.svg') }}" class="w-16 h-16 object-contain"></a>
        <div class="w-32 hidden md:block"></div> 
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="flex items-baseline gap-4 mb-12">
            <h1 class="text-6xl font-black italic tracking-tighter uppercase">Your Bag</h1>
            <span class="text-sm opacity-30 font-bold uppercase tracking-widest">({{ session('cart') ? count(session('cart')) : 0 }} Items)</span>
        </div>

        <div class="space-y-10">
            @php $total = 0 @endphp
            @if(session('cart'))
                @foreach(session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['quantity'] @endphp
                    <div class="flex items-center justify-between border-b border-neutral-950/10 pb-10 group relative">
                        <div class="flex items-center gap-8">
                            <div class="w-28 h-36 bg-neutral-200 overflow-hidden border border-neutral-950/5">
                                <img src="{{ asset('storage/products/'.$details['image']) }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    onerror="this.src='https://placehold.co/400x600?text=No+Image'">
                            </div>

                            <div class="flex flex-col gap-1">
                                <h3 class="font-black uppercase tracking-tight text-xl">{{ $details['name'] }}</h3>
                                <p class="text-xs font-bold opacity-40 uppercase tracking-widest mb-2">Unit Price: IDR {{ number_format($details['price'], 0, ',', '.') }}</p>
                                
                                <div class="flex items-center border border-neutral-950/20 w-fit mt-2">
                                    <button onclick="updateQty({{ $id }}, 'minus')" class="px-3 py-1 hover:bg-neutral-950 hover:text-white transition-colors border-r border-neutral-950/20 font-bold">-</button>
                                    <span class="px-4 py-1 text-xs font-black" id="qty-{{ $id }}">{{ $details['quantity'] }}</span>
                                    <button onclick="updateQty({{ $id }}, 'plus')" class="px-3 py-1 hover:bg-neutral-950 hover:text-white transition-colors border-l border-neutral-950/20 font-bold">+</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end gap-4">
                            <span class="font-black italic text-lg">IDR {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                            
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button class="text-neutral-950/30 hover:text-red-600 transition-colors p-2" title="Remove Item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="py-32 text-center border-y border-neutral-950/10">
                    <p class="uppercase tracking-[0.5em] opacity-20 text-sm font-black mb-8">Your bag is currently empty</p>
                    <a href="{{ route('catalog') }}" class="bg-neutral-950 text-white px-10 py-4 text-xs font-black tracking-widest hover:bg-neutral-800 transition-all uppercase">Start Shopping</a>
                </div>
            @endif
        </div>

        @if(session('cart'))
        <div class="mt-20 border-t-[6px] border-neutral-950 pt-10 flex flex-col items-end mb-20">
            <div class="flex justify-between w-full md:w-1/2 mb-2">
                <span class="text-[10px] tracking-[0.4em] font-bold opacity-40 uppercase">Subtotal</span>
                <span class="font-bold opacity-40 italic">IDR {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between w-full md:w-1/2 mb-10">
                <span class="text-[10px] tracking-[0.4em] font-bold opacity-40 uppercase">Total</span>
                <span class="text-4xl font-black italic tracking-tighter">IDR {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            
            <a href="{{ route('checkout.view') }}" class="w-full md:w-1/2 bg-neutral-950 text-white py-6 font-black tracking-[0.4em] text-sm hover:bg-neutral-800 transition-all uppercase active:scale-[0.98] text-center block">
            Proceed to Checkout
        </a>
            
            <p class="mt-6 text-[9px] opacity-30 uppercase tracking-widest text-center md:text-right w-full md:w-1/2">
                Shipping and taxes calculated at checkout.
            </p>
        </div>
        @endif
    </main>

    <script>
        function updateQty(id, type) {
            let currentQty = parseInt(document.getElementById(`qty-${id}`).innerText);
            let newQty = type === 'plus' ? currentQty + 1 : currentQty - 1;

            if (newQty < 1) return; // Minimal 1 barang

            // Update ke server pakai Fetch API
            fetch("{{ route('cart.update') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: id,
                    quantity: newQty
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Reload halaman agar harga total terupdate otomatis
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>