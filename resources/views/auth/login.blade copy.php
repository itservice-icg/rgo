<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทะเบียนออนไลน์ (RGO)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="relative min-h-screen bg-gradient-to-b from-pink-200 via-purple-200 to-blue-200 flex items-center justify-center">
    <div class="">
        <h1 class="text-5xl font-bold text-white text-center mb-12 whitespace-nowrap">
            ทะเบียนออนไลน์ ( RGO )
        </h1>
        <h2 class="text-3xl text-white text-center mb-8">เข้าสู่ระบบ</h2>

        <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
            @csrf
            <div class="flex items-center bg-white rounded-full px-4 py-2 shadow-sm max-w-sm mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 mr-2" fill="none"
                    viewBox="0 0 24 24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <input type="email" name="email" placeholder="Email"
                    class="flex-1 outline-none text-sm bg-transparent" required>
            </div>


            <div class="flex items-center bg-white rounded-full px-4 py-2 shadow-sm max-w-sm mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>


                <input type="password" name="password" placeholder="Password"
                    class="flex-1 outline-none text-sm bg-transparent" required>
            </div>

            <div class="flex justify-center">
                <button type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-10 rounded-full shadow-md">
                    Login
                </button>
            </div>
        </form>
    </div>

    <!-- Wave Bottom Decoration (Flipped) -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none transform rotate-180">
        <svg viewBox="0 0 1440 150" xmlns="http://www.w3.org/2000/svg">
            <path fill="#d8a4f7" fill-opacity="1"
                d="M0,96L60,106.7C120,117,240,139,360,144C480,149,600,139,720,128C840,117,960,107,1080,106.7C1200,107,1320,117,1380,122.7L1440,128L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z">
            </path>
        </svg>
    </div>

</body>

</html>
