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
                    ทะเบียนสินค้าทั้งหมด
                </span>
            </h1>
            {{-- สรุปสถานะทะเบียน --}}
            <div class="flex flex-row justify-around mb-10">
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
                <a href="{{ route('newregis.productall', array_merge(request()->except('status_filter', 'page'), ['page' => 1])) }}"
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
                    <h2 class="text-lg font-bold text-blue-700 mb-1 tracking-wide">ทะเบียนทั้งหมด</h2>
                    <p class="text-2xl font-bold text-blue-600">{{ $total ?? 0 }}</p>
                </a>
                {{-- อยู่ระหว่างดำเนินการ --}}
                <a href="{{ route('newregis.productall', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'soon_expired', 'page' => 1])) }}"
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
                </a>
                {{-- ขึ้นทะเบียนใหม่เสร็จแล้ว --}}
                <a href="{{ route('newregis.productall', array_merge(request()->except('status_filter', 'page'), ['status_filter' => 'expired', 'page' => 1])) }}"
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
            </div>

            {{-- 1 --}}

            <div class="flex flex-col sm:flex-row justify-between items-center mx-3 mb-2">
                <form action="{{ route('newregis.productall') }}" method="GET" class="flex items-center gap-2 mb-2">
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
                            placeholder="ชื่อสามัญ /ชื่อการค้า /เลขที่ทะเบียน" value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 w-[500px] rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600 transition duration-200 ease-in-out text-gray-700 shadow-sm" />
                        {{-- class="pl-10 pr-4 py-2 w-96 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600 transition duration-200 ease-in-out text-gray-700 shadow-sm" /> --}}
                    </div>
                    <div class="flex-grow min-w-[180px]">
                        <label for="expiry_date_from"
                            class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันที่เริ่ม</label>
                        <input id="expiry_date_from"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-200 ease-in-out text-gray-500 text-base shadow-sm w-full"
                            type="date" name="expiry_date_from" value="{{ request('expiry_date_from') }}" />
                    </div>
                    <div class="flex-grow min-w-[180px]">
                        <label for="expiry_date_to"
                            class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันที่สิ้นสุด</label>
                        <input id="expiry_date_to"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-200 ease-in-out text-gray-500 text-base shadow-sm w-full"
                            type="date" name="expiry_date_to" value="{{ request('expiry_date_to') }}" />
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
                    </div>
                    <div class="mt-9">
                        {{-- เพิ่มปุ่มล้างการค้นหา --}}
                        @if (request('search') || request('expiry_date_from') || request('expiry_date_to'))
                            <a href="{{ route('newregis.productall') }}"
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
            </div>
            {{-- 1 --}}

            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-indigo-600 text-white text-left">
                                <th class="py-4 px-8 rounded-tl-2xl">ลำดับ</th>
                                <th class="py-4 px-8">ชื่อการค้า</th>
                                <th class="py-4 px-8">ชื่อสามัญ</th>
                                <th class="py-4 px-8">ผู้ขึ้นทะเบียน</th>
                                <th class="py-4 px-8">เลขที่ทะเบียน</th>
                                <th class="py-4 px-8">วันหมดอายุ</th>
                                <th class="py-4 px-8">สถานะ</th>
                                <th class="py-4 px-8 rounded-tr-2xl text-center">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paginatedProducts as $index => $product)
                                <tr class="border-b hover:bg-indigo-50 transition">
                                    <td class="py-4 px-8 font-semibold text-gray-700">
                                        {{ ($paginatedProducts->currentPage() - 1) * $paginatedProducts->perPage() + $index + 1 }}
                                    </td>
                                    <td class="py-4 px-8">{{ $product->trade_name ?? '' }}</td>
                                    <td class="py-4 px-8">{{ $product->chemicalImport->chemical_name_th ?? '' }}</td>
                                    <td class="py-4 px-8">{{ $product->importer ?? '' }}</td>
                                    <td class="py-4 px-8">{{ $product->registration_number ?? '' }}</td>
                                    <td class="py-4 px-8">
                                        {{ \Carbon\Carbon::parse($product->expired_license_number)->addYears(543)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4 px-8">
                                        @php
                                            $statusClass = '';
                                            $statusText = $product->status;
                                            if ($statusText == 'หมดอายุ') {
                                                $statusClass =
                                                    'inline-block rounded-full px-3 py-1 font-semibold text-white bg-red-500';
                                            } elseif ($statusText == 'ใกล้หมด') {
                                                $statusClass =
                                                    'inline-block rounded-full px-3 py-1 font-semibold text-gray-600 bg-yellow-300';
                                            } else {
                                                $statusClass =
                                                    'inline-block rounded-full px-3 py-1 font-semibold text-white bg-green-500'; // สถานะปกติ เช่น 'ใช้งานอยู่'
                                            }
                                        @endphp
                                        <span class="{{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-12 mx-auto">
                                        {{-- ปุ่มดูรายละเอียด --}}
                                        <div class="flex items-center gap-3 justify-center">
                                            @can('RegisterAll read')
                                                <a href="{{ route('newregis.showall', $product->id) }}"
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
                                            @can('RegisterAll update')
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

                                                @if ($incomplete || auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                                                    <a href="{{ route('newregis.editall', $product->id) }}"
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
                                            @can('RegisterAll delete')
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
                    {{ $paginatedProducts->links() }}
                </div>
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
