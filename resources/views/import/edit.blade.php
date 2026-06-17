<x-app-layout>
    <div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-2xl space-y-10 mt-6">
        <h2 class="text-4xl font-extrabold text-gray-700 mb-8 pb-4 text-center border-b border-gray-300">
            แก้ไขข้อมูลทะเบียนนำเข้า
        </h2>

        {{-- The form action now points to the 'update' route and passes the import ID --}}
        <form method="POST" action="{{ route('import.update', $import->id) }}" class="space-y-10" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Use PUT method for updating --}}

            <div>
                <h3
                    class="text-2xl font-semibold text-white bg-gradient-to-r from-blue-400 to-indigo-400 px-4 py-3 rounded-t-md">
                    ข้อมูลการนำเข้าทั่วไป
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-2 gap-6 mt-4">

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่ทะเบียน</label>
                        <input type="text" id="registration_number" name="registration_number"
                            value="{{ old('registration_number', $import->registration_number) }}"
                            placeholder="เช่น 123-2568"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            inputmode="numeric" pattern="^\d+-\d{4}$"
                            title="รูปแบบต้องเป็น ตัวเลขใดๆ ตามด้วย - และเลขท้าย 4 หลัก เช่น 123-2568"
                            oninput="filterRegisNo(this)" required />
                        @error('registration_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full md:w-1/3">
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุ</label>
                        <input type="text" name="expired_license_date" id="expired_license_date"
                            class="date-th w-full p-3 pl-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('expired_license_date', $import->expired_license_date ? \Carbon\Carbon::parse($import->expired_license_date)->addYears(543)->format('d/m/Y') : '') }}"
                            placeholder="วว/ดด/ปปปป" autocomplete="off">
                        @error('expired_license_date')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">บริษัทที่ขึ้นทะเบียน
                            <span class="text-red-500"> *</span>
                        </label>
                        <div class="dropdown" id="companyDropdown">
                            <div style="height: 50px;" class="text-gray-700 dropdown-btn" id="companyBtn">
                                @php
                                    $selectedCompanyId = old('company_id', $import->company_id);
                                    $companyName =
                                        $companies->firstWhere('id', $selectedCompanyId)->full_name ?? '-- เลือก --';
                                @endphp
                                {{ $companyName }}
                            </div>
                            <div class="dropdown-list" id="companyList">
                                <div class="dropdown-item text-gray-700" data-value="">-- เลือก --</div>
                                @foreach ($companies as $company)
                                    @if ($company->type == 1)
                                        <div class="dropdown-item" data-value="{{ $company->id }}">
                                            {{ $company->full_name }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="company_id" id="companyInput"
                            value="{{ old('company_id', $import->company_id) }}">
                        @error('company_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เปอร์เซ็นต์และสูตร</label>
                        <input type="text" name="composition" value="{{ old('composition', $import->composition) }}"
                            placeholder="ใส่เปอร์เซ็นต์และสูตร"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('composition')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อวัตถุอันตราย (ไทย)</label>
                        <input type="text" name="chemical_name_th"
                            value="{{ old('chemical_name_th', $import->chemical_name_th) }}"
                            placeholder="ใส่ชื่อวัตถุอันตราย (ไทย)"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('chemical_name_th')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อวัตถุอันตราย (อังกฤษ)</label>
                        <input type="text" name="chemical_name_en"
                            value="{{ old('chemical_name_en', $import->chemical_name_en) }}"
                            placeholder="ใส่ชื่อวัตถุอันตราย (อังกฤษ)"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('chemical_name_en')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ผู้ผลิตและแหล่งผลิต</label>
                        <input type="text" name="manufacturer"
                            value="{{ old('manufacturer', $import->manufacturer) }}"
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
                                @php
                                    $registrationTypes = ['T' => 'T', 'I' => 'I', 'R(F)' => 'R(F)'];
                                    $selectedType = old('registration_type', $import->registration_type);
                                @endphp
                                {{ $registrationTypes[$selectedType] ?? '-- เลือก --' }}
                            </div>
                            <div class="dropdown-list" id="registrationTypeList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกประเภททะเบียน --</div>
                                <div class="dropdown-item" data-value="T : นำเข้าสารเข้มข้น">T :
                                    นำเข้าสารเข้มข้น</div>
                                <div class="dropdown-item" data-value="I : นำเข้าสำเร็จรูป">I :
                                    นำเข้าสำเร็จรูป</div>
                                <div class="dropdown-item" data-value="F : ผลิตผสมปรุงแต่ง">F :
                                    ผลิตผสมปรุงแต่ง</div>
                                <div class="dropdown-item" data-value="R : ผลิตแบ่งบรรจุ (จากนำเข้า)">R :
                                    ผลิตแบ่งบรรจุ (จากนำเข้า)</div>
                                <div class="dropdown-item" data-value="R(F) : ผลิตแบ่งบรรจุ (จากผสมปรุงแต่ง)">R(F) :
                                    ผลิตแบ่งบรรจุ
                                    (จากผสมปรุงแต่ง)</div>
                                <div class="dropdown-item" data-value="F(E) : ผลิตเพื่อส่งออก">F(E) :
                                    ผลิตเพื่อส่งออก</div>
                            </div>
                        </div>
                        <input type="hidden" name="registration_type" id="registrationTypeInput"
                            value="{{ old('registration_type', $import->registration_type) }}">
                        @error('registration_type')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อผู้นำเข้า</label>
                        <div class="dropdown" id="importerDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="importerBtn">
                                @php
                                    $selectedImporterId = old('importer', $import->importer);
                                    $importerName =
                                        $companies->firstWhere('id', $selectedImporterId)->full_name ?? '-- เลือก --';
                                @endphp
                                {{ $importerName }}
                            </div>
                            <div class="dropdown-list" id="importerList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือก --</div>
                                @foreach ($companies as $company)
                                    {{-- @if ($company->type == 1) --}}
                                    <div class="dropdown-item" data-value="{{ $company->id }}">
                                        {{ $company->full_name }}
                                    </div>
                                    {{-- @endif --}}
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="importer" id="importerInput"
                            value="{{ old('importer', $import->importer) }}">
                        @error('importer')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อผู้จำหน่าย</label>
                        <div class="dropdown" id="distributorDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="distributorBtn">
                                @php
                                    $selectedDistributorId = old('distributor', $import->distributor);
                                    $distributorName =
                                        $companies->firstWhere('id', $selectedDistributorId)->full_name ??
                                        '-- เลือก --';
                                @endphp
                                {{ $distributorName }}
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
                            value="{{ old('distributor', $import->distributor) }}">
                        @error('distributor')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อการค้า</label>
                        <input type="text" name="trade_name" value="{{ old('trade_name', $import->trade_name) }}"
                            placeholder="ใส่ชื่อการค้า"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('trade_name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อการค้าที่ <span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="namePositionDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="namePositionBtn">--
                                เลือกชื่อการที่ --</div>
                            <div class="dropdown-list" id="namePositionList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกชื่การที่
                                    --</div>
                                <div class="dropdown-item" data-value="T">T</div>
                                <div class="dropdown-item" data-value="-">-</div>
                                <div class="dropdown-item" data-value="1">1</div>
                                <div class="dropdown-item" data-value="2">2</div>
                                <div class="dropdown-item" data-value="3">3</div>
                            </div>
                        </div>
                        <input type="hidden" name="trade_name_at" id="namePositionInput"
                            value="{{ old('trade_name_at', $import->trade_name_at) }}">
                        @error('trade_name_at')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชนิดทะเบียน <span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="typeRegistrationDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="typeRegistrationBtn">--
                                เลือกชนิดทะเบียน --</div>
                            <div class="dropdown-list" id="typeRegistrationList">
                                <div class="dropdown-item text-gray-500" data-value="">--
                                    เลือกชนิดทะเบียน --</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 1">ชนิดที่ 1</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 2">ชนิดที่ 2</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 3">ชนิดที่ 3</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 4">ชนิดที่ 4</div>
                            </div>
                        </div>
                        <input type="hidden" name="type_production_registration" id="typeRegistrationInput"
                            value="{{ old('type_production_registration', $import->type_production_registration) }}">
                        @error('type_production_registration')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ประเภทของการใช้ <span
                                class="text-red-500"> *</span></label>
                        <div class="dropdown" id="typeOfUseDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="typeOfUseBtn">--
                                เลือกประเภทของการใช้ --</div>
                            <div class="dropdown-list" id="typeOfUseList">
                                <div class="dropdown-item text-gray-500" data-value="">--
                                    เลือกประเภทของการใช้ --</div>
                                <div class="dropdown-item" data-value="A : Acaricide (สารกำจัดไรศัตรูพืช)">A :
                                    Acaricide (สารกำจัดไรศัตรูพืช)</div>
                                <div class="dropdown-item" data-value="F : Fungicide (สารป้องกันกำจัดโรคพืช)">F :
                                    Fungicide (สารป้องกันกำจัดโรคพืช)</div>
                                <div class="dropdown-item" data-value="H : Herbicide (สารกำจัดวัชพืช)">H :
                                    Herbicide (สารกำจัดวัชพืช)</div>
                                <div class="dropdown-item" data-value="I : Insecticide (สารกำจัดแมลง)">I :
                                    Insecticide (สารกำจัดแมลง)</div>
                                <div class="dropdown-item" data-value="M : Molluscicide (สารกำจัดหอย)">M :
                                    Molluscicide (สารกำจัดหอย)</div>
                                <div class="dropdown-item" data-value="N : Nematicide (สารกำจัดไส้เดือนฝอย)">N :
                                    Nematicide (สารกำจัดไส้เดือนฝอย)</div>
                                <div class="dropdown-item"
                                    data-value="P : PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)">
                                    P :
                                    PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)</div>
                                <div class="dropdown-item" data-value="R : Rodenticide (สารกำจัดหนู)">R :
                                    Rodenticide (สารกำจัดหนู)</div>
                            </div>
                        </div>
                        <input type="hidden" name="usage_production_registration" id="typeOfUseInput"
                            value="{{ old('usage_production_registration', $import->usage_production_registration) }}">
                        @error('usage_production_registration')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">กลุ่มสาร</label>
                        <input type="text" name="group_of_substances"
                            value="{{ old('group_of_substances', $import->group_of_substances) }}"
                            placeholder="ใส่กลุ่มสาร"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('group_of_substances')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">พืช</label>
                        <input type="text" name="plant" value="{{ old('plant', $import->plant) }}"
                            placeholder="ใส่พืช"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('plant')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ศัตรูพืช</label>
                        <input type="text" name="pests" value="{{ old('pests', $import->pests) }}"
                            placeholder="ใส่ศัตรูพืช"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('pests')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="production_license_quantity"
                            class="mx-3 text-base block text-gray-700 mb-1 mt-3">ปริมาณ</label>
                        <input type="text" name="production_license_quantity" id="production_license_quantity"
                            value="{{ old('production_license_quantity', $import->production_license_quantity) }}"
                            placeholder="ใส่ปริมาณ"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('production_license_quantity')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="registration_number_pass"
                            class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่ใบอนุญาต</label>
                        <input type="text" name="registration_number_pass" id="registration_number_pass"
                            value="{{ old('registration_number_pass', $import->registration_number_pass) }}"
                            placeholder="ใส่เลขที่ใบอนุญาต"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('registration_number_pass')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบอนุญาต</label>
                        <input type="text" name="production_license_expiry" id="production_license_expiry"
                            class="date-th w-full p-3 pl-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('production_license_expiry', $import->production_license_expiry ? \Carbon\Carbon::parse($import->production_license_expiry)->addYears(543)->format('d/m/Y') : '') }}"
                            placeholder="วว/ดด/ปปปป" autocomplete="off">
                        @error('production_license_expiry')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ใบอนุญาตเลขที่เดิม</label>
                        <input type="text" name="production_license_number"
                            value="{{ old('production_license_number', $import->production_license_number) }}"
                            placeholder="ใส่เลขที่ใบอนุญาต"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('production_license_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบอนุญาตเดิม</label>
                        <input type="text" name="expired_at" value="{{ old('expired_at', $import->expired_at) }}"
                            placeholder="ใส่วันหมดอายุใบอนุญาตเดิม"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('expired_at')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ใบแจ้งครอบครอง วอ.2</label>
                        <input type="text" name="possession_form_wo2"
                            value="{{ old('possession_form_wo2', $import->possession_form_wo2 ?? '') }}"
                            placeholder="ใส่ใบแจ้งครอบครอง วอ.2"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('possession_form_wo2')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบแจ้งครอบครอง
                            วอ.2</label>
                        <input type="text" name="possession_form_expiry" {{-- value="{{ old('possession_form_expiry', $import->possession_form_expiry ? $import->possession_form_expiry->format('Y-m-d') : '-') }}" --}}
                            value="{{ old('possession_form_expiry', $import->possession_form_expiry ?? '') }}"
                            placeholder="ใส่วันหมดอายุใบแจ้งครอบครองวอ.2"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('possession_form_expiry')
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
                            class="w-full p-3 border rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2">{{ old('packaging_size_details', $import->packaging_size_details) }}</textarea>
                        @error('packaging_size_details')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hidden fields with current values --}}
                    <input type="hidden" name="new_or_old" value="{{ old('new_or_old', $import->new_or_old) }}">
                    <input type="hidden" name="step" value="{{ old('step', $import->step) }}">
                    <input type="hidden" name="status" value="{{ old('status', $import->status) }}">
                    <input type="hidden" name="is_active" value="{{ old('is_active', $import->is_active) }}">
                    <input type="hidden" name="is_deleted" value="{{ old('is_deleted', $import->is_deleted) }}">
                    <input type="hidden" name="progress" value="{{ old('progress', $import->progress) }}">
                    <input type="hidden" name="sub_progress"
                        value="{{ old('sub_progress', $import->sub_progress) }}">

                    {{-- We can keep created_by but update updated_by --}}
                    <input type="hidden" name="created_by" value="{{ $import->created_by }}">
                    <input type="hidden" name="updated_by" value="{{ auth()->user()->name ?? 'system' }}">

                </div>
            </div>
            <div>
                <h3
                    class="text-2xl font-semibold text-white bg-gradient-to-r from-blue-400 to-indigo-400 px-4 py-3 rounded-t-md">
                    อัพโหลดเอกสารเพิ่มเติม (ถ้ามี) <span class="text-gray-500 text-sm">(เช่น ไฟล์ PDF)</span>
                </h3>
                @php
                    $importFiles = $import->files ?? collect();
                    $approvalDocumentTypeCode = 'import_license';
                    $approvalFiles = $importFiles->where('document_type_code', $approvalDocumentTypeCode);
                    $registrationFiles = $importFiles->reject(function ($file) use ($approvalDocumentTypeCode) {
                        return $file->document_type_code === $approvalDocumentTypeCode;
                    });
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div class="md:col-span-2">
                            <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ไฟล์ทะเบียนนำเข้า ( PDF )</label>
                            @canany('import_data_staple create')
                            <input type="file" name="import_registration_documents[]" id="import_registration_documents" multiple
                                class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @else
                            <input type="file" name="import_registration_documents[]" id="import_registration_documents"
                                class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
                            @endcanany
                            @error('import_registration_documents')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                            @error('import_registration_documents.*')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                            @if ($registrationFiles->isNotEmpty())
                                <div class="mt-4 space-y-2">
                                    <p class="text-gray-600 text-sm font-semibold">ไฟล์ทะเบียนนำเข้า: {{ $registrationFiles->count() }} ไฟล์</p>
                                    @foreach ($registrationFiles as $file)
                                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                                            <div class="min-w-0 flex items-center gap-3">
                                                 @canany('import_data_staple delete')
                                                <button type="button"
                                                    data-delete-file-url="{{ route('import.file.destroy', [$import, $file]) }}"
                                                    class="shrink-0 inline-flex h-9 w-9 items-center justify-center rounded-full bg-red-500 text-white shadow-sm transition hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                                                    title="ลบเอกสาร" aria-label="ลบเอกสาร">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-trash-2">
                                                        <path d="M3 6h18"/>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                        <path d="M10 11v6"/>
                                                        <path d="M14 11v6"/>
                                                    </svg>
                                                </button>
                                                @endcanany
                                                <div class="min-w-0">
                                                <p class="truncate text-gray-700 font-medium">{{ $file->original_name ?: basename($file->file_path) }}</p>
                                                <p class="text-xs text-gray-500">
                                                    <!-- {{ $file->document_type_code ?: '-' }} -->
                                                    <!-- · -->
                                                    {{ optional($file->created_at)->format('d/m/Y H:i') }}
                                                    @if ($file->file_size)
                                                        · {{ number_format($file->file_size / 1024, 1) }} KB
                                                    @endif
                                                </p>
                                                </div>
                                            </div>
                                             @canany('import_data_staple read')
                                            <button type="button"
                                                data-file-url="{{ route('import.file', [$import, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                data-file-name="{{ $file->original_name ?: basename($file->file_path) }}"
                                                class="shrink-0 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-600 text-white shadow-md ring-1 ring-blue-700/20 transition hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
                                                title="ดูเอกสาร" aria-label="ดูเอกสาร">
                                                @include('components.document-pdf-icon')
                                            </button>
                                                @else
                                                    <button type="button"
                                                        disabled
                                                        class="shrink-0 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-gray-300 text-gray-500 shadow-sm ring-1 ring-gray-400/20 cursor-not-allowed opacity-70"
                                                        title="ไม่มีสิทธิ์ดูเอกสาร" aria-label="ไม่มีสิทธิ์ดูเอกสาร">
                                                        @include('components.document-pdf-icon')
                                                    </button>
                                                @endcanany
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                             <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ไฟล์ใบอนุญาตนำเข้า( PDF )</label>
                                @canany('import_data_staple create')
                                <input type="file" name="import_approval_documents[]" id="import_approval_documents" multiple
                                class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @else
                                <input type="file" name="import_approval_documents[]" id="import_approval_documents"
                                class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />
                                @endcanany
                            @error('import_approval_documents')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                            @error('import_approval_documents.*')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                            @if ($approvalFiles->isNotEmpty())
                                <div class="mt-4 space-y-2">
                                    <p class="text-gray-600 text-sm font-semibold">ไฟล์ใบอนุญาตนำเข้า: {{ $approvalFiles->count() }} ไฟล์</p>
                                    @foreach ($approvalFiles as $file)
                                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                                            <div class="min-w-0 flex items-center gap-3">
                                                    @canany('import_data_staple delete')
                                                <button type="button"
                                                    data-delete-file-url="{{ route('import.file.destroy', [$import, $file]) }}"
                                                    class="shrink-0 inline-flex h-9 w-9 items-center justify-center rounded-full bg-red-500 text-white shadow-sm transition hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                                                    title="ลบเอกสาร" aria-label="ลบเอกสาร">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-trash-2">
                                                        <path d="M3 6h18"/>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                        <path d="M10 11v6"/>
                                                        <path d="M14 11v6"/>
                                                    </svg>
                                                </button>
                                                @endcanany
                                                <div class="min-w-0">
                                                <p class="truncate text-gray-700 font-medium">{{ $file->original_name ?: basename($file->file_path) }}</p>
                                                <p class="text-xs text-gray-500">
                                                    <!-- {{ $file->document_type_code ?: '-' }} -->
                                                    <!-- · -->
                                                    {{ optional($file->created_at)->format('d/m/Y H:i') }}
                                                    @if ($file->file_size)
                                                        · {{ number_format($file->file_size / 1024, 1) }} KB
                                                    @endif
                                                </p>
                                                </div>
                                            </div>
                                                @canany('import_data_staple read')
                                            <button type="button"
                                                data-file-url="{{ route('import.file', [$import, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                data-file-name="{{ $file->original_name ?: basename($file->file_path) }}"
                                                class="shrink-0 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-600 text-white shadow-md ring-1 ring-blue-700/20 transition hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
                                                title="ดูเอกสาร" aria-label="ดูเอกสาร">
                                                @include('components.document-pdf-icon')
                                            </button>
                                                @else
                                                    <button type="button"
                                                        disabled
                                                        class="shrink-0 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-gray-300 text-gray-500 shadow-sm ring-1 ring-gray-400/20 cursor-not-allowed opacity-70"
                                                        title="ไม่มีสิทธิ์ดูเอกสาร" aria-label="ไม่มีสิทธิ์ดูเอกสาร">
                                                        @include('components.document-pdf-icon')
                                                    </button>
                                                @endcanany
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @php
                                $currentDocumentPath = $importFiles->isEmpty() ? ($import->additional_document ?: $import->document) : null;
                                $currentDocumentExists = false;

                                if ($currentDocumentPath) {
                                    $currentDocumentExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($currentDocumentPath);

                                    if (!$currentDocumentExists && $import->document && \Illuminate\Support\Facades\Storage::disk('public')->exists($import->document)) {
                                        $currentDocumentPath = $import->document;
                                        $currentDocumentExists = true;
                                    }
                                }
                            @endphp
                            @if ($currentDocumentPath && $currentDocumentExists)
                                @php
                                    $additionalDocumentUrl = route('import.additional-document', $import);
                                    $additionalDocumentViewerUrl = $additionalDocumentUrl . '#toolbar=0&navpanes=0&scrollbar=0';
                                    $additionalDocumentName = $import->additional_document
                                        ? ($import->document ?: basename($import->additional_document))
                                        : basename($import->document);
                                @endphp
                                <p class="text-gray-500 text-sm mt-1">ไฟล์ปัจจุบัน: {{ $additionalDocumentName }}</p>
                                <div class="pt-4">
                                    <button type="button" id="openAdditionalDocumentModal"
                                        class="group inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-lg shadow-md transition">

                                        <!-- PDF Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-file-text">
                                            <path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                            <line x1="10" y1="9" x2="8" y2="9"/>
                                        </svg>

                                        <span>ดูเอกสาร PDF</span>

                                        <!-- External Arrow -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-square-arrow-out-up-right transition-transform group-hover:translate-x-1 group-hover:-translate-y-1">
                                            <path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"/>
                                            <path d="m21 3-9 9"/>
                                            <path d="M15 3h6v6"/>
                                        </svg>

                                    </button>
                                </div>
                            @endif
                        </div>
                </div>

            </div>
            <div class="flex justify-center gap-4 pt-4">
                {{-- Cancel button now links to the index page --}}
                <a href="{{ route('import.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg shadow-md flex items-center justify-center">
                    ยกเลิก
                </a>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md">
                    บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </form>
    </div>

    {{-- Custom Message Box and Scripts remain largely the same, but JS is updated --}}
    <div id="customMessageBox"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        {{-- ... content of message box ... --}}
    </div>

    <div id="importFileModal"
        class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 px-4 py-6">
        <div class="bg-white max-w-5xl mx-auto h-full rounded-lg shadow-lg flex flex-col overflow-hidden">
            <div class="flex items-center justify-between gap-4 px-5 py-4 border-b">
                <h3 id="importFileModalTitle" class="text-lg font-semibold text-gray-700 truncate">
                    เอกสาร
                </h3>
                <button type="button" id="closeImportFileModal"
                    class="text-gray-500 hover:text-gray-800 text-2xl leading-none">
                    &times;
                </button>
            </div>
            <div id="importFileViewer"
                class="flex-1 bg-gray-100 overflow-auto p-4 flex flex-col items-center gap-4"
                oncontextmenu="return false;">
            </div>
        </div>
    </div>

    @if ($currentDocumentPath && $currentDocumentExists)
        <div id="additionalDocumentModal"
            class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 px-4 py-6">
            <div class="bg-white max-w-5xl mx-auto h-full rounded-lg shadow-lg flex flex-col overflow-hidden">
                <div class="flex items-center justify-between gap-4 px-5 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-700 truncate">
                        {{ $additionalDocumentName }}
                    </h3>
                    <button type="button" id="closeAdditionalDocumentModal"
                        class="text-gray-500 hover:text-gray-800 text-2xl leading-none">
                        &times;
                    </button>
                </div>
                <div class="flex-1 bg-gray-100">
                    <iframe src="{{ $additionalDocumentViewerUrl }}" class="w-full h-full" title="เอกสารเพิ่มเติม"
                        oncontextmenu="return false;">
                    </iframe>
                </div>
                <!-- <div class="flex justify-end gap-3 px-5 py-4 border-t">
                    <button type="button" id="cancelAdditionalDocumentModal"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-5 rounded-lg shadow-md">
                        ปิด
                    </button>
                </div> -->
            </div>
        </div>
    @endif


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

    @if ($currentDocumentPath && $currentDocumentExists)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('additionalDocumentModal');
                const openBtn = document.getElementById('openAdditionalDocumentModal');
                const closeBtn = document.getElementById('closeAdditionalDocumentModal');

                function openModal() {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }

                function closeModal() {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

                openBtn?.addEventListener('click', openModal);
                closeBtn?.addEventListener('click', closeModal);
                modal?.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
                document.addEventListener('keydown', (event) => {
                    if ((event.ctrlKey || event.metaKey) && ['p', 's'].includes(event.key.toLowerCase()) && !modal.classList.contains('hidden')) {
                        event.preventDefault();
                    }

                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            });
        </script>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('importFileModal');
            const viewer = document.getElementById('importFileViewer');
            const title = document.getElementById('importFileModalTitle');
            const closeBtn = document.getElementById('closeImportFileModal');
            let renderToken = 0;

            if (window.pdfjsLib) {
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            }

            function closeModal() {
                renderToken++;
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                viewer.innerHTML = '';
            }

            async function renderPdf(url) {
                const token = ++renderToken;
                viewer.innerHTML = '<p class="text-gray-500 py-8">กำลังโหลดเอกสาร...</p>';

                if (!window.pdfjsLib) {
                    viewer.innerHTML = '<p class="text-red-500 py-8">ไม่สามารถโหลดตัวอ่าน PDF ได้</p>';
                    return;
                }

                try {
                    const pdf = await pdfjsLib.getDocument(url).promise;
                    if (token !== renderToken) return;

                    viewer.innerHTML = '';
                    for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                        const page = await pdf.getPage(pageNumber);
                        if (token !== renderToken) return;

                        const viewport = page.getViewport({ scale: 1.4 });
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;
                        canvas.className = 'max-w-full bg-white shadow-md';
                        viewer.appendChild(canvas);

                        await page.render({ canvasContext: context, viewport }).promise;
                    }
                } catch (error) {
                    if (token === renderToken) {
                        viewer.innerHTML = '<p class="text-red-500 py-8">ไม่สามารถแสดงเอกสารนี้ได้</p>';
                    }
                }
            }

            document.querySelectorAll('[data-file-url]').forEach(button => {
                button.addEventListener('click', () => {
                    title.textContent = button.dataset.fileName || 'เอกสาร';
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    renderPdf(button.dataset.fileUrl);
                });
            });

            closeBtn?.addEventListener('click', closeModal);
            modal?.addEventListener('click', event => {
                if (event.target === modal) {
                    closeModal();
                }
            });
            modal?.addEventListener('contextmenu', event => event.preventDefault());
            document.addEventListener('keydown', event => {
                if (!modal.classList.contains('hidden') && (event.ctrlKey || event.metaKey) && ['p', 's'].includes(event.key.toLowerCase())) {
                    event.preventDefault();
                }

                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-delete-file-url]').forEach(button => {
                button.addEventListener('click', async () => {
                    const submitDelete = () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = button.dataset.deleteFileUrl;
                    form.style.display = 'none';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                    };

                    if (window.Swal) {
                        const result = await Swal.fire({
                            icon: 'warning',
                            title: 'ลบเอกสารนี้?',
                            text: 'เมื่อลบแล้วจะไม่สามารถกู้คืนไฟล์นี้ได้',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'ลบเอกสาร',
                            cancelButtonText: 'ยกเลิก',
                            reverseButtons: true,
                            focusCancel: true,
                        });

                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'กำลังลบเอกสาร...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => Swal.showLoading(),
                            });
                            submitDelete();
                        }
                    } else if (confirm('ต้องการลบเอกสารนี้หรือไม่?')) {
                        submitDelete();
                    }
                });
            });
        });
    </script>





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

            // ก่อน submit → แปลงเป็น ค.ศ. yyyy-mm-dd
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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->has('registration_number'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'เลขทะเบียนซ้ำ',
                text: '{{ $errors->first('registration_number') }}'
            });
        </script>
    @endif

    @php
        $importFileErrors = collect([
            ...$errors->get('import_registration_documents'),
            ...$errors->get('import_registration_documents.*'),
            ...$errors->get('import_approval_documents'),
            ...$errors->get('import_approval_documents.*'),
        ])->filter()->values();
    @endphp

    @if ($importFileErrors->isNotEmpty())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'อัปโหลดเอกสารไม่สำเร็จ',
                html: `{!! $importFileErrors->map(fn($message) => e($message))->implode('<br>') !!}`,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ตกลง'
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('file_deleted') ? 'ลบเอกสารสำเร็จ!' : 'อัปเดตสำเร็จ!' }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                if (result.isConfirmed && !@json(session('file_deleted'))) {
                    window.location.href = "{{ route('import.index') }}";
                }
            });
        </script>
    @endif

    <script>
        document.getElementById('menu-inregister')?.classList.add('side-menu--active');
        document.addEventListener('DOMContentLoaded', () => {
            function setupDropdown(btnId, listId, inputId, currentValue = null) {
                const btn = document.getElementById(btnId);
                const list = document.getElementById(listId);
                const input = document.getElementById(inputId);
                const items = list.querySelectorAll('.dropdown-item');

                function updateBtn(label, value) {
                    btn.textContent = label.trim();
                    if (value === "" || label.includes('--')) {
                        btn.classList.add('text-gray-500');
                    } else {
                        btn.classList.remove('text-gray-500');
                    }
                    input.value = value;
                }

                // Set initial value from the model data or old input
                if (currentValue) {
                    const match = [...items].find(i => i.dataset.value == currentValue);
                    if (match) {
                        updateBtn(match.textContent, match.dataset.value);
                    } else if (btn.textContent.trim() === '-- เลือก --') {
                        // Fallback for cases where initial value is not in the list
                        updateBtn('-- เลือก --', '');
                    }
                } else {
                    updateBtn('-- เลือก --', '');
                }

                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    list.classList.toggle('open');
                    btn.classList.toggle('open');
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
                        updateBtn(item.textContent, item.dataset.value);
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

            // Setup for all dropdowns, passing the current data
            setupDropdown('companyBtn', 'companyList', 'companyInput',
                "{{ old('company_id', $import->company_id) }}");
            setupDropdown('importerBtn', 'importerList', 'importerInput',
                "{{ old('importer', $import->importer) }}");
            setupDropdown('distributorBtn', 'distributorList', 'distributorInput',
                "{{ old('distributor', $import->distributor) }}");
            setupDropdown('registrationTypeBtn', 'registrationTypeList', 'registrationTypeInput',
                "{{ old('registration_type', $import->registration_type) }}");
            setupDropdown('namePositionBtn', 'namePositionList', 'namePositionInput',
                "{{ old('trade_name_at', $import->trade_name_at) }}");
            setupDropdown('typeRegistrationBtn', 'typeRegistrationList', 'typeRegistrationInput',
                "{{ old('type_production_registration', $import->type_production_registration) }}");
            setupDropdown('typeOfUseBtn', 'typeOfUseList', 'typeOfUseInput',
                "{{ old('usage_production_registration', $import->usage_production_registration) }}");
        });
    </script>

    {{-- Styles remain unchanged --}}
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
