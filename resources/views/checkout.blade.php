<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREED | CHECKOUT</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-neutral-50 text-neutral-950 min-h-screen">
    
    <nav class="p-8 flex justify-between items-center border-b border-neutral-950/5">
            <a href="{{ route('catalog') }}" class="text-[10px] tracking-[0.4em] font-bold opacity-50 uppercase flex items-center gap-2 hover:opacity-100 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Back to Catalog
            </a>
            
            <a href="/">
                <img src="{{ asset('img/logo.svg') }}" class="w-12 h-12 hover:scale-110 transition-transform" alt="GREED LOGO">
            </a>
        </nav>

   <main class="max-w-6xl mx-auto px-6 py-16 grid grid-cols-1 lg:grid-cols-2 gap-20">
    
    <div>
        <h2 class="text-3xl font-black italic uppercase tracking-tighter mb-10">Shipping Address</h2>
        
        <div id="address-container" class="space-y-4 mb-8">
            <label class="block cursor-pointer group">
                <input type="radio" name="selected_address" value="default" class="hidden peer" checked>
                <div class="border-2 border-neutral-950/10 peer-checked:border-neutral-950 p-6 relative bg-white transition-all">
                    <p class="text-[10px] font-black tracking-widest opacity-40 uppercase mb-2">Default Address</p>
                    <p class="font-bold tracking-tight">{{ auth()->user()->full_name }} <span class="opacity-50 ml-2 font-medium">({{ auth()->user()->phone_number ?? 'No phone' }})</span></p>
                    <p class="text-sm opacity-60 leading-relaxed mt-1">
                        {{ auth()->user()->address_detail }}, {{ auth()->user()->city }}, {{ auth()->user()->province }}
                    </p>
                    <div class="absolute top-6 right-6 w-3 h-3 bg-neutral-950 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                </div>
            </label>

            @foreach($addresses as $addr)
<div class="relative group/card">
    <label class="block cursor-pointer">
        <input type="radio" name="selected_address" value="{{ $addr->id }}" class="hidden peer">
        <div class="border-2 border-neutral-950/10 peer-checked:border-neutral-950 p-6 bg-white transition-all">
            <p class="text-[10px] font-black tracking-widest opacity-40 uppercase mb-2">{{ $addr->label }}</p>
            <p class="font-bold tracking-tight">{{ $addr->receiver_name }} <span class="opacity-50 ml-2 font-medium">({{ $addr->phone_number }})</span></p>
            <p class="text-sm opacity-60 leading-relaxed mt-1">
                {{ $addr->address_detail }}, {{ $addr->city }}, {{ $addr->province }}
            </p>
            <div class="absolute top-6 right-6 w-3 h-3 bg-neutral-950 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
        </div>
    </label>
    <button onclick="deleteAddress({{ $addr->id }})" class="absolute bottom-6 right-6 text-red-500 opacity-0 group-hover/card:opacity-100 transition-opacity p-2 hover:bg-red-50 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
    </button>
