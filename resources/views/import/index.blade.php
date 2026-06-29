<style>
    nav[aria-label="Pagination Navigation"] .sm\:hidden > a,
    nav[aria-label="Pagination Navigation"] .sm\:hidden > span {
        font-size: 0 !important;
        border-radius: 10px;
        padding: 8px 16px;
    }

    nav[aria-label="Pagination Navigation"] .sm\:hidden > a:first-child::after,
    nav[aria-label="Pagination Navigation"] .sm\:hidden > span:first-child::after {
        content: "« ย้อนกลับ";
        font-size: 14px;
        font-weight: 600;
    }

    nav[aria-label="Pagination Navigation"] .sm\:hidden > a:last-child::after,
    nav[aria-label="Pagination Navigation"] .sm\:hidden > span:last-child::after {
        content: "ถัดไป »";
        font-size: 14px;
        font-weight: 600;
    }

    .document-popup-grid {
        display: grid;
        grid-template-columns: 20rem minmax(0, 1fr);
        gap: 1rem;
        min-height: 70vh;
        text-align: left;
    }

    .document-pdf-toolbar {
        align-items: center;
        background: #2f3133;
        color: #ffffff;
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        min-height: 3rem;
        padding: 0.35rem 0.75rem;
    }

    .document-pdf-toolbar button {
        align-items: center;
        background: #3a3c3f;
        border: 1px solid #4b4d50;
        border-radius: 0.4rem;
        color: #ffffff;
        display: inline-flex;
        font-size: 1rem;
        font-weight: 700;
        height: 2.2rem;
        justify-content: center;
        line-height: 1;
        width: 2.2rem;
    }

    .document-pdf-toolbar button:hover:not(:disabled) {
        background: #4a4d50;
    }

    .document-pdf-toolbar button:disabled {
        cursor: not-allowed;
        opacity: 0.4;
    }

    .document-pdf-toolbar svg {
        height: 1.15rem;
        width: 1.15rem;
    }

    .document-pdf-stage {
        align-items: flex-start;
        background: #f3f4f6;
        display: flex;
        flex: 1;
        justify-content: center;
        overflow: auto;
        padding: 1rem;
    }

    .document-pdf-stage canvas {
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
        max-width: none;
    }

    @media (max-width: 1023px) {
        .document-popup-grid {
            grid-template-columns: 1fr;
        }
    }

    .custom-close-popup>.swal2-close{
        width: 82px;
    }

    .swal2-popup.custom-close-popup {
        max-width: calc(100vw - 1rem) !important;
    }

    .summary-status-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    @media (max-width: 640px) {
        .summary-status-grid {
            grid-template-columns: 1fr;
        }

        .document-popup-grid {
            gap: 0.35rem;
            min-height: calc(100vh - 8rem);
        }

        .document-popup-grid > :first-child {
            max-height: 22vh;
            padding: 0.35rem !important;
        }

        .document-popup-grid #swalDocumentPane {
            min-height: calc(100vh - 19rem);
        }

        .document-pdf-toolbar {
            gap: 0.2rem;
            justify-content: center;
            overflow-x: auto;
            min-height: 2.35rem;
            padding: 0.2rem 0.3rem;
        }

        .document-pdf-toolbar button {
            flex: 0 0 auto;
            height: 1.85rem;
            width: 1.85rem;
        }

        .document-pdf-toolbar svg {
            height: 1rem;
            width: 1rem;
        }

        .document-pdf-stage {
            padding: 0.15rem;
        }

        .swal2-popup.custom-close-popup {
            padding: 0.2rem !important;
        }

        .swal2-popup.custom-close-popup .swal2-title {
            font-size: 1rem;
            padding: 0.35rem 2rem 0.15rem 0.35rem;
        }

        .swal2-popup.custom-close-popup .swal2-html-container {
            margin: 0 !important;
        }

        .swal2-popup.custom-close-popup .swal2-close {
            height: 2rem;
            width: 2rem;
        }
    }
