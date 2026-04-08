<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREED | ACCESS</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center p-6 bg-cover bg-center font-sans" style="background-image: url('/img/bgauth.jpg')">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

    <a href="/" class="fixed top-10 left-10 z-[70] flex items-center gap-2 group">
        <div class="w-10 h-10 border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-black transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </div>
        <span class="text-[10px] tracking-[0.4em] font-bold opacity-0 group-hover:opacity-100 transition-all uppercase">Back to Home</span>
    </a>

    <div class="relative z-10 w-full max-w-2xl bg-white/5 border border-white/10 p-8 backdrop-blur-md">
        <div class="flex gap-8 mb-8 border-b border-white/10 pb-4">
            <button onclick="switchTab('login')" id="btn-login" class="text-xl font-black italic opacity-100 transition-all uppercase">SIGN IN</button>
            <button onclick="switchTab('register')" id="btn-register" class="text-xl font-black italic opacity-30 transition-all uppercase">SIGN UP</button>
        </div>

        <form id="form-login" action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="user_email" placeholder="USERNAME" class="w-full bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white tracking-widest text-sm">
            <div class="relative">
                <input type="password" name="password" id="pass-login" placeholder="PASSWORD" class="w-full bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white tracking-widest text-sm">
                <button type="button" onclick="togglePass('pass-login')" class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] opacity-50 hover:opacity-100">SHOW/HIDE</button>
            </div>
            <button class="w-full bg-white text-black font-black py-4 hover:bg-black hover:text-white transition-all uppercase tracking-widest">Login</button>
        </form>

        <form id="form-register" action="{{ route('register') }}" method="POST" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input type="text" name="full_name" placeholder="FULL NAME" class="bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white text-sm">
            <input type="text" name="username" maxlength="10" placeholder="USERNAME (MAX 10)" class="bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white text-sm">
            <input type="text" name="phone_number" placeholder="PHONE" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white text-sm">
            <input type="text" name="province" placeholder="PROVINCE" class="bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white text-sm">
            <input type="text" name="city" placeholder="CITY / DISTRICT" class="bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white text-sm">
            <input type="text" name="postal_code" placeholder="POSTAL CODE" class="bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white text-sm">
            <textarea name="address_detail" placeholder="ADDRESS DETAIL" class="md:col-span-2 bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white text-sm min-h-[100px]"></textarea>
            
            <div class="relative">
                <input type="password" name="password" id="pass-reg" placeholder="PASSWORD" class="w-full bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white text-sm">
                <button type="button" onclick="togglePass('pass-reg')" class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] opacity-50 hover:opacity-100">SHOW/HIDE</button>
            </div>
            <div class="relative">
                <input type="password" name="password_confirmation" id="pass-conf" placeholder="CONFIRM PASSWORD" class="w-full bg-transparent border border-white/20 p-4 focus:outline-none focus:border-white text-sm">
                <button type="button" onclick="togglePass('pass-conf')" class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] opacity-50 hover:opacity-100">SHOW/HIDE</button>
            </div>
            
            <button class="md:col-span-2 bg-white text-black font-black py-4 hover:bg-black hover:text-white transition-all mt-4 uppercase tracking-widest">Create Account</button>
        </form>
    </div>

    <script>
        function switchTab(type) {
            const isLogin = type === 'login';
            document.getElementById('form-login').classList.toggle('hidden', !isLogin);
            document.getElementById('form-register').classList.toggle('hidden', isLogin);
            document.getElementById('btn-login').style.opacity = isLogin ? '1' : '0.3';
            document.getElementById('btn-register').style.opacity = isLogin ? '0.3' : '1';
        }
        function togglePass(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>