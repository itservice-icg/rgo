<x-app-layout>
    <main class="flex-1 overflow-x-hidden overflow-y-auto">
        <div class="container mx-auto px-6 py-6">
            <h1 class="text-4xl font-extrabold text-center text-indigo-700 mt-5 mb-10 tracking-wide">
                รายละเอียดการขึ้นทะเบียนใหม่
            </h1>

            <div class="bg-white rounded-2xl overflow-hidden shadow-lg p-8 border border-gray-200">
                {{-- รายละเอียดข้อมูลยา --}}
                <div class="grid grid-cols-3 md:grid-cols-2 gap-6 text-lg text-gray-700">
                    {{-- <div>
                        <p class="font-semibold text-indigo-600">เลขที่ทะเบียน:</p>
                        <p>{{ $drug->registration_number ?? '-' }}</p>
                    </div> --}}
                    <div>
                        <p class="font-semibold text-indigo-600 mb-2">วันที่ยื่นคำขอ</p>
                        @if ($drug->date_submit_request)
                            <p>{{ \Carbon\Carbon::parse($drug->date_submit_request)->addYears(+543)->format('d/m/Y') }}
                            </p>
                        @else
                            <p>-</p>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-indigo-600 mb-2">บริษัทที่ขึ้นทะเบียน</p>
                        <p>{{ $drug->registrant ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-indigo-600 mb-2">ชื่อทางการค้า</p>
                        <p>{{ $drug->trade_name ?? '-' }}</p>
                    </div>
                </div>

                @php
                    $labels = [
                        1 => 'คณะ PDC อนุมัติให้ดำเนินการขึ้นทะเบียน',
                        2 => 'นำเข้าตัวอย่าง',
                        3 => 'ส่งตัวอย่างข้อมูลศึกษาความเป็นพิษ (ทำTox)',
                        4 => 'ยื่นคำขอขึ้นทะเบียน',
                        5 => 'แผนการทดลอง Eff, PHI (ถ้ามี) + Phase1 + ผลวิเคราะห์ (อนุมัติ)',
                        6 => 'ยื่น Phase3 (ผลการทดลอง Eff, PHI (ถ้ามี) อนุมัติ + ผลวิเคราะห์อนุมัติ)',
                        7 => 'Phase3 อนุมัติ (ยื่นเอกสารเข้าประชุมพิจารณาขึ้นทะเบียน)',
                        8 => 'ยื่นขอออกทะเบียน',
                    ];

                    // สร้างข้อมูลสรุปของแต่ละขั้นตอนจาก step_summary
                    $stepsInfo = [];
                    for ($i = 1; $i <= 8; $i++) {
                        $s = $drug->step_summary[$i] ?? null;
                        $lastIndex = data_get($s, 'last_index');
                        $total = is_numeric($lastIndex) ? ($lastIndex + 1) : 0;
                        $unchecked = (int) data_get($s, 'unchecked_count', 0);
                        $checked = max(0, $total - $unchecked);
                        $stepsInfo[$i] = ['total' => $total, 'unchecked' => $unchecked, 'checked' => $checked];
                    }

                    // หากยังไม่มีการบันทึกใดๆ ให้แสดงขั้นตอนที่ 1 เป็น current
                    $anyChecked = collect($stepsInfo)->pluck('checked')->sum() > 0;
                @endphp

                {{-- Timeline ของขั้นตอนการดำเนินการ --}}
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">ไทม์ไลน์การขึ้นทะเบียน</h2>
                    @php
                        // แบ่ง label เป็น chunk ละ 4 เพื่อแสดงใน 2 แถว
                        $labelChunks = array_chunk($labels, 4, true);
                    @endphp
                    @foreach ($labelChunks as $chunk)
                        <ol class="items-center sm:flex space-y-4 sm:space-y-0 mb-6 {{ $loop->first ? '' : 'mt-6' }}">
                            @foreach ($chunk as $stepNumber => $label)
                                @php
                                    $info = $stepsInfo[$stepNumber] ?? ['total'=>0,'checked'=>0,'unchecked'=>0];
                                    
                                    // ตรวจสอบว่าขั้นตอนถัดไปมีการติ๊กหรือไม่
                                    $nextStepHasChecked = false;
                                    if ($stepNumber < 8) {
                                        $nextInfo = $stepsInfo[$stepNumber + 1] ?? ['total'=>0,'checked'=>0,'unchecked'=>0];
                                        $nextStepHasChecked = $nextInfo['checked'] > 0;
                                    }
                                    
                                    // ขั้นตอนจะถือว่าเสร็จสมบูรณ์ต่อเมื่อขั้นตอนถัดไปมีการติ๊กแล้ว
                                    // ยกเว้นขั้นตอนสุดท้าย (8) ที่ดูจาก progress
                                    $isCompleted = false;
                                    if ($stepNumber == 8) {
                                        $isCompleted = $drug->progress >= 100;
                                    } else {
                                        $isCompleted = $nextStepHasChecked;
                                    }
                                    
                                    $isCurrent = false;
                                    if (!$isCompleted) {
                                        // ถ้ามีการติ๊กบางรายการ ให้มองเป็นขั้นตอนกำลังดำเนินการ
                                        if ($info['checked'] > 0) {
                                            $isCurrent = true;
                                        } elseif (!$anyChecked && $stepNumber == 1) {
                                            // หากยังไม่มีการติ๊กเลย ให้ขั้นตอนที่ 1 เป็น current
                                            $isCurrent = true;
                                        }
                                    }

                                    $iconClass = $isCompleted ? 'text-white' : 'text-blue-800 dark:text-blue-300';
                                    $bgClass = $isCompleted
                                        ? 'bg-green-500'
                                        : ($isCurrent ? 'bg-blue-500 ring-4 ring-blue-300' : 'bg-blue-100');
                                    $lineClass = $isCompleted ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-700';
                                    $dotClass = $isCurrent ? 'ring-blue-500 dark:ring-blue-500' : 'ring-white dark:ring-gray-900';
                                @endphp

                                <li class="relative mb-6 sm:mb-0 w-full sm:w-1/4">
                                    <div class="flex items-center">
                                        <div class="z-10 flex items-center justify-center w-8 h-8 rounded-full ring-0 sm:ring-8 shrink-0 {{ $bgClass }} {{ $dotClass }}">
                                            @if ($isCompleted)
                                                <svg class="w-4 h-4 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 {{ $iconClass }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            @endif
                                        </div>
                                        @if (!$loop->last || !$loop->parent->last)
                                            {{-- ซ่อนเส้นเชื่อมสำหรับขั้นตอนสุดท้ายของแต่ละแถว --}}
                                            <div class="hidden sm:flex w-full h-0.5 {{ $lineClass }}"></div>
                                        @endif
                                    </div>
                                    <div class="mt-3 flex flex-col">
                                        <h3 class="text-gray-900 dark:text-white {{ $isCurrent ? 'font-bold text-blue-600' : '' }}">ขั้นตอนที่ {{ $stepNumber }}</h3>
                                        <p class="font-normal text-gray-500 dark:text-gray-400">{!! $label !!}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endforeach
                </div>


                {{-- สถานะความคืบหน้าโดยรวม --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-lg text-gray-700 mt-6">
                    <div>
                        <p class="font-semibold text-indigo-600">สถานะความคืบหน้าโดยรวม:</p>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                            <div class="h-2.5 rounded-full
                                @if ($drug->progress < 25) bg-red-500
                                @elseif ($drug->progress < 75) bg-yellow-500
                                @else bg-green-500 @endif"
                                style="width: {{ $drug->progress }}%">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-center mt-1">
                            {{ $drug->progress }}%
                        </div>
                    </div>
                </div>
                @if ($drug->progress <= 99)
                    <div class="mt-8">
                        @php
                            $subStepsAll = [
                                1 => [
                                    'title' => 'คณะ PDC อนุมัติให้ดำเนินการขึ้นทะเบียน',
                                    'items' => [
                                        'จัดซื้อต่างประเทศ' => [
                                            'ทะเบียน',
                                            'ใบอนุญาตในประเทศผู้ผลิต',
                                            'เอกสารอนุญาตอื่นๆ',
                                        ],
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
                                        'จัดซื้อต่างประเทศ' => [
                                            'ประสานเพื่อส่งออกตัวอย่าง',
                                            'Data requirement จากผู้ผลิต',
                                        ],
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
                                            'ผล Eff +ผล PHI (ถ้ามี) ที่อนุมัติ',
                                            'เอกสารตามที่ DOA กำหนด และติดตามผล Phase III',
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
                                            'แผนกทะเบียนได้รับผล Tox Phase III ที่อนุมัติ ทำการรวบรวมข้อมูลเอกสารยื่นขอเข้าประชุมพิจารณาขึ้นทะเบียนใหม่',
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

                            // ดึงค่าจาก "แผนการทดลอง" ในขั้นตอนที่ 1
                            $planIndex = collect($subStepsAll[1]['items'])->flatten()->search('แผนการทดลอง');
                            $planNote = $checkplan ?? null;
                            $hideAcademicSteps = $planNote == 'ไม่มี';

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

                            $mappedUserDept = mapDepartment(auth()->user()->department);
                        @endphp

                        {{-- แสดงรายการทุกขั้นตอนเป็น readonly เพื่อให้เห็นการติ๊กของแต่ละขั้นตอน --}}
                        @foreach ($subStepsAll as $stepNumber => $stepData)
                            @php
                                $stepTitle = $stepData['title'];
                                $allDepartments = $stepData['items'];

                                if ($hideAcademicSteps && in_array($stepNumber, [4, 5, 6])) {
                                    $allDepartments = collect($allDepartments)
                                        ->reject(fn($_, $dept) => $dept === 'แผนกวิชาการ')
                                        ->all();
                                }

                                // แสดงทุกแผนกในโหมดดูอย่างเดียว
                                $departments = $allDepartments;
                                $savedSubSteps = $drug->stepSubSteps($stepNumber)->get()->keyBy('sub_step_index');

                                // หากขั้นตอนนี้ยังไม่มีรายการใดถูกติ๊ก ให้ข้ามการแสดงผล (ผู้ใช้ต้องการเห็นเฉพาะขั้นตอนที่มีการติ๊ก)
                                $checkedInStep = $savedSubSteps->whereNotNull('checked_at')->count() ?? 0;
                                if ($checkedInStep == 0) {
                                    continue;
                                }
                            @endphp

                            @if (count($departments) > 0)
                                <div class="mt-8 bg-gray-50 border border-gray-200 rounded-xl p-4">
                                    <h4 class="text-lg font-semibold text-indigo-600 mb-3">
                                        ขั้นตอนที่ {{ $stepNumber }}: {{ $stepTitle }}
                                    </h4>

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
                                            @endphp

                                            <div>
                                                <h5 class="text-sm font-bold text-gray-700 mb-2">{{ $dept }}</h5>
                                                <div class="space-y-2 pl-4">
                                                    @foreach ($subItems as $label)
                                                        @php
                                                            $record = $savedSubSteps[$checkboxIndex] ?? null;
                                                            $isChecked = $record && $record->checked_at;
                                                            $note = $record->created_by ?? '';
                                                            $remark = $record->remark ?? '';
                                                        @endphp
                                                        <div class="flex flex-wrap items-center gap-3">
                                                            <input disabled type="checkbox" name="sub_steps[]"
                                                                id="substep_{{ $stepNumber }}_{{ $checkboxIndex }}"
                                                                value="{{ $checkboxIndex }}"
                                                                {{ $isChecked ? 'checked' : '' }}
                                                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                            <label for="substep_{{ $stepNumber }}_{{ $checkboxIndex }}"
                                                                class="text-sm text-gray-800">{{ $label }}</label>

                                                            @if (in_array($label, ['แผนการทดลอง', 'หนังสือขอยกเว้น PHI', 'แผน PHI']))
                                                                <div id="radio_container_{{ $stepNumber }}_{{ $checkboxIndex }}"
                                                                    class="flex items-center space-x-4"
                                                                    style="{{ $isChecked ? '' : 'display: none;' }}">
                                                                    <label class="inline-flex items-center">
                                                                        <input disabled type="radio"
                                                                            class="form-radio text-green-500 w-5 h-5"
                                                                            name="sub_step_notes[{{ $checkboxIndex }}]"
                                                                            value="ไม่มี"
                                                                            {{ $note == 'ไม่มี' ? 'checked' : '' }}>
                                                                        <span class="ml-2 text-gray-800">ไม่มี</span>
                                                                    </label>
                                                                    <label class="inline-flex items-center">
                                                                        <input disabled type="radio"
                                                                            class="form-radio text-yellow-500 w-5 h-5"
                                                                            name="sub_step_notes[{{ $checkboxIndex }}]"
                                                                            value="มี"
                                                                            {{ $note == 'มี' ? 'checked' : '' }}>
                                                                        <span class="ml-2 text-gray-800">มี</span>
                                                                    </label>
                                                                </div>
                                                            @endif

                                                            <input type="text" value="{{ $remark }}"
                                                                placeholder="หมายเหตุ"
                                                                class="p-2 border rounded-md flex-1 w-48"
                                                                disabled>
                                                        </div>
                                                        @php $checkboxIndex++; @endphp
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="text-center mt-12">
                    <a href="{{ route('newregis.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow transition duration-300 ">
                        <i class="fa-solid fa-arrow-left mr-2"></i> ย้อนกลับ
                    </a>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('menu-newregis')?.classList.add('side-menu--active');
    </script>
</x-app-layout>