</style>
<x-app-layout>
    <main class="flex-1 overflow-x-hidden overflow-y-auto">
        <div class="container mx-auto px-1 py-2 sm:px-6 sm:py-6">
            <h1 class="text-2xl sm:text-4xl font-extrabold text-center text-indigo-700 mt-1 sm:mt-5 mb-3 sm:mb-10 tracking-wide">
                <span class="inline-flex items-center justify-center gap-2">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 1.343-3 3v1c0 1.657 1.343 3 3 3s3-1.343 3-3v-1c0-1.657-1.343-3-3-3z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 2v2m0 16v2m8-10h2M2 12H4m15.364-7.364l1.414 1.414M4.222 19.778l1.414-1.414m12.728 0l1.414 1.414M4.222 4.222l1.414 1.414" />
                    </svg>
                    ข้อมูลทะเบียนนำเข้า
                </span>
            </h1>

            {{-- สรุปสถานะทะเบียน --}}
            <!-- <div class="flex flex-row justify-around mb-10 space-x-4">
                {{-- ทั้งหมด --}}
                <a href="{{ route('import.index', array_merge(request()->except('status_filter', 'page'), ['page' => 1])) }}"
                    class="group block h-full bg-gradient-to-br from-blue-200 to-blue-100 p-4 rounded-3xl text-center border-2 border-blue-400 hover:scale-105 transition-all duration-300">
                    <div class="flex justify-center mb-2">
                        <div class="bg-blue-300 rounded-full p-3 group-hover:bg-blue-400 transition">
                            {{-- ไอคอนใหม่ --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-lg font-bold text-blue-700 mb-1 tracking-wide">ทะเบียนนำเข้าทั้งหมด</h2>
                    <p class="text-2xl font-bold text-blue-600">{{ $total ?? 0 }}</p>
                </a>

                {{-- ใกล้หมดอายุ --}}
                <a href="{{ route('import.index', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'soon_expired', 'page' => 1])) }}"
                    class="group block h-full bg-gradient-to-br from-yellow-100 to-yellow-50 p-4 rounded-3xl text-center border-2 border-yellow-200 hover:scale-105 transition-all duration-300">
                    <div class="flex justify-center mb-2">
                        <div class="bg-yellow-200 rounded-full p-3 group-hover:bg-yellow-300 transition">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                <circle cx="12" cy="12" r="10" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-lg font-bold text-yellow-700 mb-1 tracking-wide">ทะเบียนนำเข้าใกล้หมดอายุ</h2>
                    <p class="text-2xl font-bold text-yellow-600">{{ $soonCount ?? 0 }}</p>
                </a>

                {{-- หมดอายุ --}}
                <a href="{{ route('import.index', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'expired', 'page' => 1])) }}"
                    class="group block h-full bg-gradient-to-br from-red-100 to-red-50 p-4 rounded-3xl text-center border-2 border-red-200 hover:scale-105 transition-all duration-300">
                    <div class="flex justify-center mb-2">
                        <div class="bg-red-200 rounded-full p-3 group-hover:bg-red-300 transition">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                <circle cx="12" cy="12" r="10" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-lg font-bold text-red-700 mb-1 tracking-wide">ทะเบียนนำเข้าหมดอายุ</h2>
                    <p class="text-2xl font-bold text-red-600">{{ $expiredCount ?? 0 }}</p>
                </a>
            </div>
             -->
            <div class="summary-status-grid gap-1 sm:gap-3 lg:gap-4 mb-2 sm:mb-4 lg:mb-6">

    {{-- ทั้งหมด --}}
    <a href="{{ route('import.index', array_merge(request()->except('status_filter', 'page'), ['page' => 1])) }}"
        class="rounded-2xl border border-blue-200 bg-blue-50 px-1 py-2 sm:px-2 sm:py-3 lg:px-4 lg:py-4 text-center
               active:scale-95 transition
               {{ !request('status_filter') ? 'ring-2 ring-blue-300 bg-blue-100' : '' }}">

        <div class="mx-auto mb-1 flex h-8 w-8 items-center justify-center rounded-full bg-blue-200" style="width: 60px; height: 60px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  style="width: 50px!important; height: 50px!important;"
                stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-700">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
            </svg>
        </div>

        <div class="text-2xl font-semibold leading-tight text-blue-700">
            ทั้งหมด
        </div>

        <div class="mt-0.5 text-4xl font-extrabold leading-none text-blue-700">
            {{ $total ?? 0 }}
        </div>
    </a>

    {{-- ใกล้หมดอายุ --}}
    <a href="{{ route('import.index', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'soon_expired', 'page' => 1])) }}"
        class="rounded-2xl border border-yellow-200 bg-yellow-50 px-1 py-2 sm:px-2 sm:py-3 lg:px-4 lg:py-4 text-center
               active:scale-95 transition
               {{ request('status_filter') == 'soon_expired' ? 'ring-2 ring-yellow-300 bg-yellow-100' : '' }}">

        <div class="mx-auto mb-1 flex h-8 w-8 items-center justify-center rounded-full bg-yellow-200" style="width: 60px; height: 60px;">
            <svg class="h-4 w-4 text-yellow-700" fill="none" stroke="currentColor" stroke-width="2" style="width: 50px!important; height: 50px!important;"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                <circle cx="12" cy="12" r="10" />
            </svg>
        </div>

        <div class="text-2xl font-semibold leading-tight text-yellow-700">
            ใกล้หมดอายุ
        </div>

        <div class="mt-0.5 text-4xl font-extrabold leading-none text-yellow-700">
            {{ $soonCount ?? 0 }}
        </div>
    </a>

    {{-- หมดอายุ --}}
    <a href="{{ route('import.index', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'expired', 'page' => 1])) }}"
        class="rounded-2xl border border-red-200 bg-red-50 px-1 py-2 sm:px-2 sm:py-3 lg:px-4 lg:py-4 text-center
               active:scale-95 transition
               {{ request('status_filter') == 'expired' ? 'ring-2 ring-red-300 bg-red-100' : '' }}">

        <div class="mx-auto mb-1 flex h-8 w-8 items-center justify-center rounded-full bg-red-200" style="width: 60px; height: 60px;">
            <svg class="h-4 w-4 text-red-700" fill="none" stroke="currentColor" stroke-width="2" style="width: 50px!important; height: 50px!important;"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                <circle cx="12" cy="12" r="10" />
            </svg>
        </div>

        <div class="text-2xl font-semibold leading-tight text-red-700">
            หมดอายุ
        </div>

        <div class="mt-0.5 text-4xl font-extrabold leading-none text-red-700">
            {{ $expiredCount ?? 0 }}
        </div>
    </a>

</div>
            <div class="hidden lg:block flex flex-col sm:flex-row justify-between items-center mx-3 mb-2" >
                {{-- <form action="{{ route('import.index') }}" method="GET" class="flex items-center gap-2 mb-2"> --}}
                <form id="filterForm" action="{{ route('import.index') }}" method="GET" class="flex items-center gap-2 mb-2">
                    <div class="relative flex-grow min-w-[280px]" >
                        <label for="search_query" class="mx-3 text-base block text-gray-700 mb-1 mt-3">ค้นหา</label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none mt-9">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input type="text" id="search_query" name="search"
                            placeholder="ชื่อวัตถุอันตราย /เลขที่ทะเบียน" value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 w-[500px] rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600 transition duration-200 ease-in-out text-gray-700 shadow-sm" style="width:100%" />
                        {{-- class="pl-10 pr-4 py-2 w-96 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600 transition duration-200 ease-in-out text-gray-700 shadow-sm" /> --}}
                    </div>
                    {{-- วันที่เริ่ม --}}
                    <div class="flex-grow min-w-[180px]">
                        <label for="expiry_date_from"
                            class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันที่เริ่ม</label>
                        <input id="expiry_date_from"
                            class="date-th px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-200 ease-in-out text-gray-500 text-base shadow-sm w-full"
                            type="text" name="expiry_date_from" value="{{ request('expiry_date_from') }}"
                            placeholder="วว/ดด/ปปปป" autocomplete="off" autocorrect="off" autocapitalize="off"
                            spellcheck="false" />
                    </div>

                    {{-- วันที่สิ้นสุด --}}
                    <div class="flex-grow min-w-[180px]">
                        <label for="expiry_date_to"
                            class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันที่สิ้นสุด</label>
                        <input id="expiry_date_to"
                            class="date-th px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-200 ease-in-out text-gray-500 text-base shadow-sm w-full"
                            type="text" name="expiry_date_to" value="{{ request('expiry_date_to') }}"
                            placeholder="วว/ดด/ปปปป" autocomplete="off" autocorrect="off" autocapitalize="off"
                            spellcheck="false" />
                    </div>

                    <div class="flex gap-3 mt-10">
                        <button type="submit"
                            class="bg-gradient-to-r from-blue-500 to-blue-500 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transform hover:scale-105 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                            <svg class="w-5 h-5 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            ค้นหา
                        </button>

                        {{-- เพิ่มปุ่มล้างการค้นหา --}}
                        @if (request('search') || request('expiry_date_from') || request('expiry_date_to'))
                            <a href="{{ route('import.index') }}"
                                class="inline-flex items-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg shadow-md transform hover:scale-105 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                                <svg class="w-5 h-5 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                                ล้างค้นหา
                            </a>
                        @endif
                    </div>
                </form>
                {{-- @can('Inregister create')
                    <a href="{{ route('import.create') }}"
                        class="mt-8 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transform hover:scale-105 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                        + เพิ่มข้อมูล
                    </a>
                @endcan --}}
            </div>
<div class="lg:hidden ">
    <form id="filterForm"
        action="{{ route('import.index') }}"
        method="GET"
        class="bg-white border border-gray-100 rounded-2xl shadow-sm p-2 sm:p-4">

        <div class="grid grid-cols-1 lg:grid-cols-[1.5fr_0.8fr_0.8fr_auto] gap-2 sm:gap-3 lg:items-end">

            {{-- ค้นหา --}}
            <div class="min-w-0">
                <label for="search_query" class="block text-sm font-semibold text-gray-700 mb-1">
                    ค้นหา
                </label>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>

                    <input type="text"
                        id="search_query"
                        name="search"
                        placeholder="ชื่อวัตถุอันตราย / เลขที่ทะเบียน"
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-3 py-2 sm:pr-4 sm:py-3 rounded-xl border border-gray-300
                               focus:outline-none focus:ring-2 focus:ring-blue-500
                               text-gray-700 shadow-sm" />
                </div>
            </div>

            {{-- วันที่เริ่ม --}}
            <div class="min-w-0">
                <label for="expiry_date_from" class="block text-sm font-semibold text-gray-700 mb-1">
                    วันที่เริ่ม
                </label>

                <input id="expiry_date_from"
                    class="date-th w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-blue-500
                           text-gray-700 shadow-sm"
                    type="text"
                    name="expiry_date_from"
                    value="{{ request('expiry_date_from') }}"
                    placeholder="วว/ดด/ปปปป"
                    autocomplete="off"
                    autocorrect="off"
                    autocapitalize="off"
                    spellcheck="false" />
            </div>

            {{-- วันที่สิ้นสุด --}}
            <div class="min-w-0">
                <label for="expiry_date_to" class="block text-sm font-semibold text-gray-700 mb-1">
                    วันที่สิ้นสุด
                </label>

                <input id="expiry_date_to"
                    class="date-th w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-blue-500
                           text-gray-700 shadow-sm"
                    type="text"
                    name="expiry_date_to"
                    value="{{ request('expiry_date_to') }}"
                    placeholder="วว/ดด/ปปปป"
                    autocomplete="off"
                    autocorrect="off"
                    autocapitalize="off"
                    spellcheck="false" />
            </div>

            {{-- ปุ่ม --}}
            <div class="grid grid-cols-2 lg:flex gap-2 lg:min-w-max">

                <button type="submit"
                    class="w-full lg:w-auto inline-flex items-center justify-center gap-2
                           bg-blue-600 hover:bg-blue-700 text-white font-semibold
                           px-3 py-2 sm:px-5 sm:py-3 rounded-xl shadow-sm active:scale-95 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    ค้นหา
                </button>

                @if (request('search') || request('expiry_date_from') || request('expiry_date_to'))
                    <a href="{{ route('import.index') }}"
                        class="w-full lg:w-auto inline-flex items-center justify-center gap-2
                               bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold
                               px-3 py-2 sm:px-5 sm:py-3 rounded-xl border border-gray-200 shadow-sm
                               active:scale-95 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        ล้าง
                    </a>
                @else
                    <div class="hidden lg:block"></div>
                @endif

            </div>

        </div>
    </form>
</div>

            {{-- Desktop Table --}}
            <div class="hidden lg:block bg-white rounded-2xl overflow-hidden border border-gray-200 ">
                <div class="overflow-x-auto ">
                    <table class="min-w-[1180px] table-fixed bg-white">
                        <colgroup>
                            <col class="w-20">
                            <col class="w-64">
                            <col class="w-64">
                            <col class="w-64">
                            <col class="w-40">
                            <col class="w-36">
                            <col class="w-36">
                            <col class="w-56">
                        </colgroup>
                        <thead>
                            <tr class="bg-indigo-600 text-white text-left">
                                <th class="py-4 px-4 rounded-tl-2xl text-center">ลำดับ</th>
                                <th class="py-4 px-4">ชื่อวัตถุอันตราย (ไทย)</th>
                                <th class="py-4 px-4">ชื่อวัตถุอันตราย (อังกฤษ)</th>
                                <th class="py-4 px-4 ">ผู้นำเข้า</th>
                                {{-- <th class="py-4 px-4 text-center">ตัวย่อ</th> --}}
                                <th class="py-4 px-4 text-center">เลขที่ทะเบียน</th>
                                <th class="py-4 px-4 text-center">วันหมดอายุ</th>
                                <th class="py-4 px-4 text-center">สถานะ</th>
                                <th class="py-4 px-4 rounded-tr-2xl text-center">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('Inregister read')
                                @forelse ($imports as $index => $import)
                                    <tr class="border-b hover:bg-indigo-50 transition">
                                        <td class="py-4 px-4 font-semibold text-center text-gray-700">
                                            {{ $loop->iteration + ($imports->currentPage() - 1) * $imports->perPage() }}
                                        </td>
                                        <td class="py-4 px-4 break-words">{{ $import->chemical_name_th }}</td>
                                        <td class="py-4 px-4 break-words">{{ $import->chemical_name_en }}</td>
                                        <td class="py-4 px-4 break-words">{{ $import->importerCompany->full_name ?? '' }}</td>
                                        {{-- <td class="py-4 px-4 text-center">{{ $import->importerCompany->name ?? '' }}</td> --}}
                                        <td class="py-4 px-4 text-center break-words">{{ $import->registration_number }}</td>
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            @if ($import->expired_license_date)
                                                {{ \Carbon\Carbon::parse($import->expired_license_date)->addYears(543)->format('d/m/Y') }}
                                            @endif
                                        </td>
                                        {{-- การตกแต่งสีสถานะ --}}
                                        @php
                                            $importFiles = $import->files ?? collect();
                                            $approvalDocumentTypeCode = 'import_license';
                                            $approvalFiles = $importFiles->where('document_type_code', $approvalDocumentTypeCode);
                                            $registrationFiles = $importFiles->reject(function ($file) use ($approvalDocumentTypeCode) {
                                                return $file->document_type_code === $approvalDocumentTypeCode;
                                            });
                                            $legacyDocumentExists = $importFiles->isEmpty() && ($import->additional_document || $import->document);
                                        @endphp
                                        <td class="py-4 px-4 text-center">
                                            @if ($import->expired_license_date)
                                                @php
                                                    $statusClass = '';
                                                    $statusText = $import->status; // ใช้ค่าสถานะที่ถูกส่งมาจาก Controller

                                                    if ($statusText == 'หมดอายุ') {
                                                        $statusClass =
                                                            'inline-flex w-24 justify-center rounded-full px-3 py-1 font-semibold text-white bg-red-500';
                                                    } elseif ($statusText == 'ใกล้หมด') {
                                                        $statusClass =
                                                            'inline-flex w-24 justify-center rounded-full px-3 py-1 font-semibold text-gray-600 bg-yellow-300';
                                                    } else {
                                                        $statusClass =
                                                            'inline-flex w-24 justify-center rounded-full px-3 py-1 font-semibold text-white bg-green-500'; // สถานะปกติ เช่น 'ใช้งานอยู่'
                                                    }
                                                @endphp
                                                <span class="{{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                            @endif
                                        </td>
                                        {{-- สิ้นสุดการตกแต่งสีสถานะ --}}
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center gap-3 justify-center">
                                                @can('Inregister read')
                                                    <a href="{{ route('import.show', $import->id) }}"
                                                        class="inline-flex items-center justify-center p-2 rounded-full text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200"
                                                        title="ดูรายละเอียด">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                             @can('import_data_staple read')
                                                    <button type="button"
                                                        onclick="openDocumentFilesModal('import-files-template-{{ $import->id }}', 'รายการไฟล์ทะเบียนนำเข้า')"
                                                        class="inline-flex items-center justify-center p-2 rounded-full text-white bg-blue-600 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200"
                                                        title="ดู PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            aria-hidden="true">
                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                            <path d="M14 2v6h6"></path>
                                                            <path d="M8 13h8"></path>
                                                            <path d="M8 17h5"></path>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="inline-flex items-center justify-center p-2 rounded-full text-gray-400 bg-gray-200 cursor-not-allowed"
                                                        title="ไม่มีสิทธิ์ดูไฟล์เอกสาร"
                                                        disabled>
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            aria-hidden="true">
                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                            <path d="M14 2v6h6"></path>
                                                            <path d="M8 13h8"></path>
                                                            <path d="M8 17h5"></path>
                                                        </svg>
                                                    </button>
                                                @endcan
                                                @can('import_data_staple read')
                                                    <template id="import-files-template-{{ $import->id }}">
                                                        <div class="space-y-4 text-left">
                                                                <div>
                                                                    <h4 class="mb-2 text-sm font-bold text-gray-700" style="padding: 8px; color:#fff; background-color: rgba(79, 70, 229, var(--tw-bg-opacity)); border-radius:6px 6px 0 0;" >ไฟล์ทะเบียนนำเข้า</h4>
                                                                    @if ($registrationFiles->isNotEmpty())
                                                                        <div class="space-y-2">
                                                                            @foreach ($registrationFiles as $file)
                                                                                <button type="button"
                                                                                    data-file-url="{{ route('import.file', [$import, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                                                    data-file-name="{{ $file->original_name ?: basename($file->file_path) }}"
                                                                                    class="js-open-document-file flex w-full items-center justify-between gap-3 rounded-lg border border-gray-200 px-3 py-2 text-left text-sm text-gray-700 hover:bg-blue-50">
                                                                                    <span class="truncate">{{ $file->original_name ?: basename($file->file_path) }}</span>
                                                                                    <span class="shrink-0 text-xs text-gray-400">{{ optional($file->created_at)->format('d/m/Y H:i') }}</span>
                                                                                </button>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <p class="rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-400">
                                                                            ไม่มีไฟล์เอกสาร
                                                                        </p>
                                                                    @endif
                                                                </div>

                                                                <div>
                                                                    <h4 class="mb-2 text-sm font-bold text-gray-700" style="padding: 8px; color:#fff; background-color: rgba(79, 70, 229, var(--tw-bg-opacity));  border-radius:6px 6px 0 0;">ไฟล์ใบอนุญาตนำเข้า</h4>
                                                                    @if ($approvalFiles->isNotEmpty())
                                                                        <div class="space-y-2">
                                                                            @foreach ($approvalFiles as $file)
                                                                                <button type="button"
                                                                                    data-file-url="{{ route('import.file', [$import, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                                                    data-file-name="{{ $file->original_name ?: basename($file->file_path) }}"
                                                                                    class="js-open-document-file flex w-full items-center justify-between gap-3 rounded-lg border border-gray-200 px-3 py-2 text-left text-sm text-gray-700 hover:bg-blue-50">
                                                                                    <span class="truncate">{{ $file->original_name ?: basename($file->file_path) }}</span>
                                                                                    <span class="shrink-0 text-xs text-gray-400">{{ optional($file->created_at)->format('d/m/Y H:i') }}</span>
                                                                                </button>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <p class="rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-400">
                                                                            ไม่มีไฟล์เอกสาร
                                                                        </p>
                                                                    @endif
                                                                </div>

                                                        </div>
                                                    </template>
                                                @endcan
                                                @can('Inregister update')
                                                    <a href="{{ route('import.edit', $import->id) }}"
                                                        class="inline-flex items-center justify-center p-2 rounded-full text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                                        title="แก้ไข">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                                @can('Inregister delete')
                                                    <button onclick="confirmDelete({{ $import->id }})"
                                                        class="inline-flex items-center justify-center p-2 rounded-full text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200"
                                                        title="ลบ">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.92a2.25 2.25 0 0 1-2.244-2.077L4.74 5.959m1.049-.165c.51-.158 1.029-.28 1.563-.35L12 4.75m-4.78 2.152A.75.75 0 0 1 9 6.75h6m-3 0V4.5m-2.25 4.5h.008v.008H9.75V9Zm0 0H9.75Zm4.5 0h.008v.008H14.25V9Z" />
                                                        </svg>
                                                    </button>
                                                    <form id="delete-form-{{ $import->id }}"
                                                        action="{{ route('import.destroy', $import->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-6 px-8 text-center text-gray-400">
                                            ไม่มีข้อมูลทะเบียนนำเข้า</td>
                                    </tr>
                                @endforelse
                            @endcan
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 bg-white border-t border-gray-100 rounded-b-2xl">
                    {{-- {{ $imports->links() }} --}}
                    {{ $imports->appends(request()->query())->links() }}
                </div>
            </div>
{{-- Mobile Card List --}}
<div class="lg:hidden space-y-2 sm:space-y-4">
    @can('Inregister read')
        @forelse ($imports as $index => $import)
            @php
                $statusClass = '';
                $statusText = $import->status;

                if ($statusText == 'หมดอายุ') {
                    $statusClass = 'bg-red-100 text-red-700 border border-red-200';
                } elseif ($statusText == 'ใกล้หมด') {
                    $statusClass = 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                } else {
                    $statusClass = 'bg-green-100 text-green-700 border border-green-200';
                }
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 sm:p-4 active:scale-[0.99] transition">
                {{-- Header --}}
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="min-w-0">
                        <div class="text-xs text-gray-400 mb-1">
                            #{{ $loop->iteration + ($imports->currentPage() - 1) * $imports->perPage() }}
                        </div>

                        <h3 class="text-base font-bold text-gray-800 leading-snug line-clamp-2">
                            {{ $import->chemical_name_th }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1 line-clamp-1">
                            {{ $import->chemical_name_en }}
                        </p>
                    </div>

                    @if ($import->expired_license_date)
                        <span class="inline-flex w-24 shrink-0 justify-center rounded-full px-3 py-1 text-center text-xs font-bold {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="grid grid-cols-1 gap-2 text-sm">
                    <div class="flex justify-between gap-3 border-t pt-3">
                        <span class="text-gray-400">ผู้นำเข้า</span>
                        <span class="text-gray-700 font-medium text-right">
                            {{ $import->importerCompany->full_name ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-3">
                        <span class="text-gray-400">เลขทะเบียน</span>
                        <span class="text-gray-700 font-semibold text-right">
                            {{ $import->registration_number ?: '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-3">
                        <span class="text-gray-400">วันหมดอายุ</span>
                        <span class="text-gray-700 font-semibold text-right">
                            @if ($import->expired_license_date)
                                {{ \Carbon\Carbon::parse($import->expired_license_date)->addYears(543)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="grid grid-cols-2 gap-1.5 sm:gap-2 mt-3 sm:mt-4">
                    @can('Inregister read')
                        <a href="{{ route('import.show', $import->id) }}"
                            class="flex items-center justify-center gap-1 rounded-xl bg-green-500 px-2 py-2 sm:px-3 sm:py-2.5 text-sm font-bold text-white active:scale-95 transition">
                            ดู
                        </a>
                    @endcan

                    @can('import_data_staple read')
                        <button type="button"
                            onclick="openDocumentFilesModal('import-files-template-{{ $import->id }}', 'รายการไฟล์ทะเบียนนำเข้า')"
                            class="flex items-center justify-center gap-1 rounded-xl bg-blue-600 px-2 py-2 sm:px-3 sm:py-2.5 text-sm font-bold text-white active:scale-95 transition">
                            PDF
                        </button>
                    @endcan

                    @can('Inregister update')
                        <a href="{{ route('import.edit', $import->id) }}"
                            class="flex items-center justify-center gap-1 rounded-xl bg-yellow-500 px-2 py-2 sm:px-3 sm:py-2.5 text-sm font-bold text-white active:scale-95 transition">
                            แก้ไข
                        </a>
                    @endcan

                    @can('Inregister delete')
                        <button onclick="confirmDelete({{ $import->id }})"
                            class="flex items-center justify-center gap-1 rounded-xl bg-red-500 px-2 py-2 sm:px-3 sm:py-2.5 text-sm font-bold text-white active:scale-95 transition">
                            ลบ
                        </button>

                        <form id="delete-form-{{ $import->id }}"
                            action="{{ route('import.destroy', $import->id) }}"
                            method="POST"
                            style="display: none;">
                            @csrf
                            @method('delete')
                        </form>
                    @endcan
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-8 text-center">
                <p class="text-gray-400 font-medium">ไม่มีข้อมูลทะเบียนนำเข้า</p>
            </div>
        @endforelse
      <div class="px-1 sm:px-6 lg:px-8 py-2 sm:py-6 bg-white border-t border-gray-100 rounded-b-2xl">
    {{ $imports->appends(request()->query())->onEachSide(1)->links() }}
</div>
    @endcan
</div>
        </div>
    </main>

    <div id="documentFileModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 px-4 py-6">
        <div class="bg-white max-w-5xl mx-auto h-full rounded-lg shadow-lg flex flex-col overflow-hidden">
            <div class="flex items-center justify-between gap-4 px-5 py-4 border-b">
                <h3 id="documentFileModalTitle" class="text-lg font-semibold text-gray-700 truncate">เอกสาร</h3>
                <button type="button" id="closeDocumentFileModal" class="text-gray-500 hover:text-gray-800 text-2xl leading-none">
                    &times;
                </button>
            </div>
            <div id="documentFileViewer" class="flex-1 bg-gray-100 overflow-auto p-4 flex flex-col items-center gap-4" oncontextmenu="return false;"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    @php
        $pdfWatermarkLogoPath = config('pdf.tiled_watermark.logo_path', 'images/logo.png');
        $pdfWatermarkLogoPublicRoot = str_replace('\\', '/', public_path());
        $pdfWatermarkLogoNormalizedPath = str_replace('\\', '/', $pdfWatermarkLogoPath);

        if (filter_var($pdfWatermarkLogoPath, FILTER_VALIDATE_URL)) {
            $pdfWatermarkLogoUrl = $pdfWatermarkLogoPath;
        } elseif (strpos($pdfWatermarkLogoNormalizedPath, $pdfWatermarkLogoPublicRoot . '/') === 0) {
            $pdfWatermarkLogoUrl = rtrim(request()->getBaseUrl(), '/') . '/' . ltrim(substr($pdfWatermarkLogoNormalizedPath, strlen($pdfWatermarkLogoPublicRoot)), '/');
        } else {
            $pdfWatermarkLogoUrl = rtrim(request()->getBaseUrl(), '/') . '/' . ltrim(preg_replace('#^/?public/#', '', $pdfWatermarkLogoNormalizedPath), '/');
        }

        $pdfTiledWatermark = [
            'enabled' => (bool) config('pdf.tiled_watermark.enabled', true),
            'color' => (bool) config('pdf.tiled_watermark.color', true),
            'opacity' => (float) config('pdf.tiled_watermark.opacity', 0.08),
            'logoUrl' => $pdfWatermarkLogoUrl,
            'logoSize' => (int) config('pdf.tiled_watermark.logo_size', 120),
            'gapX' => (int) config('pdf.tiled_watermark.gap_x', 180),
            'gapY' => (int) config('pdf.tiled_watermark.gap_y', 160),
            'angle' => (float) config('pdf.tiled_watermark.angle', -30),
        ];
    @endphp
    <script>
        const pdfTiledWatermark = @json($pdfTiledWatermark);
        const documentFileModal = document.getElementById('documentFileModal');
        const documentFileViewer = document.getElementById('documentFileViewer');
        const documentFileModalTitle = document.getElementById('documentFileModalTitle');
        const closeDocumentFileModal = document.getElementById('closeDocumentFileModal');
        let documentRenderToken = 0;
        let activePdf = null;
        let activePdfUrl = '';
        let activePdfPage = 1;
        let activePdfScale = 1.25;
        let activePdfViewer = null;
        let activePdfRenderTask = null;
        let pdfWatermarkImagePromise = null;
        let pdfWatermarkGrayscaleImagePromise = null;

        if (window.pdfjsLib) {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        }

        function openDocumentFilesModal(templateId, title) {
            const template = document.getElementById(templateId);
            const listHtml = template ? template.innerHTML : '<p class="text-gray-400">ไม่มีไฟล์เอกสาร</p>';
            const html = `
                <div class="document-popup-grid">
                    <div class="max-h-[70vh] overflow-auto rounded-lg border border-gray-200 bg-white p-1 sm:p-3">
                        ${listHtml}
                    </div>
                    <div id="swalDocumentPane" class="flex min-h-[18rem] sm:min-h-[24rem] flex-col overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                        <div id="swalDocumentTitle" class="border-b border-gray-200 bg-white px-2 py-1.5 sm:px-4 sm:py-3 text-sm font-semibold text-gray-700">
                            เลือกไฟล์เอกสาร
                        </div>
                        <div class="document-pdf-toolbar" oncontextmenu="return false;">
                            <button type="button" id="swalPdfPrev" title="หน้าก่อนหน้า" disabled>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
                            </button>
                            <span id="swalPdfPageInfo" class="min-w-[4.5rem] text-center text-sm font-bold">0 / 0</span>
                            <button type="button" id="swalPdfNext" title="หน้าถัดไป" disabled>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
                            </button>
                            <button type="button" id="swalPdfZoomOut" title="ย่อ" disabled>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M8 11h6"></path><path d="m21 21-4.3-4.3"></path></svg>
                            </button>
                            <span id="swalPdfZoomLabel" class="min-w-[4rem] text-center text-sm font-bold">125%</span>
                            <button type="button" id="swalPdfZoomIn" title="ขยาย" disabled>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M8 11h6"></path><path d="M11 8v6"></path><path d="m21 21-4.3-4.3"></path></svg>
                            </button>
                            <button type="button" id="swalPdfFullscreen" title="เต็มจอ" disabled>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 3H5a2 2 0 0 0-2 2v3"></path><path d="M16 3h3a2 2 0 0 1 2 2v3"></path><path d="M8 21H5a2 2 0 0 1-2-2v-3"></path><path d="M16 21h3a2 2 0 0 0 2-2v-3"></path></svg>
                            </button>
                        </div>
                        <div id="swalDocumentViewer" class="document-pdf-stage">
                            <p class="py-10 text-center text-sm text-gray-400">เลือกไฟล์จากรายการด้านซ้าย</p>
                        </div>
                    </div>
                </div>
            `;

            Swal.fire({
                title,
                html,
                width: '72rem',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'custom-close-popup',
                    htmlContainer: 'text-left'
                },
                didOpen: () => {
                    const firstFileButton = document.querySelector('.swal2-container .js-open-document-file');
                    firstFileButton?.click();
                },
                willClose: () => {
                    resetPdfState();
                }
            });
        }

        function closeDocumentViewerModal() {
            resetPdfState();
            documentFileModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            documentFileViewer.innerHTML = '';
        }

        function renderDocumentFrame(url, viewer = documentFileViewer) {
            viewer.innerHTML = `
                <iframe
                    src="${url}"
                    class="h-full min-h-[65vh] w-full rounded bg-white"
                    title="เอกสาร PDF"
                    oncontextmenu="return false;">
                </iframe>
            `;
        }

        function showDocumentNotFound(viewer = documentFileViewer) {
            resetPdfState();
            viewer.innerHTML = `
                <div class="m-auto max-w-md rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-center text-red-700">
                    <p class="font-semibold">ไม่พบเอกสาร</p>
                    <p class="mt-1 text-sm">ไฟล์นี้อาจถูกลบ ย้ายตำแหน่ง หรือยังไม่ได้อัปโหลด</p>
                </div>
            `;
        }

        async function documentExists(url) {
            try {
                const response = await fetch(url.split('#')[0], {
                    method: 'HEAD',
                    credentials: 'same-origin',
                    cache: 'no-store'
                });

                const contentType = response.headers.get('content-type') || '';
                return response.ok && contentType.toLowerCase().includes('application/pdf');
            } catch (error) {
                return false;
            }
        }

        async function renderDocumentPdf(url, viewer = documentFileViewer) {
            const token = ++documentRenderToken;
            viewer.innerHTML = '<p class="text-gray-500 py-8">กำลังโหลดเอกสาร...</p>';

            if (!window.pdfjsLib) {
                viewer.innerHTML = '<p class="text-red-500 py-8">ไม่สามารถโหลดตัวอ่าน PDF ได้</p>';
                return;
            }

            try {
                const pdf = await pdfjsLib.getDocument(url).promise;
                if (token !== documentRenderToken) return;

                viewer.innerHTML = '';
                for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    const page = await pdf.getPage(pageNumber);
                    if (token !== documentRenderToken) return;

                    const viewport = page.getViewport({ scale: 1.4 });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    canvas.className = 'max-w-full bg-white shadow-md';
                    viewer.appendChild(canvas);

                    await page.render({ canvasContext: context, viewport }).promise;
                    if (token !== documentRenderToken) return;
                    await drawTiledPdfWatermark(context, canvas);
                }
            } catch (error) {
                if (token === documentRenderToken) {
                    viewer.innerHTML = '<p class="text-red-500 py-8">ไม่สามารถแสดงเอกสารนี้ได้</p>';
                }
            }
        }

        function resetPdfState() {
            documentRenderToken++;
            activePdf = null;
            activePdfUrl = '';
            activePdfPage = 1;
            activePdfScale = 1.25;
            activePdfViewer = null;

            if (activePdfRenderTask) {
                activePdfRenderTask.cancel();
                activePdfRenderTask = null;
            }

            updatePdfToolbar();
        }

        function updatePdfToolbar() {
            const pageInfo = document.getElementById('swalPdfPageInfo');
            const zoomLabel = document.getElementById('swalPdfZoomLabel');
            const prevButton = document.getElementById('swalPdfPrev');
            const nextButton = document.getElementById('swalPdfNext');
            const zoomOutButton = document.getElementById('swalPdfZoomOut');
            const zoomInButton = document.getElementById('swalPdfZoomIn');
            const printButton = document.getElementById('swalPdfPrint');
            const fullscreenButton = document.getElementById('swalPdfFullscreen');
            const hasPdf = Boolean(activePdf);

            if (pageInfo) {
                pageInfo.textContent = hasPdf ? `${activePdfPage} / ${activePdf.numPages}` : '0 / 0';
            }

            if (zoomLabel) {
                zoomLabel.textContent = `${Math.round(activePdfScale * 100)}%`;
            }

            if (prevButton) prevButton.disabled = !hasPdf || activePdfPage <= 1;
            if (nextButton) nextButton.disabled = !hasPdf || activePdfPage >= activePdf.numPages;
            if (zoomOutButton) zoomOutButton.disabled = !hasPdf || activePdfScale <= 0.5;
            if (zoomInButton) zoomInButton.disabled = !hasPdf || activePdfScale >= 3;
            if (printButton) printButton.disabled = !hasPdf;
            if (fullscreenButton) fullscreenButton.disabled = !hasPdf;
        }

        function getPdfWatermarkImage() {
            if (!pdfTiledWatermark.enabled || !pdfTiledWatermark.logoUrl) {
                return Promise.resolve(null);
            }

            if (!pdfWatermarkImagePromise) {
                pdfWatermarkImagePromise = new Promise(resolve => {
                    const image = new Image();
                    image.onload = () => resolve(image);
                    image.onerror = () => resolve(null);
                    image.src = pdfTiledWatermark.logoUrl;
                });
            }

            return pdfWatermarkImagePromise;
        }

        async function getPdfWatermarkDrawable() {
            const image = await getPdfWatermarkImage();
            if (!image || pdfTiledWatermark.color) {
                return image;
            }

            if (!pdfWatermarkGrayscaleImagePromise) {
                pdfWatermarkGrayscaleImagePromise = new Promise(resolve => {
                    const width = image.naturalWidth || image.width;
                    const height = image.naturalHeight || image.height;
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    canvas.width = width;
                    canvas.height = height;
                    context.drawImage(image, 0, 0, width, height);

                    const imageData = context.getImageData(0, 0, width, height);
                    const data = imageData.data;

                    for (let i = 0; i < data.length; i += 4) {
                        const gray = Math.round(data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114);
                        data[i] = gray;
                        data[i + 1] = gray;
                        data[i + 2] = gray;
                    }

                    context.putImageData(imageData, 0, 0);
                    resolve(canvas);
                });
            }

            return pdfWatermarkGrayscaleImagePromise;
        }

        async function drawTiledPdfWatermark(context, canvas, outputScale = 1) {
            const watermarkImage = await getPdfWatermarkDrawable();
            if (!watermarkImage) return;

            const maxLogoSize = Math.max(24, Number(pdfTiledWatermark.logoSize) || 120) * outputScale;
            const imageWidth = watermarkImage.naturalWidth || watermarkImage.width || maxLogoSize;
            const imageHeight = watermarkImage.naturalHeight || watermarkImage.height || maxLogoSize;
            const logoRatio = imageWidth / imageHeight;
            const logoWidth = logoRatio >= 1 ? maxLogoSize : maxLogoSize * logoRatio;
            const logoHeight = logoRatio >= 1 ? maxLogoSize / logoRatio : maxLogoSize;
            const gapX = Math.max(0, Number(pdfTiledWatermark.gapX) || 180) * outputScale;
            const gapY = Math.max(0, Number(pdfTiledWatermark.gapY) || 160) * outputScale;
            const tileWidth = logoWidth + gapX;
            const tileHeight = logoHeight + gapY;
            const opacity = Math.min(1, Math.max(0.01, Number(pdfTiledWatermark.opacity) || 0.08));
            const angle = (Number(pdfTiledWatermark.angle) || 0) * Math.PI / 180;

            context.save();
            context.setTransform(1, 0, 0, 1, 0, 0);
            context.globalAlpha = opacity;

            for (let y = -tileHeight; y < canvas.height + tileHeight; y += tileHeight) {
                for (let x = -tileWidth; x < canvas.width + tileWidth; x += tileWidth) {
                    context.save();
                    context.translate(x + logoWidth / 2, y + logoHeight / 2);
                    context.rotate(angle);
                    context.drawImage(watermarkImage, -logoWidth / 2, -logoHeight / 2, logoWidth, logoHeight);
                    context.restore();
                }
            }

            context.restore();
        }

        async function renderActivePdfPage() {
            if (!activePdf || !activePdfViewer) return;

            const token = ++documentRenderToken;
            activePdfViewer.innerHTML = '<p class="m-auto text-gray-500 py-8">กำลังโหลดเอกสาร...</p>';

            try {
                if (activePdfRenderTask) {
                    activePdfRenderTask.cancel();
                    activePdfRenderTask = null;
                }

                const page = await activePdf.getPage(activePdfPage);
                if (token !== documentRenderToken) return;

                const viewport = page.getViewport({ scale: activePdfScale });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                const outputScale = window.devicePixelRatio || 1;

                canvas.width = Math.floor(viewport.width * outputScale);
                canvas.height = Math.floor(viewport.height * outputScale);
                canvas.style.width = `${Math.floor(viewport.width)}px`;
                canvas.style.height = `${Math.floor(viewport.height)}px`;
                context.setTransform(outputScale, 0, 0, outputScale, 0, 0);

                activePdfViewer.innerHTML = '';
                activePdfViewer.appendChild(canvas);
                activePdfRenderTask = page.render({ canvasContext: context, viewport });
                await activePdfRenderTask.promise;
                activePdfRenderTask = null;
                if (token !== documentRenderToken) return;
                await drawTiledPdfWatermark(context, canvas, outputScale);
                updatePdfToolbar();
            } catch (error) {
                if (error?.name === 'RenderingCancelledException') return;
                if (token === documentRenderToken) {
                    activePdfViewer.innerHTML = '<p class="m-auto text-red-500 py-8">ไม่สามารถแสดงเอกสารนี้ได้</p>';
                }
            }
        }

        async function loadDocumentPdf(url, viewer = documentFileViewer) {
            resetPdfState();
            const token = ++documentRenderToken;
            viewer.innerHTML = '<p class="m-auto text-gray-500 py-8">กำลังโหลดเอกสาร...</p>';

            if (!window.pdfjsLib) {
                viewer.innerHTML = '<p class="m-auto text-red-500 py-8">ไม่สามารถโหลดตัวอ่าน PDF ได้</p>';
                return;
            }

            try {
                const pdf = await pdfjsLib.getDocument(url.split('#')[0]).promise;
                if (token !== documentRenderToken) return;

                activePdf = pdf;
                activePdfUrl = url.split('#')[0];
                activePdfPage = 1;
                activePdfScale = 1.25;
                activePdfViewer = viewer;
                updatePdfToolbar();
                await renderActivePdfPage();
            } catch (error) {
                if (token === documentRenderToken) {
                    viewer.innerHTML = '<p class="m-auto text-red-500 py-8">ไม่สามารถแสดงเอกสารนี้ได้</p>';
                }
            }
        }

        function printActivePdf() {
            if (!activePdfUrl) return;

            const iframe = document.createElement('iframe');
            iframe.style.bottom = '0';
            iframe.style.height = '0';
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.width = '0';
            iframe.src = activePdfUrl;
            document.body.appendChild(iframe);
            iframe.onload = () => {
                iframe.contentWindow?.focus();
                iframe.contentWindow?.print();
                setTimeout(() => iframe.remove(), 1000);
            };
        }

        function toggleDocumentFullscreen() {
            const pane = document.getElementById('swalDocumentPane') || activePdfViewer;
            if (!pane) return;

            if (document.fullscreenElement) {
                document.exitFullscreen?.();
                return;
            }

            pane.requestFullscreen?.();
        }

        async function openDocumentViewer(url, fileName) {
            const inlineViewer = document.getElementById('swalDocumentViewer');
            const inlineTitle = document.getElementById('swalDocumentTitle');
            const viewer = inlineViewer || documentFileViewer;

            if (inlineViewer) {
                inlineTitle.textContent = fileName || 'เอกสาร';
                await loadDocumentPdf(url, inlineViewer);
                return;
            }

            Swal.close();
            documentFileModalTitle.textContent = fileName || 'เอกสาร';
            documentFileModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            await loadDocumentPdf(url);
            return;

            if (inlineViewer) {
                inlineTitle.textContent = fileName || 'เอกสาร';
                inlineViewer.innerHTML = '<p class="text-gray-500 py-8">กำลังตรวจสอบเอกสาร...</p>';
                if (await documentExists(url)) {
                    await loadDocumentPdf(url, inlineViewer);
                } else {
                    showDocumentNotFound(inlineViewer);
                }
                return;
            }

            Swal.close();
            documentFileModalTitle.textContent = fileName || 'เอกสาร';
            documentFileModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            viewer.innerHTML = '<p class="text-gray-500 py-8">กำลังตรวจสอบเอกสาร...</p>';
            if (await documentExists(url)) {
                await loadDocumentPdf(url);
            } else {
                showDocumentNotFound();
            }
        }

        document.addEventListener('click', event => {
            if (event.target.closest('#swalPdfPrev')) {
                if (activePdf && activePdfPage > 1) {
                    activePdfPage--;
                    updatePdfToolbar();
                    renderActivePdfPage();
                }
                return;
            }

            if (event.target.closest('#swalPdfNext')) {
                if (activePdf && activePdfPage < activePdf.numPages) {
                    activePdfPage++;
                    updatePdfToolbar();
                    renderActivePdfPage();
                }
                return;
            }

            if (event.target.closest('#swalPdfZoomOut')) {
                if (activePdf && activePdfScale > 0.5) {
                    activePdfScale = Math.max(0.5, activePdfScale - 0.25);
                    updatePdfToolbar();
                    renderActivePdfPage();
                }
                return;
            }

            if (event.target.closest('#swalPdfZoomIn')) {
                if (activePdf && activePdfScale < 3) {
                    activePdfScale = Math.min(3, activePdfScale + 0.25);
                    updatePdfToolbar();
                    renderActivePdfPage();
                }
                return;
            }

            if (event.target.closest('#swalPdfPrint')) {
                printActivePdf();
                return;
            }

            if (event.target.closest('#swalPdfFullscreen')) {
                toggleDocumentFullscreen();
                return;
            }

            const button = event.target.closest('.js-open-document-file');
            if (!button) return;

            openDocumentViewer(button.dataset.fileUrl, button.dataset.fileName);
        });

        closeDocumentFileModal?.addEventListener('click', closeDocumentViewerModal);
        documentFileModal?.addEventListener('click', event => {
            if (event.target === documentFileModal) {
                closeDocumentViewerModal();
            }
        });
        document.addEventListener('keydown', event => {
            if (!documentFileModal.classList.contains('hidden') && (event.ctrlKey || event.metaKey) && ['p', 's'].includes(event.key.toLowerCase())) {
                event.preventDefault();
            }

            if (event.key === 'Escape' && !documentFileModal.classList.contains('hidden')) {
                closeDocumentViewerModal();
            }
        });

        function confirmDelete(roleId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่',
                text: "คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${roleId}`).submit();
                }
            });
        }
        document.getElementById('menu-inregister')?.classList.add('side-menu--active');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Helper: แปลง yyyy-mm-dd -> dd/mm/yyyy (พ.ศ.) เพื่อแสดงผลเริ่มต้น ---
            function adToBeDisplay(isoStr) {
                // รับค่าเป็น 'YYYY-MM-DD' -> คืน 'dd/mm/yyyy(พ.ศ.)'
                if (!isoStr || !/^\d{4}-\d{2}-\d{2}$/.test(isoStr)) return null;
                const [y, m, d] = isoStr.split('-').map(n => parseInt(n, 10));
                if (!y || !m || !d) return null;
                const be = y + 543;
                return String(d).padStart(2, '0') + '/' + String(m).padStart(2, '0') + '/' + be;
            }

            // --- ถ้าค่าที่มากับ request เป็น ค.ศ. iso -> เปลี่ยนเป็น พ.ศ. แสดงผล ---
            ['expiry_date_from', 'expiry_date_to'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const v = (el.value || '').trim();

                // case: yyyy-mm-dd จาก query string เดิม
                const beDisplay = adToBeDisplay(v);
                if (beDisplay) {
                    el.value = beDisplay;
                } else if (/^\d{4}\/\d{2}\/\d{2}$/.test(v)) {
                    // ถ้าติดมาผิดรูปแบบ (yyyy/mm/dd) ก็ปล่อยไว้ หรือปรับเพิ่มตามต้องการ
                }
                // ถ้าเป็น dd/mm/yyyy อยู่แล้วก็ไม่เปลี่ยน
            });

            // --- ติดตั้ง flatpickr แบบหน้าแก้ไข ---
            flatpickr(".date-th", {
                allowInput: true,
                locale: "th",
                dateFormat: "d/m/Y", // ใช้ dd/mm/yyyy
                parseDate: (datestr, format) => {
                    if (!datestr) return null;
                    // รองรับ dd/mm/yyyy ทั้ง ค.ศ./พ.ศ.
                    const m = datestr.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
                    if (m) {
                        let dd = parseInt(m[1], 10);
                        let mm = parseInt(m[2], 10);
                        let yyyy = parseInt(m[3], 10);
                        if (yyyy > 2400) yyyy -= 543; // ถ้าเป็น พ.ศ. -> แปลง ค.ศ.
                        return new Date(yyyy, mm - 1, dd);
                    }
                    // เผื่อกรณีผู้ใช้วางเป็น 'yyyy-mm-dd'
                    const n = datestr.match(/^(\d{4})-(\d{2})-(\d{2})$/);
                    if (n) {
                        return new Date(parseInt(n[1], 10), parseInt(n[2], 10) - 1, parseInt(n[3], 10));
                    }
                    return flatpickr.parseDate(datestr, format);
                },
                onReady: (selectedDates, dateStr, instance) => showBE(instance),
                onChange: (selectedDates, dateStr, instance) => showBE(instance),
                onOpen: (selectedDates, dateStr, instance) => showBE(instance)
            });

            function showBE(instance) {
                const sel = instance.selectedDates[0];
                if (!sel) return;
                const dd = String(sel.getDate()).padStart(2, "0");
                const mm = String(sel.getMonth() + 1).padStart(2, "0");
                const yyyyBE = sel.getFullYear() + 543;
                instance.input.value = `${dd}/${mm}/${yyyyBE}`;
            }

            // --- ก่อน submit: แปลง dd/mm/yyyy(พ.ศ.) -> yyyy-mm-dd(ค.ศ.) ---
            const form = document.getElementById("filterForm");
            if (form) {
                form.addEventListener("submit", () => {
                    // Disable empty search field so it won't be submitted
                    const searchInput = form.querySelector('input[name="search"]');
                    if (searchInput) {
                        const val = (searchInput.value || '').trim();
                        if (val === '') {
                            searchInput.disabled = true;
                        } else {
                            searchInput.value = val; // trim surrounding whitespace
                        }
                    }

                    document.querySelectorAll("#filterForm .date-th").forEach(input => {
                        const v = (input.value || '').trim();
                        const m = v.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
                        if (m) {
                            let dd = m[1],
                                mm = m[2],
                                y = parseInt(m[3], 10);
                            if (y > 2400) y -= 543; // พ.ศ. -> ค.ศ.
                            input.value = `${y}-${mm}-${dd}`; // ส่งรูปแบบที่ backend/Query ถนัด
                        }
                    });
                });
            }
        });
    </script>


</x-app-layout>
