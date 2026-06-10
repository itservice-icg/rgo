{{--
ไฟล์เมนูสำหรับมือถือที่ปรับปรุงดีไซน์แล้ว
- พื้นหลังของเมนูถูกควบคุมจากไฟล์ app.blade.php (ปกติจะเป็น bg-gray-800)
- เราจะใช้ Flexbox เพื่อจัดวางให้ส่วนโปรไฟล์อยู่ด้านล่างสุด
--}}
<div class="flex h-full flex-col p-4 text-white">

    {{-- ส่วนหัว: โลโก้และชื่อแอป --}}
    <div class="mb-8 flex items-center">
        <img alt="Logo" style="width: 200px; padding-left:20px;" src="{{ asset('images/logo.png') }}" />
        {{-- <h2 class="text-2xl font-bold">RGO</h2> --}}
    </div>

    {{-- รายการเมนูหลัก --}}
    <nav class="flex-grow">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-x-3 rounded-lg p-3 text-gray-300 transition-colors duration-200 hover:bg-blue-700 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-house-icon lucide-house">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path
                            d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                    <span>แดชบอร์ด</span>
                </a>
            </li>
            @canany('Inregister read', 'Inregister create', 'Inregister update', 'Inregister delete')
                <li>
                    <a href="{{ route('import.index') }}"
                        class="flex items-center gap-x-3 rounded-lg p-3 text-gray-300 transition-colors duration-200 hover:bg-blue-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-file-text-icon lucide-file-text">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M10 9H8" />
                            <path d="M16 13H8" />
                            <path d="M16 17H8" />
                        </svg>
                        <span>ทะเบียนนำเข้าทั้งหมด</span>
                    </a>
                </li>
            @endcanany
            @canany('RegisterManufacture read',
                'RegisterManufacture create',
                'RegisterManufacture update',
                'RegisterManufacture
                delete')
                <li>
                    <a href="{{ route('createproduct.index') }}"
                        class="flex items-center gap-x-3 rounded-lg p-3 text-gray-300 transition-colors duration-200 hover:bg-blue-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-clipboard-check-icon lucide-clipboard-check">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                            <path d="m9 14 2 2 4-4" />
                        </svg>
                        <span>ทะเบียนผลิตทั้งหมด</span>
                    </a>
                </li>
            @endcanany
            {{-- @canany('RegisterAll read', 'RegisterAll create', 'RegisterAll update', 'RegisterAll delete')
                <li>
                    <a href="{{ route('newregis.productall') }}"
                        class="flex items-center gap-x-3 rounded-lg p-3 text-gray-300 transition-colors duration-200 hover:bg-blue-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-files-icon lucide-files">
                            <path d="M20 7h-3a2 2 0 0 1-2-2V2" />
                            <path d="M9 18a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h7l4 4v10a2 2 0 0 1-2 2Z" />
                            <path d="M3 7.6v12.8A1.6 1.6 0 0 0 4.6 22h9.8" />
                        </svg>
                        <span>ทะเบียนสินค้าทั้งหมด</span>
                    </a>
                </li>
            @endcanany --}}
            @canany('RegisterNew read', 'RegisterNew create', 'RegisterNew update', 'RegisterNew delete')
                <li>
                    <a href="{{ route('newregis.index') }}"
                        class="flex items-center gap-x-3 rounded-lg p-3 text-gray-300 transition-colors duration-200 hover:bg-blue-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-clipboard-pen-icon lucide-clipboard-pen">
                            <rect width="8" height="4" x="8" y="2" rx="1" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5.5" />
                            <path d="M4 13.5V6a2 2 0 0 1 2-2h2" />
                            <path
                                d="M13.378 15.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                        </svg>
                        <span>ขึ้นทะเบียนใหม่</span>
                    </a>
                </li>
            @endcanany
            @canany('Role read', 'Role create', 'Role update', 'Role delete')
                <li>
                    <a href="{{ route('admin.roles.index') }}"
                        class="flex items-center gap-x-3 rounded-lg p-3 text-gray-300 transition-colors duration-200 hover:bg-blue-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check">
                            <path
                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                            <path d="m9 12 2 2 4-4" />
                        </svg>
                        <span>กำหนดสิทธื์</span>
                    </a>
                </li>
            @endcanany
            @canany('User read', 'User create', 'User update', 'User delete')
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-x-3 rounded-lg p-3 text-gray-300 transition-colors duration-200 hover:bg-blue-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                            <path d="M18 21a8 8 0 0 0-16 0" />
                            <circle cx="10" cy="8" r="5" />
                            <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                        </svg>
                        <span>ผู้ใช้งาน</span>
                    </a>
                </li>
            @endcanany
            @canany('User read', 'User create', 'User update', 'User delete')
                <li>
                    <a href="{{ route('company.index') }}"
                        class="flex items-center gap-x-3 rounded-lg p-3 text-gray-300 transition-colors duration-200 hover:bg-blue-700 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-building-icon lucide-building">
                            <rect width="16" height="20" x="4" y="2" rx="2" ry="2" />
                            <path d="M9 22v-4h6v4" />
                            <path d="M8 6h.01" />
                            <path d="M16 6h.01" />
                            <path d="M12 6h.01" />
                            <path d="M12 10h.01" />
                            <path d="M12 14h.01" />
                            <path d="M16 10h.01" />
                            <path d="M16 14h.01" />
                            <path d="M8 10h.01" />
                            <path d="M8 14h.01" />
                        </svg>
                        <span>บริษัท</span>
                    </a>
                </li>
            @endcanany
        </ul>
    </nav>

    {{-- ส่วนท้าย: โปรไฟล์ผู้ใช้และออกจากระบบ --}}
    <div class="mt-auto">
        <hr class="my-4 border-white">
        @php
            $profileImage = 'aa_user.png';
            if (auth()->user()) {
                if (auth()->user()->prefix == 'นาย') {
                    $profileImage = 'm.png';
                } elseif (auth()->user()->prefix == 'นาง' || auth()->user()->prefix == 'นางสาว') {
                    $profileImage = 'w.png';
                }
            }
        @endphp

        {{-- ข้อมูลผู้ใช้ --}}
        <div class="mb-2 flex items-center gap-x-3 p-3">
            <img class="h-10 w-10 rounded-full object-cover" src="/images/{{ $profileImage }}" {{-- เปลี่ยนเป็นรูปโปรไฟล์ของผู้ใช้ --}}
                alt="User Avatar">
            <div>
                <p class="text-sm font-semibold">{{ auth()->user()->name ?? 'Guest User' }}</p>
                <p class="text-xs text-gray-400">{{ auth()->user()->department ?? 'No Department' }}</p>
            </div>
        </div>

        {{-- ปุ่มออกจากระบบ --}}
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
            class="px-6 flex items-center gap-x-3 rounded-lg p-3 text-gray-300 transition-colors duration-200 hover:bg-blue-700 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out">
                <path d="m16 17 5-5-5-5" />
                <path d="M21 12H9" />
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            </svg>
            <span>ออกจากระบบ</span>
        </a>

        {{-- Form สำหรับ Logout --}}
        <form id="logout-form-mobile" action="{{ route('admin.logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
    <p class="mt-auto p-4">Copyright 2025 รุ่น 1.0.0</p>

</div>
<script>
    window.addEventListener('load', function() {
        const currentUrl = window.location.pathname;
        // ตรวจสอบและ active เมนูหลังโหลดหน้าเสร็จ
        if (currentUrl === "{{ route('admin.dashboard', [], false) }}") {
            document.getElementById('menu-dashboard')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('import.index', [], false) }}") {
            document.getElementById('menu-inregister')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('newregis.index', [], false) }}") {
            document.getElementById('menu-newregis')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('renewregis.index', [], false) }}") {
            document.getElementById('menu-renewregis')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('manufactureregis.index', [], false) }}") {
            document.getElementById('menu-manufacture')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('admin.permissions.index', [], false) }}") {
            document.getElementById('menu-permissions')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('admin.production.index', [], false) }}") {
            document.getElementById('menu-production')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('admin.roles.index', [], false) }}") {
            document.getElementById('menu-roles')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('admin.users.index', [], false) }}") {
            document.getElementById('menu-users')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('company.index', [], false) }}") {
            document.getElementById('menu-company')?.classList.add('side-menu--active');
        }
        if (currentUrl === "{{ route('newregis.productall', [], false) }}") {
            document.getElementById('menu-newregisall')?.classList.add('side-menu--active');
        }

    });
</script>
