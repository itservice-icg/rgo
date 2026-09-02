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

    .custom-close-popup>.swal2-close {
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
        <div class="container px-1 py-2 mx-auto sm:px-6 sm:py-6">
            <h1 class="mt-1 mb-3 text-2xl font-extrabold tracking-wide text-center text-indigo-700 sm:text-4xl sm:mt-5 sm:mb-10">
                <span class="inline-flex items-center justify-center gap-2">
                    <svg class="w-8 h-8 text-indigo-400 sm:w-10 sm:h-10" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 1.343-3 3v1c0 1.657 1.343 3 3 3s3-1.343 3-3v-1c0-1.657-1.343-3-3-3z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 2v2m0 16v2m8-10h2M2 12H4m15.364-7.364l1.414 1.414M4.222 19.778l1.414-1.414m12.728 0l1.414 1.414M4.222 4.222l1.414 1.414" />
                    </svg>
                    ข้อมูลทะเบียนผลิต
                </span>
            </h1>

            {{-- สรุปสถานะทะเบียน --}}
            <div class="gap-1 mb-2 summary-status-grid sm:gap-3 lg:gap-4 sm:mb-4 lg:mb-6">
                {{-- ทั้งหมด --}}
                <a href="{{ route('createproduct.index', array_merge(request()->except('status_filter', 'page'), ['page' => 1])) }}"
                    class="rounded-2xl border border-blue-200 bg-blue-50 px-1 py-2 sm:px-2 sm:py-3 lg:px-4 lg:py-4 text-center active:scale-95 transition {{ !request('status_filter') ? 'ring-2 ring-blue-300 bg-blue-100' : '' }}">
                    <div class="flex justify-center mb-1">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-200 rounded-full" style="width: 60px; height: 60px;">
                            {{-- ไอคอนใหม่ --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 50px!important; height: 50px!important;"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-700">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-semibold leading-tight text-blue-700">ทั้งหมด</h2>
                    <p class="mt-0.5 text-4xl font-extrabold leading-none text-blue-700">{{ $total ?? 0 }}</p>
                </a>

                {{-- ใกล้หมดอายุ --}}
                <a href="{{ route('createproduct.index', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'soon_expired', 'page' => 1])) }}"
                    class="rounded-2xl border border-yellow-200 bg-yellow-50 px-1 py-2 sm:px-2 sm:py-3 lg:px-4 lg:py-4 text-center active:scale-95 transition {{ request('status_filter') == 'soon_expired' ? 'ring-2 ring-yellow-300 bg-yellow-100' : '' }}">
                    <div class="flex justify-center mb-1">
                        <div class="flex items-center justify-center w-8 h-8 bg-yellow-200 rounded-full" style="width: 60px; height: 60px;">
                            <svg class="w-4 h-4 text-yellow-700" fill="none" stroke="currentColor" stroke-width="2" style="width: 50px!important; height: 50px!important;"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                <circle cx="12" cy="12" r="10" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-semibold leading-tight text-yellow-700">ใกล้หมด</h2>
                    <p class="mt-0.5 text-4xl font-extrabold leading-none text-yellow-700">{{ $soonCount ?? 0 }}</p>
                </a>

                {{-- หมดอายุ --}}
                <a href="{{ route('createproduct.index', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'expired', 'page' => 1])) }}"
                    class="rounded-2xl border border-red-200 bg-red-50 px-1 py-2 sm:px-2 sm:py-3 lg:px-4 lg:py-4 text-center active:scale-95 transition {{ request('status_filter') == 'expired' ? 'ring-2 ring-red-300 bg-red-100' : '' }}">
                    <div class="flex justify-center mb-1">
                        <div class="flex items-center justify-center w-8 h-8 bg-red-200 rounded-full" style="width: 60px; height: 60px;">
                            <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" stroke-width="2" style="width: 50px!important; height: 50px!important;"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                <circle cx="12" cy="12" r="10" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-semibold leading-tight text-red-700">หมดอายุ</h2>
                    <p class="mt-0.5 text-4xl font-extrabold leading-none text-red-700">{{ $expiredCount ?? 0 }}</p>
                </a>
            </div>

            <div class="flex flex-col items-center justify-between hidden mx-3 mb-2 lg:block sm:flex-row">
                <form id="filterForm" action="{{ route('createproduct.index') }}" method="GET"
                    data-filter-form class="flex items-center gap-2 mb-2">
                    <div class="relative flex-grow min-w-[280px]">
                        <label for="search_query" class="block mx-3 mt-3 mb-1 text-base text-gray-700">ค้นหา</label>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none mt-9">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input type="text" id="search_query" name="search"
                            placeholder="ชื่อวัตถุอันตราย / เลขที่ทะเบียน / ผู้ขึ้นทะเบียน"
                            value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 w-[500px] rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600 transition duration-200 ease-in-out text-gray-700 shadow-sm"
                            style="width:100%" />
                    </div>

                    <div class="relative flex-grow min-w-[260px]">
                        <label for="trade_name_search" class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อการค้า</label>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none mt-9">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input type="text" id="trade_name_search" name="trade_name_search"
                            placeholder="ค้นหาชื่อการค้า"
                            value="{{ request('trade_name_search') }}"
                            class="pl-10 pr-4 py-2 w-[500px] rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition duration-200 ease-in-out text-gray-700 shadow-sm"
                            style="width:100%" />
                    </div>
                    {{-- วันที่เริ่ม --}}
                    <div class="flex-grow min-w-[180px]">
                        <label for="expiry_date_from"
                            class="block mx-3 mt-3 mb-1 text-base text-gray-700">วันที่เริ่ม</label>
                        <input id="expiry_date_from"
                            class="w-full px-4 py-2 text-base text-gray-500 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm date-th focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                            type="text" name="expiry_date_from" value="{{ request('expiry_date_from') }}"
                            placeholder="วว/ดด/ปปปป" autocomplete="off" autocorrect="off" autocapitalize="off"
                            spellcheck="false" />
                    </div>

                    {{-- วันที่สิ้นสุด --}}
                    <div class="flex-grow min-w-[180px]">
                        <label for="expiry_date_to"
                            class="block mx-3 mt-3 mb-1 text-base text-gray-700">วันที่สิ้นสุด</label>
                        <input id="expiry_date_to"
                            class="w-full px-4 py-2 text-base text-gray-500 transition duration-200 ease-in-out border border-gray-300 rounded-lg shadow-sm date-th focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                            type="text" name="expiry_date_to" value="{{ request('expiry_date_to') }}"
                            placeholder="วว/ดด/ปปปป" autocomplete="off" autocorrect="off" autocapitalize="off"
                            spellcheck="false" />
                    </div>
                    <div class="flex gap-3 mt-10">
                        <button type="submit"
                            class="px-6 py-2 font-semibold text-white transition duration-200 ease-in-out transform rounded-lg shadow-md bg-gradient-to-r from-blue-500 to-blue-500 hover:from-blue-600 hover:to-indigo-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                            <svg class="w-5 h-5 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            ค้นหา
                        </button>
                        @if (request('search') || request('trade_name_search') || request('expiry_date_from') || request('expiry_date_to') || request('status_filter'))
                            <a href="{{ route('createproduct.index') }}" {{-- class="inline-flex items-center px-6 py-2 font-semibold text-gray-800 transition duration-200 ease-in-out transform bg-gray-300 rounded-lg shadow-md hover:bg-gray-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50"> --}}
                                class="inline-flex items-center px-6 py-2 font-semibold text-gray-800 transition duration-200 ease-in-out transform bg-gray-300 rounded-lg shadow-md hover:bg-gray-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">

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
                    <a href="{{ route('createproduct.create') }}"
                        class="px-6 py-2 mt-8 font-semibold text-white transition duration-200 ease-in-out transform rounded-lg shadow-md bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                        + เพิ่มข้อมูล
                    </a>
                @endcan --}}
            </div>

            <div class="lg:hidden">
                <form action="{{ route('createproduct.index') }}" method="GET" data-filter-form
                    class="p-2 bg-white border border-gray-100 shadow-sm rounded-2xl sm:p-4">
                    <div class="grid grid-cols-1 gap-2 sm:gap-3">
                        <div class="min-w-0">
                            <label for="mobile_search_query" class="block mb-1 text-sm font-semibold text-gray-700">ค้นหา</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </div>
                                <input type="text" id="mobile_search_query" name="search"
                                    placeholder="ชื่อวัตถุอันตราย / เลขที่ทะเบียน / ผู้ขึ้นทะเบียน"
                                    value="{{ request('search') }}"
                                    class="w-full py-2 pl-10 pr-3 text-gray-700 border border-gray-300 shadow-sm sm:pr-4 sm:py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                        </div>

                        <div class="min-w-0">
                            <label for="mobile_trade_name_search" class="block mb-1 text-sm font-semibold text-gray-700">ชื่อการค้า</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </div>
                                <input type="text" id="mobile_trade_name_search" name="trade_name_search"
                                    placeholder="ค้นหาชื่อการค้า"
                                    value="{{ request('trade_name_search') }}"
                                    class="w-full py-2 pl-10 pr-3 text-gray-700 border border-gray-300 shadow-sm sm:pr-4 sm:py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                        </div>

                        <div class="min-w-0">
                            <label for="mobile_expiry_date_from" class="block mb-1 text-sm font-semibold text-gray-700">วันที่เริ่ม</label>
                            <input id="mobile_expiry_date_from"
                                class="w-full px-3 py-2 text-gray-700 border border-gray-300 shadow-sm date-th sm:px-4 sm:py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                type="text" name="expiry_date_from" value="{{ request('expiry_date_from') }}"
                                placeholder="วว/ดด/ปปปป" autocomplete="off" autocorrect="off" autocapitalize="off"
                                spellcheck="false" />
                        </div>

                        <div class="min-w-0">
                            <label for="mobile_expiry_date_to" class="block mb-1 text-sm font-semibold text-gray-700">วันที่สิ้นสุด</label>
                            <input id="mobile_expiry_date_to"
                                class="w-full px-3 py-2 text-gray-700 border border-gray-300 shadow-sm date-th sm:px-4 sm:py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                type="text" name="expiry_date_to" value="{{ request('expiry_date_to') }}"
                                placeholder="วว/ดด/ปปปป" autocomplete="off" autocorrect="off" autocapitalize="off"
                                spellcheck="false" />
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center w-full gap-2 px-3 py-2 font-semibold text-white transition bg-blue-600 shadow-sm hover:bg-blue-700 sm:px-5 sm:py-3 rounded-xl active:scale-95">
                                ค้นหา
                            </button>
                            @if (request('search') || request('trade_name_search') || request('expiry_date_from') || request('expiry_date_to') || request('status_filter'))
                                <a href="{{ route('createproduct.index') }}"
                                    class="inline-flex items-center justify-center w-full gap-2 px-3 py-2 font-semibold text-gray-700 transition bg-gray-100 border border-gray-200 shadow-sm hover:bg-gray-200 sm:px-5 sm:py-3 rounded-xl active:scale-95">
                                    ล้าง
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="hidden overflow-hidden bg-white border border-gray-200 lg:block rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-[1320px] table-fixed bg-white">
                        <colgroup>
                            <col class="w-20">
                            <col class="w-64">
                            <col class="w-64">
                            <col class="w-56">
                            <col class="w-56">
                            <col class="w-40">
                            <col class="w-36">
                            <col class="w-36">
                            <col class="w-56">
                        </colgroup>
                        <thead>
                            <tr class="text-left text-white bg-indigo-600">
                                <th class="px-4 py-4 text-center rounded-tl-2xl">ลำดับ</th>
                                {{-- <th class="px-6 py-4">ชื่อการค้า</th> --}}
                                <th class="px-4 py-4">ชื่อวัตถุอันตราย (ไทย)</th>
                                <th class="px-4 py-4">ชื่อวัตถุอันตราย (อังกฤษ) </th>
                                {{-- <th class="px-6 py-4">ชื่อวัตถุอันตราย (อังกฤษ)</th> --}}
                                <th class="px-4 py-4">ผู้ขึ้นทะเบียน</th>
                                <th class="px-4 py-4">ผู้จำหน่าย</th>
                                {{-- <th class="px-6 py-4 text-center">ตัวย่อ</th> --}}
                                <th class="px-4 py-4 text-center">เลขที่ทะเบียน</th>
                                <th class="px-4 py-4 text-center">วันหมดอายุ</th>
                                <th class="px-4 py-4 text-center">สถานะ</th>
                                <th class="px-4 py-4 text-center rounded-tr-2xl">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('Inregister read')
                                @forelse ($imports as $index => $import)
                                    <tr class="transition border-b hover:bg-indigo-50">
                                        <td class="px-4 py-4 font-semibold text-center text-gray-700">
                                            {{ $loop->iteration + ($imports->currentPage() - 1) * $imports->perPage() }}
                                        </td>
                                        {{-- <td class="px-6 py-4">{{ $import->trade_name ?? '' }}</td> --}}
                                        <td class="px-6 py-4 break-words">{{ $import->chemical_name_th ?? '' }}</td>
                                        <td class="px-6 py-4 break-words">{{ $import->chemical_name_en ?? '' }}</td>
                                        {{-- <td class="px-6 py-4 text-center">{{ $import->Companes->full_name ?? '' }}</td> --}}
                                        <td class="px-4 py-4 break-words">{{ $import->company->full_name ?? '' }}</td>
                                        <td class="px-4 py-4 break-words">{{ $import->distributorCompany->full_name ?? '' }}</td>
                                        <td class="px-4 py-4 text-center break-words">{{ $import->registration_number ?? '' }}</td>
                                        <td class="px-4 py-4 text-center whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($import->expired_license_date)->addYears(543)->format('d/m/Y') }}
                                        </td>
                                        @php
                                            $productionFiles = $import->files ?? collect();
                                            $approvalDocumentTypeCode = 'prod_license';
                                            $approvalFiles = $productionFiles->where('document_type_code', $approvalDocumentTypeCode);
                                            $registrationFiles = $productionFiles->reject(function ($file) use ($approvalDocumentTypeCode) {
                                                return $file->document_type_code === $approvalDocumentTypeCode;
                                            });
                                            $legacyDocumentExists = $productionFiles->isEmpty() && ($import->additional_document || $import->document);
                                        @endphp
                                        <td class="px-4 py-4 text-center">
                                            @php
                                                $statusClass = '';
                                                $statusText = $import->status;

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
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                @can('Inregister read')
                                                    <a href="{{ route('createproduct.show', $import->id) }}"
                                                        class="inline-flex items-center justify-center p-2 text-white transition-all duration-200 bg-green-500 rounded-full hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
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
                                                @can('import_data_manufacture read')
                                                    <button type="button"
                                                        onclick="openDocumentFilesModal('production-files-template-{{ $import->id }}', 'รายการไฟล์ทะเบียนผลิต')"
                                                        class="inline-flex items-center justify-center p-2 text-white transition-all duration-200 bg-blue-600 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                        title="ดู PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            aria-hidden="true">
                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                            <path d="M14 2v6h6"></path>
                                                            <path d="M8 13h8"></path>
                                                            <path d="M8 17h5"></path>
                                                        </svg>
                                                    </button>
                                                    <template id="production-files-template-{{ $import->id }}">
                                                        <div class="space-y-4 text-left">
                                                                <div>
                                                                    <h4 class="mb-2 text-sm font-bold text-gray-700" style="padding: 8px; color:#fff; background-color: rgba(79, 70, 229, var(--tw-bg-opacity));  border-radius:6px 6px 0 0;">ไฟล์ทะเบียนผลิต</h4>
                                                            @if ($registrationFiles->isNotEmpty())
                                                                    <div class="space-y-2">
                                                                        @foreach ($registrationFiles as $file)
                                                                            <button type="button"
                                                                                data-file-url="{{ route('createproduct.file', [$import, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                                                data-file-name="{{ $file->original_name ?: basename($file->file_path) }}"
                                                                                class="flex items-center justify-between w-full gap-3 px-3 py-2 text-sm text-left text-gray-700 border border-gray-200 rounded-lg js-open-document-file hover:bg-blue-50">
                                                                                <span class="truncate">{{ $file->original_name ?: basename($file->file_path) }}</span>
                                                                                <span class="text-xs text-gray-400 shrink-0">{{ optional($file->created_at)->format('d/m/Y H:i') }}</span>
                                                                            </button>
                                                                        @endforeach
                                                                    </div>
                                                            @endif

                                                                </div>
                                                            @if ($registrationFiles->isEmpty())
                                                                <div>
                                                                    <p class="px-4 py-6 text-sm text-center text-gray-400 border border-gray-300 border-dashed rounded-lg" >
                                                                        ไม่มีไฟล์เอกสาร
                                                                    </p>
                                                                </div>
                                                            @endif
                                                                <div>
                                                                    <h4 class="mb-2 text-sm font-bold text-gray-700" style="padding: 8px; color:#fff; background-color: rgba(79, 70, 229, var(--tw-bg-opacity));  border-radius:6px 6px 0 0;">ไฟล์ใบอนุญาตผลิต</h4>
                                                            @if ($approvalFiles->isNotEmpty())
                                                                    <div class="space-y-2">
                                                                        @foreach ($approvalFiles as $file)
                                                                            <button type="button"
                                                                                data-file-url="{{ route('createproduct.file', [$import, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                                                data-file-name="{{ $file->original_name ?: basename($file->file_path) }}"
                                                                                class="flex items-center justify-between w-full gap-3 px-3 py-2 text-sm text-left text-gray-700 border border-gray-200 rounded-lg js-open-document-file hover:bg-blue-50">
                                                                                <span class="truncate">{{ $file->original_name ?: basename($file->file_path) }}</span>
                                                                                <span class="text-xs text-gray-400 shrink-0">{{ optional($file->created_at)->format('d/m/Y H:i') }}</span>
                                                                            </button>
                                                                        @endforeach
                                                                    </div>
                                                            @endif
                                                                </div>
                                                            @if ($approvalFiles->isEmpty())
                                                                <div>
                                                                    <p class="px-4 py-6 text-sm text-center text-gray-400 border border-gray-300 border-dashed rounded-lg">
                                                                        ไม่มีไฟล์เอกสาร
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            @if ($legacyDocumentExists)
                                                                <button type="button"
                                                                    data-file-url="{{ route('createproduct.additional-document', $import) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                                    data-file-name="ไฟล์เอกสารเดิม"
                                                                    class="flex items-center justify-between w-full gap-3 px-3 py-2 text-sm text-left text-gray-700 border border-gray-200 rounded-lg js-open-document-file hover:bg-blue-50">
                                                                    <span class="truncate">ไฟล์เอกสารเดิม</span>
                                                                    <span class="text-xs text-blue-600 shrink-0">เปิดไฟล์</span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </template>
                                                @endcan
                                                @can('Inregister update')
                                                    <a href="{{ route('createproduct.edit', $import->id) }}"
                                                        class="inline-flex items-center justify-center p-2 text-white transition-all duration-200 bg-yellow-500 rounded-full hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
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
                                                        class="inline-flex items-center justify-center p-2 text-white transition-all duration-200 bg-red-500 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                        title="ลบ">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.92a2.25 2.25 0 0 1-2.244-2.077L4.74 5.959m1.049-.165c.51-.158 1.029-.28 1.563-.35L12 4.75m-4.78 2.152A.75.75 0 0 1 9 6.75h6m-3 0V4.5m-2.25 4.5h.008v.008H9.75V9Zm0 0H9.75Zm4.5 0h.008v.008H14.25V9Z" />
                                                        </svg>
                                                    </button>
                                                    <form id="delete-form-{{ $import->id }}"
                                                        action="{{ route('createproduct.destroy', $import->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-8 py-6 text-center text-gray-400">
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
            <div class="space-y-2 lg:hidden sm:space-y-4">
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
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="min-w-0">
                                    <div class="mb-1 text-xs text-gray-400">
                                        #{{ $loop->iteration + ($imports->currentPage() - 1) * $imports->perPage() }}
                                    </div>
                                    <h3 class="text-base font-bold leading-snug text-gray-800 line-clamp-2">
                                        {{ $import->chemical_name_th ?: '-' }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 line-clamp-1">
                                        {{ $import->chemical_name_en ?: '-' }}
                                    </p>
                                </div>

                                @if ($import->expired_license_date)
                                    <span class="inline-flex w-24 shrink-0 justify-center rounded-full px-3 py-1 text-center text-xs font-bold {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 gap-2 text-sm">
                                <div class="flex justify-between gap-3 pt-3 border-t">
                                    <span class="text-gray-400">ผู้ขึ้นทะเบียน</span>
                                    <span class="font-medium text-right text-gray-700">
                                        {{ $import->company->full_name ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-400">ผู้จำหน่าย</span>
                                    <span class="font-medium text-right text-gray-700">
                                        {{ $import->distributorCompany->full_name ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-400">เลขทะเบียน</span>
                                    <span class="font-semibold text-right text-gray-700">
                                        {{ $import->registration_number ?: '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-400">วันหมดอายุ</span>
                                    <span class="font-semibold text-right text-gray-700">
                                        @if ($import->expired_license_date)
                                            {{ \Carbon\Carbon::parse($import->expired_license_date)->addYears(543)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-1.5 sm:gap-2 mt-3 sm:mt-4">
                                @can('Inregister read')
                                    <a href="{{ route('createproduct.show', $import->id) }}"
                                        class="flex items-center justify-center gap-1 rounded-xl bg-green-500 px-2 py-2 sm:px-3 sm:py-2.5 text-sm font-bold text-white active:scale-95 transition">
                                        ดู
                                    </a>
                                @endcan

                                @can('import_data_manufacture read')
                                    <button type="button"
                                        onclick="openDocumentFilesModal('production-files-template-{{ $import->id }}', 'รายการไฟล์ทะเบียนผลิต')"
                                        class="flex items-center justify-center gap-1 rounded-xl bg-blue-600 px-2 py-2 sm:px-3 sm:py-2.5 text-sm font-bold text-white active:scale-95 transition">
                                        PDF
                                    </button>
                                @endcan

                                @can('Inregister update')
                                    <a href="{{ route('createproduct.edit', $import->id) }}"
                                        class="flex items-center justify-center gap-1 rounded-xl bg-yellow-500 px-2 py-2 sm:px-3 sm:py-2.5 text-sm font-bold text-white active:scale-95 transition">
                                        แก้ไข
                                    </a>
                                @endcan

                                @can('Inregister delete')
                                    <button onclick="confirmDelete({{ $import->id }})"
                                        class="flex items-center justify-center gap-1 rounded-xl bg-red-500 px-2 py-2 sm:px-3 sm:py-2.5 text-sm font-bold text-white active:scale-95 transition">
                                        ลบ
                                    </button>

                                    <form id="delete-form-{{ $import->id }}" action="{{ route('createproduct.destroy', $import->id) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('delete')
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center bg-white border border-gray-300 border-dashed rounded-2xl">
                            <p class="font-medium text-gray-400">ไม่มีข้อมูลทะเบียนผลิต</p>
                        </div>
                    @endforelse
                    <div class="px-1 py-2 bg-white border-t border-gray-100 sm:px-6 lg:px-8 sm:py-6 rounded-b-2xl">
                        {{ $imports->appends(request()->query())->onEachSide(1)->links() }}
                    </div>
                @endcan
            </div>

        </div>
    </main>

    <div id="documentFileModal" class="fixed inset-0 z-50 hidden px-4 py-6 bg-gray-900 bg-opacity-60">
        <div class="flex flex-col h-full max-w-5xl mx-auto overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="flex items-center justify-between gap-4 px-5 py-4 border-b">
                <h3 id="documentFileModalTitle" class="text-lg font-semibold text-gray-700 truncate">เอกสาร</h3>
                <button type="button" id="closeDocumentFileModal" class="text-2xl leading-none text-gray-500 hover:text-gray-800">
                    &times;
                </button>
            </div>
            <div id="documentFileViewer" class="flex flex-col items-center flex-1 gap-4 p-4 overflow-auto bg-gray-100" oncontextmenu="return false;"></div>
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
            const popupHtml = `
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
                            <p class="py-10 text-sm text-center text-gray-400">เลือกไฟล์จากรายการด้านซ้าย</p>
                        </div>
                    </div>
                </div>
            `;

            Swal.fire({
                title,
                html: popupHtml,
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
            return;
            const html = template ? template.innerHTML : '<p class="text-gray-400">ไม่มีไฟล์เอกสาร</p>';

            Swal.fire({
                title,
                html,
                width: '44rem',
                confirmButtonText: 'ปิด',
                customClass: {
                    htmlContainer: 'text-left'
                }
            });
        }

        function closeDocumentViewerModal() {
            resetPdfState();
            documentFileModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            documentFileViewer.innerHTML = '';
        }

        async function renderDocumentPdf(url) {
            const token = ++documentRenderToken;
            documentFileViewer.innerHTML = '<p class="py-8 text-gray-500">กำลังโหลดเอกสาร...</p>';

            if (!window.pdfjsLib) {
                documentFileViewer.innerHTML = '<p class="py-8 text-red-500">ไม่สามารถโหลดตัวอ่าน PDF ได้</p>';
                return;
            }

            try {
                const pdf = await pdfjsLib.getDocument(url).promise;
                if (token !== documentRenderToken) return;

                documentFileViewer.innerHTML = '';
                for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    const page = await pdf.getPage(pageNumber);
                    if (token !== documentRenderToken) return;

                    const viewport = page.getViewport({ scale: 1.4 });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    canvas.className = 'max-w-full bg-white shadow-md';
                    documentFileViewer.appendChild(canvas);

                    await page.render({ canvasContext: context, viewport }).promise;
                    if (token !== documentRenderToken) return;
                    await drawTiledPdfWatermark(context, canvas);
                }
            } catch (error) {
                if (token === documentRenderToken) {
                    documentFileViewer.innerHTML = '<p class="py-8 text-red-500">ไม่สามารถแสดงเอกสารนี้ได้</p>';
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
            const fullscreenButton = document.getElementById('swalPdfFullscreen');
            const hasPdf = Boolean(activePdf);

            if (pageInfo) pageInfo.textContent = hasPdf ? `${activePdfPage} / ${activePdf.numPages}` : '0 / 0';
            if (zoomLabel) zoomLabel.textContent = `${Math.round(activePdfScale * 100)}%`;
            if (prevButton) prevButton.disabled = !hasPdf || activePdfPage <= 1;
            if (nextButton) nextButton.disabled = !hasPdf || activePdfPage >= activePdf.numPages;
            if (zoomOutButton) zoomOutButton.disabled = !hasPdf || activePdfScale <= 0.5;
            if (zoomInButton) zoomInButton.disabled = !hasPdf || activePdfScale >= 3;
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
            activePdfViewer.innerHTML = '<p class="py-8 m-auto text-gray-500">กำลังโหลดเอกสาร...</p>';

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
                    activePdfViewer.innerHTML = '<p class="py-8 m-auto text-red-500">ไม่สามารถแสดงเอกสารนี้ได้</p>';
                }
            }
        }

        async function loadDocumentPdf(url, viewer = documentFileViewer) {
            resetPdfState();
            const token = ++documentRenderToken;
            viewer.innerHTML = '<p class="py-8 m-auto text-gray-500">กำลังโหลดเอกสาร...</p>';

            if (!window.pdfjsLib) {
                viewer.innerHTML = '<p class="py-8 m-auto text-red-500">ไม่สามารถโหลดตัวอ่าน PDF ได้</p>';
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
                    viewer.innerHTML = '<p class="py-8 m-auto text-red-500">ไม่สามารถแสดงเอกสารนี้ได้</p>';
                }
            }
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
        document.getElementById('menu-manufacture')?.classList.add('side-menu--active');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- helper แปลงค่าเริ่มต้น (yyyy-mm-dd -> dd/mm/yyyy(พ.ศ.)) ---
            function adIsoToBeDisplay(isoStr) {
                if (!isoStr || !/^\d{4}-\d{2}-\d{2}$/.test(isoStr)) return null;
                const [y, m, d] = isoStr.split('-').map(n => parseInt(n, 10));
                const be = y + 543;
                return String(d).padStart(2, '0') + '/' + String(m).padStart(2, '0') + '/' + be;
            }

            // --- ถ้า request ส่งมาเป็น ค.ศ. iso ให้แปลงมาแสดงเป็น พ.ศ. ---
            document.querySelectorAll(".date-th").forEach(el => {
                const v = (el.value || '').trim();
                const beDisplay = adIsoToBeDisplay(v);
                if (beDisplay) el.value = beDisplay; // แสดงเป็น พ.ศ. dd/mm/yyyy
            });

            // --- ติดตั้ง flatpickr (ไทย + รับพิมพ์/แปะได้) ---
            flatpickr(".date-th", {
                allowInput: true,
                locale: "th",
                dateFormat: "d/m/Y",
                parseDate: (datestr, format) => {
                    if (!datestr) return null;
                    // รับ dd/mm/yyyy (พ.ศ./ค.ศ.)
                    const m = datestr.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
                    if (m) {
                        let dd = parseInt(m[1], 10);
                        let mm = parseInt(m[2], 10);
                        let yyyy = parseInt(m[3], 10);
                        if (yyyy > 2400) yyyy -= 543; // พ.ศ. -> ค.ศ.
                        return new Date(yyyy, mm - 1, dd);
                    }
                    // เผื่อวางเป็น yyyy-mm-dd
                    const n = datestr.match(/^(\d{4})-(\d{2})-(\d{2})$/);
                    if (n) return new Date(parseInt(n[1], 10), parseInt(n[2], 10) - 1, parseInt(n[3],
                        10));
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

            // --- ก่อน submit ฟอร์ม: แปลง dd/mm/yyyy(พ.ศ.) -> yyyy-mm-dd(ค.ศ.)
            document.querySelectorAll("[data-filter-form]").forEach(form => {
                form.addEventListener("submit", () => {
                    const searchInput = form.querySelector('input[name="search"]');
                    if (searchInput) {
                        const val = (searchInput.value || '').trim();
                        if (val === '') {
                            searchInput.disabled = true;
                        } else {
                            searchInput.value = val;
                        }
                    }

                    form.querySelectorAll(".date-th").forEach(input => {
                        const v = (input.value || '').trim();
                        const m = v.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
                        if (m) {
                            let dd = m[1],
                                mm = m[2],
                                y = parseInt(m[3], 10);
                            if (y > 2400) y -= 543; // พ.ศ. -> ค.ศ.
                            input.value = `${y}-${mm}-${dd}`;
                        }
                    });
                });
            });
        });
    </script>

</x-app-layout>
