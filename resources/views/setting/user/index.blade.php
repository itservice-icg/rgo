<x-app-layout>
    <div>
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <div class="container px-6 py-6 mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-4xl font-extrabold tracking-wide text-gray-600">
                        จัดการผู้ใช้งาน
                        {{-- <span class="inline-flex items-center gap-2">
                            <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span> --}}
                    </h1>
                    @can('User create')
                        <a href="{{ route('admin.users.create') }}"
                            class="px-4 py-2 font-bold text-white transition duration-300 bg-blue-500 rounded-lg shadow-md hover:bg-blue-700">
                            + สร้างผู้ใช้งาน
                        </a>
                    @endcan
                </div>

                <div class="overflow-hidden bg-white border border-gray-200 rounded-2xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="text-left text-white bg-indigo-600">
                                    <th class="px-4 py-3 rounded-tl-2xl">ชื่อผู้ใช้งาน</th>
                                    {{-- <th class="px-4 py-3">ชื่อผู้ใช้งาน</th> --}}
                                    <th class="px-4 py-3">อีเมล์เข้าสู่ระบบ</th>
                                    <th class="w-64 px-3 py-3 sm:px-4">สิทธิ์</th>
                                    <th class="px-4 py-3">ล็อกอินล่าสุด</th>
                                    <th class="px-4 py-3">สถานะใช้งาน</th>
                                    <th class="px-4 py-3 text-right rounded-tr-2xl">การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-light text-gray-700">
                                @can('User read')
                                    @php
                                        function translateRoleName($roleName)
                                        {
                                            $positions = [
                                                'manager' => 'ผู้จัดการแผนก',
                                                'head' => 'หัวหน้า',
                                                'staff' => 'พนักงาน',
                                                'ceo' => 'ผู้บริหาร',
                                            ];
                                            $departments = [
                                                'Registration' => 'ทะเบียน',
                                                'InternationalProcurement' => 'จัดซื้อต่างประเทศ',
                                                'ResearchAndDevelopment' => 'วิจัยและพัฒนา',
                                                'Academic' => 'วิชาการ',
                                                'SalesDepartment' => 'ฝ่ายขาย',
                                                'IT' => 'เทคโนโลยีสารสนเทศ',
                                                'no' => 'ไม่มีสิทธิ์ดำเนินการ',
                                            ];
                                            $parts = explode(' ', $roleName);
                                            $position = $positions[$parts[0]] ?? $parts[0];
                                            $department = $departments[$parts[1] ?? ''] ?? ($parts[1] ?? '');
                                            return trim("$position $department");
                                        }

                                    @endphp
                                    @foreach ($users as $user)
                                        <tr class="transition border-b hover:bg-indigo-50">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="font-semibold">{{ $user->name }}</span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="font-semibold">{{ $user->email }}</span>
                                            </td>
                                            <td class="w-64 px-3 py-3 sm:px-4">
                                                <div class="flex flex-wrap gap-1.5 sm:gap-2 max-w-xs">
                                                    @foreach ($user->roles as $role)
                                                        <span
                                                            class="inline-block max-w-full px-2 py-1 text-xs font-bold leading-snug text-white break-words whitespace-normal bg-gray-500 rounded-full">
                                                            {{ translateRoleName($role->name) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @php
                                                    $lastLogin = $user->last_login_at;

                                                    $login = [
                                                        'text' => 'ยังไม่เคยล็อกอิน',
                                                        'badge' => 'bg-slate-50 text-slate-500 border-slate-200',
                                                        'dot' => 'bg-slate-300',
                                                        'status' => 'never',
                                                    ];

                                                    if ($lastLogin) {
                                                        $minutes = $lastLogin->diffInMinutes();
                                                        $hours = $lastLogin->diffInHours();
                                                        $days = $lastLogin->diffInDays();

                                                        if ($hours < 1) {
                                                            $login = [
                                                                'text' => "เมื่อ {$minutes} นาทีที่แล้ว",
                                                                'badge' => 'bg-blue-50 text-blue-600 border-blue-200',
                                                                'dot' => 'bg-blue-400',
                                                                'status' => 'online',
                                                            ];
                                                        } elseif ($hours <= 6) {
                                                            $login = [
                                                                'text' => "เมื่อ {$hours} ชั่วโมงที่แล้ว",
                                                                'badge' => 'bg-blue-50 text-blue-600 border-blue-200',
                                                                'dot' => 'bg-blue-400',
                                                                'status' => 'online',
                                                            ];
                                                        } elseif ($hours <= 24) {
                                                            $login = [
                                                                'text' => "เมื่อ {$hours} ชั่วโมงที่แล้ว",
                                                                'badge' => 'bg-blue-50 text-blue-600 border-blue-200',
                                                                'dot' => 'bg-blue-400',
                                                                'status' => 'idle',
                                                            ];
                                                        } else {
                                                            $login = [
                                                                'text' => "เมื่อ {$days} วันที่แล้ว",
                                                                'badge' => 'bg-blue-50 text-blue-600 border-blue-200',
                                                                'dot' => 'bg-blue-400',
                                                                'status' => 'offline',
                                                            ];
                                                        }
                                                    }
                                                @endphp

                                                <div
                                                    class="inline-flex items-center rounded-full border px-3 py-1.5 shadow-sm transition hover:shadow-md {{ $login['badge'] }}">
                                                    <span class="text-sm font-semibold">
                                                        {{ $login['text'] }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @php
                                                    $isActive = $user->employment_status === 'active';

                                                    $employment = [
                                                        'label' => $isActive ? 'ใช้งาน' : 'ไม่ใช้งาน',
                                                        'badge' => $isActive
                                                            ? 'bg-green-50 text-green-700 border-green-200'
                                                            : 'bg-red-50 text-red-700 border-red-200',
                                                        'dot' => $isActive ? 'bg-green-500' : 'bg-red-500',
                                                    ];
                                                @endphp
                                                <span
                                                    class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold transition hover:shadow-sm {{ $employment['badge'] }}">
                                                    <span class="relative flex h-2.5 w-2.5">

                                                        <span
                                                            class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $employment['dot'] }} opacity-40"></span>

                                                        <span
                                                            class="relative inline-flex h-2.5 w-2.5 rounded-full {{ $employment['dot'] }}"></span>

                                                    </span>

                                                    {{ $employment['label'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    @can('User update')
                                                        <a href="{{ route('admin.users.edit', $user->id) }}"
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

                                                    @can('User delete')
                                                        <button onclick="confirmDelete({{ $user->id }})"
                                                            class="inline-flex items-center justify-center p-2 text-white transition-all duration-200 bg-red-500 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                            title="ลบ">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.92a2.25 2.25 0 0 1-2.244-2.077L4.74 5.959m1.049-.165c.51-.158 1.029-.28 1.563-.35L12 4.75m-4.78 2.152A.75.75 0 0 1 9 6.75h6m-3 0V4.5m-2.25 4.5h.008v.008H9.75V9Zm0 0H9.75Zm4.5 0h.008v.008H14.25V9Z" />
                                                            </svg>
                                                        </button>

                                                        <form id="delete-form-{{ $user->id }}"
                                                            action="{{ route('admin.users.destroy', $user->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('delete')
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endcan
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-white border-t border-gray-100 rounded-b-2xl">
                        @if ($users->hasPages())
                            <div class="text-center">
                                <nav class="inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($users->onFirstPage())
                                        <span
                                            class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-l-md">&laquo;</span>
                                    @else
                                        <a href="{{ $users->previousPageUrl() }}"
                                            class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-l-md">&laquo;</a>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                        @if ($page == $users->currentPage())
                                            <span
                                                class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 border border-indigo-600">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($users->hasMorePages())
                                        <a href="{{ $users->nextPageUrl() }}"
                                            class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-r-md">&raquo;</a>
                                    @else
                                        <span
                                            class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-300 cursor-not-allowed rounded-r-md">&raquo;</span>
                                    @endif
                                </nav>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

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
</x-app-layout>
