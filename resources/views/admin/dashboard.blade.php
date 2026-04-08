<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREED | SYSTEM DASHBOARD</title>
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link:hover { letter-spacing: 0.2em; }
        .active-tab { background: black !important; color: white !important; opacity: 1 !important; }
    </style>
</head>
<body class="bg-[#F4F4F4] text-neutral-900 antialiased flex" x-data="{ tab: 'inventory' }">

    <aside class="w-64 h-screen sticky top-0 bg-white border-r border-black/5 p-8 flex flex-col justify-between hidden lg:flex">
        <div>
            <div class="font-black italic uppercase tracking-tighter text-2xl mb-12 text-center underline decoration-4 uppercase">Greed</div>
            
            <nav class="space-y-2">
                <button @click="tab = 'inventory'" 
                    :class="tab === 'inventory' ? 'active-tab' : ''"
                    class="w-full block py-3 px-4 text-black/40 text-[10px] font-black uppercase tracking-widest text-center transition-all">
                    Inventory Control
                </button>
                <button @click="tab = 'orders'" 
                    :class="tab === 'orders' ? 'active-tab' : ''"
                    class="w-full block py-3 px-4 text-black/40 text-[10px] font-black uppercase tracking-widest text-center transition-all">
                    Logistics Queue
                </button>
            </nav>
        </div>

        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full border border-black/10 py-3 text-[9px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white hover:border-red-500 transition-all">
                Terminate Session —>
            </button>
        </form>
    </aside>

    <main class="flex-1 p-6 lg:p-12 overflow-y-auto">
        
        <section x-show="tab === 'inventory'" x-cloak>
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-[10px] font-bold uppercase tracking-[0.4em] opacity-20 mb-2 underline">Warehouse v1.0</h2>
                    <h1 class="text-4xl font-black italic uppercase tracking-tighter">Inventory Control</h1>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">
                <div class="xl:col-span-1 bg-white border border-black/5 p-8 h-fit shadow-sm" x-data="{ imageUrl: null }">
                    <h3 class="text-[9px] font-black uppercase tracking-widest mb-8 border-b pb-4 italic">New Drop Entry</h3>
                    <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="text-[8px] font-bold uppercase opacity-30 tracking-widest block mb-2">Item Designation</label>
                            <input type="text" name="name" required placeholder="E.G. 'NOIR HOODIE'" class="w-full border-b border-black/10 py-2 outline-none focus:border-black font-bold uppercase text-xs">
                        </div>
                        <div>
                            <label class="text-[8px] font-bold uppercase opacity-30 tracking-widest block mb-2">Unit Price (IDR)</label>
                            <input type="number" name="price" required placeholder="0.00" class="w-full border-b border-black/10 py-2 outline-none focus:border-black text-xs font-mono">
                        </div>
                        
                        <div x-data="{ imageUrl: null }">
                            <div class="relative border-2 border-dashed border-black/5 p-6 text-center h-48 flex items-center justify-center">
                                <template x-if="imageUrl">
                                    <img :src="imageUrl" class="absolute inset-0 w-full h-full object-cover grayscale">
                                </template>
                                
                                <template x-if="!imageUrl">
                                    <span class="text-[8px] font-black uppercase opacity-40">Attach Visual Artwork</span>
                                </template>

                                <input type="file" name="image" required 
                                    @change="imageUrl = URL.createObjectURL($event.target.files[0])"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-black text-white py-4 text-[9px] font-black uppercase tracking-[0.3em] hover:invert transition-all">Publish Drop</button>
                    </form>
                </div>

                <div class="xl:col-span-2 bg-white border border-black/5 shadow-sm overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-neutral-50 border-b border-black/5 font-mono">
                                <th class="p-6 text-[9px] font-black uppercase tracking-widest opacity-30">Art</th>
                                <th class="p-6 text-[9px] font-black uppercase tracking-widest opacity-30">Designation</th>
                                <th class="p-6 text-[9px] font-black uppercase tracking-widest opacity-30">Valuation</th>
                                <th class="p-6 text-[9px] font-black uppercase tracking-widest opacity-30 text-right">Control</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @foreach($products as $product)
                            <tr class="hover:bg-neutral-50/50 transition-colors group">
                                <td class="p-6 w-24">
                                    <img src="{{ asset('storage/products/' . $product->image) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-16 h-16 object-cover grayscale group-hover:grayscale-0 transition-all duration-500 shadow-sm"
                                         onerror="this.src='https://placehold.co/100x100?text=Error'">
                                </td>
                                <td class="p-6">
                                    <h4 class="font-black uppercase text-sm italic tracking-tighter">{{ $product->name }}</h4>
                                    <p class="text-[8px] font-mono opacity-30 uppercase tracking-widest">UID: {{ substr($product->id, 0, 8) }}</p>
                                </td>
                                <td class="p-6 font-mono text-xs italic">IDR {{ number_format($product->price) }}</td>
                                <td class="p-6 text-right">
                                    <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" onsubmit="return confirm('ERASE THIS DROP?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[8px] font-black uppercase tracking-widest text-red-500 border border-red-500/10 px-4 py-2 hover:bg-red-500 hover:text-white transition-all">Erase</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section x-show="tab === 'orders'" x-cloak>
            <div class="mb-10">
                <h2 class="text-[10px] font-bold uppercase tracking-[0.4em] opacity-20 mb-2 underline">Distribution v1.0</h2>
                <h1 class="text-4xl font-black italic uppercase tracking-tighter">Logistics Queue</h1>
            </div>

            <div class="bg-white border border-black/5 shadow-sm overflow-x-auto">
                <table class="w-full text-left min-w-[800px]">
                    <thead class="bg-neutral-50 border-b border-black/5">
                        <tr class="font-mono">
                            <th class="p-6 text-[9px] font-black uppercase tracking-widest opacity-40 italic">Reference</th>
                            <th class="p-6 text-[9px] font-black uppercase tracking-widest opacity-40 italic">Receiver Entity</th>
                            <th class="p-6 text-[9px] font-black uppercase tracking-widest opacity-40 italic text-center">Protocol</th>
                            <th class="p-6 text-[9px] font-black uppercase tracking-widest opacity-40 italic text-right">Command</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @foreach($orders as $order)
                        <tr class="hover:bg-neutral-50 transition-all group">
                            <td class="p-6 font-mono text-[10px] opacity-40">#GRD-{{ $order->id }}</td>
                            <td class="p-6">
                                <div class="font-black uppercase text-xs italic">{{ $order->receiver_name }}</div>
                                <div class="text-[9px] opacity-40 uppercase tracking-tighter">{{ $order->city }}</div>
                            </td>
                            <td class="p-6 text-center">
                                <span class="px-3 py-1 border border-black/5 text-[8px] font-black uppercase {{ $order->status == 'paid' ? 'bg-green-500 text-white' : 'bg-orange-500 text-white' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="p-6 text-right">
                                @if($order->shipping_status != 'DELIVERED')
                                <form action="{{ route('admin.order.update', $order->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-black text-white px-6 py-3 text-[9px] font-black uppercase tracking-widest hover:bg-neutral-800 transition-all">Authorize Shipment</button>
                                </form>
                                @else
                                <span class="text-[9px] font-black uppercase opacity-20 italic tracking-[0.3em]">Fulfilled</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</body>
</html>