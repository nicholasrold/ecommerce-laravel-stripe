<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREED | MY ORDERS</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F9F9F9] text-neutral-950 min-h-screen font-sans antialiased">
    
    <nav class="p-8 flex justify-between items-center bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-neutral-900/5">
        <a href="{{ route('catalog') }}" class="text-[10px] tracking-[0.4em] font-bold opacity-40 uppercase flex items-center gap-2 hover:opacity-100 transition-all group">
            <svg class="group-hover:-translate-x-1 transition-transform" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
            Back to Catalog
        </a>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-16">
        <header class="mb-16">
            <h1 class="text-6xl font-black italic uppercase tracking-tighter mb-2">Orders</h1>
            <p class="text-[11px] tracking-[0.4em] opacity-40 uppercase font-medium">Archive of your digital drops</p>
        </header>

        <div class="grid gap-12">
            @forelse($orders as $order)
                <div class="group relative bg-white border border-neutral-900/10 p-10 transition-all hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)]">
                    
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-10 border-b border-neutral-900/5 pb-6">
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.3em] uppercase opacity-30 mb-1">Receipt ID</p>
                            <p class="font-mono text-sm font-bold opacity-80">GRD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-right">
                                <p class="text-[10px] font-bold tracking-[0.3em] uppercase opacity-30 mb-1">Payment</p>
                                <span class="text-[9px] font-black tracking-widest uppercase {{ $order->status == 'paid' ? 'text-green-600' : 'text-orange-500' }}">
                                    {{ $order->status == 'paid' ? 'SUCCESS' : 'PENDING' }}
                                </span>
                            </div>
                            <div class="text-right border-l border-neutral-900/10 pl-4">
                                <p class="text-[10px] font-bold tracking-[0.3em] uppercase opacity-30 mb-1">Logistic</p>
                                <span class="text-[9px] font-black tracking-widest uppercase text-neutral-950">
                                    {{ $order->shipping_status ?? 'PENDING' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach($order->items as $item)
                        <div class="flex items-end justify-between border-b border-neutral-900/[0.03] pb-4">
                            <div class="flex-grow">
                                <h3 class="text-2xl font-black italic uppercase tracking-tighter leading-none mb-2 group-hover:text-neutral-500 transition-colors">
                                    {{ $item->product->name }}
                                </h3>
                                <div class="flex gap-4 text-[9px] font-bold uppercase tracking-[0.2em] opacity-40">
                                    <span>QTY: {{ $item->quantity }}</span>
                                    <span>UNIT: IDR {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-lg font-black italic tracking-tighter">
                                    IDR {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-10 pt-8 border-t border-neutral-900/5 flex flex-wrap justify-between items-end gap-4">
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.3em] uppercase opacity-30 mb-2">Shipping to</p>
                            <p class="text-[11px] font-bold uppercase tracking-tight">{{ $order->receiver_name }}</p>
                            <p class="text-[10px] opacity-40 uppercase max-w-[200px] leading-relaxed">{{ $order->address_detail }}, {{ $order->city }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold tracking-[0.3em] uppercase opacity-30 mb-1">Total Transaction</p>
                            <p class="text-3xl font-black italic tracking-tighter">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-40 text-center border border-dashed border-neutral-900/20">
                    <p class="text-[10px] font-bold opacity-30 tracking-[0.5em] uppercase mb-8">No archive records found</p>
                    <a href="{{ route('catalog') }}" class="px-10 py-4 bg-neutral-950 text-white text-[10px] font-black tracking-[0.3em] uppercase hover:scale-105 transition-transform">Browse Drops</a>
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>