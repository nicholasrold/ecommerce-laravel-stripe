<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREED | SYSTEM ACCESS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0F0F0F] text-white font-sans antialiased flex items-center justify-center min-h-screen">

    <div class="w-full max-w-sm p-10">
        <div class="mb-12 text-center">
            <h1 class="text-5xl font-black italic uppercase tracking-tighter">Greed</h1>
            <p class="text-[9px] tracking-[0.5em] uppercase opacity-40 mt-2">Core System v1.0</p>
        </div>

        @if($errors->any())
            <div class="mb-6 text-[10px] font-bold uppercase tracking-widest text-red-500 border border-red-500/20 p-4 bg-red-500/5">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-8">
            @csrf
            <div>
                <label class="text-[8px] font-black  tracking-[0.3em] opacity-30 block mb-3">Identity</label>
                <input type="text" name="username" required autofocus 
                    class="w-full bg-transparent border-b border-white/10 py-3 outline-none focus:border-white transition-colors text-sm font-bold  tracking-widest">
            </div>

            <div>
                <label class="text-[8px] font-black  tracking-[0.3em] opacity-30 block mb-3">Security Key</label>
                <input type="password" name="password" required 
                    class="w-full bg-transparent border-b border-white/10 py-3 outline-none focus:border-white transition-colors text-sm tracking-widest">
            </div>

            <button type="submit" 
                class="w-full bg-white text-black py-5 text-[10px] font-black  tracking-[0.5em] hover:bg-neutral-200 transition-all active:scale-[0.98]">
                Authorize Access —>
            </button>
        </form>

        <div class="mt-12 text-center">
            <a href="{{ url('/') }}" class="text-[8px] font-bold uppercase tracking-widest opacity-20 hover:opacity-100 transition-opacity">
                Back to Terminal
            </a>
        </div>
    </div>

</body>
</html>