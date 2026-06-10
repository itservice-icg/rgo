<x-app-layout>
    <div>
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <div class="container mx-auto px-6 py-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-4xl font-extrabold text-gray-800 tracking-wide">
                        <span class="text-gray-600">แก้ไขสิทธิ์การใช้งาน</span>
                    </h1>
                </div>

                <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
                    @csrf
                    @method('put')

                    {{-- Input ที่เก็บค่ารวมตำแหน่ง+แผนก --}}
                    <input id="role_combined_name" type="hidden" name="name" value="{{ old('name', $role->name) }}"
                        readonly />

                    <div class="flex flex-wrap items-center gap-4 mt-6 w-6/12">
                        <label class="text-xl text-gray-600 whitespace-nowrap">ชื่อสิทธิ์การใช้งาน :</label>
                        <select name="position" id="select_position"
                            class="flex-1 min-w-[150px] p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- เลือกตำแหน่ง --</option>
                            <option value="ceo" {{ Str::contains($role->name, 'ceo') ? 'selected' : '' }}>
                                ผู้บริหาร</option>
                            <option value="manager" {{ Str::contains($role->name, 'manager') ? 'selected' : '' }}>
                                ผู้จัดการแผนก</option>
                            <option value="head" {{ Str::contains($role->name, 'head') ? 'selected' : '' }}>หัวหน้า
                            </option>
                            <option value="staff" {{ Str::contains($role->name, 'staff') ? 'selected' : '' }}>พนักงาน
                            </option>
                        </select>
                        <label class="text-xl text-gray-600 whitespace-nowrap">แผนก </label>
                        <select name="department" id="select_department"
                            class="flex-1 min-w-[150px] p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- เลือกแผนก --</option>
                            @php
                                $departments = [
                                    'Registration' => 'ทะเบียน',
                                    'InternationalProcurement' => 'จัดซื้อต่างประเทศ',
                                    'ResearchAndDevelopment' => 'วิจัยและพัฒนา',
                                    'Academic' => 'วิชาการ',
                                    'SalesDepartment' => 'ฝ่ายขาย',
                                    'IT' => 'เทคโนโลยีสารสนเทศ',
                                    'no' => 'ไม่มีสิทธิ์ดำเนินการ',
                                ];
                            @endphp
                            @foreach ($departments as $key => $label)
                                <option value="{{ $key }}"
                                    {{ Str::contains($role->name, $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror

                    <h3 class="text-xl mt-10 mb-4 text-gray-600">สิทธิ์การเข้าถึงแต่ละเมนู</h3>
                    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-indigo-600 text-white text-left">
                                        <th class="py-4 px-8 rounded-tl-2xl">เมนู</th>
                                        <th class="py-4 px-8 text-center">อ่าน</th>
                                        <th class="py-4 px-8 text-center">สร้าง</th>
                                        <th class="py-4 px-8 text-center">แก้ไข</th>
                                        <th class="py-4 px-8 rounded-tr-2xl text-center">ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $menu => $actions)
                                        @if (!in_array($menu, ['Report', 'Role', 'User', 'RegisterContinue']))
                                            <tr class="border-b hover:bg-gray-50 transition">
                                                <td class="py-4 px-8 font-semibold text-gray-700">{{ $menu }}
                                                </td>
                                                @foreach (['read', 'create', 'update', 'delete'] as $action)
                                                    <td class="py-4 px-8 text-center">
                                                        @if (isset($actions[$action]))
                                                            <input type="checkbox"
                                                                id="permission_{{ $actions[$action]->id }}"
                                                                name="permissions[]"
                                                                value="{{ $actions[$action]->id }}"
                                                                class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                                                @if ($role->permissions->contains('id', $actions[$action]->id)) checked @endif>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-center mt-8">
                        <a href="{{ route('admin.roles.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow transition duration-300 mr-2">
                            <i class="fa-solid fa-arrow-left mr-2"></i> ย้อนกลับ
                        </a>
                        <button type="submit"
                            class="bg-blue-800 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline shadow-md ml-2">
                            บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('menu-roles')?.classList.add('side-menu--active');
        document.addEventListener('DOMContentLoaded', function() {
            const selectPosition = document.getElementById('select_position');
            const selectDepartment = document.getElementById('select_department');
            const combinedNameInput = document.getElementById('role_combined_name');
            const originalName = combinedNameInput.value;

            function updateCombinedName() {
                const position = selectPosition.value;
                const department = selectDepartment.value;

                let combinedText = '';
                if (position) combinedText += position;
                if (department) {
                    if (combinedText) combinedText += ' ';
                    combinedText += department;
                }

                combinedNameInput.value = combinedText || originalName;
            }

            selectPosition.addEventListener('change', updateCombinedName);
            selectDepartment.addEventListener('change', updateCombinedName);
            updateCombinedName();
        });
    </script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'บันทึกสำเร็จ!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('admin.roles.index') }}";
                }
            });
        </script>
    @endif
</x-app-layout>
