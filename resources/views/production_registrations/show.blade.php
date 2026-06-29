<style>
    .document-pdf-toolbar {
        align-items: center;
        background: #2f3133;
        color: #ffffff;
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        min-height: 3rem;
        padding: 0.35rem 0.75rem;
    }

    .document-pdf-toolbar button {
        align-items: center;
        background: #3a3c3f;
        border: 1px solid #4b4d50;
        border-radius: 0.4rem;
        color: #ffffff;
        display: inline-flex;
        font-size: 1rem;
        font-weight: 700;
        height: 2.2rem;
        justify-content: center;
        line-height: 1;
        width: 2.2rem;
    }

    .document-pdf-toolbar button:hover:not(:disabled) {
        background: #4a4d50;
    }

    .document-pdf-toolbar button:disabled {
        cursor: not-allowed;
        opacity: 0.4;
    }

    .document-pdf-toolbar svg {
        height: 1.15rem;
        width: 1.15rem;
    }

    .document-pdf-stage {
        align-items: flex-start;
        background: #f3f4f6;
        display: flex;
        flex: 1;
        justify-content: center;
        overflow: auto;
        padding: 1rem;
    }

    .document-pdf-stage canvas {
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
        max-width: none;
    }
</style>
<x-app-layout>
    <div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-2xl space-y-10 mt-6">
        <h2 class="text-4xl font-extrabold text-gray-700 mb-8 pb-4 text-center border-b border-gray-300">
            รายละเอียดข้อมูลทะเบียนผลิต
        </h2>

        @php
            // ฟังก์ชันช่วยแปลงวันที่ให้เป็น พ.ศ. dd/mm/yyyy
            function beDate($value)
            {
                if (empty($value)) {
                    return '-';
                }
                try {
                    $d = \Carbon\Carbon::parse($value);
                    $yyyyBE = $d->year + 543;
                    return $d->format('d/m/') . $yyyyBE;
                } catch (\Exception $e) {
                    return '-';
                }
            }

            // แม็ปประเภททะเบียนให้ตรงกับตัวเลือกในฟอร์มแก้ไข
            $registrationTypeMap = [
                'T' => 'T : นำเข้าสารเข้มข้น',
                'I' => 'I : นำเข้าสำเร็จรูป',
                'F' => 'F : ผลิตผสมปรุงแต่ง',
                'R' => 'R : ผลิตแบ่งบรรจุ (จากนำเข้า)',
                'R(F)' => 'R(F) : ผลิตแบ่งบรรจุ (จากผสมปรุงแต่ง)',
                'F(E)' => 'F(E) : ผลิตเพื่อส่งออก',
            ];

            // ชนิดทะเบียน ให้ตรงกับดรอปดาวน์ "ชนิดทะเบียน"
            $typeProductionMap = [
                'ชนิดที่ 1' => 'ชนิดที่ 1',
                'ชนิดที่ 2' => 'ชนิดที่ 2',
                'ชนิดที่ 3' => 'ชนิดที่ 3',
                'ชนิดที่ 4' => 'ชนิดที่ 4',
            ];

            // ประเภทของการใช้ ให้ตรงกับดรอปดาวน์ "ประเภทของการใช้"
            $usageMap = [
                'A : Acaricide (สารกำจัดไรศัตรูพืช)' => 'A : Acaricide (สารกำจัดไรศัตรูพืช)',
                'F : Fungicide (สารป้องกันกำจัดโรคพืช)' => 'F : Fungicide (สารป้องกันกำจัดโรคพืช)',
                'H : Herbicide (สารกำจัดวัชพืช)' => 'H : Herbicide (สารกำจัดวัชพืช)',
                'I : Insecticide (สารกำจัดแมลง)' => 'I : Insecticide (สารกำจัดแมลง)',
                'M : Molluscicide (สารกำจัดหอย)' => 'M : Molluscicide (สารกำจัดหอย)',
                'N : Nematicide (สารกำจัดไส้เดือนฝอย)' => 'N : Nematicide (สารกำจัดไส้เดือนฝอย)',
                'P : PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)' =>
                    'P : PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)',
                'R : Rodenticide (สารกำจัดหนู)' => 'R : Rodenticide (สารกำจัดหนู)',
            ];

            // ช่วยแสดงค่าโดย fallback เป็น '-' เมื่อค่าว่าง
            function showOrDash($v)
            {
                return $v === null || $v === '' ? '-' : $v;
            }

            // เตรียมค่าที่จะแสดงให้ตรงกับฟอร์มแก้ไข
            $companyName = $product->company->full_name ?? '-';
            $importerName = $product->importerCompany->full_name ?? '-';
            $distributorName = $product->distributorCompany->full_name ?? '-';

            // ประเภททะเบียน: รองรับทั้งกรณีเก็บเป็นโค้ด (T/I/...) หรือเก็บเป็นข้อความยาว
            $regTypeRaw = $product->registration_type ?? '';
            $regTypeShown =
                $registrationTypeMap[$regTypeRaw] ??
                ($registrationTypeMap[trim(explode(' ', $regTypeRaw)[0])] ?? showOrDash($regTypeRaw));

            // ชนิดทะเบียน
            $typeProdRaw = $product->type_production_registration ?? '';
            $typeProdShown = $typeProductionMap[$typeProdRaw] ?? showOrDash($typeProdRaw);

            // ประเภทของการใช้
            $usageRaw = $product->usage_production_registration ?? '';
            $usageShown = $usageMap[$usageRaw] ?? showOrDash($usageRaw);

            $currentDocumentPath = $product->additional_document ?: $product->document;
            $currentDocumentExists = false;
            $additionalDocumentUrl = null;
            $additionalDocumentViewerUrl = null;
            $additionalDocumentName = null;
            $productionFiles = $product->files ?? collect();
            $approvalDocumentTypeCode = 'prod_license';
            $approvalFiles = $productionFiles->where('document_type_code', $approvalDocumentTypeCode);
            $registrationFiles = $productionFiles->reject(function ($file) use ($approvalDocumentTypeCode) {
                return $file->document_type_code === $approvalDocumentTypeCode;
            });

            if ($productionFiles->isEmpty() && $currentDocumentPath) {
                $currentDocumentExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($currentDocumentPath);

                if (!$currentDocumentExists && $product->document && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->document)) {
                    $currentDocumentPath = $product->document;
                    $currentDocumentExists = true;
                }

                if ($currentDocumentExists) {
                    $additionalDocumentUrl = route('createproduct.additional-document', $product);
                    $additionalDocumentViewerUrl = $additionalDocumentUrl . '#toolbar=0&navpanes=0&scrollbar=0';
                    $additionalDocumentName = $product->additional_document
                        ? ($product->document ?: basename($product->additional_document))
                        : basename($product->document);
                }
            }
        @endphp

        {{-- Section: ข้อมูลการนำเข้าทั่วไป (จัดหัวข้อ/ลำดับให้ตรงกับหน้าแก้ไข) --}}
        <div>
            <h3
                class="text-2xl font-semibold text-white bg-gradient-to-r from-blue-400 to-indigo-400 px-4 py-3 rounded-t-md">
                ข้อมูลการนำเข้าทั่วไป
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-2 gap-6 mt-4">

                {{-- เลขที่ทะเบียน --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่ทะเบียน</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->registration_number) }}
                    </p>
                </div>

                {{-- วันหมดอายุ (ทะเบียน) --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุ</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ beDate($product->expired_license_date) }}
                    </p>
                </div>

                {{-- บริษัทที่ขึ้นทะเบียน --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">บริษัทที่ขึ้นทะเบียน</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ $companyName }}</p>
                </div>

                {{-- เปอร์เซ็นต์และสูตร --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เปอร์เซ็นต์และสูตร</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->composition) }}
                    </p>
                </div>

                {{-- ชื่อวัตถุอันตราย (ไทย) --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อวัตถุอันตราย (ไทย)</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->chemical_name_th) }}
                    </p>
                </div>

                {{-- ชื่อวัตถุอันตราย (อังกฤษ) --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อวัตถุอันตราย (อังกฤษ)</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->chemical_name_en) }}
                    </p>
                </div>

                {{-- ผู้ผลิตและแหล่งผลิต --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ผู้ผลิตและแหล่งผลิต</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->manufacturer) }}
                    </p>
                </div>

                {{-- ประเภททะเบียน --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ประเภททะเบียน</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ $regTypeShown }}</p>
                </div>

                {{-- ชื่อผู้นำเข้า --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อผู้นำเข้า</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ $importerName }}</p>
                </div>

                {{-- ชื่อผู้จำหน่าย --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อผู้จำหน่าย</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ $distributorName }}</p>
                </div>

                {{-- ชื่อการค้า --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อการค้า</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->trade_name) }}</p>
                </div>

                {{-- ชื่อการค้าที่ --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อการค้าที่</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->trade_name_at) }}</p>
                </div>

                {{-- ชนิดทะเบียน --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชนิดทะเบียน</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ $typeProdShown }}</p>
                </div>

                {{-- ประเภทของการใช้ --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ประเภทของการใช้</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ $usageShown }}</p>
                </div>

                {{-- กลุ่มสาร --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">กลุ่มสาร</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->group_of_substances) }}</p>
                </div>

                {{-- พืช --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">พืช</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->plant) }}</p>
                </div>

                {{-- ศัตรูพืช --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ศัตรูพืช</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->pests) }}</p>
                </div>

                {{-- ปริมาณ --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ปริมาณ</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->production_license_quantity) }}</p>
                </div>

                {{-- เลขที่ใบอนุญาต --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่ใบอนุญาต</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->registration_number_pass) }}</p>
                </div>

                {{-- วันหมดอายุใบอนุญาต --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบอนุญาต</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ beDate($product->production_license_expiry) }}</p>
                </div>

                {{-- ใบอนุญาตเลขที่เดิม --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ใบอนุญาตเลขที่เดิม</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->production_license_number) }}</p>
                </div>

                {{-- วันหมดอายุใบอนุญาตเดิม --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบอนุญาตเดิม</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->expired_at) }}</p>
                </div>

                {{-- ใบแจ้งครอบครอง วอ.2 --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ใบแจ้งครอบครอง วอ.2</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->possession_form_wo2) }}</p>
                </div>

                {{-- วันหมดอายุใบแจ้งครอบครอง วอ.2 --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบแจ้งครอบครอง วอ.2</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">
                        {{ showOrDash($product->possession_form_expiry) }}</p>
                </div>

                {{-- รายละเอียดขนาดบรรจุ --}}
                <div class="md:col-span-2">
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">รายละเอียดขนาดบรรจุ</label>
                    <textarea disabled class="text-gray-700 bg-gray-100 w-full p-3 border rounded-2xl" rows="2">{{ showOrDash($product->packaging_size_details) }}</textarea>
                </div>
            </div>
        </div>

        @if ($productionFiles->isNotEmpty())
            <div class="pt-4 space-y-6">
                @if ($registrationFiles->isNotEmpty())
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-3">ไฟล์ทะเบียนผลิต</h3>
                        <div class="space-y-2">
                            @foreach ($registrationFiles as $file)
                                <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-gray-700 font-medium">{{ $file->original_name ?: basename($file->file_path) }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ optional($file->created_at)->format('d/m/Y H:i') }}
                                            @if ($file->file_size)
                                                · {{ number_format($file->file_size / 1024, 1) }} KB
                                            @endif
                                        </p>
                                    </div>
                                    @canany('import_data_manufacture read')
                                    <button type="button"
                                        data-file-url="{{ route('createproduct.file', [$product, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
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
                    </div>
                @endif

                @if ($approvalFiles->isNotEmpty())
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-3">ไฟล์ใบอนุญาตผลิต</h3>
                        <div class="space-y-2">
                            @foreach ($approvalFiles as $file)
                                <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-gray-700 font-medium">{{ $file->original_name ?: basename($file->file_path) }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ optional($file->created_at)->format('d/m/Y H:i') }}
                                            @if ($file->file_size)
                                                · {{ number_format($file->file_size / 1024, 1) }} KB
                                            @endif
                                        </p>
                                    </div>
                                    @canany('import_data_manufacture read')
                                    <button type="button"
                                        data-file-url="{{ route('createproduct.file', [$product, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
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
                    </div>
                @endif
            </div>
        @endif

        @if ($currentDocumentPath && $currentDocumentExists)
            <div class="pt-4">
                <button type="button"
                    data-file-url="{{ $additionalDocumentViewerUrl }}"
                    data-file-name="{{ $additionalDocumentName }}"
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

        <div id="productionFileModal"
            class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 px-4 py-6">
            <div class="bg-white max-w-5xl mx-auto h-full rounded-lg shadow-lg flex flex-col overflow-hidden">
                <div class="flex items-center justify-between gap-4 px-5 py-4 border-b">
                    <h3 id="productionFileModalTitle" class="text-lg font-semibold text-gray-700 truncate">
                        เอกสาร
                    </h3>
                    <button type="button" id="closeProductionFileModal"
                        class="text-gray-500 hover:text-gray-800 text-2xl leading-none">
                        &times;
                    </button>
                </div>
                <div class="document-pdf-toolbar" oncontextmenu="return false;">
                    <button type="button" id="productionPdfPrev" title="หน้าก่อนหน้า" disabled>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
                    </button>
                    <span id="productionPdfPageInfo" class="min-w-[4.5rem] text-center text-sm font-bold">0 / 0</span>
                    <button type="button" id="productionPdfNext" title="หน้าถัดไป" disabled>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
                    </button>
                    <button type="button" id="productionPdfZoomOut" title="ย่อ" disabled>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M8 11h6"></path><path d="m21 21-4.3-4.3"></path></svg>
                    </button>
                    <span id="productionPdfZoomLabel" class="min-w-[4rem] text-center text-sm font-bold">125%</span>
                    <button type="button" id="productionPdfZoomIn" title="ขยาย" disabled>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M8 11h6"></path><path d="M11 8v6"></path><path d="m21 21-4.3-4.3"></path></svg>
                    </button>
                    <button type="button" id="productionPdfFullscreen" title="เต็มจอ" disabled>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 3H5a2 2 0 0 0-2 2v3"></path><path d="M16 3h3a2 2 0 0 1 2 2v3"></path><path d="M8 21H5a2 2 0 0 1-2-2v-3"></path><path d="M16 21h3a2 2 0 0 0 2-2v-3"></path></svg>
                    </button>
                </div>
                <div id="productionFileViewer"
                    class="document-pdf-stage"
                    oncontextmenu="return false;">
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-center gap-4 pt-4">
            <a href="{{ route('createproduct.index') }}"
                class="bg-gray-500 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg shadow-md flex items-center justify-center">
                ย้อนกลับ
            </a>
        </div>
    </div>

    <script>
        document.getElementById('menu-manufacture')?.classList.add('side-menu--active');
    </script>

    @php
        $pdfWatermarkLogoPath = config('pdf.tiled_watermark.logo_path', 'images/logo.png');
        $pdfWatermarkLogoPublicRoot = str_replace('\\', '/', public_path());
        $pdfWatermarkLogoNormalizedPath = str_replace('\\', '/', $pdfWatermarkLogoPath);

        if (filter_var($pdfWatermarkLogoPath, FILTER_VALIDATE_URL)) {
            $pdfWatermarkLogoUrl = $pdfWatermarkLogoPath;
        } elseif (strpos($pdfWatermarkLogoNormalizedPath, $pdfWatermarkLogoPublicRoot . '/') === 0) {
            $pdfWatermarkLogoUrl = rtrim(request()->getBaseUrl(), '/') . '/' . ltrim(substr($pdfWatermarkLogoNormalizedPath, strlen($pdfWatermarkLogoPublicRoot)), '/');
        } else {
            $pdfWatermarkLogoUrl = rtrim(request()->getBaseUrl(), '/') . '/' . ltrim(preg_replace('#^/?public/#', '', $pdfWatermarkLogoNormalizedPath), '/');
        }

        $pdfTiledWatermark = [
            'enabled' => (bool) config('pdf.tiled_watermark.enabled', true),
            'color' => (bool) config('pdf.tiled_watermark.color', true),
            'opacity' => (float) config('pdf.tiled_watermark.opacity', 0.08),
            'logoUrl' => $pdfWatermarkLogoUrl,
            'logoSize' => (int) config('pdf.tiled_watermark.logo_size', 120),
            'gapX' => (int) config('pdf.tiled_watermark.gap_x', 180),
            'gapY' => (int) config('pdf.tiled_watermark.gap_y', 160),
            'angle' => (float) config('pdf.tiled_watermark.angle', -30),
        ];
    @endphp

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pdfTiledWatermark = @json($pdfTiledWatermark);
            const modal = document.getElementById('productionFileModal');
            const viewer = document.getElementById('productionFileViewer');
            const title = document.getElementById('productionFileModalTitle');
            const closeBtn = document.getElementById('closeProductionFileModal');
            const prevBtn = document.getElementById('productionPdfPrev');
            const nextBtn = document.getElementById('productionPdfNext');
            const pageInfo = document.getElementById('productionPdfPageInfo');
            const zoomOutBtn = document.getElementById('productionPdfZoomOut');
            const zoomInBtn = document.getElementById('productionPdfZoomIn');
            const zoomLabel = document.getElementById('productionPdfZoomLabel');
            const fullscreenBtn = document.getElementById('productionPdfFullscreen');
            let renderToken = 0;
            let activePdf = null;
            let activePdfPage = 1;
            let activePdfScale = 1.25;
            let activePdfRenderTask = null;
            let pdfWatermarkImagePromise = null;
            let pdfWatermarkGrayscaleImagePromise = null;

            if (window.pdfjsLib) {
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            }

            function updatePdfToolbar() {
                const hasPdf = !!activePdf;
                const totalPages = activePdf?.numPages || 0;

                pageInfo.textContent = hasPdf ? `${activePdfPage} / ${totalPages}` : '0 / 0';
                zoomLabel.textContent = `${Math.round(activePdfScale * 100)}%`;
                prevBtn.disabled = !hasPdf || activePdfPage <= 1;
                nextBtn.disabled = !hasPdf || activePdfPage >= totalPages;
                zoomOutBtn.disabled = !hasPdf || activePdfScale <= 0.5;
                zoomInBtn.disabled = !hasPdf || activePdfScale >= 3;
                fullscreenBtn.disabled = !hasPdf;
            }

            function resetPdfState() {
                renderToken++;

                if (activePdfRenderTask) {
                    activePdfRenderTask.cancel();
                    activePdfRenderTask = null;
                }

                activePdf = null;
                activePdfPage = 1;
                activePdfScale = 1.25;
                updatePdfToolbar();
            }

            function closeModal() {
                resetPdfState();
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                viewer.innerHTML = '';
            }

            function getPdfWatermarkImage() {
                if (!pdfTiledWatermark.enabled || !pdfTiledWatermark.logoUrl) {
                    return Promise.resolve(null);
                }

                if (!pdfWatermarkImagePromise) {
                    pdfWatermarkImagePromise = new Promise(resolve => {
                        const image = new Image();
                        image.crossOrigin = 'anonymous';
                        image.onload = () => resolve(image);
                        image.onerror = () => resolve(null);
                        image.src = pdfTiledWatermark.logoUrl;
                    });
                }

                return pdfWatermarkImagePromise;
            }

            async function getPdfWatermarkDrawable() {
                const image = await getPdfWatermarkImage();

                if (!image || pdfTiledWatermark.color) {
                    return image;
                }

                if (!pdfWatermarkGrayscaleImagePromise) {
                    pdfWatermarkGrayscaleImagePromise = new Promise(resolve => {
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        const width = image.naturalWidth || image.width;
                        const height = image.naturalHeight || image.height;

                        canvas.width = width;
                        canvas.height = height;
                        context.drawImage(image, 0, 0, width, height);

                        const imageData = context.getImageData(0, 0, width, height);
                        const data = imageData.data;

                        for (let i = 0; i < data.length; i += 4) {
                            const gray = data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114;
                            data[i] = gray;
                            data[i + 1] = gray;
                            data[i + 2] = gray;
                        }

                        context.putImageData(imageData, 0, 0);

                        const grayscaleImage = new Image();
                        grayscaleImage.onload = () => resolve(grayscaleImage);
                        grayscaleImage.onerror = () => resolve(image);
                        grayscaleImage.src = canvas.toDataURL('image/png');
                    });
                }

                return pdfWatermarkGrayscaleImagePromise;
            }

            async function drawTiledPdfWatermark(context, canvas, outputScale = 1) {
                if (!pdfTiledWatermark.enabled) return;

                const watermarkImage = await getPdfWatermarkDrawable();
                if (!watermarkImage) return;

                const maxLogoSize = Number(pdfTiledWatermark.logoSize) || 120;
                const imageWidth = watermarkImage.naturalWidth || watermarkImage.width || maxLogoSize;
                const imageHeight = watermarkImage.naturalHeight || watermarkImage.height || maxLogoSize;
                const ratio = imageWidth >= imageHeight ? maxLogoSize / imageWidth : maxLogoSize / imageHeight;
                const logoWidth = imageWidth * ratio;
                const logoHeight = imageHeight * ratio;
                const tileWidth = logoWidth + (Number(pdfTiledWatermark.gapX) || 180);
                const tileHeight = logoHeight + (Number(pdfTiledWatermark.gapY) || 160);
                const angle = ((Number(pdfTiledWatermark.angle) || 0) * Math.PI) / 180;

                context.save();
                context.setTransform(outputScale, 0, 0, outputScale, 0, 0);
                context.globalAlpha = Math.max(0, Math.min(1, Number(pdfTiledWatermark.opacity) || 0.08));

                for (let y = -tileHeight; y < canvas.height / outputScale + tileHeight; y += tileHeight) {
                    for (let x = -tileWidth; x < canvas.width / outputScale + tileWidth; x += tileWidth) {
                        context.save();
                        context.translate(x + logoWidth / 2, y + logoHeight / 2);
                        context.rotate(angle);
                        context.drawImage(watermarkImage, -logoWidth / 2, -logoHeight / 2, logoWidth, logoHeight);
                        context.restore();
                    }
                }

                context.restore();
            }

            async function renderActivePdfPage() {
                if (!activePdf) return;

                const token = ++renderToken;
                viewer.innerHTML = '<p class="m-auto text-gray-500 py-8">กำลังโหลดเอกสาร...</p>';

                try {
                    if (activePdfRenderTask) {
                        activePdfRenderTask.cancel();
                        activePdfRenderTask = null;
                    }

                    const page = await activePdf.getPage(activePdfPage);
                    if (token !== renderToken) return;

                    const viewport = page.getViewport({ scale: activePdfScale });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    const outputScale = window.devicePixelRatio || 1;

                    canvas.width = Math.floor(viewport.width * outputScale);
                    canvas.height = Math.floor(viewport.height * outputScale);
                    canvas.style.width = `${Math.floor(viewport.width)}px`;
                    canvas.style.height = `${Math.floor(viewport.height)}px`;
                    context.setTransform(outputScale, 0, 0, outputScale, 0, 0);

                    viewer.innerHTML = '';
                    viewer.appendChild(canvas);
                    activePdfRenderTask = page.render({ canvasContext: context, viewport });
                    await activePdfRenderTask.promise;
                    activePdfRenderTask = null;

                    if (token !== renderToken) return;
                    await drawTiledPdfWatermark(context, canvas, outputScale);
                    updatePdfToolbar();
                } catch (error) {
                    if (error?.name === 'RenderingCancelledException') return;

                    if (token === renderToken) {
                        viewer.innerHTML = '<p class="m-auto text-red-500 py-8">ไม่สามารถแสดงเอกสารนี้ได้</p>';
                    }
                }
            }

            async function renderPdf(url) {
                resetPdfState();
                const token = ++renderToken;
                viewer.innerHTML = '<p class="m-auto text-gray-500 py-8">กำลังโหลดเอกสาร...</p>';

                if (!window.pdfjsLib) {
                    viewer.innerHTML = '<p class="m-auto text-red-500 py-8">ไม่สามารถโหลดตัวอ่าน PDF ได้</p>';
                    return;
                }

                try {
                    const pdf = await pdfjsLib.getDocument(url.split('#')[0]).promise;
                    if (token !== renderToken) return;

                    activePdf = pdf;
                    activePdfPage = 1;
                    activePdfScale = 1.25;
                    updatePdfToolbar();
                    await renderActivePdfPage();
                } catch (error) {
                    if (token === renderToken) {
                        viewer.innerHTML = '<p class="m-auto text-red-500 py-8">ไม่สามารถแสดงเอกสารนี้ได้</p>';
                    }
                }
            }

            prevBtn?.addEventListener('click', () => {
                if (!activePdf || activePdfPage <= 1) return;

                activePdfPage--;
                updatePdfToolbar();
                renderActivePdfPage();
            });

            nextBtn?.addEventListener('click', () => {
                if (!activePdf || activePdfPage >= activePdf.numPages) return;

                activePdfPage++;
                updatePdfToolbar();
                renderActivePdfPage();
            });

            zoomOutBtn?.addEventListener('click', () => {
                if (!activePdf || activePdfScale <= 0.5) return;

                activePdfScale = Math.max(0.5, activePdfScale - 0.25);
                updatePdfToolbar();
                renderActivePdfPage();
            });

            zoomInBtn?.addEventListener('click', () => {
                if (!activePdf || activePdfScale >= 3) return;

                activePdfScale = Math.min(3, activePdfScale + 0.25);
                updatePdfToolbar();
                renderActivePdfPage();
            });

            fullscreenBtn?.addEventListener('click', () => {
                const pane = modal.querySelector('.bg-white') || viewer;

                if (document.fullscreenElement) {
                    document.exitFullscreen?.();
                    return;
                }

                pane?.requestFullscreen?.();
            });

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
</x-app-layout>
