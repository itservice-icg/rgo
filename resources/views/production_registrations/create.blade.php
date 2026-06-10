<x-app-layout>
    <div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-2xl space-y-10 mt-6">
        <h2 class="text-4xl font-extrabold text-gray-700 mb-8 pb-4 text-center border-b border-gray-300">
            แบบฟอร์มข้อมูลทะเบียนผลิต
        </h2>

        <form method="POST" action="{{ route('createproduct.store') }}" class="space-y-10">
            @csrf
            <div>
                <h3
                    class="text-2xl font-semibold text-white bg-gradient-to-r from-blue-400 to-indigo-400 px-4 py-3 rounded-t-md">
                    ข้อมูลการผลิตทั่วไป
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">บริษัทที่ขึ้นทะเบียนผลิต
                            <span class="text-red-500"> *</span>
                        </label>
                        <div class="dropdown" id="companyDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="companyBtn">
                                @if (old('company_id'))
                                    {{ $companies->firstWhere('id', old('company_id'))->full_name ?? '-- เลือก --' }}
                                @else
                                    -- เลือก --
                                @endif
                            </div>
                            <div class="dropdown-list" id="companyList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือก --</div>
                                @foreach ($companies as $company)
                                    @if ($company->type == 1 || $company->type == 2)
                                        <div class="dropdown-item" data-value="{{ $company->id }}">
                                            {{ $company->full_name }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="company_id" id="companyInput" value="{{ old('company_id') }}">
                        @error('company_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่ทะเบียนผลิต</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number') }}"
                            placeholder="ใส่เลขที่ทะเบียนผลิต"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('registration_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุทะเบียน</label>
                        <input type="date" name="expired_license_date" value="{{ old('expired_license_date') }}"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('expired_license_date')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อวัตถุอันตราย (ไทย)</label>
                        <input type="text" name="chemical_name_th" value="{{ old('chemical_name_th') }}"
                            placeholder="ใส่ชื่อวัตถุอันตราย (ไทย)"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('chemical_name_th')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อวัตถุอันตราย (อังกฤษ)</label>
                        <input type="text" name="chemical_name_en" value="{{ old('chemical_name_en') }}"
                            placeholder="ใส่ชื่อวัตถุอันตราย (อังกฤษ)"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('chemical_name_en')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">% และสูตร</label>
                        <input type="text" name="composition" value="{{ old('composition') }}"
                            placeholder="ใส่ % และสูตร"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('composition')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ผู้ผลิตและแหล่งผลิต</label>
                        <input type="text" name="manufacturer" value="{{ old('manufacturer') }}"
                            placeholder="ใส่ผู้ผลิตและแหล่งผลิต"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('manufacturer')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ประเภททะเบียน</label>
                        <div class="dropdown" id="registrationTypeDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="registrationTypeBtn">
                                @if (old('registration_type'))
                                    @php
                                        $registrationTypes = [
                                            'F' => 'F',
                                            'R' => 'R',
                                            'R(F)' => 'R(F)',
                                        ];
                                    @endphp
                                    {{ $registrationTypes[old('registration_type')] ?? '-- เลือก --' }}
                                @else
                                    -- เลือก --
                                @endif
                            </div>
                            <div class="dropdown-list" id="registrationTypeList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกประเภททะเบียน --</div>
                                <div class="dropdown-item" data-value="F">F</div>
                                <div class="dropdown-item" data-value="R">R</div>
                                <div class="dropdown-item" data-value="R(F)">R(F)</div>
                            </div>
                        </div>
                        <input type="hidden" name="registration_type" id="registrationTypeInput"
                            value="{{ old('registration_type') }}">
                        @error('registration_type')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อผู้นำเข้า</label>
                        <div class="dropdown" id="importerDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="importerBtn">
                                @if (old('importer'))
                                    {{ $companies->firstWhere('id', old('importer'))->full_name ?? '-- เลือก --' }}
                                @else
                                    -- เลือก --
                                @endif
                            </div>
                            <div class="dropdown-list" id="importerList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือก --</div>
                                @foreach ($companies as $company)
                                    {{-- @if ($company->id != 4) --}}
                                        <div class="dropdown-item" data-value="{{ $company->id }}">
                                            {{ $company->full_name }}
                                        </div>
                                    {{-- @endif --}}
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="importer" id="importerInput" value="{{ old('importer') }}">
                        @error('importer')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อผู้จำหน่าย</label>
                        <div class="dropdown" id="distributorDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="distributorBtn">
                                @if (old('distributor'))
                                    {{ $companies->firstWhere('id', old('distributor'))->full_name ?? '-- เลือก --' }}
                                @else
                                    -- เลือก --
                                @endif
                            </div>
                            <div class="dropdown-list" id="distributorList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือก --</div>
                                @foreach ($companies as $company)
                                    <div class="dropdown-item" data-value="{{ $company->id }}">
                                        {{ $company->full_name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="distributor" id="distributorInput"
                            value="{{ old('distributor') }}">
                        @error('distributor')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อการค้า</label>
                        <input type="text" name="trade_name" value="{{ old('trade_name') }}"
                            placeholder="ใส่ชื่อการค้า"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('trade_name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อการค้าที่</label>
                        <input type="text" name="trade_name_at" value="{{ old('trade_name_at') }}"
                            placeholder="ใส่ชื่อการค้าที่"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('trade_name_at')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชนิด</label>
                        <input type="text" name="type_production_registration"
                            value="{{ old('type_production_registration') }}" placeholder="ใส่ชื่อชนิด"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('type_production_registration')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">การใช้</label>
                        <input type="text" name="usage_production_registration"
                            value="{{ old('usage_production_registration') }}" placeholder="ใส่ชื่อการใช้"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('usage_production_registration')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">กลุ่มสาร</label>
                        <input type="text" name="group_of_substances" value="{{ old('group_of_substances') }}"
                            placeholder="ใส่กลุ่มสาร"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('group_of_substances')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">พืช</label>
                        <input type="text" name="plant" value="{{ old('plant') }}" placeholder="ใส่พืช"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('plant')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ศัตรูพืช</label>
                        <input type="text" name="pests" value="{{ old('pests') }}" placeholder="ใส่ศัตรูพืช"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('pests')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่ใบอนุญาตผลิต</label>
                        <input type="text" name="production_license_number"
                            value="{{ old('production_license_number') }}" placeholder="ใส่เลขที่ใบอนุญาตผลิต"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('production_license_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบอนุญาต</label>
                        <input type="date" name="production_license_expiry"
                            value="{{ old('production_license_expiry') }}"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('production_license_expiry')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="production_license_quantity"
                            class="mx-3 text-base block text-gray-700 mb-1 mt-3">ปริมาณผลิตใบอนุญาต</label>
                        <input type="number" name="production_license_quantity" id="production_license_quantity"
                            value="{{ old('production_license_quantity') }}" placeholder="ใส่ปริมาณผลิต"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('production_license_quantity')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ใบแจ้งครอบครอง วอ.2</label>
                        <input type="text" name="possession_form_wo2" value="{{ old('possession_form_wo2') }}"
                            placeholder="ใส่เลขที่ใบอนุญาต"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('possession_form_wo2')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบแจ้งครอบครอง
                            วอ.2</label>
                        <input type="text" name="possession_form_expiry"
                            value="{{ old('possession_form_expiry') }}"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('possession_form_expiry')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="registration_number_pass"
                            class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่ใบอนุญาตหมดอายุ</label>
                        <input type="text" name="registration_number_pass" id="registration_number_pass"
                            value="{{ old('registration_number_pass') }}" placeholder="ใส่เลขที่ใบอนุญาตหมดอายุ"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('registration_number_pass')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">หมดอายุเมื่อ</label>
                        <input type="text" name="expired_at" value="{{ old('expired_at') }}"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('expired_at')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div>
                <h3
                    class="text-2xl font-semibold text-white bg-gradient-to-r from-blue-400 to-indigo-400 px-4 py-3 rounded-t-md">
                    ข้อมูลอื่นๆ
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="md:col-span-2">
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">รายละเอียดขนาดบรรจุ</label>
                        <textarea name="packaging_size_details" placeholder="ใส่รายละเอียดขนาดบรรจุ"
                            class="w-full p-3 border rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2">{{ old('packaging_size_details') }}</textarea>
                        @error('packaging_size_details')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <input type="hidden" name="new_or_old" value="{{ old('new_or_old', true) }}">
                    <input type="hidden" name="step" value="{{ old('step', 'initial') }}">
                    <input type="hidden" name="status" value="{{ old('status', 'pending') }}">
                    <input type="hidden" name="is_active" value="{{ old('is_active', true) }}">
                    <input type="hidden" name="is_deleted" value="{{ old('is_deleted', false) }}">
                    <input type="hidden" name="progress" value="{{ old('progress', 0) }}">
                    <input type="hidden" name="sub_progress" value="{{ old('sub_progress', 0) }}">
                    <input type="hidden" name="created_by"
                        value="{{ old('created_by', auth()->user()->name ?? '') }}">
                    <input type="hidden" name="updated_by"
                        value="{{ old('updated_by', auth()->user()->name ?? '') }}">
                </div>
            </div>
            <div class="flex justify-center gap-4 pt-4">
                <a href="{{ route('createproduct.index') }}"
                    class="bg-gray-500 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg shadow-md flex items-center justify-center">
                    ยกเลิก
                </a>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md">
                    บันทึก
                </button>
            </div>
        </form>
    </div>
    <div id="customMessageBox"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="messageBoxTitle">แจ้งเตือน</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="messageBoxContent"></p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeMessageBox"
                        class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        ตกลง
                    </button>
                </div>
            </div>
        </div>
    </div>
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
                    window.location.href = "{{ route('createproduct.index') }}";
                }
            });
        </script>
    @endif

    <script>
        document.getElementById('menu-manufacture')?.classList.add('side-menu--active');

        document.addEventListener('DOMContentLoaded', () => {
            function setupDropdown(btnId, listId, inputId, oldValue = null) {
                const btn = document.getElementById(btnId);
                const list = document.getElementById(listId);
                const input = document.getElementById(inputId);
                const items = list.querySelectorAll('.dropdown-item');

                function updateBtn(label, value) {
                    btn.textContent = label;
                    if (value === "" || label.includes('--')) {
                        btn.classList.add('text-gray-500');
                    } else {
                        btn.classList.remove('text-gray-500');
                    }
                    input.value = value;
                }
                // Correctly set initial value based on old data
                if (oldValue) {
                    const match = [...items].find(i => i.dataset.value == oldValue);
                    if (match) updateBtn(match.textContent, match.dataset.value);
                } else {
                    const initial = [...items].find(item => item.dataset.value === "");
                    if (initial) updateBtn(initial.textContent, "");
                }


                btn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent document click from closing immediately
                    list.classList.toggle('open');
                    btn.classList.toggle('open');
                    // Close other dropdowns
                    document.querySelectorAll('.dropdown-list.open').forEach(openlist => {
                        if (openlist.id !== listId) {
                            openlist.classList.remove('open');
                            document.getElementById(openlist.id.replace('List', 'Btn')).classList
                                .remove('open');
                        }
                    });
                });

                items.forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const selectedValue = item.dataset.value;
                        const selectedLabel = item.textContent;

                        updateBtn(selectedLabel, selectedValue);
                        list.classList.remove('open');
                        btn.classList.remove('open');
                    });
                });

                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.dropdown')) {
                        list.classList.remove('open');
                        btn.classList.remove('open');
                    }
                });
            }
            const messageBox = document.getElementById('customMessageBox');
            const messageBoxContent = document.getElementById('messageBoxContent');
            const closeMessageBox = document.getElementById('closeMessageBox');

            function showMessageBox(message) {
                messageBoxContent.textContent = message;
                messageBox.classList.remove('hidden');
            }

            function hideMessageBox() {
                messageBox.classList.add('hidden');
            }

            closeMessageBox.addEventListener('click', hideMessageBox);

            // Setup for all dropdowns
            setupDropdown('companyBtn', 'companyList', 'companyInput', "{{ old('company_id') }}");
            setupDropdown('importerBtn', 'importerList', 'importerInput', "{{ old('importer') }}");
            setupDropdown('distributorBtn', 'distributorList', 'distributorInput', "{{ old('distributor') }}");
            // There were no typeProductionBtn/List/Input and usageProductionBtn/List/Input in the provided HTML.
            // If you intend to have them, you'll need to add the corresponding HTML structure.
            // setupDropdown('typeProductionBtn', 'typeProductionList', 'typeProductionInput',
            //     "{{ old('type_production_registration') }}");
            // setupDropdown('usageProductionBtn', 'usageProductionList', 'usageProductionInput',
            //     "{{ old('usage_production_registration') }}");

            setupDropdown('registrationTypeBtn', 'registrationTypeList', 'registrationTypeInput',
                "{{ old('registration_type') }}");
        });
    </script>

    <style>
        * {
            box-sizing: border-box;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-size: 16px;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-btn {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #edeff3;
            border-radius: 9999px;
            background-color: #fff;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-btn:after {
            content: "▾";
            font-size: 26px;
            color: #7f838a;
            margin-left: 8px;
        }

        .dropdown-list {
            position: absolute;
            top: 105%;
            left: 0;
            width: 100%;
            background-color: #fff;
            border: 1px solid #edeff3;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 10;
            display: none;
            max-height: 230px;
            overflow-y: auto;
        }

        .dropdown-list::-webkit-scrollbar {
            width: 6px;
        }

        .dropdown-list::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 3px;
        }

        .dropdown-list::-webkit-scrollbar-track {
            background-color: #f1f5f9;
        }

        .dropdown-list.open {
            display: block;
        }

        .dropdown-item {
            padding: 12px 16px;
            cursor: pointer;
            border-radius: 20px;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background-color: #e0f2fe;
        }

        .hidden-input {
            display: none;
        }
    </style>
</x-app-layout>
