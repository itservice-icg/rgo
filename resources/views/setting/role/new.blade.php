<x-app-layout>
    <div>
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <div class="container mx-auto px-6 py-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-4xl font-extrabold text-gray-800 tracking-wide">
                        <span class="text-gray-600">สร้างสิทธิ์การใช้งาน</span>
                    </h1>

                </div>
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf
                    @method('post')
                    <div class="mb-4">

                        <div class="flex flex-wrap items-center gap-4">
                            <input id="role_combined_name" type="hidden" name="name" value="{{ old('name') }}"
                                placeholder="ชื่อสิทธิ์การใช้งาน"
                                class="bg-gray-200 flex-1 min-w-xl px-4 py-2 rounded-lg border border-gray-300 focus:blue-green-500 focus:ring-2 focus:ring-gray-200 text-gray-800 shadow transition placeholder-gray-400"
                                readonly />
                        </div>

                        <div class="flex flex-wrap items-center gap-4 mt-6 w-6/12">
                            <label class="text-xl text-gray-600 whitespace-nowrap">ชื่อสิทธิ์การใช้งาน :</label>
                            <select name="position" id="select_position"
                                class="flex-1 min-w-[150px] p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- เลือกตำแหน่ง --</option>
                                <option value="ceo">ผู้บริหาร</option>
                                <option value="manager">ผู้จัดการแผนก</option>
                                <option value="head">หัวหน้า</option>
                                <option value="staff">พนักงาน</option>
                            </select>
                             <label class="text-xl text-gray-600 whitespace-nowrap">แผนก </label>
                            <select name="department" id="select_department"
                                class="flex-1 min-w-[150px] p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- เลือกแผนก --</option>
                                <option value="Registration"
                                    {{ old('department') == 'Registration' ? 'selected' : '' }}>ทะเบียน</option>
                                <option value="InternationalProcurement"
                                    {{ old('department') == 'InternationalProcurement' ? 'selected' : '' }}>
                                    จัดซื้อต่างประเทศ</option>
                                <option value="ResearchAndDevelopment"
                                    {{ old('department') == 'ResearchAndDevelopment' ? 'selected' : '' }}>วิจัยและพัฒนา
                                </option>
                                <option value="Academic" {{ old('department') == 'Academic' ? 'selected' : '' }}>วิชาการ
                                </option>
                                <option value="SalesDepartment"
                                    {{ old('department') == 'SalesDepartment' ? 'selected' : '' }}>ฝ่ายขาย</option>
                                <option value="IT" {{ old('department') == 'IT' ? 'selected' : '' }}>
                                    เทคโนโลยีสารสนเทศ</option>
                                <option value="no" {{ old('department') == 'IT' ? 'selected' : '' }}>
                                    ไม่มีสิทธิ์ดำเนินการ</option>
                            </select>
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <h3 class="text-xl mt-10 mb-4 text-gray-600">สิทธิ์การเข้าถึงแต่ละเมนู </h3>
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
                                                                @if (is_array(old('permissions')) && in_array($actions[$action]->id, old('permissions'))) checked @endif>
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

    </div>

    <script>
        document.getElementById('menu-roles')?.classList.add('side-menu--active');
        document.addEventListener('DOMContentLoaded', function() {
            const selectPosition = document.getElementById('select_position');
            const selectDepartment = document.getElementById('select_department');
            const combinedNameInput = document.getElementById('role_combined_name');

            function updateCombinedName() {
                const positionText = selectPosition.value;
                const departmentText = selectDepartment.value;

                let combinedText = '';
                if (positionText && positionText !== '-- เลือกตำแหน่ง --') {
                    combinedText += positionText;
                }
                if (departmentText && departmentText !== '-- เลือกแผนก --') {
                    if (combinedText) { // ถ้ามีตำแหน่งแล้ว ให้เพิ่มช่องว่าง
                        combinedText += ' ';
                    }
                    combinedText += departmentText;
                }

                combinedNameInput.value = combinedText;
            }

            selectPosition.addEventListener('change', updateCombinedName);
            selectDepartment.addEventListener('change', updateCombinedName);
            updateCombinedName();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'บันทึกสำเร็จ!',
                // text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href = "{{ route('admin.roles.index') }}";
                }
            })
        </script>
    @endif
</x-app-layout>
