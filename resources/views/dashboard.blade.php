<style>
    .mobile-only {
        display: none !important;
    }

    @media (max-width: 960px) {
        .mobile-hidden {
            display: none !important;
        }

        .mobile-only {
            display: grid !important;
        }
    }
</style>

<x-app-layout>
    <main class="flex-1 overflow-x-hidden overflow-y-auto">
        {{-- <div class="rounded-2xl shadow-md max-w-full mx-auto py-10 px-4"> --}}
        <div class="rounded-2xl  max-w-5xl mx-auto py-10 px-4">
            <h1 class="text-4xl font-extrabold text-center text-indigo-700 mt-5 mb-10 tracking-wide">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 1.343-3 3v1c0 1.657 1.343 3 3 3s3-1.343 3-3v-1c0-1.657-1.343-3-3-3z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 2v2m0 16v2m8-10h2M2 12H4m15.364-7.364l1.414 1.414M4.222 19.778l1.414-1.414m12.728 0l1.414 1.414M4.222 4.222l1.414 1.414" />
                    </svg>
                    แดชบอร์ด
                </span>
            </h1>

            <!-- layout responsive: บนสุด 1 ใบ, ล่าง 2 ใบ -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <!-- การ์ดบน: ขึ้นทะเบียนสินค้าใหม่ (กิน 2 ช่องบนจอใหญ่) -->
                <a href="{{ route('newregis.index') }}"
                    class="group bg-gradient-to-br from-green-100 to-green-50 p-4 rounded-3xl text-center 
                           border-2 border-green-200 hover:scale-105 transition-all duration-300 
                           h-[200px] col-span-1 md:col-span-2">
                    <div class="flex justify-center mb-2">
                        <div class="bg-green-200 rounded-full p-3 group-hover:bg-green-300 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-clipboard-pen">
                                <rect width="8" height="4" x="8" y="2" rx="1" />
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5.5" />
                                <path d="M4 13.5V6a2 2 0 0 1 2-2h2" />
                                <path
                                    d="M13.378 15.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                            </svg>
                        </div>
                    </div>
                    {{-- <h2 class="text-lg font-bold text-green-700 mb-1 tracking-wide">ขึ้นทะเบียนสินค้าใหม่</h2> --}}
                    <h2 class="text-2xl font-bold text-green-700 mb-4 tracking-wide">ขึ้นทะเบียนใหม่</h2>

                    {{-- <p class="text-4xl text-green-600 font-extrabold mb-1">{{ $totalNewRegistrations }}</p> --}}
                    <p class="text-4xl text-green-600 font-extrabold mb-1">{{ $totalNewRegistrations }}</p>


                </a>

                <!-- การ์ดนำเข้า -->
                <a href="{{ route('import.index') }}"
                    class="group bg-gradient-to-br from-blue-300 to-blue-50 p-4 rounded-3xl text-center 
                           border-2 border-blue-200 hover:scale-105 transition-all duration-300 
                           h-[200px]">
                    <div class="flex justify-center mb-2">
                        <div class="bg-blue-200 rounded-full p-3 group-hover:bg-blue-300 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-file-text">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <path d="M10 9H8" />
                                <path d="M16 13H8" />
                                <path d="M16 17H8" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-blue-700 mb-4 tracking-wide">ทะเบียนนำเข้า</h2>
                    <div class="grid grid-cols-3 gap-3 mt-2 mobile-hidden">
                        <div class="bg-green-100 border border-green-200 rounded-xl p-2 shadow-sm">
                            <p class="text-2xl font-bold text-green-600 mb-2 mt-2 tracking-wide">ทั้งหมด</p>
                            <p class="text-4xl text-green-600 font-extrabold mb-1">{{ $totalImport }}</p>
                        </div>
                        <div class="bg-yellow-100 border border-yellow-200 rounded-xl p-2 shadow-sm">
                            <p class="text-2xl font-bold text-yellow-600 mb-2 mt-2 tracking-wide">ใกล้หมด</p>
                            <p class="text-4xl text-yellow-600 font-extrabold mb-1">{{ $soonImport }}</p>
                        </div>
                        <div class="bg-red-100 border border-red-200 rounded-xl p-2 shadow-sm">
                            <p class="text-2xl font-bold text-red-600 mb-2 mt-2 tracking-wide">หมดอายุ</p>
                            {{-- <p class="md:text-xl font-bold text-red-600 mt-2">{{ $expiredImport }}</p> --}}
                            <p class="text-4xl text-red-600 font-extrabold mb-1">{{ $expiredImport }}</p>

                        </div>
                    </div>
                    <div class="grid grid-rows-3 gap-3 mt-2 mobile-only">
                        <div class="bg-green-100 border border-green-200 rounded-xl p-2 shadow-sm">
                            <p class="text-2xl font-bold text-green-600 mb-2 mt-2 tracking-wide">ทั้งหมด</p>
                            <p class="text-4xl text-green-600 font-extrabold mb-1">{{ $totalImport }}</p>
                        </div>
                        <div class="bg-yellow-100 border border-yellow-200 rounded-xl p-2 shadow-sm">
                            <p class="text-2xl font-bold text-yellow-600 mb-2 mt-2 tracking-wide">ใกล้หมด</p>
                            <p class="text-4xl text-yellow-600 font-extrabold mb-1">{{ $soonImport }}</p>
                        </div>
                        <div class="bg-red-100 border border-red-200 rounded-xl p-2 shadow-sm">
                            <p class="text-2xl font-bold text-red-600 mb-2 mt-2 tracking-wide">หมดอายุ</p>
                            {{-- <p class="md:text-xl font-bold text-red-600 mt-2">{{ $expiredImport }}</p> --}}
                            <p class="text-4xl text-red-600 font-extrabold mb-1">{{ $expiredImport }}</p>

                        </div>
                    </div>
                </a>

                <!-- การ์ดผลิต -->
                <a href="{{ route('createproduct.index') }}"
                    class="group bg-gradient-to-br from-blue-300 to-blue-50 p-4 rounded-3xl text-center 
                           border-2 border-blue-200 hover:scale-105 transition-all duration-300 
                           h-[250px]">
                    <div class="flex justify-center mb-2">
                        <div class="bg-blue-200 rounded-full p-3 group-hover:bg-blue-300 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-clipboard-check">
                                <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                <path d="m9 14 2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    {{-- <h2 class="text-lg font-bold text-blue-700 mb-4 tracking-wide">ทะเบียนผลิต</h2> --}}
                    <h2 class="text-2xl font-bold text-blue-700 mb-4 tracking-wide">ทะเบียนผลิต</h2>

                    <div class="grid grid-cols-3 gap-3 mt-2 mobile-hidden">
                        <div class="bg-green-100 border border-green-200 rounded-xl p-2 shadow-sm">
                            {{-- <p class="md:text-lg text-green-600">ทั้งหมด</p> --}}
                            <p class="text-2xl font-bold text-green-600 mb-2 mt-2 tracking-wide">ทั้งหมด</p>
                            {{-- <p class="md:text-xl font-bold text-green-600 mt-2">{{ $totalProduct }}</p> --}}
                            <p class="text-4xl text-green-600 font-extrabold mb-1">{{ $totalProduct }}</p>
                        </div>
                        <div class="bg-yellow-100 border border-yellow-200 rounded-xl p-2 shadow-sm">
                            {{-- <p class="md:text-lg text-yellow-600">ใกล้หมด</p> --}}
                            <p class="text-2xl font-bold text-yellow-600 mb-2 mt-2 tracking-wide">ใกล้หมด</p>
                            {{-- <p class="md:text-xl font-bold text-yellow-600 mt-2">{{ $soonProduct }}</p> --}}
                            <p class="text-4xl text-yellow-600 font-extrabold mb-1">{{ $soonProduct }}</p>
                        </div>
                        <div class="bg-red-100 border border-red-200 rounded-xl p-2 shadow-sm">
                            {{-- <p class="md:text-lg text-red-600">หมดอายุ</p> --}}
                            <p class="text-2xl font-bold text-red-600 mb-2 mt-2 tracking-wide">หมดอายุ</p>

                            {{-- <p class="md:text-xl font-bold text-red-600 mt-2">{{ $expiredProduct }}</p> --}}
                            <p class="text-4xl text-red-600 font-extrabold mb-1">{{ $expiredProduct }}</p>

                        </div>
                    </div>
                    <div class="grid grid-rows-3 gap-3 mt-2 mobile-only">
                        <div class="bg-green-100 border border-green-200 rounded-xl p-2 shadow-sm">
                            {{-- <p class="md:text-lg text-green-600">ทั้งหมด</p> --}}
                            <p class="text-2xl font-bold text-green-600 mb-2 mt-2 tracking-wide">ทั้งหมด</p>
                            {{-- <p class="md:text-xl font-bold text-green-600 mt-2">{{ $totalProduct }}</p> --}}
                            <p class="text-4xl text-green-600 font-extrabold mb-1">{{ $totalProduct }}</p>
                        </div>
                        <div class="bg-yellow-100 border border-yellow-200 rounded-xl p-2 shadow-sm">
                            {{-- <p class="md:text-lg text-yellow-600">ใกล้หมด</p> --}}
                            <p class="text-2xl font-bold text-yellow-600 mb-2 mt-2 tracking-wide">ใกล้หมด</p>
                            {{-- <p class="md:text-xl font-bold text-yellow-600 mt-2">{{ $soonProduct }}</p> --}}
                            <p class="text-4xl text-yellow-600 font-extrabold mb-1">{{ $soonProduct }}</p>
                        </div>
                        <div class="bg-red-100 border border-red-200 rounded-xl p-2 shadow-sm">
                            {{-- <p class="md:text-lg text-red-600">หมดอายุ</p> --}}
                            <p class="text-2xl font-bold text-red-600 mb-2 mt-2 tracking-wide">หมดอายุ</p>

                            {{-- <p class="md:text-xl font-bold text-red-600 mt-2">{{ $expiredProduct }}</p> --}}
                            <p class="text-4xl text-red-600 font-extrabold mb-1">{{ $expiredProduct }}</p>

                        </div>
                    </div>
                </a>
            </div>
        </div>
    </main>
</x-app-layout>
