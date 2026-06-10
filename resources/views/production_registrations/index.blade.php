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
</style>

<x-app-layout>
    <main class="flex-1 overflow-x-hidden overflow-y-auto">
        <div class="container mx-auto px-6 py-6">
            <h1 class="text-4xl font-extrabold text-center text-indigo-700 mt-5 mb-10 tracking-wide">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2"
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
            <div class="grid grid-cols-3 gap-2 mb-4">
                {{-- ทั้งหมด --}}
                <a href="{{ route('createproduct.index', array_merge(request()->except('status_filter', 'page'), ['page' => 1])) }}"
                    class="rounded-2xl border border-blue-200 bg-blue-50 px-2 py-3 text-center active:scale-95 transition {{ !request('status_filter') ? 'ring-2 ring-blue-300 bg-blue-100' : '' }}">
                    <div class="flex justify-center mb-1">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-200" style="width: 60px; height: 60px;">
                            {{-- ไอคอนใหม่ --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 50px!important; height: 50px!important;"
                                stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-700">
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
                    class="rounded-2xl border border-yellow-200 bg-yellow-50 px-2 py-3 text-center active:scale-95 transition {{ request('status_filter') == 'soon_expired' ? 'ring-2 ring-yellow-300 bg-yellow-100' : '' }}">
                    <div class="flex justify-center mb-1">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-200" style="width: 60px; height: 60px;">
                            <svg class="h-4 w-4 text-yellow-700" fill="none" stroke="currentColor" stroke-width="2" style="width: 50px!important; height: 50px!important;"
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
                    class="rounded-2xl border border-red-200 bg-red-50 px-2 py-3 text-center active:scale-95 transition {{ request('status_filter') == 'expired' ? 'ring-2 ring-red-300 bg-red-100' : '' }}">
                    <div class="flex justify-center mb-1">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-200" style="width: 60px; height: 60px;">
                            <svg class="h-4 w-4 text-red-700" fill="none" stroke="currentColor" stroke-width="2" style="width: 50px!important; height: 50px!important;"
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

            <div class="hidden lg:block flex flex-col sm:flex-row justify-between items-center mx-3 mb-2">
                <form id="filterForm" action="{{ route('createproduct.index') }}" method="GET"
                    data-filter-form class="flex items-center gap-2 mb-2">
                    <div class="relative flex-grow min-w-[280px]">
                        <label for="search_query" class="mx-3 text-base block text-gray-700 mb-1 mt-3">ค้นหา</label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none mt-9">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input type="text" id="search_query" name="search"
                            placeholder="ชื่อวัตถุอันตราย /เลขที่ทะเบียน /ผู้ขึ้นทะเบียน"
                            value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 w-[500px] rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600 transition duration-200 ease-in-out text-gray-700 shadow-sm"
                            style="width:100%" />
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
                        @if (request('search') || request('expiry_date_from') || request('expiry_date_to') || request('status_filter'))
                            <a href="{{ route('createproduct.index') }}" {{-- class="inline-flex items-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg shadow-md transform hover:scale-105 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50"> --}}
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
                    <a href="{{ route('createproduct.create') }}"
                        class="mt-8 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transform hover:scale-105 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                        + เพิ่มข้อมูล
                    </a>
                @endcan --}}
            </div>

            <div class="lg:hidden">
                <form action="{{ route('createproduct.index') }}" method="GET" data-filter-form
                    class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">
                    <div class="grid grid-cols-1 gap-3">
                        <div class="min-w-0">
                            <label for="mobile_search_query" class="block text-sm font-semibold text-gray-700 mb-1">ค้นหา</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </div>
                                <input type="text" id="mobile_search_query" name="search"
                                    placeholder="ชื่อวัตถุอันตราย / เลขที่ทะเบียน"
                                    value="{{ request('search') }}"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 shadow-sm" />
                            </div>
                        </div>

                        <div class="min-w-0">
                            <label for="mobile_expiry_date_from" class="block text-sm font-semibold text-gray-700 mb-1">วันที่เริ่ม</label>
                            <input id="mobile_expiry_date_from"
                                class="date-th w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 shadow-sm"
                                type="text" name="expiry_date_from" value="{{ request('expiry_date_from') }}"
                                placeholder="วว/ดด/ปปปป" autocomplete="off" autocorrect="off" autocapitalize="off"
                                spellcheck="false" />
                        </div>

                        <div class="min-w-0">
                            <label for="mobile_expiry_date_to" class="block text-sm font-semibold text-gray-700 mb-1">วันที่สิ้นสุด</label>
                            <input id="mobile_expiry_date_to"
                                class="date-th w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 shadow-sm"
                                type="text" name="expiry_date_to" value="{{ request('expiry_date_to') }}"
                                placeholder="วว/ดด/ปปปป" autocomplete="off" autocorrect="off" autocapitalize="off"
                                spellcheck="false" />
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl shadow-sm active:scale-95 transition">
                                ค้นหา
                            </button>
                            @if (request('search') || request('expiry_date_from') || request('expiry_date_to') || request('status_filter'))
                                <a href="{{ route('createproduct.index') }}"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-3 rounded-xl border border-gray-200 shadow-sm active:scale-95 transition">
                                    ล้าง
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="hidden lg:block bg-white rounded-2xl overflow-hidden border border-gray-200">
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
                            <col class="w-44">
                        </colgroup>
                        <thead>
                            <tr class="bg-indigo-600 text-white text-left">
                                <th class="py-4 px-4 rounded-tl-2xl text-center">ลำดับ</th>
                                {{-- <th class="py-4 px-6">ชื่อการค้า</th> --}}
                                <th class="py-4 px-4">ชื่อวัตถุอันตราย (ไทย)</th>
                                <th class="py-4 px-4">ชื่อวัตถุอันตราย (อังกฤษ) </th>
                                {{-- <th class="py-4 px-6">ชื่อวัตถุอันตราย (อังกฤษ)</th> --}}
                                <th class="py-4 px-4">ผู้ขึ้นทะเบียน</th>
                                <th class="py-4 px-4">ผู้จำหน่าย</th>
                                {{-- <th class="py-4 px-6 text-center">ตัวย่อ</th> --}}
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
                                        {{-- <td class="py-4 px-6">{{ $import->trade_name ?? '' }}</td> --}}
                                        <td class="py-4 px-6 break-words">{{ $import->chemical_name_th ?? '' }}</td>
                                        <td class="py-4 px-6 break-words">{{ $import->chemical_name_en ?? '' }}</td>
                                        {{-- <td class="py-4 px-6 text-center">{{ $import->Companes->full_name ?? '' }}</td> --}}
                                        <td class="py-4 px-4 break-words">{{ $import->company->full_name ?? '' }}</td>
                                        <td class="py-4 px-4 break-words">{{ $import->distributorCompany->full_name ?? '' }}</td>
                                        <td class="py-4 px-4 text-center break-words">{{ $import->registration_number ?? '' }}</td>
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($import->expired_license_date)->addYears(543)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-4 px-4 text-center">
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
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center gap-3 justify-center">
                                                @can('Inregister read')
                                                    <a href="{{ route('createproduct.show', $import->id) }}"
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
                                                @can('Inregister update')
                                                    <a href="{{ route('createproduct.edit', $import->id) }}"
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
                                        <td colspan="7" class="py-6 px-8 text-center text-gray-400">
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
            <div class="lg:hidden space-y-4">
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

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 active:scale-[0.99] transition">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="min-w-0">
                                    <div class="text-xs text-gray-400 mb-1">
                                        #{{ $loop->iteration + ($imports->currentPage() - 1) * $imports->perPage() }}
                                    </div>
                                    <h3 class="text-base font-bold text-gray-800 leading-snug line-clamp-2">
                                        {{ $import->chemical_name_th ?: '-' }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-1">
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
                                <div class="flex justify-between gap-3 border-t pt-3">
                                    <span class="text-gray-400">ผู้ขึ้นทะเบียน</span>
                                    <span class="text-gray-700 font-medium text-right">
                                        {{ $import->company->full_name ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-400">ผู้จำหน่าย</span>
                                    <span class="text-gray-700 font-medium text-right">
                                        {{ $import->distributorCompany->full_name ?? '-' }}
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

                            <div class="grid grid-cols-3 gap-2 mt-4">
                                @can('Inregister read')
                                    <a href="{{ route('createproduct.show', $import->id) }}"
                                        class="flex items-center justify-center gap-1 rounded-xl bg-green-500 px-3 py-2.5 text-sm font-bold text-white active:scale-95 transition">
                                        ดู
                                    </a>
                                @endcan

                                @can('Inregister update')
                                    <a href="{{ route('createproduct.edit', $import->id) }}"
                                        class="flex items-center justify-center gap-1 rounded-xl bg-yellow-500 px-3 py-2.5 text-sm font-bold text-white active:scale-95 transition">
                                        แก้ไข
                                    </a>
                                @endcan

                                @can('Inregister delete')
                                    <button onclick="confirmDelete({{ $import->id }})"
                                        class="flex items-center justify-center gap-1 rounded-xl bg-red-500 px-3 py-2.5 text-sm font-bold text-white active:scale-95 transition">
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
                        <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-8 text-center">
                            <p class="text-gray-400 font-medium">ไม่มีข้อมูลทะเบียนผลิต</p>
                        </div>
                    @endforelse
                    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6 bg-white border-t border-gray-100 rounded-b-2xl">
                        {{ $imports->appends(request()->query())->onEachSide(1)->links() }}
                    </div>
                @endcan
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
