<x-app-layout>
    <div class="max-w-7xl mx-auto p-8 bg-white shadow-lg rounded-2xl space-y-10 mt-6">
        <h2 class="text-4xl font-extrabold text-gray-700 mb-8 pb-4 text-center border-b border-gray-300">
            แก้ไขข้อมูลการขึ้นทะเบียน
        </h2>

        {{-- The form action will now point to the update route and use the PUT method --}}
        <form method="POST" action="{{ route('newregis.updateall', $registration->id) }}" class="space-y-10">
            @csrf
            @method('PUT') {{-- This tells Laravel to treat the request as a PUT/PATCH --}}

            {{-- General Information Section --}}
            <div>
                <h3
                    class="text-2xl font-semibold text-white bg-gradient-to-r from-blue-400 to-indigo-400 px-4 py-3 rounded-t-md">
                    ข้อมูลทั่วไป
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่ทะเบียน</label>
                        <input type="text"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            name="registration_number"
                            value="{{ old('registration_number', $registration->registration_number) }}" />
                        @error('registration_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันที่หมดอายุ</label>
                        <input type="date" name="expired_license_number"
                            value="{{ old('expired_license_number', $registration->expired_license_number) }}"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('expired_license_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อสามัญ</label>
                        <input type="text" name="chemical_name_th" value="{{ $registration->chemical_name_th ?? '-' }}"
                            placeholder="ไม่มีข้อมูล" disabled
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 disabled-field bg-gray-100 text-gray-700" />
                    </div> --}}

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อสามัญ</label>
                        <input type="text" id="productSearch"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 disabled-field bg-gray-100 text-gray-700"
                            placeholder="พิมพ์ชื่อสามัญ..." oninput="autocompleteSearch(this.value)" autocomplete="off"
                            value="{{ old('chemical_name_th', $registration->chemical_name_th) }}" disabled />
                        <ul id="autocomplete-list"
                            class="absolute z-10 bg-white border w-80 rounded-2xl shadow max-h-60 overflow-y-auto hidden">
                        </ul>
                        <input type="hidden" id="hazardous_name_th" name="chemical_name_th"
                            value="{{ old('chemical_name_th', $registration->chemical_name_th) }}" />
                        <input type="hidden" id="formulation_ratio" name="common_name"
                            value="{{ old('common_name', $registration->common_name) }}" />
                        <input type="hidden" id="chemical_imports_id" name="chemical_imports_id"
                            value="{{ old('chemical_imports_id', $registration->chemical_imports_id) }}" />
                    </div>

                    <div>
                        <label
                            class="mx-3 text-base block text-gray-700 mb-1 mt-3">สูตรอัตราส่วนผสมของสารสำคัญและลักษณะ</label>
                        <input type="text"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            name="formula_of_ratio"
                            value="{{ old('formula_of_ratio', $registration->formula_of_ratio) }}" />
                        @error('formula_of_ratio')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ผู้ขอขึ้นทะเบียน</label>
                        <div class="dropdown" id="registrantDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="registrantBtn">--
                                เลือกผู้ขอขึ้นทะเบียน --</div>
                            <div class="dropdown-list" id="registrantList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกผู้ขอขึ้นทะเบียน --
                                </div>
                                @foreach ($companies as $company)
                                    {{-- @if ($company->id != 4) --}}
                                        <div class="dropdown-item" data-value="{{ $company->full_name }}">
                                            {{ $company->full_name }}</div>
                                    {{-- @endif --}}
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="registrant" id="registrantInput"
                            value="{{ old('registrant', $registration->registrant) }}">
                        @error('registrant')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชนิดทะเบียน</label>
                        <div class="dropdown" id="typeRegistrationDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="typeRegistrationBtn">--
                                เลือกชนิดทะเบียน --</div>
                            <div class="dropdown-list" id="typeRegistrationList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกชนิดทะเบียน --</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 2">ชนิดที่ 2</div>
                                <div class="dropdown-item" data-value="ชนิดที่ 3">ชนิดที่ 3</div>
                            </div>
                        </div>
                        <input type="hidden" name="type_registration" id="typeRegistrationInput"
                            value="{{ old('type_registration', $registration->type_registration) }}">
                        @error('type_registration')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ประเภททะเบียน</label>
                        <div class="dropdown" id="registrationTypeDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="registrationTypeBtn">--
                                เลือกประเภททะเบียน --</div>
                            <div class="dropdown-list" id="registrationTypeList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกประเภททะเบียน --</div>
                                <div class="dropdown-item" data-value="นำเข้า (สารเข้มข้น)">นำเข้า (สารเข้มข้น)</div>
                                <div class="dropdown-item" data-value="นำเข้า (สำเร็จรูป)">นำเข้า (สำเร็จรูป)</div>
                                <div class="dropdown-item" data-value="ผลิต (ผสมปรุงแต่ง)">ผลิต (ผสมปรุงแต่ง)</div>
                                <div class="dropdown-item" data-value="นำเข้า (แบ่งบรรจุ)">นำเข้า (แบ่งบรรจุ)</div>
                            </div>
                        </div>
                        <input type="hidden" name="registration_type" id="registrationTypeInput"
                            value="{{ old('registration_type', $registration->registration_type) }}">
                        @error('registration_type')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อการค้า</label>
                        <input type="text"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            name="trade_name" value="{{ old('trade_name', $registration->trade_name) }}" />
                        @error('trade_name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อการที่</label>
                        <div class="dropdown" id="namePositionDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="namePositionBtn">--
                                เลือกชื่อการที่ --</div>
                            <div class="dropdown-list" id="namePositionList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกชื่อการที่ --</div>
                                <div class="dropdown-item" data-value="T">T</div>
                                <div class="dropdown-item" data-value="-">-</div>
                                <div class="dropdown-item" data-value="1">1</div>
                                <div class="dropdown-item" data-value="2">2</div>
                                <div class="dropdown-item" data-value="3">3</div>
                            </div>
                        </div>
                        <input type="hidden" name="name_position" id="namePositionInput"
                            value="{{ old('name_position', $registration->name_position) }}">
                        @error('name_position')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อผู้นำเข้า</label>
                        <div class="dropdown" id="importerDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="importerBtn">--
                                เลือกผู้นำเข้า --</div>
                            <div class="dropdown-list" id="importerList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกผู้นำเข้า --</div>
                                @foreach ($companies as $company)
                                    {{-- @if ($company->id != 4) --}}
                                        <div class="dropdown-item" data-value="{{ $company->full_name }}">
                                            {{ $company->full_name }}</div>
                                    {{-- @endif --}}
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="importer" id="importerInput"
                            value="{{ old('importer', $registration->importer) }}">
                        @error('importer')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อผู้จำหน่าย</label>
                        <div class="dropdown" id="distributorDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="distributorBtn">--
                                เลือกผู้จำหน่าย --</div>
                            <div class="dropdown-list" id="distributorList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกผู้จำหน่าย --</div>
                                @foreach ($companies as $company)
                                    <div class="dropdown-item" data-value="{{ $company->full_name }}">
                                        {{ $company->full_name }}</div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="distributor" id="distributorInput"
                            value="{{ old('distributor', $registration->distributor) }}">
                        @error('distributor')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อผู้ผลิตและแหล่งผลิต</label>
                        <input type="text"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                            name="manufacturer" value="{{ old('manufacturer', $registration->manufacturer) }}" />
                        @error('manufacturer')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ประเภทของการใช้</label>
                        <div class="dropdown" id="typeOfUseDropdown">
                            <div style="height: 50px;" class="text-gray-500 dropdown-btn" id="typeOfUseBtn">--
                                เลือกประเภทของการใช้ --</div>
                            <div class="dropdown-list" id="typeOfUseList">
                                <div class="dropdown-item text-gray-500" data-value="">-- เลือกประเภทของการใช้ --
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
                            value="{{ old('type_of_use', $registration->type_of_use) }}">
                        @error('type_of_use')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">รายละเอียดขนาดบรรจุ</label>
                        <textarea name="packaging_size_details"
                            class="w-full p-3 border rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2">{{ old('packaging_size_details', $registration->packaging_size_details) }}</textarea>
                        @error('packaging_size_details')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันที่ยื่นคำขอ</label>
                        <input type="date" name="date_submit_request"
                            value="{{ old('date_submit_request', $registration->date_submit_request) }}"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('date_submit_request')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่รับคำขอ</label>
                        <input type="text" name="request_number_1"
                            value="{{ old('request_number_1', $registration->request_number_1) }}"
                            class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('request_number_1')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}


                    <div class="md:col-span-2">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/3">
                                <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันที่ยื่นคำขอ</label>
                                <input type="date" name="date_submit_request"
                                    value="{{ old('date_submit_request', $registration->date_submit_request) }}"
                                    autocomplete="off"
                                    class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('date_submit_request')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/3">
                                <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่รับคำขอ</label>
                                <input type="text" name="request_number_1"
                                    value="{{ old('request_number_1', $registration->request_number_1) }}"
                                    class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('request_number_1')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full md:w-1/3">

                            </div>
                        </div>
                    </div>




                    <div class="md:col-span-2">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/3">
                                <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันที่ยื่น Phase
                                    III</label>
                                <input type="date" name="date_request_phase_3"
                                    value="{{ old('date_request_phase_3', $registration->date_request_phase_3) }}"
                                    autocomplete="off"
                                    class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('date_request_phase_3')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/3">
                                <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลข # Phase III</label>
                                <input type="text"
                                    value="{{ old('request_number_phase_3', $registration->request_number_phase_3) }}"
                                    class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    name="request_number_phase_3" />
                                @error('request_number_phase_3')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full md:w-1/3">
                                <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลข # Phase I</label>
                                <input type="text" name="request_number_phase_1"
                                    value="{{ old('request_number_phase_1', $registration->request_number_phase_1) }}"
                                    class="w-full p-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                @error('request_number_phase_1')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">อื่นๆ (ระบุ)</label>
                        <textarea name="remarks" class="w-full p-3 border rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="2">{{ old('remarks', $registration->remarks) }}</textarea>
                        @error('remarks')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-center gap-4 pt-4">
                <a href="{{ route('newregis.index') }}"
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

    <script>
        document.getElementById('menu-newregisall')?.classList.add('side-menu--active');

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

                // Restore old value from Laravel if available
                if (oldValue) {
                    const match = [...items].find(i => i.dataset.value == oldValue);
                    if (match) updateBtn(match.textContent, match.dataset.value);
                    else updateBtn(initial.textContent, ""); // If no match, set to default
                } else {
                    // Set initial state if no old value
                    const initial = [...items].find(item => item.dataset.value === "");
                    if (initial) updateBtn(initial.textContent, "");
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

            // Setup for all dropdowns, passing the current $registration data
            setupDropdown('registrantBtn', 'registrantList', 'registrantInput',
                "{{ old('registrant', $registration->registrant) }}");
            setupDropdown('typeRegistrationBtn', 'typeRegistrationList', 'typeRegistrationInput',
                "{{ old('type_registration', $registration->type_registration) }}");
            setupDropdown('registrationTypeBtn', 'registrationTypeList', 'registrationTypeInput',
                "{{ old('registration_type', $registration->registration_type) }}");
            setupDropdown('namePositionBtn', 'namePositionList', 'namePositionInput',
                "{{ old('name_position', $registration->name_position) }}");
            setupDropdown('importerBtn', 'importerList', 'importerInput',
                "{{ old('importer', $registration->importer) }}");
            setupDropdown('distributorBtn', 'distributorList', 'distributorInput',
                "{{ old('distributor', $registration->distributor) }}");
            setupDropdown('typeOfUseBtn', 'typeOfUseList', 'typeOfUseInput',
                "{{ old('type_of_use', $registration->type_of_use) }}");

            // Manually pre-fill the productSearch input for the autocomplete
            // This is needed because its value is not directly bound to old() or $registration
            productSearchInput.value = "{{ old('chemical_name_th', $registration->chemical_name_th) }}";
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
                    window.location.href = "{{ route('newregis.productall') }}";
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
