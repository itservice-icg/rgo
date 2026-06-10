<x-app-layout>
    <div class="p-8 mx-auto mt-6 space-y-10 bg-white shadow-lg max-w-7xl rounded-2xl">
        <h2 class="pb-4 mb-8 text-4xl font-extrabold text-center text-gray-700 border-b border-gray-300">
            แบบฟอร์มขึ้นทะเบียนใหม่
        </h2>

        {{-- <form method="POST" action="{{ route('newregis.store') }}" class="space-y-10"> --}}
        <form id="newRegisForm" method="POST" action="{{ route('newregis.store') }}" class="space-y-10">
            @csrf

            {{-- General Information Section --}}
            <div>
                <h3
                    class="px-4 py-3 text-2xl font-semibold text-white bg-gradient-to-r from-blue-400 to-indigo-400 rounded-t-md">
                    ข้อมูลทั่วไป
                </h3>
                <div class="grid grid-cols-2 gap-6 mt-4 md:grid-cols-2">
                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">บริษัทที่ขึ้นทะเบียน<span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="registrantDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="registrantBtn">--
                                เลือกบริษัทที่ขึ้นทะเบียน --</div>
                            <div class="dropdown-list" id="registrantList">
                                <div class="text-gray-500 dropdown-item" data-value="">-- เลือกบริษัทที่ขึ้นทะเบียน --
                                </div>
                                @foreach ($companies as $company)
                                    {{-- @if ($company->id != 4) --}}
                                        <div class="dropdown-item" data-value="{{ $company->full_name }}">
                                            {{ $company->full_name }}
                                        </div>
                                    {{-- @endif --}}
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="registrant" id="registrantInput" value="{{ old('registrant') }}">
                        @error('registrant')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">เปอร์เซ็นต์และสูตร</label>
                        <input type="text" name="composition" value="{{ old('composition') }}"
                            placeholder="เปอร์เซ็นต์และสูตร"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('composition')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ชื่อวัตถุอันตราย (ไทย) --}}
                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">
                            ชื่อวัตถุอันตราย (ไทย) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="chemical_name_th" value="{{ old('chemical_name_th') }}"
                            placeholder="ใส่ชื่อวัตถุอันตราย (ไทย)"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('chemical_name_th')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- ชื่อวัตถุอันตราย (อังกฤษ) --}}
                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">
                            ชื่อวัตถุอันตราย (อังกฤษ) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="chemical_name_en" value="{{ old('chemical_name_en') }}"
                            placeholder="ใส่ชื่อวัตถุอันตราย (อังกฤษ)"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('chemical_name_en')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ผู้ผลิตและแหล่งผลิต <span
                                class="text-red-500"> *</span></label>
                        <input type="text"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            name="manufacturer" value="{{ old('manufacturer') }}" placeholder="ผู้ผลิตและแหล่งผลิต" />
                        @error('manufacturer')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ประเภททะเบียน <span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="registrationTypeDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="registrationTypeBtn">--
                                เลือกประเภททะเบียน --</div>
                            <div class="dropdown-list" id="registrationTypeList">
                                <div class="text-gray-500 dropdown-item" data-value="">-- เลือกประเภททะเบียน --</div>
                                <div class="dropdown-item" data-value="T : นำเข้าสารเข้มข้น">T : นำเข้าสารเข้มข้น</div>
                                <div class="dropdown-item" data-value="I : นำเข้าสำเร็จรูป">I : นำเข้าสำเร็จรูป</div>
                                <div class="dropdown-item" data-value="F : ผลิตผสมปรุงแต่ง">F : ผลิตผสมปรุงแต่ง</div>
                                <div class="dropdown-item" data-value="R : ผลิตแบ่งบรรจุ (จากนำเข้า)">R :
                                    ผลิตแบ่งบรรจุ (จากนำเข้า)</div>
                                <div class="dropdown-item" data-value="R(F) : ผลิตแบ่งบรรจุ (จากผสมปรุงแต่ง)">R(F) :
                                    ผลิตแบ่งบรรจุ (จากผสมปรุงแต่ง)</div>
                                <div class="dropdown-item" data-value="F(E) : ผลิตเพื่อส่งออก">F(E) : ผลิตเพื่อส่งออก
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="registration_type" id="registrationTypeInput"
                            value="{{ old('registration_type') }}">
                        @error('registration_type')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อผู้นำเข้า <span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="importerDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="importerBtn">--
                                เลือกผู้นำเข้า --</div>
                            <div class="dropdown-list" id="importerList">
                                <div class="text-gray-500 dropdown-item" data-value="">-- เลือกผู้นำเข้า --</div>
                                @foreach ($companies as $company)
                                    {{-- @if ($company->id != 4) --}}
                                        <div class="dropdown-item" data-value="{{ $company->full_name }}">
                                            {{ $company->full_name }}
                                        </div>
                                    {{-- @endif --}}
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="importer" id="importerInput" value="{{ old('importer') }}">
                        @error('importer')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อผู้จำหน่าย <span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="distributorDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="distributorBtn">--
                                เลือกผู้จำหน่าย --</div>
                            <div class="dropdown-list" id="distributorList">
                                <div class="text-gray-500 dropdown-item" data-value="">-- เลือกผู้จำหน่าย --</div>
                                @foreach ($companies as $company)
                                    <div class="dropdown-item" data-value="{{ $company->full_name }}">
                                        {{ $company->full_name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="distributor" id="distributorInput"
                            value="{{ old('distributor') }}">
                        @error('distributor')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อการค้า <span
                                class="text-red-500"> *</span></label>
                        <input type="text"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            name="trade_name" value="{{ old('trade_name') }}" placeholder="ชื่อการค้า" />
                        @error('trade_name')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อการค้าที่ <span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="namePositionDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="namePositionBtn">--
                                เลือกชื่อการที่ --</div>
                            <div class="dropdown-list" id="namePositionList">
                                <div class="text-gray-500 dropdown-item" data-value="">-- เลือกชื่อการที่ --</div>
                                <div class="dropdown-item" data-value="T">T</div>
                                <div class="dropdown-item" data-value="-">-</div>
                                <div class="dropdown-item" data-value="1">1</div>
                                <div class="dropdown-item" data-value="2">2</div>
                                <div class="dropdown-item" data-value="3">3</div>
                            </div>
                        </div>
                        <input type="hidden" name="name_position" id="namePositionInput"
                            value="{{ old('name_position') }}">
                        @error('name_position')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชนิดทะเบียน <span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="typeRegistrationDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="typeRegistrationBtn">--
                                เลือกชนิดทะเบียน --</div>
                            <div class="dropdown-list" id="typeRegistrationList">
                                <div class="text-gray-500 dropdown-item" data-value="">-- เลือกชนิดทะเบียน --</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 1">ชนิดที่ 1</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 2">ชนิดที่ 2</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 3">ชนิดที่ 3</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 4">ชนิดที่ 4</div>
                            </div>
                        </div>
                        <input type="hidden" name="type_registration" id="typeRegistrationInput"
                            value="{{ old('type_registration') }}">
                        @error('type_registration')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ประเภทของการใช้ <span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="typeOfUseDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="typeOfUseBtn">--
                                เลือกประเภทของการใช้ --</div>
                            <div class="dropdown-list" id="typeOfUseList">
                                <div class="text-gray-500 dropdown-item" data-value="">-- เลือกประเภทของการใช้ --
                                </div>
                                <div class="dropdown-item" data-value="A : Acaricide (สารกำจัดไรศัตรูพืช)">A :
                                    Acaricide (สารกำจัดไรศัตรูพืช)</div>
                                <div class="dropdown-item" data-value="F : Fungicide (สารป้องกันกำจัดโรคพืช)">F :
                                    Fungicide (สารป้องกันกำจัดโรคพืช)</div>
                                <div class="dropdown-item" data-value="H : Herbicide (สารป้องกันกำจัดโรคพืช)">H :
                                    Herbicide (สารกำจัดวัชพืช)</div>
                                <div class="dropdown-item" data-value="I : Insecticide (สารกำจัดแมลง)">I : Insecticide
                                    (สารกำจัดแมลง)</div>
                                <div class="dropdown-item" data-value="M : Molluscicide (สารกำจัดหอย)">M :
                                    Molluscicide (สารกำจัดหอย)</div>
                                <div class="dropdown-item" data-value="N : Nematicide (สารกำจัดไส้เดือนฝอย)">N :
                                    Nematicide (สารกำจัดไส้เดือนฝอย)</div>
                                <div class="dropdown-item"
                                    data-value="P : PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)">P :
                                    PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)</div>
                                <div class="dropdown-item" data-value="R : Rodenticide (สารกำจัดหนู)">R : Rodenticide
                                    (สารกำจัดหนู)</div>
                            </div>
                        </div>
                        <input type="hidden" name="type_of_use" id="typeOfUseInput"
                            value="{{ old('type_of_use') }}">
                        @error('type_of_use')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">กลุ่มสาร</label>
                        <input type="text" name="group_of_substances" value="{{ old('group_of_substances') }}"
                            placeholder="ใส่กลุ่มสาร"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('group_of_substances')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">พืช</label>
                        <input type="text" name="plant" value="{{ old('plant') }}" placeholder="ใส่พืช"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('plant')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ศัตรูพืช</label>
                        <input type="text" name="pests" value="{{ old('pests') }}" placeholder="ใส่ศัตรูพืช"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('pests')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
{{-- 
                    <div>
                        <label for="quantity" class="block mx-3 mt-3 mb-1 text-base text-gray-700">ปริมาณ</label>
                        <input type="text" name="quantity" id="quantity" value="{{ old('quantity') }}"
                            placeholder="ใส่ปริมาณ"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('quantity')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div> --}}



                    <div class="md:col-span-2">
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">รายละเอียดขนาดบรรจุ</label>
                        <textarea name="packaging_size_details"
                            class="w-full p-3 border rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2">{{ old('packaging_size_details') }}</textarea>
                        @error('packaging_size_details')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex flex-col gap-6 md:flex-row">
                            {{-- วันที่ยื่นคำขอ --}}
                            <div class="w-full md:w-1/3">
                                <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">วันที่ยื่นคำขอ</label>
                                <input type="text" id="date_submit_request" name="date_submit_request"
                                    class="w-full p-3 border rounded-full date-th focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="วว/ดด/ปปปป" value="{{ old('date_submit_request') }}"
                                    autocomplete="off">
                                @error('date_submit_request')
                                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/3">
                                <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">เลขที่รับคำขอ</label>
                                <input type="text" name="request_number_1" value="{{ old('request_number_1') }}"
                                    class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('request_number_1')
                                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/3">
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex flex-col gap-6 md:flex-row">
                            <div class="w-full md:w-1/3">
                                <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">วันที่ยื่น Phase
                                    III</label>
                                <input type="text" id="date_request_phase_3" name="date_request_phase_3"
                                    autocomplete="off"
                                    class="w-full p-3 border rounded-full date-th focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="วว/ดด/ปปปป" value="{{ old('date_request_phase_3') }}">
                                @error('date_request_phase_3')
                                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                @enderror


                            </div>
                            <div class="w-full md:w-1/3">
                                <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">เลข Phase III</label>
                                <input type="text" value="{{ old('request_number_phase_3') }}"
                                    class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    name="request_number_phase_3" />
                                @error('request_number_phase_3')
                                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full md:w-1/3">
                                <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">เลข Phase I</label>
                                <input type="text" name="request_number_phase_1"
                                    value="{{ old('request_number_phase_1') }}"
                                    class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('request_number_phase_1')
                                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">อื่นๆ (ระบุ)</label>
                        <textarea name="remarks" class="w-full p-3 border rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="2">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-center gap-4 pt-4">
                <a href="{{ route('newregis.index') }}"
                    class="flex items-center justify-center px-6 py-2 font-bold text-white bg-gray-500 rounded-lg shadow-md hover:bg-gray-500">
                    ยกเลิก
                </a>
                <button type="submit"
                    class="px-6 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-700">
                    บันทึก
                </button>
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ===== 1) flatpickr dd/mm/yyyy =====
            flatpickr(".date-th", {
                allowInput: true,
                locale: "th",
                dateFormat: "d/m/Y",

                // ตรวจ input manual
                parseDate: (datestr, format) => {
                    if (!datestr) return null;
                    const parts = datestr.split('/');
                    if (parts.length === 3) {
                        let [dd, mm, yyyy] = parts.map(n => parseInt(n, 10));
                        // ถ้ากรอกเป็น พ.ศ. (>2400) → แปลงเป็น ค.ศ.
                        if (yyyy > 2400) yyyy = yyyy - 543;
                        return new Date(yyyy, mm - 1, dd);
                    }
                    return flatpickr.parseDate(datestr, format);
                },

                // เวลาเลือกหรือโหลด ให้โชว์เป็น พ.ศ.
                onReady: (selectedDates, dateStr, instance) => {
                    showBE(instance);
                },
                onChange: (selectedDates, dateStr, instance) => {
                    showBE(instance);
                },
                onOpen: (selectedDates, dateStr, instance) => {
                    showBE(instance);
                }
            });

            function showBE(instance) {
                const selDate = instance.selectedDates[0];
                if (!selDate) return;
                const dd = String(selDate.getDate()).padStart(2, "0");
                const mm = String(selDate.getMonth() + 1).padStart(2, "0");
                const yyyyBE = selDate.getFullYear() + 543; // แสดงเฉพาะ พ.ศ.
                instance.input.value = `${dd}/${mm}/${yyyyBE}`;
            }

            // ก่อน submit → แปลงกลับเป็น ค.ศ. ISO
            const form = document.getElementById("newRegisForm");
            if (form) {
                form.addEventListener("submit", () => {
                    document.querySelectorAll(".date-th").forEach(input => {
                        if (input.value.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
                            let [dd, mm, yyyyBE] = input.value.split("/");
                            let yyyyAD = parseInt(yyyyBE, 10);
                            if (yyyyAD > 2400) yyyyAD -= 543; // พ.ศ. → ค.ศ.
                            input.value = `${yyyyAD}-${mm}-${dd}`;
                        }
                    });
                });
            }

            // ===== 2) จำกัดการพิมพ์ชื่อวัตถุอันตราย =====
            const chemThInput = document.querySelector('input[name="chemical_name_th"]');
            const chemEnInput = document.querySelector('input[name="chemical_name_en"]');

            // ไทย: อักษรไทย, ตัวเลข, - , , . , +, เว้นวรรค
            chemThInput?.addEventListener('input', () => {
                const cleaned = chemThInput.value.replace(/[^ก-๙0-9\-,. +]/g, '');
                if (cleaned !== chemThInput.value) chemThInput.value = cleaned;
            });

            // อังกฤษ: A-Z a-z, ตัวเลข, - , , . , +, เว้นวรรค
            chemEnInput?.addEventListener('input', () => {
                const cleaned = chemEnInput.value.replace(/[^A-Za-z0-9\-,. +]/g, '');
                if (cleaned !== chemEnInput.value) chemEnInput.value = cleaned;
            });
        });
    </script>


    <script>
        document.getElementById('menu-newregis')?.classList.add('side-menu--active');

        let typingTimer;
        const delay = 300;
        const listElement = document.getElementById("autocomplete-list");

        const productSearchInput = document.getElementById("productSearch");
        const hazardousNameThInput = document.getElementById("hazardous_name_th");
        const formulationRatioInput = document.getElementById("formulation_ratio");
        const chemicalImportsId = document.getElementById("chemical_imports_id");

        function clearFields() {
            hazardousNameThInput.value = "";
            formulationRatioInput.value = "";
            chemicalImportsId.value = "";
            // Add any other fields you want to clear here
        }

        function autocompleteSearch(keyword) {
            clearTimeout(typingTimer);

            if (!keyword.trim()) {
                listElement.innerHTML = "";
                listElement.classList.add("hidden");
                clearFields();
                return;
            }

            typingTimer = setTimeout(() => {
                fetch('/api/products/search-list?name=' + encodeURIComponent(keyword))
                    .then(res => res.json())
                    .then(data => {
                        listElement.innerHTML = "";
                        listElement.classList.remove("hidden");

                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(item => {
                                const li = document.createElement("li");
                                li.className = "px-4 py-2 hover:bg-blue-100 cursor-pointer";
                                li.textContent = item.chemical_name_th;
                                li.addEventListener("click", () => {
                                    fillProductData(item);
                                    listElement.classList.add("hidden");
                                });
                                listElement.appendChild(li);
                            });
                        } else {
                            // กรณีไม่พบข้อมูล
                            const li = document.createElement("li");
                            li.className = "px-4 py-2 text-gray-500 text-center cursor-default";
                            li.textContent = "ไม่พบข้อมูล";
                            listElement.appendChild(li);
                            clearFields(); // เคลียร์ค่าเมื่อไม่พบข้อมูล
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        listElement.innerHTML =
                            '<li class="p-2 text-red-500">เกิดข้อผิดพลาดในการดึงข้อมูล</li>';
                        listElement.classList.remove("hidden");
                        clearFields();
                    });
            }, delay);
        }

        function fillProductData(product) {
            productSearchInput.value = product.chemical_name_th || "";
            hazardousNameThInput.value = product.chemical_name_th || ""; // เติมค่าใน hidden field
            // formulationRatioInput.value = product.formula || "";
            chemicalImportsId.value = product.id || "";
            // ถ้ามีการนำ expiry_date กลับมาใช้ ให้ uncomment บรรทัดนี้
            // expiryDateInput.value = product.expiry_date || "";
        }

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

                // Initial state
                const initial = [...items].find(item => item.dataset.value === "");
                if (initial) updateBtn(initial.textContent, "");

                // Restore old value from Laravel if available
                if (oldValue) {
                    const match = [...items].find(i => i.dataset.value == oldValue);
                    if (match) updateBtn(match.textContent, match.dataset.value);
                }

                btn.addEventListener('click', (event) => {
                    event.stopPropagation(); // Prevent document click from closing immediately
                    list.classList.toggle('open');
                    btn.classList.toggle('open');
                });

                items.forEach(item => {
                    item.addEventListener('click', () => {
                        updateBtn(item.textContent, item.dataset.value);
                        list.classList.remove('open');
                        btn.classList.remove('open');
                    });
                });

                document.addEventListener('click', (e) => {
                    if (!btn.closest('.dropdown').contains(e.target) && !list.contains(e.target)) {
                        list.classList.remove('open');
                        btn.classList.remove('open');
                    }
                });
            }

            // Setup for all dropdowns
            setupDropdown('registrantBtn', 'registrantList', 'registrantInput', "{{ old('registrant') }}");
            setupDropdown('typeRegistrationBtn', 'typeRegistrationList', 'typeRegistrationInput',
                "{{ old('type_registration') }}");
            setupDropdown('registrationTypeBtn', 'registrationTypeList', 'registrationTypeInput',
                "{{ old('registration_type') }}");
            setupDropdown('namePositionBtn', 'namePositionList', 'namePositionInput',
                "{{ old('name_position') }}");
            setupDropdown('importerBtn', 'importerList', 'importerInput', "{{ old('importer') }}");
            setupDropdown('distributorBtn', 'distributorList', 'distributorInput', "{{ old('distributor') }}");
            setupDropdown('typeOfUseBtn', 'typeOfUseList', 'typeOfUseInput', "{{ old('type_of_use') }}");
        });
    </script>
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
                    window.location.href = "{{ route('newregis.index') }}";
                }
            })
        </script>
    @endif

    <style>
        * {
            box-sizing: border-box;
        }

        .dropdown-container {
            max-width: 300px;
            margin: auto;
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
            /* แสดงได้ประมาณ 3-4 รายการ */
            overflow-y: auto;
            /* เพิ่ม scrollbar ถ้าเกิน */
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

        .autocomplete-list {
            position: absolute;
            top: 100%;
            /* อยู่ใต้ input field */
            left: 0;
            width: 100%;
            /* กว้างเท่า input field */
        }

        .autocomplete-item {
            color: #333;
            /* สีตัวอักษรเข้ม */
            background-color: #fff;
            /* พื้นหลังสีขาว */
        }
    </style>
</x-app-layout>
