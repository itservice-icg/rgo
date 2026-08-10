<x-app-layout>
    <main class="flex-1 overflow-x-hidden overflow-y-auto">
        <div class="container px-6 py-6 mx-auto">
            <h1 class="mt-5 mb-10 text-4xl font-extrabold tracking-wide text-center text-indigo-700">
                รายละเอียดการขึ้นทะเบียนสินค้าใหม่
                {{-- {{ dd(auth()->user()->getRoleNames()) }} --}}
            </h1>
            <div class="p-8 overflow-hidden bg-white border border-gray-200 shadow-lg rounded-2xl">
                {{-- รายละเอียดข้อมูลยา --}}
                @if (can_manage_new_registration_steps(auth()->user()))
                    @if ($errors->any())
                        <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded"
                            role="alert">
                            <strong class="font-bold">เกิดข้อผิดพลาด!</strong>
                            <span class="block sm:inline">โปรดตรวจสอบข้อมูลที่คุณกรอกอีกครั้ง</span>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ส่วนนี้สำหรับข้อผิดพลาดทั่วไปที่มาจาก Controller's catch block (เช่น database error) --}}
                    @if (session('error'))
                        <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- เพื่อแสดงข้อผิดพลาดที่ส่งมาจาก catch block เช่น 'error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ...' --}}
                    @if (session('error'))
                        <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('newregis.update', $drug->id) }}" class="space-y-10">
                        @csrf
                        @method('PUT')
                        <div>
                            <h3
                                class="px-4 py-3 text-2xl font-semibold text-white bg-gradient-to-r from-blue-400 to-indigo-400 rounded-t-md">
                                ข้อมูลทั่วไป
                            </h3>

                            <div class="grid grid-cols-2 gap-6 mt-4 md:grid-cols-2">
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">เลขที่ทะเบียน</label>
                                    <input type="text" id="registration_number" name="registration_number"
                                        value="{{ old('registration_number', $drug->registration_number) }}"
                                        placeholder="เช่น 123-2568"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        inputmode="numeric" pattern="^\d+-\d{4}$"
                                        title="รูปแบบต้องเป็น ตัวเลขใดๆ ตามด้วย - และเลขท้าย 4 หลัก เช่น 123-2568"
                                        oninput="filterRegisNo(this)" />
                                    @error('registration_number')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="w-full md:w-1/3">
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">วันหมดอายุ</label>
                                    <input type="text" name="production_license_expiry"
                                        id="production_license_expiry"
                                        class="w-full p-3 pl-2 border rounded-full date-th focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="{{ old('production_license_expiry', $drug->production_license_expiry ? \Carbon\Carbon::parse($drug->production_license_expiry)->addYears(543)->format('d/m/Y') : '') }}"
                                        placeholder="วว/ดด/ปปปป">
                                    @error('production_license_expiry')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block mx-3 mt-3 mb-1 text-base text-gray-700">บริษัทที่ขึ้นทะเบียน<span
                                            class="text-red-500"> *</span></label>
                                    <div class="dropdown" id="registrantDropdown">
                                        <div style="height: 50px;" class="text-gray-500 dropdown-btn"
                                            id="registrantBtn">--
                                            เลือกบริษัทที่ขึ้นทะเบียน --</div>
                                        <div class="dropdown-list" id="registrantList">
                                            <div class="text-gray-500 dropdown-item" data-value="">--
                                                เลือกบริษัทที่ขึ้นทะเบียน --</div>
                                            @foreach ($companies as $company)
                                                {{-- @if ($company->id != 4) --}}
                                                <div class="dropdown-item" data-value="{{ $company->full_name }}">
                                                    {{ $company->full_name }}
                                                </div>
                                                {{-- @endif --}}
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="registrant" id="registrantInput"
                                        value="{{ old('registrant', $drug->registrant) }}">
                                    @error('registrant')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block mx-3 mt-3 mb-1 text-base text-gray-700">เปอร์เซ็นต์และสูตร</label>
                                    <input type="text" name="composition"
                                        value="{{ old('composition', $drug->composition) }}"
                                        placeholder="ใส่ เปอร์เซ็นต์และสูตร"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('composition')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อวัตถุอันตราย
                                        (ไทย)</label>
                                    <input type="text" name="chemical_name_th"
                                        value="{{ old('chemical_name_th', $drug->chemical_name_th) }}"
                                        placeholder="ใส่ชื่อวัตถุอันตราย (ไทย)"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('chemical_name_th')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อวัตถุอันตราย
                                        (อังกฤษ)</label>
                                    <input type="text" name="chemical_name_en"
                                        value="{{ old('chemical_name_en', $drug->chemical_name_en) }}"
                                        placeholder="ใส่ชื่อวัตถุอันตราย (อังกฤษ)"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('chemical_name_en')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ผู้ผลิตและแหล่งผลิต
                                        <span class="text-red-500"> *</span></label>
                                    <input type="text"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        name="manufacturer" value="{{ old('manufacturer', $drug->manufacturer) }}" />
                                    @error('manufacturer')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ประเภททะเบียน <span
                                            class="text-red-500"> *</span></label>
                                    <div class="dropdown" id="registrationTypeDropdown">
                                        <div style="height: 50px;" class="text-gray-500 dropdown-btn"
                                            id="registrationTypeBtn">--
                                            เลือกประเภททะเบียน --</div>
                                        <div class="dropdown-list" id="registrationTypeList">
                                            <div class="text-gray-500 dropdown-item" data-value="">--
                                                เลือกประเภททะเบียน --</div>
                                            <div class="dropdown-item" data-value="T : นำเข้าสารเข้มข้น">T :
                                                นำเข้าสารเข้มข้น</div>
                                            <div class="dropdown-item" data-value="I : นำเข้าสำเร็จรูป">I :
                                                นำเข้าสำเร็จรูป</div>
                                            <div class="dropdown-item" data-value="F : ผลิตผสมปรุงแต่ง">F :
                                                ผลิตผสมปรุงแต่ง</div>
                                            <div class="dropdown-item" data-value="R : ผลิตแบ่งบรรจุ (จากนำเข้า)">R :
                                                ผลิตแบ่งบรรจุ (จากนำเข้า)</div>
                                            <div class="dropdown-item"
                                                data-value="R(F) : ผลิตแบ่งบรรจุ (จากผสมปรุงแต่ง)">R(F) : ผลิตแบ่งบรรจุ
                                                (จากผสมปรุงแต่ง)</div>
                                            <div class="dropdown-item" data-value="F(E) : ผลิตเพื่อส่งออก">F(E) :
                                                ผลิตเพื่อส่งออก</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="registration_type" id="registrationTypeInput"
                                        value="{{ old('registration_type', $drug->registration_type) }}">
                                    @error('registration_type')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อผู้นำเข้า <span
                                            class="text-red-500"> *</span></label>
                                    <div class="dropdown" id="importerDropdown">
                                        <div style="height: 50px;" class="text-gray-500 dropdown-btn"
                                            id="importerBtn">--
                                            เลือกผู้นำเข้า --</div>
                                        <div class="dropdown-list" id="importerList">
                                            <div class="text-gray-500 dropdown-item" data-value="">-- เลือกผู้นำเข้า
                                                --</div>
                                            @foreach ($companies as $company)
                                                {{-- @if ($company->id != 4) --}}
                                                <div class="dropdown-item" data-value="{{ $company->full_name }}">
                                                    {{ $company->full_name }}
                                                </div>
                                                {{-- @endif --}}
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="importer" id="importerInput"
                                        value="{{ old('importer', $drug->importer) }}">
                                    @error('importer')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อผู้จำหน่าย <span
                                            class="text-red-500"> *</span></label>
                                    <div class="dropdown" id="distributorDropdown">
                                        <div style="height: 50px;" class="text-gray-500 dropdown-btn"
                                            id="distributorBtn">-- เลือกผู้จำหน่าย --</div>
                                        <div class="dropdown-list" id="distributorList">
                                            <div class="text-gray-500 dropdown-item" data-value="">-- เลือกผู้จำหน่าย
                                                --</div>
                                            @foreach ($companies as $company)
                                                <div class="dropdown-item" data-value="{{ $company->full_name }}">
                                                    {{ $company->full_name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="distributor" id="distributorInput"
                                        value="{{ old('distributor', $drug->distributor) }}">
                                    @error('distributor')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อการค้า <span
                                            class="text-red-500"> *</span></label>
                                    <input type="text"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        name="trade_name" value="{{ old('trade_name', $drug->trade_name) }}" />
                                    @error('trade_name')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชื่อการค้าที่ <span
                                            class="text-red-500"> *</span></label>
                                    <div class="dropdown" id="namePositionDropdown">
                                        <div style="height: 50px;" class="text-gray-500 dropdown-btn"
                                            id="namePositionBtn">-- เลือกชื่อการที่ --</div>
                                        <div class="dropdown-list" id="namePositionList">
                                            <div class="text-gray-500 dropdown-item" data-value="">-- เลือกชื่การที่
                                                --</div>
                                            <div class="dropdown-item" data-value="T">T</div>
                                            <div class="dropdown-item" data-value="-">-</div>
                                            <div class="dropdown-item" data-value="1">1</div>
                                            <div class="dropdown-item" data-value="2">2</div>
                                            <div class="dropdown-item" data-value="3">3</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="name_position" id="namePositionInput"
                                        value="{{ old('name_position', $drug->name_position) }}">
                                    @error('name_position')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ชนิดทะเบียน <span
                                            class="text-red-500"> *</span></label>
                                    <div class="dropdown" id="typeRegistrationDropdown">
                                        <div style="height: 50px;" class="text-gray-500 dropdown-btn"
                                            id="typeRegistrationBtn">--
                                            เลือกชนิดทะเบียน --</div>
                                        <div class="dropdown-list" id="typeRegistrationList">
                                            <div class="text-gray-500 dropdown-item" data-value="">--
                                                เลือกชนิดทะเบียน --</div>
                                            <div class="dropdown-item" data-value="ชนิดที่ 1">ชนิดที่ 1</div>
                                            <div class="dropdown-item" data-value="ชนิดที่ 2">ชนิดที่ 2</div>
                                            <div class="dropdown-item" data-value="ชนิดที่ 3">ชนิดที่ 3</div>
                                            <div class="dropdown-item" data-value="ชนิดที่ 4">ชนิดที่ 4</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="type_registration" id="typeRegistrationInput"
                                        value="{{ old('type_registration', $drug->type_registration) }}">
                                    @error('type_registration')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ประเภทของการใช้ <span
                                            class="text-red-500"> *</span></label>
                                    <div class="dropdown" id="typeOfUseDropdown">
                                        <div style="height: 50px;" class="text-gray-500 dropdown-btn"
                                            id="typeOfUseBtn">--
                                            เลือกประเภทของการใช้ --</div>
                                        <div class="dropdown-list" id="typeOfUseList">
                                            <div class="text-gray-500 dropdown-item" data-value="">--
                                                เลือกประเภทของการใช้ --</div>
                                            <div class="dropdown-item"
                                                data-value="A : Acaricide (สารกำจัดไรศัตรูพืช)">A :
                                                Acaricide (สารกำจัดไรศัตรูพืช)</div>
                                            <div class="dropdown-item"
                                                data-value="F : Fungicide (สารป้องกันกำจัดโรคพืช)">F :
                                                Fungicide (สารป้องกันกำจัดโรคพืช)</div>
                                            <div class="dropdown-item" data-value="H : Herbicide (สารกำจัดวัชพืช)">H :
                                                Herbicide (สารกำจัดวัชพืช)</div>
                                            <div class="dropdown-item" data-value="I : Insecticide (สารกำจัดแมลง)">I :
                                                Insecticide (สารกำจัดแมลง)</div>
                                            <div class="dropdown-item" data-value="M : Molluscicide (สารกำจัดหอย)">M :
                                                Molluscicide (สารกำจัดหอย)</div>
                                            <div class="dropdown-item"
                                                data-value="N : Nematicide (สารกำจัดไส้เดือนฝอย)">N :
                                                Nematicide (สารกำจัดไส้เดือนฝอย)</div>
                                            <div class="dropdown-item"
                                                data-value="P : PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)">
                                                P :
                                                PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)</div>
                                            <div class="dropdown-item" data-value="R : Rodenticide (สารกำจัดหนู)">R :
                                                Rodenticide (สารกำจัดหนู)</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="type_of_use" id="typeOfUseInput"
                                        value="{{ old('type_of_use', $drug->type_of_use) }}">
                                    @error('type_of_use')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">กลุ่มสาร</label>
                                    <input type="text" name="group_of_substances"
                                        value="{{ old('group_of_substances', $drug->group_of_substances) }}"
                                        placeholder="ใส่กลุ่มสาร"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('group_of_substances')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">พืช</label>
                                    <input type="text" name="plant" value="{{ old('plant', $drug->plant) }}"
                                        placeholder="ใส่พืช"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('plant')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ศัตรูพืช</label>
                                    <input type="text" name="pests" value="{{ old('pests', $drug->pests) }}"
                                        placeholder="ใส่ศัตรูพืช"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('pests')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">ปริมาณ</label>
                                    <input type="text" name="quantity"
                                        value="{{ old('quantity', $drug->quantity) }}" placeholder="ใส่ปริมาณ"
                                        class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('quantity')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label
                                        class="block mx-3 mt-3 mb-1 text-base text-gray-700">รายละเอียดขนาดบรรจุ</label>
                                    <textarea name="packaging_size_details"
                                        class="w-full p-3 border rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2">{{ old('packaging_size_details', $drug->packaging_size_details) }}</textarea>
                                    @error('packaging_size_details')
                                        <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <div class="flex flex-col gap-6 md:flex-row">

                                        {{-- วันที่ยื่นคำขอ --}}
                                        <div class="w-full md:w-1/3">
                                            <label
                                                class="block mx-3 mt-3 mb-1 text-base text-gray-700">วันที่ยื่นคำขอ</label>
                                            <input type="text" name="date_submit_request" id="date_submit_request"
                                                class="w-full p-3 pl-2 border rounded-full date-th focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                value="{{ old('date_submit_request', $drug->date_submit_request ? \Carbon\Carbon::parse($drug->date_submit_request)->addYears(543)->format('d/m/Y') : '') }}"
                                                placeholder="วว/ดด/ปปปป" autocomplete="off">
                                            @error('date_submit_request')
                                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label
                                                class="block mx-3 mt-3 mb-1 text-base text-gray-700">เลขที่รับคำขอ</label>
                                            <input type="text" name="request_number_1"
                                                value="{{ old('request_number_1', $drug->request_number_1) }}"
                                                class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            @error('request_number_1')
                                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="w-full md:w-1/3"></div>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="flex flex-col gap-6 md:flex-row">
                                        <div class="w-full md:w-1/3">
                                            <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">วันที่ยื่น
                                                Phase
                                                III</label>
                                            <input type="text" name="date_request_phase_3"
                                                id="date_request_phase_3"
                                                class="w-full p-3 pl-2 border rounded-full date-th focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                value="{{ old('date_request_phase_3', $drug->date_request_phase_3 ? \Carbon\Carbon::parse($drug->date_request_phase_3)->addYears(543)->format('d/m/Y') : '') }}"
                                                placeholder="วว/ดด/ปปปป">
                                            @error('date_request_phase_3')
                                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>


                                        <div class="w-full md:w-1/3">
                                            <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">เลข Phase
                                                III</label>
                                            <input type="text" name="request_number_phase_3"
                                                value="{{ old('request_number_phase_3', $drug->request_number_phase_3) }}"
                                                class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            @error('request_number_phase_3')
                                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block mx-3 mt-3 mb-1 text-base text-gray-700">เลข Phase
                                                I</label>
                                            <input type="text" name="request_number_phase_1"
                                                value="{{ old('request_number_phase_1', $drug->request_number_phase_1) }}"
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
                                        rows="2">{{ old('remarks', $drug->remarks) }}</textarea>
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


                    <script>
                        function filterRegisNo(el) {
                            // แปลง en-dash/em-dash เป็น hyphen ปกติ และคงไว้เฉพาะตัวเลขกับ '-'
                            let v = el.value.replace(/[–—]/g, '-').replace(/[^\d-]/g, '');

                            // ให้มี '-' ได้ตัวเดียว
                            const firstDash = v.indexOf('-');
                            if (firstDash !== -1) {
                                v = v.slice(0, firstDash + 1) + v.slice(firstDash + 1).replace(/-/g, '');
                            }

                            // ตัดความยาวส่วนหน้าไม่เกิน 4 และส่วนหลังไม่เกิน 4
                            const parts = v.split('-');
                            if (parts.length === 1) {
                                // ยังไม่มี '-', จำกัดหน้าสุด 4 หลัก
                                parts[0] = parts[0].slice(0, 4);
                                v = parts[0];
                            } else {
                                parts[0] = parts[0].slice(0, 4); // 3–4 หลักจะตรวจจริงด้วย pattern/regex อีกชั้น
                                parts[1] = parts[1].slice(0, 4);
                                v = parts[0] + '-' + parts[1];
                            }

                            // จำกัดความยาวรวมไม่เกิน 9 (เช่น 1234-2568)
                            el.value = v.slice(0, 9);
                        }
                    </script>



                    <script>
                        document.getElementById('menu-newregis')?.classList.add('side-menu--active');

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

                                const initial = [...items].find(item => item.dataset.value === "");
                                if (initial) updateBtn(initial.textContent, "");

                                if (oldValue) {
                                    const match = [...items].find(i => i.dataset.value == oldValue);
                                    if (match) updateBtn(match.textContent, match.dataset.value);
                                }

                                btn.addEventListener('click', (event) => {
                                    event.stopPropagation();
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
                                    if (!btn.contains(e.target)) {
                                        list.classList.remove('open');
                                        btn.classList.remove('open');
                                    }
                                });
                            }

                            setupDropdown('registrantBtn', 'registrantList', 'registrantInput',
                                "{{ old('registrant', $drug->registrant) }}");
                            setupDropdown('typeRegistrationBtn', 'typeRegistrationList', 'typeRegistrationInput',
                                "{{ old('type_registration', $drug->type_registration) }}");
                            setupDropdown('registrationTypeBtn', 'registrationTypeList', 'registrationTypeInput',
                                "{{ old('registration_type', $drug->registration_type) }}");
                            setupDropdown('namePositionBtn', 'namePositionList', 'namePositionInput',
                                "{{ old('name_position', $drug->name_position) }}");
                            setupDropdown('importerBtn', 'importerList', 'importerInput',
                                "{{ old('importer', $drug->importer) }}");
                            setupDropdown('distributorBtn', 'distributorList', 'distributorInput',
                                "{{ old('distributor', $drug->distributor) }}");
                            setupDropdown('typeOfUseBtn', 'typeOfUseList', 'typeOfUseInput',
                                "{{ old('type_of_use', $drug->type_of_use) }}");
                        });
                    </script>

                    <style>
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

                        .dropdown-list.open {
                            display: block;
                        }

                        .dropdown-item {
                            padding: 12px 16px;
                            cursor: pointer;
                            border-radius: 20px;
                        }

                        .dropdown-item:hover {
                            background-color: #e0f2fe;
                        }
                    </style>
                @endif

                {{-- ไทม์ไลน์การขึ้นทะเบียน --}}

                <div class="mt-8">
                    @php
                        // ข้อมูลขั้นตอนทั้งหมด
                        $subStepsAll = [
                            1 => [
                                'title' => 'คณะ PDC อนุมัติให้ดำเนินการขึ้นทะเบียน',
                                'items' => [
                                    'จัดซื้อต่างประเทศ' => ['ทะเบียน', 'ใบอนุญาตในประเทศผู้ผลิต', 'เอกสารอนุญาตอื่นๆ'],
                                    'ฝ่ายขาย' => ['รายชื่อผู้ขอขึ้นทะเบียน', 'ชื่อการค้า', 'Packing'],
                                    'วิจัยและพัฒนา' => ['เตรียมข้อมูลผลิตตัวอย่าง'],
                                    'แผนกวิชาการ' => ['แผนการทดลอง', 'หนังสือขอยกเว้น PHI', 'แผน PHI'],
                                    'แผนกทะเบียน' => [
                                        'ตรวจสอบเอกสารขึ้นทะเบียน',
                                        'ตรวจชื่อการค้า',
                                        'ขอใบอนุญาตนำเข้าตัวอย่าง',
                                    ],
                                ],
                            ],
                            2 => [
                                'title' => 'นำเข้าตัวอย่าง',
                                'items' => [
                                    'จัดซื้อต่างประเทศ' => ['ประสานเพื่อนำเข้าตัวอย่าง'],
                                    'วิจัยและพัฒนา' => ['จัดเตรียมตัวอย่าง'],
                                    'แผนกทะเบียน' => ['ส่งตัวอย่างให้วิจัยและพัฒนา', 'ขอใบอนุญาตผลิต', 'ตรวจ COA'],
                                ],
                            ],
                            3 => [
                                'title' => 'ส่งข้อมูลศึกษาความเป็นพิษ (Tox)',
                                'items' => [
                                    'จัดซื้อต่างประเทศ' => ['ประสานเพื่อส่งออกตัวอย่าง', 'Data requirement จากผู้ผลิต'],
                                    'แผนกทะเบียน' => [
                                        'ประสานส่งออกตัวอย่าง',
                                        'ตรวจผลการศึกษา Tox',
                                        'เตรียมข้อมูลประกอบการยื่นขอขึ้นทะเบียน',
                                    ],
                                ],
                            ],
                            4 => [
                                'title' => 'ยื่นคำขอขึ้นทะเบียน',
                                'items' => [
                                    'จัดซื้อต่างประเทศ' => [
                                        'ทะเบียน',
                                        'ใบอนุญาตในประเทศผู้ผลิต (ส่ง DOA)',
                                        'เอกสารอนุญาตอื่นๆ',
                                    ],
                                    'วิจัยและพัฒนา' => ['เตรียมและส่งตัวอย่างให้ทะเบียน'],
                                    'แผนกวิชาการ' => ['ติดตามแผนการทดลอง Eff+ PHI (ถ้ามี)'],
                                    'แผนกทะเบียน' => [
                                        'รวบรวมข้อมูลและเอกสารยื่นขอขขึ้นทะเบียนตามที่ DOA กำหนด',
                                        'ติดตามผล Phase I',
                                    ],
                                ],
                            ],
                            5 => [
                                'title' => 'แผนการทดลอง Eff, PHI (ถ้ามี) + Phase I+ ผลวิเคราะห์ (อนุมัติ)',
                                'items' => [
                                    'แผนกทะเบียน' => [
                                        'รวบรวมข้อมูล',
                                        'เอกสารยื่นขอขึ้นทะเบียนตามที่ DOA กำหนด',
                                        'ติดตามผล Phase I',
                                    ],
                                    'แผนกวิชาการ' => [
                                        'รับแผนการทดลอง Eff, PHI (ถ้ามี)',
                                        'ทำการทดลอง Eff และผลการทดลอง PHI (ถ้ามี)',
                                    ],
                                    'วิจัยและพัฒนา' => [
                                        'รับทราบผลวิเคราะห์ในกรณีที่วิเคราะห์ไม่ผ่าน',
                                        'ส่งตัวอย่างให้ทะเบียนเพื่อยื่นขอขึ้นทะเบียนใหม่',
                                    ],
                                ],
                            ],
                            6 => [
                                'title' => 'ยื่น Phase III (ผลการทดลอง Eff, PHI (ถ้ามี)อนุมัติ+ผลวิเคราะห์อนุมัติ)',
                                'items' => [
                                    'แผนกวิชาการ' => ['ติดตามผลการทดลอง Eff', 'ผลการทดลอง PHI (ถ้ามี) จนอนุมัติ'],
                                    'แผนกทะเบียน' => [
                                        'รวบรวมข้อมูล',
                                        ' ผล Eff +ผล PHI (ถ้ามี) ที่อนุมัติ',
                                        ' เอกสารตามที่ DOA กำหนด และติดตามผล Phase III',
                                    ],
                                    'จัดซื้อต่างประเทศ' => [
                                        'ประสานขอเอกสารจากผู้ผลิตเพิ่มเติมในกรณีที่ผลพิจารณา Tox Phase III ไม่ผ่าน',
                                    ],
                                ],
                            ],
                            7 => [
                                'title' => 'Phase III อนุมัติ (ยื่นเอกสารเข้าประชุมพิจารณาขึ้นทะเบียน)',
                                'items' => [
                                    'แผนกทะเบียน' => [
                                        'แผนกทะเบียนได้รับผล Tox Phase III ที่อนุมัติ
                    ทำการรวบรวมข้อมูลเอกสารยื่นขอเข้าประชุมพิจารณาขึ้นทะเบียนใหม่',
                                    ],
                                ],
                            ],
                            8 => [
                                'title' => 'ยื่นขอออกทะเบียน',
                                'items' => [
                                    'ฝ่ายขาย' => ['สรุป packing และจัดทำ A/W'],
                                    'แผนกทะเบียน' => [
                                        'จัดเตรียมคำขอขึ้นทะเบียน',
                                        'ร่างฉลาก',
                                        'มติพิจารณาขึ้นทะเบียน',
                                        ' A/W',
                                    ],
                                ],
                            ],
                        ];

                        // ไม่ต้องซ่อนขั้นตอนแผนกวิชาการตาม "แผนการทดลอง" อีกต่อไป
                        $hideAcademicSteps = false;

                        // เก็บ flag ว่าขั้นตอนใดทำครบแล้วบ้าง
                        $completedStepFlags = [];
                        foreach ($subStepsAll as $step => $data) {
                            $departments = collect($data['items']);
                            if ($hideAcademicSteps && in_array($step, [4, 5, 6])) {
                                $departments = $departments->reject(fn($_, $dept) => $dept === 'แผนกวิชาการ');
                            }
                            $totalSubSteps = $departments->flatten()->count();
                            $completedCount = $drug->stepSubSteps($step)->whereNotNull('checked_at')->count();
                            $completedStepFlags[$step] = $totalSubSteps > 0 && $completedCount === $totalSubSteps;
                        }

                        function mapDepartment($enDept)
                        {
                            return [
                                'InternationalProcurement' => 'จัดซื้อต่างประเทศ',
                                'SalesDepartment' => 'ฝ่ายขาย',
                                'ResearchAndDevelopment' => 'วิจัยและพัฒนา',
                                'Academic' => 'แผนกวิชาการ',
                                'Registration' => 'แผนกทะเบียน',
                                'IT' => 'เทคโนโลยีสารสนเทศ',
                            ][$enDept] ?? $enDept;
                        }

                        $mappedUserDept = normalize_department_name(auth()->user()->department);
                        $isAdminRole = can_manage_new_registration_steps(auth()->user());

                        // สรุปสถานะความคืบหน้าปัจจุบัน (ใช้ logic เดียวกับหน้า index)
                        $overall_show_step_number = 0;
                        $overall_number_step_number = 0;

                        // Build per-step info from step_summary
                        $stepsInfoLocal = [];
                        for ($i = 1; $i <= 8; $i++) {
                            $s = $drug->step_summary[$i] ?? null;
                            $total = 0;
                            $unchecked = 0;
                            if ($s) {
                                $total = is_numeric($s->last_index) ? ($s->last_index + 1) : 0;
                                $unchecked = (int) $s->unchecked_count;
                            }
                            $checked = $total - $unchecked;
                            $stepsInfoLocal[$i] = ['summary' => $s, 'total' => $total, 'unchecked' => $unchecked, 'checked' => $checked];
                        }

                        // find latest step that has any checked items
                        $lastCheckedStepLocal = 0;
                        for ($i = 1; $i <= 8; $i++) {
                            if ($stepsInfoLocal[$i]['checked'] > 0) $lastCheckedStepLocal = $i;
                        }

                        if ($lastCheckedStepLocal > 0) {
                            $displayStep = $lastCheckedStepLocal;
                        } else {
                            // find latest fully completed step
                            $lastFullyCompletedLocal = 0;
                            for ($i = 1; $i <= 8; $i++) {
                                if ($stepsInfoLocal[$i]['total'] > 0 && $stepsInfoLocal[$i]['unchecked'] == 0) $lastFullyCompletedLocal = $i;
                            }
                            $displayStep = $lastFullyCompletedLocal > 0 ? $lastFullyCompletedLocal : 1;
                        }

                        $overall_number_step_number = $displayStep;

                        // compute overall percent for displayStep
                        $sdisp = $drug->step_summary[$displayStep] ?? null;
                        $uncheckedDisp = $sdisp->unchecked_count ?? null;
                        $isPlanNoneLocal = $drug->isPlanNone ?? 0;
                        switch ($displayStep) {
                            case 1:
                                if ($sdisp && $uncheckedDisp >= 12) $overall_show_step_number = 0; else $overall_show_step_number = 12.5;
                                break;
                            case 2:
                                $overall_show_step_number = 25; break;
                            case 3:
                                $overall_show_step_number = 37.5; break;
                            case 4:
                                if ($sdisp && $uncheckedDisp == 1 && $isPlanNoneLocal == 1) { $overall_number_step_number = 5; $overall_show_step_number = 62.5; } else { $overall_show_step_number = 50; }
                                break;
                            case 5:
                                if ($sdisp && $uncheckedDisp == 2 && $isPlanNoneLocal == 1) { $overall_number_step_number = 6; $overall_show_step_number = 75; } else { $overall_show_step_number = 62.5; }
                                break;
                            case 6:
                                if ($sdisp && $uncheckedDisp == 2 && $isPlanNoneLocal == 1) { $overall_number_step_number = 7; $overall_show_step_number = 87.5; } else { $overall_show_step_number = 75; }
                                break;
                            case 7:
                                $overall_show_step_number = 87.5; break;
                            case 8:
                                if ($sdisp && $sdisp->unchecked_count == 0) $overall_show_step_number = 100; else $overall_show_step_number = 90; break;
                            default:
                                $overall_show_step_number = 0;
                        }
                    @endphp

                    @foreach ($subStepsAll as $stepNumber => $stepData)
                        @php
                            $stepTitle = $stepData['title'];
                            $allDepartments = $stepData['items'];

                            // กรองแผนกวิชาการออกถ้าจำเป็น
                            if ($hideAcademicSteps && in_array($stepNumber, [4, 5, 6])) {
                                $allDepartments = collect($allDepartments)
                                    ->reject(fn($_, $dept) => $dept === 'แผนกวิชาการ')
                                    ->all();
                            }

                            $departments = $isAdminRole
                                ? $allDepartments
                                : collect($allDepartments)
                                    ->filter(fn($_, $deptName) => normalize_department_name($deptName) === $mappedUserDept)
                                    ->all();

                            $savedSubSteps = $drug->stepSubSteps($stepNumber)->get()->keyBy('sub_step_index');

                            $allSubLabels = collect($departments)->flatten()->values()->all();
                            $totalSub = count($allSubLabels);
                            $completedCount = $savedSubSteps->whereNotNull('checked_at')->count();
                            $percent = $totalSub > 0 ? round(($completedCount / $totalSub) * 100, 2) : 0;

                            $visibleSubStepIndexes = [];
                            $visibleSubStepCount = 0;
                            $indexCursor = 0;
                            foreach ($allDepartments as $dept => $subItems) {
                                $skipThisDept =
                                    $hideAcademicSteps &&
                                    in_array($stepNumber, [4, 5, 6]) &&
                                    $dept === 'แผนกวิชาการ';
                                if ($skipThisDept) {
                                    $indexCursor += count($subItems);
                                    continue;
                                }

                                $showDept = $isAdminRole || normalize_department_name($dept) === $mappedUserDept;
                                if ($showDept) {
                                    foreach ($subItems as $label) {
                                        $visibleSubStepIndexes[] = $indexCursor;
                                        $visibleSubStepCount++;
                                        $indexCursor++;
                                    }
                                } else {
                                    $indexCursor += count($subItems);
                                }
                            }

                            $completedVisibleSubSteps = collect($visibleSubStepIndexes)
                                ->filter(fn($subStepIndex) => ($savedSubSteps[$subStepIndex]->checked_at ?? null) !== null)
                                ->count();
                            $stepCompletedForScope = $visibleSubStepCount > 0 && $completedVisibleSubSteps === $visibleSubStepCount;

                            $canEdit = !empty($mappedUserDept);
                            $previousStepsCompleted = collect(range(1, $stepNumber - 1))->every(
                                fn($s) => $completedStepFlags[$s] ?? false,
                            );

                            // Visibility: admins/managers/heads see steps as before;
                            // other users only see the current step.
                            if ($canEdit) {
                                $isVisible = $stepNumber === 1 || $previousStepsCompleted;
                            } else {
                                $isVisible = $stepNumber === ($displayStep ?? ($drug->current_step_number ?? 1));
                            }
                            if ($isAdminRole) {
                                $isVisible = true;
                            } else {
                                $isVisible = $isVisible && ! $stepCompletedForScope;
                            }

                            // Allow the current department to keep editing its own step
                            // even after its own sub-steps are marked complete.
                            $isEditable =
                                $canEdit ||
                                (!auth()
                                    ->user()
                                    ->hasAnyRole(['admin', 'manager', 'head Registration']) &&
                                    $stepNumber === ($displayStep ?? ($drug->current_step_number ?? 1)));
                        @endphp

                        @if ($isVisible && count($departments) > 0)
                            <form data-step-form method="POST"
                                action="{{ route('newregis.update-subprogress', $drug->id) }}">
                                @csrf
                                @method('PUT')

                                @php
                                    // compute progress value for this specific step form
                                    $form_show_step_number = 0;
                                    $form_number_step_number = $stepNumber;
                                    $sfor = $drug->step_summary[$stepNumber] ?? null;
                                    $uncheckedFor = $sfor->unchecked_count ?? null;
                                    $isPlanNoneLocal2 = $drug->isPlanNone ?? 0;
                                    switch ($stepNumber) {
                                        case 1:
                                            if ($sfor && $uncheckedFor >= 12) $form_show_step_number = 0; else $form_show_step_number = 12.5;
                                            break;
                                        case 2:
                                            $form_show_step_number = 25; break;
                                        case 3:
                                            $form_show_step_number = 37.5; break;
                                        case 4:
                                            if ($sfor && $uncheckedFor == 1 && $isPlanNoneLocal2 == 1) { $form_number_step_number = 5; $form_show_step_number = 62.5; } else { $form_show_step_number = 50; }
                                            break;
                                        case 5:
                                            if ($sfor && $uncheckedFor == 2 && $isPlanNoneLocal2 == 1) { $form_number_step_number = 6; $form_show_step_number = 75; } else { $form_show_step_number = 62.5; }
                                            break;
                                        case 6:
                                            if ($sfor && $uncheckedFor == 2 && $isPlanNoneLocal2 == 1) { $form_number_step_number = 7; $form_show_step_number = 87.5; } else { $form_show_step_number = 75; }
                                            break;
                                        case 7:
                                            $form_show_step_number = 87.5; break;
                                        case 8:
                                            if ($sfor && $sfor->unchecked_count == 0) $form_show_step_number = 100; else $form_show_step_number = 90; break;
                                        default:
                                            $form_show_step_number = 0;
                                    }
                                @endphp

                                <input type="hidden" name="step_number" value="{{ $stepNumber }}">
                                <input type="hidden" name="progress" value="{{ $form_show_step_number }}">
                                <div class="p-4 mt-8 border border-gray-200 bg-gray-50 rounded-xl">
                                    <h4 class="mb-3 text-lg font-semibold text-indigo-600">
                                        ขั้นตอนที่ {{ $stepNumber }}: {{ $stepTitle }}
                                    </h4>


                                    {{-- แถบเปอร์เซ็นต์ --}}
                                    {{-- <div class="mb-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full @if ($percent < 25) bg-red-500 @elseif ($percent < 75) bg-yellow-500 @else bg-green-500 @endif"
                                                style="width: {{ $percent }}%">
                            </div>
                </div>
                <div class="mt-1 text-xs text-right text-gray-500">{{ $percent }}%</div>
            </div> --}}

                                    {{-- รายการ checkbox --}}
                                    <div class="space-y-6">
                                        @php $checkboxIndex = 0; @endphp
                                        @foreach ($stepData['items'] as $dept => $subItems)
                                            @php
                                                $skipThisDept =
                                                    $hideAcademicSteps &&
                                                    in_array($stepNumber, [4, 5, 6]) &&
                                                    $dept === 'แผนกวิชาการ';
                                                if ($skipThisDept) {
                                                    $checkboxIndex += count($subItems);
                                                    continue;
                                                }

                                                $showDept = $isAdminRole || normalize_department_name($dept) === $mappedUserDept;
                                            @endphp

                                            @if ($showDept)
                                                <div>
                                                    <h5 class="mb-2 text-sm font-bold text-gray-700">
                                                        {{ $dept }}
                                                    </h5>
                                                    <div class="pl-4 space-y-2">
                                                        @foreach ($subItems as $label)
                                                            @php
                                                                $record = $savedSubSteps[$checkboxIndex] ?? null;
                                                                $isChecked = $record && $record->checked_at;
                                                                $note = $record->created_by ?? '';
                                                                $remark = $record->remark ?? '';
                                                            @endphp
                                                            <div class="flex flex-wrap items-center gap-3">
                                                                <input type="checkbox" name="sub_steps[]"
                                                                    id="substep_{{ $stepNumber }}_{{ $checkboxIndex }}"
                                                                    value="{{ $checkboxIndex }}"
                                                                    {{ $isChecked ? 'checked' : '' }}
                                                                    @if(
                                                                        !$isEditable ||
                                                                        (!$isAdminRole && normalize_department_name($dept) !== $mappedUserDept)
                                                                    ) disabled @endif
                                                                    class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                                    onchange="toggleInput({{ $stepNumber }}, {{ $checkboxIndex }})">
                                                                <label
                                                                    for="substep_{{ $stepNumber }}_{{ $checkboxIndex }}"
                                                                    class="text-sm text-gray-800">{{ $label }}</label>

                                                                @if (in_array($label, ['หนังสือขอยกเว้น PHI', 'แผน PHI']))
                                                                    <div id="radio_container_{{ $stepNumber }}_{{ $checkboxIndex }}"
                                                                        class="flex items-center space-x-4"
                                                                        style="{{ $isChecked ? '' : 'display: none;' }}">
                                                                        <label class="inline-flex items-center">
                                                                            <input type="radio"
                                                                                class="w-5 h-5 text-green-500 form-radio"
                                                                                name="sub_step_notes[{{ $checkboxIndex }}]"
                                                                                value="ไม่มี"
                                                                                {{ $note == 'ไม่มี' ? 'checked' : '' }}
                                                                                {{ !$isEditable ? 'disabled' : '' }}>
                                                                            <span
                                                                                class="ml-2 text-gray-800">ไม่มี</span>
                                                                        </label>
                                                                        <label class="inline-flex items-center">
                                                                            <input type="radio"
                                                                                class="w-5 h-5 text-yellow-500 form-radio"
                                                                                name="sub_step_notes[{{ $checkboxIndex }}]"
                                                                                value="มี"
                                                                                {{ $note == 'มี' ? 'checked' : '' }}
                                                                                {{ !$isEditable ? 'disabled' : '' }}>
                                                                            <span class="ml-2 text-gray-800">มี</span>
                                                                        </label>
                                                                    </div>
                                                                @endif

                                                                <input type="text"
                                                                    name="sub_step_remarks[{{ $checkboxIndex }}]"
                                                                    value="{{ $remark }}"
                                                                    placeholder="หมายเหตุ"
                                                                    class="flex-1 w-48 p-2 border rounded-md"
                                                                    {{ !$isEditable ? 'disabled' : '' }}>
                                                            </div>
                                                            @php $checkboxIndex++; @endphp
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                @php $checkboxIndex += count($subItems); @endphp
                                            @endif
                                        @endforeach
                                    </div>

                                    @php
                                        // ตรวจสอบว่าแผนกของผู้ใช้งานติ๊กครบแล้วหรือยัง
                                        $userCheckedCount = 0;
                                        $userTotalCount = 0;
                                        foreach ($departments as $dept => $subItems) {
                                            if ($dept === $mappedUserDept) {
                                                foreach ($subItems as $label) {
                                                    $record = $savedSubSteps[$userTotalCount] ?? null;
                                                    if ($record && $record->checked_at) {
                                                        $userCheckedCount++;
                                                    }
                                                    $userTotalCount++;
                                                }
                                            } else {
                                                $userTotalCount += count($subItems);
                                            }
                                        }
                                        $userDeptComplete =
                                            $userTotalCount > 0 && $userCheckedCount === $userTotalCount;
                                    @endphp

                                    @if ($isEditable && !(auth()->user()->hasRole('admin') || auth()->user()->department == 'Registration'))
                                        <div class="mt-4 text-center">
                                            @if ($stepNumber == 1)
                                                {{-- <a href="{{ route('newregis.index') }}"
                                                    class="px-4 py-2 mr-2 font-bold text-gray-800 transition duration-300 bg-gray-300 rounded-lg shadow hover:bg-gray-400">
                                                    <i class="mr-2 fa-solid fa-arrow-left"></i> ย้อนกลับ
                                                </a> --}}
                                            @endif
                                            <button type="submit"
                                                class="px-4 py-2 text-white transition bg-indigo-600 rounded-lg shadow hover:bg-indigo-700">
                                                <i class="mr-1 fa-solid fa-floppy-disk"></i> บันทึกความคืบหน้า
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        @endif
                    @endforeach
                    @if ($isAdminRole)
                        <div class="mt-8 text-center">
                            <a href="{{ route('newregis.index') }}"
                                class="px-4 py-2 mr-2 font-bold text-gray-800 transition duration-300 bg-gray-300 rounded-lg shadow hover:bg-gray-400">
                                <i class="mr-2 fa-solid fa-arrow-left"></i> ย้อนกลับ
                            </a>
                            <button type="button" id="save-all-progress"
                                class="px-6 py-2 font-bold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700">
                                <i class="mr-1 fa-solid fa-floppy-disk"></i> บันทึกความคืบหน้า
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            flatpickr(".date-th", {
                allowInput: true,
                locale: "th",
                dateFormat: "d/m/Y",
                parseDate: (datestr, format) => {
                    if (!datestr) return null;
                    const parts = datestr.split('/');
                    if (parts.length === 3) {
                        let [dd, mm, yyyy] = parts.map(n => parseInt(n, 10));
                        if (yyyy > 2400) yyyy -= 543; // ถ้าเป็น พ.ศ. → ค.ศ.
                        return new Date(yyyy, mm - 1, dd);
                    }
                    return flatpickr.parseDate(datestr, format);
                },
                onReady: (selectedDates, dateStr, instance) => showBE(instance),
                onChange: (selectedDates, dateStr, instance) => showBE(instance),
                onOpen: (selectedDates, dateStr, instance) => showBE(instance)
            });

            function showBE(instance) {
                const selDate = instance.selectedDates[0];
                if (!selDate) return;
                const dd = String(selDate.getDate()).padStart(2, "0");
                const mm = String(selDate.getMonth() + 1).padStart(2, "0");
                const yyyyBE = selDate.getFullYear() + 543;
                instance.input.value = `${dd}/${mm}/${yyyyBE}`;
            }
            const form = document.getElementById("editRegisForm");
            if (form) {
                form.addEventListener("submit", () => {
                    document.querySelectorAll(".date-th").forEach(input => {
                        if (input.value.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
                            let [dd, mm, yyyyBE] = input.value.split("/");
                            let yyyyAD = parseInt(yyyyBE, 10);
                            if (yyyyAD > 2400) yyyyAD -= 543;
                            input.value = `${yyyyAD}-${mm}-${dd}`;
                        }
                    });
                });
            }
        });
    </script>

    <script>
        document.getElementById('menu-newregis')?.classList.add('side-menu--active');
        const lastSelectedRadioValue = {};

        function toggleInput(stepNumber, checkboxIndex) {
            const checkbox = document.getElementById(`substep_${stepNumber}_${checkboxIndex}`);
            const radioContainer = document.getElementById(`radio_container_${stepNumber}_${checkboxIndex}`);
            const remarkContainer = document.getElementById(`remark_container_${stepNumber}_${checkboxIndex}`);

            if (!checkbox) return;

            if (checkbox.checked) {
                if (radioContainer) {
                    radioContainer.style.display = '';

                    const savedValue = lastSelectedRadioValue[`${stepNumber}_${checkboxIndex}`] || 'ไม่มี';
                    const radios = radioContainer.querySelectorAll('input[type="radio"]');
                    radios.forEach(radio => {
                        radio.checked = (radio.value === savedValue);
                    });
                }
            } else {
                if (radioContainer) {
                    const currentRadios = radioContainer.querySelectorAll('input[type="radio"]');
                    currentRadios.forEach(radio => {
                        if (radio.checked) {
                            lastSelectedRadioValue[`${stepNumber}_${checkboxIndex}`] = radio.value;
                        }
                    });

                    radioContainer.style.display = 'none';
                    currentRadios.forEach(radio => (radio.checked = false));
                }
            }
        }
    </script>

    @if ($isAdminRole)
        <script>
            document.getElementById('save-all-progress')?.addEventListener('click', async () => {
                const forms = document.querySelectorAll('form[data-step-form]');
                let successCount = 0;
                for (const f of forms) {
                    const formData = new FormData(f);
                    try {
                        const resp = await fetch(f.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        if (resp.ok) {
                            successCount++;
                        }
                    } catch (e) {
                        console.error('Save step failed', e);
                    }
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: successCount === forms.length ? 'success' : 'info',
                        title: successCount === forms.length ? 'บันทึกครบทุกขั้นตอนแล้ว' :
                            'บันทึกบางขั้นตอนสำเร็จ',
                        text: 'กำลังรีเฟรช...',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                } else {
                    window.location.reload();
                }
            });
        </script>
    @endif

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
            });
        </script>
    @endif
</x-app-layout>
