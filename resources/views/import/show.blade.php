
<style>
    /* ปรับขนาดและสไตล์ของ iframe ให้เหมาะสม */
    #additionalDocumentModal {
        /* width: 100%;
        height: 80vh;
        border: none;
        border-radius: 0 0 10px 10px; */
        --tw-space-y-reverse: none !important;
    }
</style>
<x-app-layout>
    <div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-2xl space-y-10 mt-6">
        <h2 class="text-4xl font-extrabold text-gray-700 mb-8 pb-4 text-center border-b border-gray-300">
            รายละเอียดข้อมูลทะเบียนนำเข้า
        </h2>

        @php
            // ฟังก์ชันช่วยแปลงวันที่ให้เป็น พ.ศ. dd/mm/yyyy
            function beDate($value) {
                if (empty($value)) return '-';
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
                'T'   => 'T : นำเข้าสารเข้มข้น',
                'I'   => 'I : นำเข้าสำเร็จรูป',
                'F'   => 'F : ผลิตผสมปรุงแต่ง',
                'R'   => 'R : ผลิตแบ่งบรรจุ (จากนำเข้า)',
                'R(F)'=> 'R(F) : ผลิตแบ่งบรรจุ (จากผสมปรุงแต่ง)',
                'F(E)'=> 'F(E) : ผลิตเพื่อส่งออก',
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
                'P : PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)' => 'P : PlantGrowthRegulators (สารควบคุมการเจริญเติบโตของพืช)',
                'R : Rodenticide (สารกำจัดหนู)' => 'R : Rodenticide (สารกำจัดหนู)',
            ];

            // ช่วยแสดงค่าโดย fallback เป็น '-' เมื่อค่าว่าง
            function showOrDash($v) { return ($v === null || $v === '') ? '-' : $v; }

            // เตรียมค่าที่จะแสดงให้ตรงกับฟอร์มแก้ไข
            $companyName      = $product->company->full_name ?? '-';
            $importerName     = $product->importerCompany->full_name ?? '-';
            $distributorName  = $product->distributorCompany->full_name ?? '-';

            // ประเภททะเบียน: รองรับทั้งกรณีเก็บเป็นโค้ด (T/I/...) หรือเก็บเป็นข้อความยาว
            $regTypeRaw = $product->registration_type ?? '';
            $regTypeShown = $registrationTypeMap[$regTypeRaw] ?? ($registrationTypeMap[trim(explode(' ', $regTypeRaw)[0])] ?? showOrDash($regTypeRaw));

            // ชนิดทะเบียน
            $typeProdRaw = $product->type_production_registration ?? '';
            $typeProdShown = $typeProductionMap[$typeProdRaw] ?? showOrDash($typeProdRaw);

            // ประเภทของการใช้
            $usageRaw = $product->usage_production_registration ?? '';
            $usageShown = $usageMap[$usageRaw] ?? showOrDash($usageRaw);

            $additionalDocumentUrl = null;
            $additionalDocumentViewerUrl = null;
            $additionalDocumentName = null;
            $additionalDocumentExists = false;
            $importFiles = $product->files ?? collect();
            $approvalDocumentTypeCode = 'import_license';
            $approvalFiles = $importFiles->where('document_type_code', $approvalDocumentTypeCode);
            $registrationFiles = $importFiles->reject(function ($file) use ($approvalDocumentTypeCode) {
                return $file->document_type_code === $approvalDocumentTypeCode;
            });
            if ($importFiles->isEmpty() && $product->additional_document) {
                $additionalDocumentExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($product->additional_document)
                    || ($product->document && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->document));
                $additionalDocumentUrl = $additionalDocumentExists ? route('import.additional-document', $product) : null;
                $additionalDocumentViewerUrl = $additionalDocumentUrl ? $additionalDocumentUrl . '#toolbar=0&navpanes=0&scrollbar=0' : null;
                $additionalDocumentName = $product->document ?: basename($product->additional_document);
            }
        @endphp

        {{-- Section: ข้อมูลการนำเข้าทั่วไป (จัดหัวข้อ/ลำดับให้ตรงกับหน้าแก้ไข) --}}
        <div>
            <h3 class="text-2xl font-semibold text-white bg-gradient-to-r from-blue-400 to-indigo-400 px-4 py-3 rounded-t-md">
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
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->trade_name) }}</p>
                </div>

                {{-- ชื่อการค้าที่ --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ชื่อการค้าที่</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->trade_name_at) }}</p>
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
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->group_of_substances) }}</p>
                </div>

                {{-- พืช --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">พืช</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->plant) }}</p>
                </div>

                {{-- ศัตรูพืช --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ศัตรูพืช</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->pests) }}</p>
                </div>

                {{-- ปริมาณ --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ปริมาณ</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->production_license_quantity) }}</p>
                </div>

                {{-- เลขที่ใบอนุญาต --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">เลขที่ใบอนุญาต</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->registration_number_pass) }}</p>
                </div>

                {{-- วันหมดอายุใบอนุญาต --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบอนุญาต</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ beDate($product->production_license_expiry) }}</p>
                </div>

                {{-- ใบอนุญาตเลขที่เดิม --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ใบอนุญาตเลขที่เดิม</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->production_license_number) }}</p>
                </div>

                {{-- วันหมดอายุใบอนุญาตเดิม --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบอนุญาตเดิม</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ $product->expired_at ?? '-' }}</p>
                </div>

                {{-- ใบแจ้งครอบครอง วอ.2 --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">ใบแจ้งครอบครอง วอ.2</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ showOrDash($product->possession_form_wo2) }}</p>
                </div>

                {{-- วันหมดอายุใบแจ้งครอบครอง วอ.2 --}}
                <div>
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">วันหมดอายุใบแจ้งครอบครอง วอ.2</label>
                    <p class="w-full p-3 border rounded-full bg-gray-100 text-gray-700">{{ $product->possession_form_expiry ?? '-' }}</p>
                </div>

                {{-- รายละเอียดขนาดบรรจุ --}}
                <div class="md:col-span-2">
                    <label class="mx-3 text-base block text-gray-700 mb-1 mt-3">รายละเอียดขนาดบรรจุ</label>
                    <textarea disabled class="text-gray-700 bg-gray-100 w-full p-3 border rounded-2xl" rows="2">{{ showOrDash($product->packaging_size_details) }}</textarea>
                </div>
            </div>
        </div>
       @if ($importFiles->isNotEmpty())
<div class="pt-4 space-y-6">
    @if ($registrationFiles->isNotEmpty())
        <div>
            <h3 class="text-xl font-semibold text-gray-700 mb-3">ทะเบียนนำเข้า</h3>
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
    @canany('import_data_staple read')
    <button type="button"
        data-file-url="{{ route('import.file', [$product, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
        data-file-name="{{ $file->original_name ?: basename($file->file_path) }}"
        class="shrink-0 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-600 text-white shadow-md ring-1 ring-blue-700/20 transition hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
        title="ดูเอกสาร"
        aria-label="ดูเอกสาร">
        @include('components.document-pdf-icon')
    </button>
@else
    <button type="button"
        disabled
        class="shrink-0 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-gray-300 text-gray-500 shadow-sm ring-1 ring-gray-400/20 cursor-not-allowed opacity-70"
        title="ไม่มีสิทธิ์ดูเอกสาร"
        aria-label="ไม่มีสิทธิ์ดูเอกสาร">
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
            <h3 class="text-xl font-semibold text-gray-700 mb-3">ใบอนุญาตนำเข้า</h3>
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
            @canany('import_data_staple read')
                        <button type="button"
                            data-file-url="{{ route('import.file', [$product, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
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
       @if (false && $importFiles->isNotEmpty())
<div class="pt-4">
    <h3 class="text-xl font-semibold text-gray-700 mb-3">เอกสารทั้งหมด</h3>
    <div class="space-y-2">
        @foreach ($importFiles as $file)
            <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                <div class="min-w-0">
                    <p class="truncate text-gray-700 font-medium">{{ $file->original_name ?: basename($file->file_path) }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $file->document_type_code ?: '-' }}
                        ·
                        {{ optional($file->created_at)->format('d/m/Y H:i') }}
                        @if ($file->file_size)
                            · {{ number_format($file->file_size / 1024, 1) }} KB
                        @endif
                    </p>
                </div>
                <button type="button"
                    data-file-url="{{ route('import.file', [$product, $file]) }}#toolbar=0&navpanes=0&scrollbar=0"
                    data-file-name="{{ $file->original_name ?: basename($file->file_path) }}"
                    class="shrink-0 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-600 text-white shadow-md ring-1 ring-blue-700/20 transition hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
                    title="ดูเอกสาร" aria-label="ดูเอกสาร">
                    @include('components.document-pdf-icon')
                </button>
            </div>
        @endforeach
    </div>
</div>
@endif
       @if ($product->additional_document && $additionalDocumentExists)
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

        @if ($product->additional_document && $additionalDocumentExists)
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
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex justify-center gap-4 pt-4">
            <a href="{{ route('import.index') }}"
               class="bg-gray-500 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg shadow-md flex items-center justify-center">
                ย้อนกลับ
            </a>
        </div>
    </div>

    <script>
        document.getElementById('menu-inregister')?.classList.add('side-menu--active');
    </script>

    @if ($product->additional_document && $additionalDocumentExists)
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
</x-app-layout>
