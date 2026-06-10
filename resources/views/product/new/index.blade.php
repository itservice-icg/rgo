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
                    ขึ้นทะเบียนใหม่
                </span>
            </h1>

            {{-- สรุปสถานะทะเบียน --}}

            {{-- <div class="flex flex-row justify-around mb-10"> --}}
            {{-- ทั้งหมด --}}
            {{-- <a href="{{ route('newregis.index', array_merge(request()->except('status_filter', 'page'), ['page' => 1])) }}"
                    class="group h-full bg-gradient-to-br from-green-100 to-green-50 p-4 rounded-3xl text-center border-2 border-green-200 hover:scale-105 transition-all duration-300">
                    <div class="flex justify-center mb-2">
                        <div class="bg-green-200 rounded-full p-3 group-hover:bg-green-300 transition">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-lg font-bold text-green-700 mb-1 tracking-wide">ทะเบียนทั้งหมด</h2>
                    <p class="text-4xl text-green-600 font-extrabold mb-1">{{ $total }}</p>
                </a> --}}
            {{-- ขึ้นทั้งหมด --}}
            {{-- <a href="{{ route('newregis.index', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'new_all', 'page' => 1])) }}"
                    class="group block h-full bg-gradient-to-br from-blue-200 to-blue-100 p-4 rounded-3xl text-center border-2 border-blue-400 hover:scale-105 transition-all duration-300">
                    <div class="flex justify-center mb-2">
                        <div class="bg-blue-300 rounded-full p-3 group-hover:bg-blue-400 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-lg font-bold text-blue-700 mb-1 tracking-wide">ขึ้นทะเบียนใหม่</h2>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalNewRegistrations ?? 0 }}</p>
                </a> --}}
            {{-- อยู่ระหว่างดำเนินการ --}}
            {{-- <a href="{{ route('newregis.index', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'soon_expired', 'page' => 1])) }}"
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
                    <h2 class="text-lg font-bold text-yellow-700 mb-1 tracking-wide">ทะเบียนใกล้หมดอายุ</h2>
                    <p class="text-2xl font-bold text-yellow-600">{{ $soonExpiredCount ?? 0 }}</p>
                </a> --}}
            {{-- ขึ้นทะเบียนใหม่เสร็จแล้ว --}}
            {{-- <a href="{{ route('newregis.index', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'expired', 'page' => 1])) }}"
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
                    <h2 class="text-lg font-bold text-red-700 mb-1 tracking-wide">ทะเบียนหมดอายุ</h2>
                    <p class="text-2xl font-bold text-red-600">{{ $expiredCount ?? 0 }}</p>
                </a>
            </div> --}}



            {{-- 1 --}}

            <div class="hidden lg:flex flex-col sm:flex-row justify-between items-center mx-3 mb-2">
                <form id="filterForm" action="{{ route('newregis.index') }}" method="GET" data-filter-form
                    class="flex items-center gap-2 mb-2">
                    <div class="relative flex-grow min-w-[150px]">
                        <label for="search_query" class="mx-3 text-base block text-gray-700 mb-1 mt-3">ค้นหา</label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none mt-9">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input type="text" id="search_query" name="search"
                            placeholder="ชื่อวัตถุอันตราย /ชื่อการค้า" value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 w-[500px] rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600 transition duration-200 ease-in-out text-gray-700 shadow-sm"
                            style="width:100%" />
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
                            <a href="{{ route('newregis.index') }}"
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
                @can('RegisterNew create')
                    <a href="{{ route('newregis.create') }}"
                        class="mt-8 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transform hover:scale-105 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                        + เพิ่มข้อมูล
                    </a>
                @endcan
            </div>
            <div class="lg:hidden mb-4">
                <form action="{{ route('newregis.index') }}" method="GET" data-filter-form
                    class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">
                    <div class="grid grid-cols-1 gap-3">
                        <div class="min-w-0">
                            <label for="mobile_search_query" class="block text-sm font-semibold text-gray-700 mb-1">
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
                                <input type="text" id="mobile_search_query" name="search"
                                    placeholder="ชื่อวัตถุอันตราย / ชื่อการค้า" value="{{ request('search') }}"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 shadow-sm" />
                            </div>
                        </div>

                        <div class="min-w-0">
                            <label for="mobile_expiry_date_from" class="block text-sm font-semibold text-gray-700 mb-1">
                                วันที่เริ่ม
                            </label>
                            <input id="mobile_expiry_date_from"
                                class="date-th w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 shadow-sm"
                                type="text" name="expiry_date_from" value="{{ request('expiry_date_from') }}"
                                placeholder="วว/ดด/ปปปป" autocomplete="off" autocorrect="off" autocapitalize="off"
                                spellcheck="false" />
                        </div>

                        <div class="min-w-0">
                            <label for="mobile_expiry_date_to" class="block text-sm font-semibold text-gray-700 mb-1">
                                วันที่สิ้นสุด
                            </label>
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
                                <a href="{{ route('newregis.index') }}"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-3 rounded-xl border border-gray-200 shadow-sm active:scale-95 transition">
                                    ล้าง
                                </a>
                            @endif
                        </div>
                    </div>

                    @can('RegisterNew create')
                        <a href="{{ route('newregis.create') }}"
                            class="mt-3 w-full inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-sm active:scale-95 transition">
                            + เพิ่มข้อมูล
                        </a>
                    @endcan
                </form>
            </div>
            {{-- 1 --}}
            <div class="hidden lg:block bg-white rounded-2xl overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-[1720px] table-fixed bg-white">
                        <colgroup>
                            <col class="w-20">
                            <col class="w-48">
                            <col class="w-56">
                            <col class="w-52">
                            <col class="w-52">
                            <col class="w-52">
                            <col class="w-36">
                            <col class="w-44">
                            <col class="w-36">
                            <col class="w-32">
                        </colgroup>
                        <thead>
                            <tr class="bg-indigo-600 text-white text-left">
                                <th class="py-4 px-4 rounded-tl-2xl text-center">ลำดับ</th>
                                <th class="py-4 px-4">ชื่อการค้า</th>
                                <th class="py-4 px-4">ชื่อวัตถุอันตราย (อังกฤษ)</th>
                                <th class="py-4 px-4">เปอร์เซ็นต์และสูตร</th>
                                <th class="py-4 px-4">บริษัทที่ขึ้นทะเบียน</th>
                                <th class="py-4 px-4">ชื่อผู้จำหน่าย</th>
                                <th class="py-4 px-4 text-center">วันที่ยื่นคำขอ</th>
                                <th class="py-4 px-4 text-center">สถานะความคืบหน้า</th>
                                <th class="py-4 px-4 text-center">สถานะ</th>
                                <th class="py-4 px-4 rounded-tr-2xl text-center">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paginatedProducts as $index => $product)
                                <tr class="border-b hover:bg-indigo-50 transition">
                                    <td class="py-4 px-4 font-semibold text-center text-gray-700">
                                        {{ ($paginatedProducts->currentPage() - 1) * $paginatedProducts->perPage() + $index + 1 }}
                                    </td>
                                    <td class="py-4 px-4 break-words">{{ $product->trade_name ?? '' }}</td>
                                    <td class="py-4 px-4 break-words">{{ $product->chemical_name_en ?? '' }}</td>
                                    <td class="py-4 px-4 break-words">{{ $product->composition ?? '' }}</td>
                                    <td class="py-4 px-4 break-words">{{ $product->registrant ?? '' }}</td>
                                    <td class="py-4 px-4 break-words">{{ $product->distributor ?? '' }}</td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        {{ $product->date_submit_request ? \Carbon\Carbon::parse($product->date_submit_request)->addYears(543)->format('d/m/Y') : '' }}
                                    </td>
                                    <td class="py-4 px-4">
                                        @php
                                            $stepTitles = [
                                                1 => 'คณะ PDC อนุมัติให้ดำเนินการขึ้นทะเบียน',
                                                2 => 'นำเข้าตัวอย่าง',
                                                3 => 'ส่งตัวอย่างข้อมูลศึกษาความเป็นพิษ (ทำTox)',
                                                4 => 'ยื่นคำขอขึ้นทะเบียน',
                                                5 => 'แผนการทดลอง Eff, PHI (ถ้ามี) + Phase1+ผลวิเคราะห์ (อนุมัติ)',
                                                6 => 'ยื่น Phase3 (ผลการทดลอง Eff, PHI (ถ้ามี) อนุมัติ+ผลวิเคราะห์อนุมัติ)',
                                                7 => 'Phase3 อนุมัติ (ยื่นเอกสารเข้าประชุมพิจารณาขึ้นทะเบียน)',
                                                8 => 'ยื่นขอออกทะเบียน',
                                            ];

                                            // New logic per requirement:
                                            // 1) Prefer the latest step that has at least one checked item.
                                            // 2) If no steps have any checked items but some steps are fully completed, show the latest fully-completed step.
                                            // 3) Otherwise default to step 1.

                                            $show_step_number = 0;
                                            $number_step_number = 1;

                                            // prepare arrays with computed counts per step
                                            $stepsInfo = [];
                                            for ($i = 1; $i <= 8; $i++) {
                                                $summary = $product->step_summary[$i] ?? null;
                                                $totalInStep = 0;
                                                $unchecked = 0;
                                                if ($summary) {
                                                    $totalInStep = is_numeric($summary->last_index) ? ($summary->last_index + 1) : 0;
                                                    $unchecked = (int) $summary->unchecked_count;
                                                }
                                                $checked = $totalInStep - $unchecked;
                                                $stepsInfo[$i] = [
                                                    'summary' => $summary,
                                                    'total' => $totalInStep,
                                                    'unchecked' => $unchecked,
                                                    'checked' => $checked,
                                                ];
                                            }

                                            // find latest step that has any checked items
                                            $lastCheckedStep = 0;
                                            for ($i = 1; $i <= 8; $i++) {
                                                if ($stepsInfo[$i]['checked'] > 0) {
                                                    $lastCheckedStep = $i;
                                                }
                                            }

                                            if ($lastCheckedStep > 0) {
                                                $displayStep = $lastCheckedStep;
                                            } else {
                                                // no step has any checked items -> check for latest fully-completed step
                                                $lastFullyCompleted = 0;
                                                for ($i = 1; $i <= 8; $i++) {
                                                    if ($stepsInfo[$i]['total'] > 0 && $stepsInfo[$i]['unchecked'] == 0) {
                                                        $lastFullyCompleted = $i;
                                                    }
                                                }
                                                if ($lastFullyCompleted > 0) {
                                                    $displayStep = $lastFullyCompleted;
                                                } else {
                                                    $displayStep = 1;
                                                }
                                            }

                                            $number_step_number = $displayStep;

                                            // Map displayStep to percentage using the same rules as elsewhere
                                            $summaryForDisplay = $product->step_summary[$displayStep] ?? null;
                                            $uncheckedForDisplay = $summaryForDisplay->unchecked_count ?? null;

                                            // helper to detect isPlanNone (keeps original behavior)
                                            $isPlanNone = $product->isPlanNone ?? 0;

                                            switch ($displayStep) {
                                                case 1:
                                                    if ($summaryForDisplay && $uncheckedForDisplay >= 12) {
                                                        $show_step_number = 0;
                                                    } else {
                                                        $show_step_number = 12.5;
                                                    }
                                                    break;
                                                case 2:
                                                    $show_step_number = 25;
                                                    break;
                                                case 3:
                                                    $show_step_number = 37.5;
                                                    break;
                                                case 4:
                                                    if ($summaryForDisplay && $uncheckedForDisplay == 1 && $isPlanNone == 1) {
                                                        $number_step_number = 5;
                                                        $show_step_number = 62.5;
                                                    } else {
                                                        $show_step_number = 50;
                                                    }
                                                    break;
                                                case 5:
                                                    if ($summaryForDisplay && $uncheckedForDisplay == 2 && $isPlanNone == 1) {
                                                        $number_step_number = 6;
                                                        $show_step_number = 75;
                                                    } else {
                                                        $show_step_number = 62.5;
                                                    }
                                                    break;
                                                case 6:
                                                    if ($summaryForDisplay && $uncheckedForDisplay == 2 && $isPlanNone == 1) {
                                                        $number_step_number = 7;
                                                        $show_step_number = 87.5;
                                                    } else {
                                                        $show_step_number = 75;
                                                    }
                                                    break;
                                                case 7:
                                                    $show_step_number = 87.5;
                                                    break;
                                                case 8:
                                                    if ($summaryForDisplay && $summaryForDisplay->unchecked_count == 0) {
                                                        $show_step_number = 100;
                                                    } else {
                                                        $show_step_number = 90;
                                                    }
                                                    break;
                                                default:
                                                    $show_step_number = 0;
                                            }

                                        @endphp
                                        {{-- ชื่อขั้นตอน --}}
                                        <div class="text-center mb-2">
                                            @if ($show_step_number >= 100)
                                                <p class="text-green-500 font-semibold">สำเร็จ</p>
                                            @else
                                                <div x-data="{ tooltip: false }" class="relative inline-block">

                                                    <p class="text-yellow-700 font-semibold cursor-pointer"
                                                        @mouseenter="tooltip = true" @mouseleave="tooltip = false">
                                                        ขั้นตอนที่ {{ $number_step_number }}
                                                    </p>
                                                    <div x-show="tooltip"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 scale-90"
                                                        x-transition:enter-end="opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 scale-100"
                                                        x-transition:leave-end="opacity-0 scale-90"
                                                        class="absolute z-50 whitespace-normal break-words rounded-lg bg-black py-1.5 px-3 font-sans text-sm font-normal text-white focus:outline-none -translate-x-1/2 left-1/2 -top-10"
                                                        style="min-width: max-content;">
                                                        {{ $stepTitles[$number_step_number] ?? 'ไม่ทราบขั้นตอน' }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- แถบความคืบหน้า --}}
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                            <div class="h-2.5 bg-green-500" style="width: {{ $show_step_number }}%;">
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500 text-center mt-1">
                                            {{ number_format($show_step_number, 1) }}%
                                        </div>
                                    </td>
                                    <td class="py-8 px-4 text-center">
                                        @if ($product->progress >= 100)
                                            @php
                                                $statusClass = '';
                                                $statusText = $product->status;

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
                                        @if ($product->progress < 100)
                                            <span
                                                class="inline-flex w-24 justify-center rounded-full px-3 py-1 font-semibold text-white bg-blue-500" style="font-size: 0.75rem;">
                                                {{ 'ขึ้นทะเบียนใหม่' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 mx-auto">
                                        {{-- ปุ่มดูรายละเอียด --}}
                                        <div class="flex items-center gap-3 justify-center">
                                            @can('RegisterNew read')
                                                <a href="{{ route('newregis.show', $product->id) }}"
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
                                            @can('RegisterNew update')
                                                @php
                                                    $userDept = auth()->user()->department;
                                                    $deptMap = [
                                                        'InternationalProcurement' => 'จัดซื้อต่างประเทศ',
                                                        'SalesDepartment' => 'ฝ่ายขาย',
                                                        'ResearchAndDevelopment' => 'วิจัยและพัฒนา',
                                                        'Academic' => 'แผนกวิชาการ',
                                                        'Registration' => 'แผนกทะเบียน',
                                                    ];
                                                    $mappedDept = $deptMap[$userDept] ?? $userDept;

                                                    // ดึงหัวข้อของแผนกในขั้นตอนล่าสุดที่ยังไม่ติ๊ก
                                                    // $step = (int) ceil($product->progress / 12.5);
                                                    $incomplete = \App\Models\DrugProgressStep::where(
                                                        'chemical_registrations_id',
                                                        $product->id,
                                                    )
                                                        // ->where('step_number', $step)
                                                        ->where('department', $mappedDept)
                                                        ->whereNull('checked_at')
                                                        ->exists();
                                                @endphp

                                                {{-- @php
                                                    Log::info('Checking edit button visibility:', [
                                                        'product_id' => $product->id,
                                                        'user_id' => auth()->id(),
                                                        'user_department' => auth()->user()->department,
                                                        'is_incomplete' => $incomplete,
                                                        'has_admin_role' => auth()->user()->hasRole('admin'),
                                                        'has_manager_role' => auth()->user()->hasRole('manager'),
                                                    ]);
                                                @endphp --}}

                                                @if (
                                                    $incomplete ||
                                                        auth()->user()->hasRole('admin') ||
                                                        auth()->user()->hasRole('manager') ||
                                                        auth()->user()->department == 'Registration')
                                                    {{-- auth()->id() == 44 ||
                                                        auth()->id() == 8) --}}
                                                    <a href="{{ route('newregis.edit', $product->id) }}"
                                                        class="inline-flex items-center justify-center p-2 rounded-full text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                                        title="แก้ไข">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endcan

                                            {{-- @can('RegisterNew update')
                                                <a href="{{ route('newregis.edit', $product->id) }}"
                                                    class="inline-flex items-center justify-center p-2 rounded-full text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                                    title="แก้ไข">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </a>
                                            @endcan --}}
                                            @can('RegisterNew delete')
                                                <button onclick="confirmDelete({{ $product->id }})"
                                                    class="inline-flex items-center justify-center p-2 rounded-full text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200"
                                                    title="ลบ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.92a2.25 2.25 0 0 1-2.244-2.077L4.74 5.959m1.049-.165c.51-.158 1.029-.28 1.563-.35L12 4.75m-4.78 2.152A.75.75 0 0 1 9 6.75h6m-3 0V4.5m-2.25 4.5h.008v.008H9.75V9Zm0 0H9.75Zm4.5 0h.008v.008H14.25V9Z" />
                                                    </svg>
                                                </button>
                                                <form id="delete-form-{{ $product->id }}"
                                                    action="{{ route('newregis.destroy', $product->id) }}" method="POST"
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
                                    <td colspan="6" class="py-6 px-8 text-center text-gray-400">
                                        ไม่มีขึ้นทะเบียนใหม่
                                    </td>
                                </tr>
                            @endforelse

                            <script>
                                function confirmDelete(id) {
                                    if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?')) {
                                        document.getElementById(`delete-form-${id}`).submit();
                                    }
                                }
                            </script>
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 bg-white border-t border-gray-100 rounded-b-2xl">
                    {{-- เปลี่ยนตัวแปรตรงนี้ด้วย --}}
                    {{ $paginatedProducts->appends(request()->query())->links() }}
                </div>
            </div>

            {{-- Mobile Card List --}}
            <div class="lg:hidden space-y-4">
                @forelse ($paginatedProducts as $index => $product)
                    @php
                        $displayStep = 1;
                        $mobileStepsInfo = [];

                        for ($i = 1; $i <= 8; $i++) {
                            $summary = $product->step_summary[$i] ?? null;
                            $totalInStep = 0;
                            $unchecked = 0;

                            if ($summary) {
                                $totalInStep = is_numeric($summary->last_index) ? ($summary->last_index + 1) : 0;
                                $unchecked = (int) $summary->unchecked_count;
                            }

                            $mobileStepsInfo[$i] = [
                                'summary' => $summary,
                                'total' => $totalInStep,
                                'unchecked' => $unchecked,
                                'checked' => $totalInStep - $unchecked,
                            ];
                        }

                        $lastCheckedStep = 0;
                        for ($i = 1; $i <= 8; $i++) {
                            if ($mobileStepsInfo[$i]['checked'] > 0) {
                                $lastCheckedStep = $i;
                            }
                        }

                        if ($lastCheckedStep > 0) {
                            $displayStep = $lastCheckedStep;
                        } else {
                            $lastFullyCompleted = 0;
                            for ($i = 1; $i <= 8; $i++) {
                                if ($mobileStepsInfo[$i]['total'] > 0 && $mobileStepsInfo[$i]['unchecked'] == 0) {
                                    $lastFullyCompleted = $i;
                                }
                            }
                            $displayStep = $lastFullyCompleted > 0 ? $lastFullyCompleted : 1;
                        }

                        $summaryForDisplay = $product->step_summary[$displayStep] ?? null;
                        $uncheckedForDisplay = $summaryForDisplay->unchecked_count ?? null;
                        $isPlanNone = $product->isPlanNone ?? 0;

                        switch ($displayStep) {
                            case 1:
                                $progressValue = $summaryForDisplay && $uncheckedForDisplay >= 12 ? 0 : 12.5;
                                break;
                            case 2:
                                $progressValue = 25;
                                break;
                            case 3:
                                $progressValue = 37.5;
                                break;
                            case 4:
                                $progressValue = $summaryForDisplay && $uncheckedForDisplay == 1 && $isPlanNone == 1 ? 62.5 : 50;
                                break;
                            case 5:
                                $progressValue = $summaryForDisplay && $uncheckedForDisplay == 2 && $isPlanNone == 1 ? 75 : 62.5;
                                break;
                            case 6:
                                $progressValue = $summaryForDisplay && $uncheckedForDisplay == 2 && $isPlanNone == 1 ? 87.5 : 75;
                                break;
                            case 7:
                                $progressValue = 87.5;
                                break;
                            case 8:
                                $progressValue = $summaryForDisplay && $summaryForDisplay->unchecked_count == 0 ? 100 : 90;
                                break;
                            default:
                                $progressValue = 0;
                        }

                        $statusText = $progressValue >= 100 ? ($product->status ?? 'สำเร็จ') : 'New';
                        $statusClass = $progressValue >= 100
                            ? 'bg-green-100 text-green-700 border border-green-200'
                            : 'bg-blue-100 text-blue-700 border border-blue-200';
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 active:scale-[0.99] transition">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="min-w-0">
                                <div class="text-xs text-gray-400 mb-1">
                                    #{{ ($paginatedProducts->currentPage() - 1) * $paginatedProducts->perPage() + $index + 1 }}
                                </div>
                                <h3 class="text-base font-bold text-gray-800 leading-snug line-clamp-2">
                                    {{ $product->trade_name ?: '-' }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-1">
                                    {{ $product->chemical_name_en ?: '-' }}
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div class="flex justify-between gap-3 border-t pt-3">
                                <span class="text-gray-400">ผู้ขึ้นทะเบียน</span>
                                <span class="text-gray-700 font-medium text-right">
                                    {{ $product->registrant ?: '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-400">ผู้จำหน่าย</span>
                                <span class="text-gray-700 font-medium text-right">
                                    {{ $product->distributor ?: '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-400">วันที่ยื่นคำขอ</span>
                                <span class="text-gray-700 font-semibold text-right">
                                    {{ $product->date_submit_request ? \Carbon\Carbon::parse($product->date_submit_request)->addYears(543)->format('d/m/Y') : '-' }}
                                </span>
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                    <span>ความคืบหน้า</span>
                                    <span>{{ number_format($progressValue, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 bg-green-500" style="width: {{ min($progressValue, 100) }}%;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 mt-4">
                            @can('RegisterNew read')
                                <a href="{{ route('newregis.show', $product->id) }}"
                                    class="flex items-center justify-center gap-1 rounded-xl bg-green-500 px-3 py-2.5 text-sm font-bold text-white active:scale-95 transition">
                                    ดู
                                </a>
                            @endcan

                            @can('RegisterNew update')
                                @php
                                    $userDept = auth()->user()->department;
                                    $deptMap = [
                                        'InternationalProcurement' => 'จัดซื้อต่างประเทศ',
                                        'SalesDepartment' => 'ฝ่ายขาย',
                                        'ResearchAndDevelopment' => 'วิจัยและพัฒนา',
                                        'Academic' => 'แผนกวิชาการ',
                                        'Registration' => 'แผนกทะเบียน',
                                    ];
                                    $mappedDept = $deptMap[$userDept] ?? $userDept;
                                    $incomplete = \App\Models\DrugProgressStep::where('chemical_registrations_id', $product->id)
                                        ->where('department', $mappedDept)
                                        ->whereNull('checked_at')
                                        ->exists();
                                @endphp

                                @if ($incomplete || auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->department == 'Registration')
                                    <a href="{{ route('newregis.edit', $product->id) }}"
                                        class="flex items-center justify-center gap-1 rounded-xl bg-yellow-500 px-3 py-2.5 text-sm font-bold text-white active:scale-95 transition">
                                        แก้ไข
                                    </a>
                                @endif
                            @endcan

                            @can('RegisterNew delete')
                                <button onclick="confirmDelete({{ $product->id }})"
                                    class="flex items-center justify-center gap-1 rounded-xl bg-red-500 px-3 py-2.5 text-sm font-bold text-white active:scale-95 transition">
                                    ลบ
                                </button>
                                <form id="delete-form-mobile-{{ $product->id }}" action="{{ route('newregis.destroy', $product->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('delete')
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-8 text-center">
                        <p class="text-gray-400 font-medium">ไม่มีขึ้นทะเบียนใหม่</p>
                    </div>
                @endforelse

                <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6 bg-white border-t border-gray-100 rounded-b-2xl">
                    {{ $paginatedProducts->appends(request()->query())->onEachSide(1)->links() }}
                </div>
            </div>

        </div>
    </main>

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
            document.querySelectorAll(".date-th").forEach(el => {
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
            document.querySelectorAll("[data-filter-form]").forEach(form => {
                form.addEventListener("submit", (e) => {
                    // If the search input is empty (or only whitespace), disable it so it's not submitted
                    const searchInput = form.querySelector('input[name="search"]');
                    if (searchInput) {
                        const val = (searchInput.value || '').trim();
                        if (val === '') {
                            searchInput.disabled = true;
                        } else {
                            // trim surrounding whitespace before submit
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
                            input.value = `${y}-${mm}-${dd}`; // ส่งรูปแบบที่ backend/Query ถนัด
                        }
                    });
                });
            });
        });
    </script>


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
                    // ส่งฟอร์มลบ
                    document.getElementById(`delete-form-${roleId}`).submit();
                }
            });
        }
    </script>

    <style>
        /* กล่องค้นหาหลัก */
        .search-form {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        /* กล่อง input ที่มี icon */
        .search-box {
            position: relative;
            width: 100%;
        }

        /* ช่องกรอกข้อความ */
        .search-box input[type="text"] {
            width: 100%;
            padding: 12px 16px 12px 40px;
            /* padding-left สำหรับ icon */
            border: 1px solid #ccc;
            border-radius: 16px;
            /* << มนตรงนี้ */
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .search-box input[type="text"]:focus {
            outline: none;
            border-color: #6366f1;
            /* สีม่วง */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
            /* วงแสง */
        }

        /* ไอคอนข้างหน้า */
        .search-icon {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            font-size: 18px;
            color: #888;
            pointer-events: none;
        }

        /* ปุ่มค้นหา */
        .search-button {
            padding: 12px 24px;
            background-color: #6366f1;
            /* สีม่วง */
            color: white;
            border: none;
            border-radius: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .search-button:hover {
            background-color: #4f46e5;
        }
    </style>

</x-app-layout>