</div>
@endforeach
        </div>
        
        <div id="modal-address" class="fixed inset-0 bg-neutral-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-lg p-10 max-h-[90vh] overflow-y-auto">
                <h3 class="text-2xl font-black uppercase italic tracking-tighter mb-8">New Shipping Address</h3>
                <div class="space-y-4">
                    <input type="text" id="receiver_name" placeholder="RECEIVER NAME" class="w-full border-b border-neutral-950 py-2 outline-none text-xs font-bold tracking-widest uppercase">
                    <input type="text" id="phone_number" placeholder="PHONE NUMBER" class="w-full border-b border-neutral-950 py-2 outline-none text-xs font-bold tracking-widest uppercase">
                    <input type="text" id="label" placeholder="LABEL (E.G. HOME / OFFICE)" class="w-full border-b border-neutral-950 py-2 outline-none text-xs font-bold tracking-widest uppercase">
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" id="province" placeholder="PROVINCE" class="w-full border-b border-neutral-950 py-2 outline-none text-xs font-bold tracking-widest uppercase">
                        <input type="text" id="city" placeholder="CITY" class="w-full border-b border-neutral-950 py-2 outline-none text-xs font-bold tracking-widest uppercase">
                    </div>
                    <textarea id="address_detail" placeholder="STREET DETAIL" rows="2" class="w-full border-b border-neutral-950 py-2 outline-none text-xs font-bold tracking-widest uppercase"></textarea>
                    
                    <div class="flex gap-4 pt-6">
                        <button onclick="document.getElementById('modal-address').classList.add('hidden')" class="flex-1 py-4 border border-neutral-950 text-[10px] font-black uppercase tracking-widest">Cancel</button>
                        <button onclick="submitAddress()" class="flex-1 py-4 bg-neutral-950 text-white text-[10px] font-black uppercase tracking-widest">Save Address</button>
                    </div>
                </div>
            </div>
        </div>

        <button onclick="document.getElementById('modal-address').classList.remove('hidden')" class="flex items-center gap-2 text-[10px] font-black tracking-[0.3em] uppercase opacity-50 hover:opacity-100 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-7-7v14"/></svg>
            Add New Address
        </button>
    </div>

    <div class="bg-white p-10 border border-neutral-950/5 h-fit sticky top-10">
        <h2 class="text-xl font-black uppercase tracking-tighter mb-8 border-b border-neutral-950/10 pb-4">Order Summary</h2>
        <div class="space-y-6 mb-10">
            @php $total = 0 @endphp
            @foreach(session('cart') as $item)
                @php $total += $item['price'] * $item['quantity'] @endphp
                <div class="flex justify-between text-sm">
                    <span class="opacity-60 uppercase tracking-widest">{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                    <span class="font-bold">IDR {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>
        <div class="border-t-[4px] border-neutral-950 pt-6">
            <div class="flex justify-between items-center mb-10">
                <span class="text-[10px] font-black tracking-widest opacity-40 uppercase">Total Amount</span>
                <span class="text-3xl font-black italic">IDR {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <button id="checkout-button" class="w-full bg-neutral-950 text-white py-6 font-black tracking-[0.4em] text-sm hover:bg-neutral-800 transition-all uppercase">
                Pay Now
            </button>
        </div>
    </div>
</main>

<div id="modal-address" class="fixed inset-0 bg-neutral-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-6">
    <div class="bg-white w-full max-w-lg p-10">
        <h3 class="text-2xl font-black uppercase italic tracking-tighter mb-8">New Shipping Address</h3>
        <div class="space-y-6">
            <input type="text" id="label" placeholder="LABEL (E.G. HOME / OFFICE)" class="w-full border-b border-neutral-950 py-3 outline-none text-xs font-bold tracking-widest uppercase">
            <textarea id="address_detail" placeholder="FULL ADDRESS" rows="3" class="w-full border-b border-neutral-950 py-3 outline-none text-xs font-bold tracking-widest uppercase"></textarea>
            <div class="flex gap-4">
                <button onclick="document.getElementById('modal-address').classList.add('hidden')" class="flex-1 py-4 border border-neutral-950 text-[10px] font-black uppercase tracking-widest">Cancel</button>
                <button onclick="submitAddress()" class="flex-1 py-4 bg-neutral-950 text-white text-[10px] font-black uppercase tracking-widest">Save Address</button>
            </div>
        </div>
    </div>
</div>

    </main>

    <script>
    // Inisialisasi Stripe
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
    const checkoutButton = document.getElementById('checkout-button');

    checkoutButton.addEventListener('click', function() {
        // 1. Ambil alamat yang dipilih
        const selectedAddress = document.querySelector('input[name="selected_address"]:checked');
        
        // 2. Ambil product_id dari URL (khusus untuk Direct Checkout)
        const urlParams = new URLSearchParams(window.location.search);
        const directId = urlParams.get('direct_id');
        
        if (!selectedAddress) {
            alert("PLEASE SELECT A SHIPPING ADDRESS.");
            return;
        }

        // UI Feedback
        checkoutButton.innerText = "PROCESSING...";
        checkoutButton.disabled = true;

        // 3. Kirim request ke CheckoutController
        fetch("{{ route('checkout.process') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                selected_address: selectedAddress.value,
                product_id: directId // Akan bernilai null jika checkout dari cart biasa
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.url) {
                // Redirect ke Stripe
                window.location.href = data.url;
            } else {
                throw new Error(data.error || 'Stripe URL not found');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("CHECKOUT ERROR: " + error.message);
            checkoutButton.innerText = "PAY NOW";
            checkoutButton.disabled = false;
        });
    });

    // --- LOGIC SIMPAN ALAMAT BARU ---
    function submitAddress() {
        const payload = {
            receiver_name: document.getElementById('receiver_name').value,
            phone_number: document.getElementById('phone_number').value,
            label: document.getElementById('label').value,
            province: document.getElementById('province').value,
            city: document.getElementById('city').value,
            address_detail: document.getElementById('address_detail').value,
        };

        // Validasi simpel sebelum kirim
        if (!payload.receiver_name || !payload.address_detail) {
            alert("Please fill in the required fields.");
            return;
        }

        fetch("{{ route('address.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload(); 
            } else {
                alert("Failed to save address: " + (data.message || "Unknown error"));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error saving address. Make sure all fields are filled.");
        });
    }

    // --- LOGIC HAPUS ALAMAT ---
    function deleteAddress(id) {
        if (!confirm('Are you sure you want to delete this address?')) return;
        
        fetch(`/address/${id}`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => console.error(err));
    }
</script>
</body>
</html>