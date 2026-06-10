<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChemicalRegistration;
use App\Models\DrugProgressStep;
use App\Models\ChemicalImport;
use App\Models\ProductionRegistration;
use Illuminate\Support\Facades\Log;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // This line is crucial


class ChemicalRegistrationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request)
    {
        //  dd($request->all());

        $query = ChemicalRegistration::query();
        $query->where('new_or_old', true);

        // ค้นหาตามคำค้น
        if ($request->filled('search')) {
            // Normalize search: trim, remove whitespace, lowercase
            $rawSearch = (string) $request->input('search');
            $normalized = mb_strtolower(preg_replace('/\s+/', '', $rawSearch));

            $query->where(function ($q) use ($normalized) {
                // Use REPLACE to remove spaces from columns and compare lowercase for better matching.
                $like = '%' . $normalized . '%';
                $q->whereRaw("REPLACE(LOWER(trade_name), ' ', '') LIKE ?", [$like])
                    ->orWhereRaw("REPLACE(LOWER(chemical_name_th), ' ', '') LIKE ?", [$like])
                    ->orWhereRaw("REPLACE(LOWER(chemical_name_en), ' ', '') LIKE ?", [$like])
                    ->orWhereRaw("REPLACE(LOWER(registration_number), ' ', '') LIKE ?", [$like]);
            });
        }

        // ค้นหาตามวันที่
        if ($request->filled('expiry_date_from') && $request->filled('expiry_date_to')) {
            $from = $this->convertThaiDateToCarbon($request->input('expiry_date_from'));
            $to   = $this->convertThaiDateToCarbon($request->input('expiry_date_to'))->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }


        // ฟิลเตอร์ตามสถานะ
        $statusFilter = $request->input('status_filter');
        $today = now();
        $in180Days = now()->addDays(180);

        if ($statusFilter === 'expired') {
            $query->whereDate('expired_license_number', '<', $today);
        } elseif ($statusFilter === 'soon_expired') {
            $query->whereBetween('expired_license_number', [$today, $in180Days]);
        } elseif ($statusFilter === 'new_all') {
            $query->where('new_or_old', true)
                ->where('progress', '<', 100);
        } else {
            // ขึ้นทะเบียนใหม่ (progress < 100)
            // $query->where('progress', '<', 100);
        }

        // สถิติ
        $totalNewRegistrations = ChemicalRegistration::where('new_or_old', true)->where('progress', '<', 100)->count();
        $soonExpiredCount = ChemicalRegistration::where('new_or_old', false)
            ->whereBetween('expired_license_number', [now(), now()->addDays(180)])
            ->count();

        $expiredCount = ChemicalRegistration::where('expired_license_number', '<', $today)
            ->where('new_or_old', false)
            ->count();

        $total = ChemicalRegistration::count();
        $paginatedProducts = $query->orderBy('created_at', 'desc')->paginate(5);



        $rawStructure = [
            1 => [
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
        ];

        foreach ($paginatedProducts as $product) {
            // สถานะใบอนุญาต
            $expiryDate = Carbon::parse($product->expired_license_number);
            $now = Carbon::now();

            if ($expiryDate->isPast()) {
                $product->status = 'หมดอายุ';
            } elseif ($expiryDate->diffInMonths($now) <= 6) {
                $product->status = 'ใกล้หมด';
            } else {
                $product->status = 'ใช้งานอยู่';
            }

            // Progress จาก field ที่คำนวณไว้ใน DB (หรือจะคำนวณเองก็ได้)
            $product->progress = $product->calculated_progress;

            // Step ปัจจุบัน (max step_number)
            $product->current_step_number = DrugProgressStep::where('chemical_registrations_id', $product->id)
                ->max('step_number');

            // ดึงข้อมูล last_index, last_is_checked, unchecked_count สำหรับ step ล่าสุด
            $stepSummary = DrugProgressStep::select([
                'step_number',
                DB::raw('MAX(sub_step_index) as last_index'),
                DB::raw("
                MAX(
                    CASE
                        WHEN sub_step_index = (
                            SELECT MAX(sub_step_index)
                            FROM drug_progress_steps d2
                            WHERE d2.chemical_registrations_id = drug_progress_steps.chemical_registrations_id
                              AND d2.step_number = drug_progress_steps.step_number
                        )
                        AND checked_at IS NOT NULL
                    THEN 1 ELSE 0 END
                ) as last_is_checked
            "),
                DB::raw('SUM(CASE WHEN checked_at IS NULL THEN 1 ELSE 0 END) as unchecked_count'),
            ])
                ->where('chemical_registrations_id', $product->id)
                ->groupBy('step_number')
                ->get()
                ->keyBy('step_number');

            // แปะข้อมูลสรุปให้ product
            $product->step_summary = $stepSummary;
            $product->current_step_number  = DrugProgressStep::where('chemical_registrations_id', $product->id)
                ->max('step_number');


            $planIndex = collect($rawStructure[1])->flatten()->search('แผนการทดลอง');
            $planNoteRecord = DrugProgressStep::where('chemical_registrations_id', $product->id)
                ->where('step_number', 1)
                ->where('sub_step_index', $planIndex)
                ->first();
            $isPlanNone = $planNoteRecord && $planNoteRecord->created_by === 'ไม่มี';
            $product->isPlanNone = $isPlanNone;
        }


        return view('product.new.index', [
            'total' => $total,
            'totalNewRegistrations' => $totalNewRegistrations,
            'soonExpiredCount' => $soonExpiredCount,
            'expiredCount' => $expiredCount,
            'paginatedProducts' => $paginatedProducts,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all(); // ดึงรายชื่อบริษัททั้งหมด
        // return view('import.create', compact('companies'));
        return view('product.new.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $validatedData = $request->validate([
                'chemical_imports_id' => 'nullable|integer', // Changed from string to integer based on schema
                'registration_number' => 'nullable|string',
                'registration_number_pass' => 'nullable|string',
                'registration_expiry_date' => 'nullable|date',
                'chemical_name_th' => 'required|string',
                'chemical_name_en' => 'required|string',
                'composition' => 'nullable|string', // text field can be validated as string
                'manufacturer' => 'required|string',
                'registrant' => 'required|string',
                'registration_type' => 'required|string',
                'importer' => 'required|string',
                'distributor' => 'required|string',
                'trade_name' => 'required|string',
                'trade_name_at' => 'nullable|string',
                'production_license_number' => 'nullable|string',
                'production_license_expiry' => 'nullable|date',
                'production_license_quantity' => 'nullable|string',
                'possession_form_wo2' => 'nullable|string',
                'possession_form_expiry' => 'nullable|string',
                'application_received_date' => 'nullable|date',
                'expired_license_number' => 'nullable|string',
                'expired_at' => 'nullable|string',
                'old_license_quantity' => 'nullable|string',
                'packaging_size' => 'nullable|string',
                'formula_of_ratio' => 'nullable|string',
                'type_registration' => 'required|string',
                'common_name' => 'nullable|string',
                'packaging_size_details' => 'nullable|string',
                'type_of_use' => 'required|string',
                'date_submit_request' => 'nullable|date',
                'request_number_1' => 'nullable|string',
                'request_number_phase_1' => 'nullable|string',
                'date_request_phase_3' => 'nullable|date',
                'request_number_phase_3' => 'nullable|string',
                'name_position' => 'required|string',
                'remarks' => 'nullable|string', // text field can be validated as string
                'new_or_old' => 'nullable|boolean', // boolean field
                'step' => 'nullable|string',
                'chemical_type' => 'nullable|string',
                'company' => 'nullable|string',
                'store_company_1' => 'nullable|string',
                'store_company_2' => 'nullable|string',
                'status' => 'nullable|string',
                'is_active' => 'nullable|boolean', // boolean field
                'is_deleted' => 'nullable|boolean', // boolean field
                'image' => 'nullable|string',
                'document' => 'nullable|string',
                'progress' => 'nullable|numeric', // decimal field
                'sub_progress' => 'nullable|numeric', // decimal field
                'created_by' => 'nullable|string',
                'updated_by' => 'nullable|string',
                'group_of_substances' => 'nullable|string',
                'plant' => 'nullable|string',
                'pests' => 'nullable|string',
                'quantity' => 'nullable|string',
            ]);

            // 2. กำหนดค่า progress เริ่มต้น 0 (หรือจะเป็น 12.5% ถ้าต้องการ)
            $validatedData['progress'] = 0;

            $chemical_registration = ChemicalRegistration::create($validatedData);
            // 4. สร้างหัวข้อย่อยเริ่มต้นให้กับขั้นตอนที่ 1 โดยไม่มีการเลือก (checked_at = null)
            // กำหนดหัวข้อย่อยขั้นตอน 1 จำนวน 3 หัวข้อ (ตาม requirement ล่าสุด)
            $rawStructure = [
                1 => [
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
            ];

            // สร้างหัวข้อย่อยเริ่มต้นเฉพาะขั้นตอนที่ 1
            $stepNumber = 1;
            $subStepIndex = 0;

            foreach ($rawStructure[$stepNumber] as $department => $subSteps) {
                foreach ($subSteps as $label) {
                    DrugProgressStep::create([
                        'chemical_registrations_id' => $chemical_registration->id,
                        'department' => $department,
                        'step_number' => $stepNumber,
                        'sub_step_index' => $subStepIndex,
                        'sub_step_label' => $label,
                        'checked_at' => null,
                    ]);
                    $subStepIndex++;
                }
            }

            return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation errors: ', $e->errors());
            return redirect()->back()->withInput()->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $drug = ChemicalRegistration::find($id);
        if (!$drug) {
            abort(404, 'ไม่พบข้อมูล');
        }

        // เช็กแผน
        $checkplan = $drug->checkPlan($id) ? 'มี' : 'ไม่มี';

        // ดึง step ล่าสุด
        $currentStep = DrugProgressStep::where('chemical_registrations_id', $id)->max('step_number') ?? 0;

        // ดึงสรุป progress สำหรับทุก step
        $stepSummary = DrugProgressStep::select([
            'step_number',
            DB::raw('MAX(sub_step_index) as last_index'),
            DB::raw("
            MAX(
                CASE
                    WHEN sub_step_index = (
                        SELECT MAX(sub_step_index)
                        FROM drug_progress_steps d2
                        WHERE d2.chemical_registrations_id = drug_progress_steps.chemical_registrations_id
                          AND d2.step_number = drug_progress_steps.step_number
                    )
                    AND checked_at IS NOT NULL
                THEN 1 ELSE 0 END
            ) as last_is_checked
        "),
            DB::raw('SUM(CASE WHEN checked_at IS NULL THEN 1 ELSE 0 END) as unchecked_count'),
        ])
            ->where('chemical_registrations_id', $id)
            ->groupBy('step_number')
            ->get()
            ->keyBy('step_number');

        // คำนวณ progress ตาม step ล่าสุด
        $show_step_number = 0;
        if (isset($stepSummary[$currentStep])) {
            $summary = $stepSummary[$currentStep];

            // กำหนด mapping สำหรับแต่ละ step
            $progressMap = [
                1 => fn($unchecked) => $unchecked >= 12 ? 0 : 12.5,
                2 => fn($unchecked) => 25,
                3 => fn($unchecked) => 37.5,
                4 => fn($unchecked) => $unchecked == 1 && $checkplan == 'ไม่มี' ? 62.5 : 50,
                5 => fn($unchecked) => $unchecked == 2 && $checkplan == 'ไม่มี' ? 75 : 62.5,
                6 => fn($unchecked) => $unchecked == 2 && $checkplan == 'ไม่มี' ? 87.5 : 75,
                7 => fn($unchecked) => 87.5,
                8 => fn($unchecked) => $unchecked == 0 ? 100 : 90,
            ];

            $show_step_number = $progressMap[$summary->step_number]($summary->unchecked_count);
        }


        // บันทึก progress ลงฐานข้อมูล (แค่ฟิลด์เดียว)
        $drug->progress = $show_step_number;
        $drug->save();

        // เพิ่ม attribute ที่ใช้แค่ใน view (ไม่บันทึก DB)
        $drug->setAttribute('step_summary', $stepSummary);
        $drug->setAttribute('current_step_number', $currentStep);

        $step_number  = DrugProgressStep::where('chemical_registrations_id', $drug->id)
            ->max('step_number');

        return view('product.new.show', compact('drug', 'checkplan', 'currentStep', 'step_number'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $companies = Company::all();
        $drug = ChemicalRegistration::where('id', $id)->first();

        if (!$drug) {
            abort(404, 'ไม่พบข้อมูล');
        }

        $checkplan = $drug->checkPlan($id);
        if ($checkplan) {
            $checkplan = 'มี';
        } else {
            $checkplan = 'ไม่มี';
        }
        // ตรวจสอบว่าผู้ใช้มีสิทธิ์แก้ไขหรือไม่
        // if (!auth()->user()->can('edit', $drug)) {
        //     abort(403, 'คุณไม่มีสิทธิ์แก้ไขข้อมูลนี้');
        // }

        $drug->current_step_number = DrugProgressStep::where('chemical_registrations_id', $id)
            ->max('step_number');

        // ดึงข้อมูล last_index, last_is_checked, unchecked_count สำหรับ step ล่าสุด
        $stepSummary = DrugProgressStep::select([
            'step_number',
            DB::raw('MAX(sub_step_index) as last_index'),
            DB::raw("
                MAX(
                    CASE
                        WHEN sub_step_index = (
                            SELECT MAX(sub_step_index)
                            FROM drug_progress_steps d2
                            WHERE d2.chemical_registrations_id = drug_progress_steps.chemical_registrations_id
                              AND d2.step_number = drug_progress_steps.step_number
                        )
                        AND checked_at IS NOT NULL
                    THEN 1 ELSE 0 END
                ) as last_is_checked
            "),
            DB::raw('SUM(CASE WHEN checked_at IS NULL THEN 1 ELSE 0 END) as unchecked_count'),
        ])
            ->where('chemical_registrations_id', $id)
            ->groupBy('step_number')
            ->get()
            ->keyBy('step_number');

        // แปะข้อมูลสรุปให้ product
        $drug->step_summary = $stepSummary;

        $drug->current_step_number  = DrugProgressStep::where('chemical_registrations_id', $id)
            ->max('step_number');

        return view('product.new.edit', compact('drug', 'companies', 'checkplan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $drug = ChemicalRegistration::findOrFail($id);

            $request->merge([
                'registration_expiry_date'  => $this->convertDate($request->input('registration_expiry_date')),
                'production_license_expiry' => $this->convertDate($request->input('production_license_expiry')),
                // 'possession_form_expiry'    => $this->convertDate($request->input('possession_form_expiry')),
                'application_received_date' => $this->convertDate($request->input('application_received_date')),
                // 'expired_at'                => $this->convertDate($request->input('expired_at')),
                'date_submit_request'       => $this->convertDate($request->input('date_submit_request')),
                'date_request_phase_3'      => $this->convertDate($request->input('date_request_phase_3')),
            ]);


            $validatedData = $request->validate([
                'chemical_imports_id' => 'nullable|integer', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ required
                'registration_number' => 'nullable|string',
                'registration_number_pass' => 'nullable|string',
                'registration_expiry_date' => 'nullable|date',
                'chemical_name_th' => 'nullable|string',
                'chemical_name_en' => 'nullable|string',
                'composition' => 'nullable|string',
                'manufacturer' => 'nullable|string', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ nullable
                'registrant' => 'nullable|string', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ nullable
                'registration_type' => 'nullable|string', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ nullable
                'importer' => 'nullable|string', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ nullable
                'distributor' => 'nullable|string', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ nullable
                'trade_name' => 'nullable|string', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ nullable
                'trade_name_at' => 'nullable|string',
                'production_license_number' => 'nullable|string',
                'production_license_expiry' => 'nullable|date',
                'production_license_quantity' => 'nullable|string',
                'possession_form_wo2' => 'nullable|string',
                'possession_form_expiry' => 'nullable|string',
                'application_received_date' => 'nullable|date',
                'expired_license_number' => 'nullable|string',
                'expired_at' => 'nullable|string',
                'old_license_quantity' => 'nullable|string',
                'packaging_size' => 'nullable|string',
                'formula_of_ratio' => 'nullable|string', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ nullable
                'type_registration' => 'nullable|string',
                'common_name' => 'nullable|string',
                'packaging_size_details' => 'nullable|string',
                'type_of_use' => 'nullable|string', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ nullable
                'date_submit_request' => 'nullable|date',
                'request_number_1' => 'nullable|string',
                'request_number_phase_1' => 'nullable|string',
                'date_request_phase_3' => 'nullable|date',
                'request_number_phase_3' => 'nullable|string',
                'name_position' => 'nullable|string', // ตัวอย่าง: ฟิลด์นี้ถูกกำหนดให้ nullable
                'remarks' => 'nullable|string',
                'new_or_old' => 'nullable|boolean',
                'step' => 'nullable|string',
                'chemical_type' => 'nullable|string',
                'company' => 'nullable|string',
                'store_company_1' => 'nullable|string',
                'store_company_2' => 'nullable|string',
                'status' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'is_deleted' => 'nullable|boolean',
                'image' => 'nullable|string',
                'document' => 'nullable|string',
                'progress' => 'nullable|numeric',
                'sub_progress' => 'nullable|numeric',
                'created_by' => 'nullable|string',
                'updated_by' => 'nullable|string',
                'group_of_substances' => 'nullable|string',
                'plant' => 'nullable|string',
                'pests' => 'nullable|string',
                'quantity' => 'nullable|string',
            ]);

            // ตั้งค่าเพิ่มเติม (หากมีเลขทะเบียนให้ถือว่าเป็นของเก่า)
            if (!empty($validatedData['registration_number'])) {
                $validatedData['new_or_old'] = false;
                $validatedData['progress'] = 100;

                if ($validatedData['registration_type'] == 'T : นำเข้าสารเข้มข้น' || $validatedData['registration_type'] == 'I : นำเข้าสำเร็จรูป') {
                    // 2. Map ข้อมูลและกำหนดค่าเริ่มต้น/เพิ่มเติม
                    $companyId = Company::where('full_name', $validatedData['registrant'])->firstOrFail()->id;
                    $distributorId = Company::where('full_name', $validatedData['distributor'])->firstOrFail()->id;
                    $importerId = Company::where('full_name', $validatedData['importer'])->firstOrFail()->id;
                    $dataToSave = $validatedData;
                    $dataToSave['expired_license_date'] = $request->input('production_license_expiry', null); // ใช้ค่าจากฟอร์ม ถ้าไม่มี ให้ 'pending'
                    $dataToSave['company_id'] = $companyId;
                    $dataToSave['distributor'] = $distributorId;
                    $dataToSave['company_id'] = $companyId;
                    $dataToSave['importer'] = $importerId;
                    $dataToSave['trade_name_at'] = $request->input('name_position', null);
                    $dataToSave['type_production_registration'] = $request->input('type_registration', null);
                    $dataToSave['usage_production_registration'] = $request->input('type_of_use', null);
                    $dataToSave['production_license_quantity'] = $request->input('quantity', null);
                    $dataToSave['production_license_expiry'] = null;

                    if (Auth::check()) {
                        $dataToSave['created_by'] = Auth::id(); // หรือ Auth::user()->name หากต้องการชื่อ
                    } else {
                        $dataToSave['created_by'] = null; // หรือกำหนดเป็นค่าอื่นหากผู้ใช้ไม่ได้ล็อกอิน
                    }

                    ChemicalImport::create($dataToSave);
                } else {
                    // 2. Map ข้อมูลและกำหนดค่าเริ่มต้น/เพิ่มเติม
                    $companyId = Company::where('full_name', $validatedData['registrant'])->firstOrFail()->id;
                    $distributorId = Company::where('full_name', $validatedData['distributor'])->firstOrFail()->id;
                    $importerId = Company::where('full_name', $validatedData['importer'])->firstOrFail()->id;
                    $dataToSave = $validatedData;
                    $dataToSave['expired_license_date'] = $request->input('production_license_expiry', null); // ใช้ค่าจากฟอร์ม ถ้าไม่มี ให้ 'pending'
                    $dataToSave['company_id'] = $companyId;
                    $dataToSave['distributor'] = $distributorId;
                    $dataToSave['company_id'] = $companyId;
                    $dataToSave['importer'] = $importerId;
                    $dataToSave['trade_name_at'] = $request->input('name_position', null);
                    $dataToSave['type_production_registration'] = $request->input('type_registration', null);
                    $dataToSave['usage_production_registration'] = $request->input('type_of_use', null);
                    $dataToSave['production_license_quantity'] = $request->input('quantity', null);
                    $dataToSave['production_license_expiry'] = null;

                    if (Auth::check()) {
                        $dataToSave['created_by'] = Auth::id(); // หรือ Auth::user()->name หากต้องการชื่อ
                    } else {
                        $dataToSave['created_by'] = null; // หรือกำหนดเป็นค่าอื่นหากผู้ใช้ไม่ได้ล็อกอิน
                    }


                    ProductionRegistration::create($dataToSave);
                }
            }

            $drug->fill($validatedData);
            $drug->save();

            return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ');
        } catch (\Exception $e) {
            \Log::error("Error update product: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(), // ข้อมูลฟอร์มที่ส่งมา
            ]);

            return redirect()->back()->withInput()->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $drug = ChemicalRegistration::findOrFail($id);

        try {
            // ลบหัวข้อย่อยที่เกี่ยวข้อง (DrugProgressStep)
            DrugProgressStep::where('chemical_registrations_id', $drug->id)->delete();

            $drug->delete();

            return redirect()->route('newregis.index')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            // \Log::error("Error deleting chemical registration: " . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }
    }

    public function updateSubProgress(Request $request, ChemicalRegistration $drug)
    {


        $stepNumber = (int) $request->input('step_number');
        $selectedIndexes = $request->input('sub_steps', []);
        $notes = $request->input('sub_step_notes', []);
        $remarks = $request->input('sub_step_remarks', []);
        $show_step_number = $request->input('progress');
        $drug->progress = $show_step_number;
        $drug->save();

        // Raw structure (เหมือนเดิม)
        $rawStructure = [
            1 => [
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
            2 => [
                'จัดซื้อต่างประเทศ' => ['ประสานเพื่อนำเข้าตัวอย่าง'],
                'วิจัยและพัฒนา' => ['จัดเตรียมตัวอย่าง'],
                'แผนกทะเบียน' => ['ส่งตัวอย่างให้วิจัยและพัฒนา', 'ขอใบอนุญาตผลิต', 'ตรวจ COA'],
            ],
            3 => [
                'จัดซื้อต่างประเทศ' => ['ประสานเพื่อส่งออกตัวอย่าง', 'Data requirement จากผู้ผลิต'],
                'แผนกทะเบียน' => [
                    'ประสานส่งออกตัวอย่าง',
                    'ตรวจผลการศึกษา Tox',
                    'เตรียมข้อมูลประกอบการยื่นขอขึ้นทะเบียน',
                ],
            ],
            4 => [
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
            5 => [
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
            6 => [
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
            7 => [
                'แผนกทะเบียน' => [
                    'แผนกทะเบียนได้รับผล Tox Phase III ที่อนุมัติ ทำการรวบรวมข้อมูลเอกสารยื่นขอเข้าประชุมพิจารณาขึ้นทะเบียนใหม่'
                ],
            ],
            8 => [
                'ฝ่ายขาย' => ['สรุป packing และจัดทำ A/W'],
                'แผนกทะเบียน' => [
                    'จัดเตรียมคำขอขึ้นทะเบียน',
                    'ร่างฉลาก',
                    'มติพิจารณาขึ้นทะเบียน',
                    'A/W',
                ],
            ],
        ];



        $userDept = auth()->user()->department;
        $departmentMap = [
            'InternationalProcurement' => 'จัดซื้อต่างประเทศ',
            'SalesDepartment' => 'ฝ่ายขาย',
            'ResearchAndDevelopment' => 'วิจัยและพัฒนา',
            'Academic' => 'แผนกวิชาการ',
            'Registration' => 'แผนกทะเบียน',
            'IT' => 'เทคโนโลยีสารสนเทศ',
        ];
        $mappedDept = $departmentMap[$userDept] ?? $userDept;

        // ✅ ตรวจว่าแผนการทดลองเป็น "ไม่มี" หรือไม่
        $planIndex = collect($rawStructure[1])->flatten()->search('แผนการทดลอง');
        $planNoteRecord = DrugProgressStep::where('chemical_registrations_id', $drug->id)
            ->where('step_number', 1)
            ->where('sub_step_index', $planIndex)
            ->first();
        $isPlanNone = $planNoteRecord && $planNoteRecord->created_by === 'ไม่มี';

        $stepStructure = $rawStructure[$stepNumber] ?? [];
        $flatItems = [];
        $index = 0;

        foreach ($stepStructure as $department => $subSteps) {
            foreach ($subSteps as $label) {
                // ✅ ข้ามแผนกวิชาการในขั้นตอน 4–6 ถ้า "แผนการทดลอง = ไม่มี"
                if ($isPlanNone && in_array($stepNumber, [4, 5, 6]) && $department === 'แผนกวิชาการ') {
                    $index++;
                    continue;
                }

                if (
                    $department === $mappedDept ||
                    auth()->user()->hasRole('admin') ||
                    auth()->user()->hasRole('manager') ||
                    auth()->user()->hasRole('head Registration')
                ) {

                    DrugProgressStep::updateOrCreate(
                        [
                            'chemical_registrations_id' => $drug->id,
                            'step_number' => $stepNumber,
                            'sub_step_index' => $index,
                        ],
                        [
                            'sub_step_label' => $label,
                            'department' => $department,
                            'checked_at' => in_array($index, $selectedIndexes) ? now() : null,
                            'created_by' => $notes[$index] ?? null,
                            'remark' => $remarks[$index] ?? null,
                        ]
                    );
                }
                $index++;
            }
        }
        // ✅ คำนวณ progress
        $totalSteps = count($rawStructure);
        $completedSteps = 0;
        foreach ($rawStructure as $step => $groupedItems) {
            $flat = collect($groupedItems);
            // ✅ ลบแผนกวิชาการในขั้นตอน 4–6 ถ้า "แผนการทดลอง = ไม่มี"
            if ($isPlanNone && in_array($step, [4, 5, 6])) {
                $flat = $flat->reject(fn($items, $dept) => $dept === 'แผนกวิชาการ');
            }

            $flatItems = $flat->flatten()->values();
            $countChecked = DrugProgressStep::where('chemical_registrations_id', $drug->id)
                ->where('step_number', $step)
                ->whereNotNull('checked_at')
                ->count();
            if ($flatItems->count() > 0 && $countChecked === $flatItems->count()) {
                $completedSteps++;
            }
        }
        // $drug->progress = round(($completedSteps / $totalSteps) * 100, 2);
        // $drug->save();
        // ✅ เช็คว่าขั้นตอนปัจจุบัน (ที่ติ๊ก) ครบหรือไม่
        $flatCurrentStep = collect($stepStructure);
        if ($isPlanNone && in_array($stepNumber, [4, 5, 6])) {
            $flatCurrentStep = $flatCurrentStep->reject(fn($items, $dept) => $dept === 'แผนกวิชาการ');
        }
        $totalSubStepsInStep = $flatCurrentStep->flatten()->count();
        $checkedCountInStep = DrugProgressStep::where('chemical_registrations_id', $drug->id)
            ->where('step_number', $stepNumber)
            ->whereNotNull('checked_at')
            ->count();

        if ($totalSubStepsInStep > 0 && $checkedCountInStep === $totalSubStepsInStep) {
            // ✅ อัปเดตค่า sub_progress ที่ ChemicalRegistration
            // $drug->sub_progress = $stepNumber;
            // $drug->save();

            // ✅ สร้างรายการ sub step สำหรับขั้นตอนถัดไป
            $nextStep = $stepNumber + 1;
            if (isset($rawStructure[$nextStep])) {
                $nextStepStructure = $rawStructure[$nextStep];

                if ($isPlanNone && in_array($nextStep, [4, 5, 6])) {
                    $nextStepStructure = collect($nextStepStructure)
                        ->reject(fn($_, $dept) => $dept === 'แผนกวิชาการ')
                        ->all();
                }

                $nextIndex = 0;
                foreach ($nextStepStructure as $department => $subSteps) {
                    foreach ($subSteps as $label) {
                        DrugProgressStep::firstOrCreate([
                            'chemical_registrations_id' => $drug->id,
                            'step_number' => $nextStep,
                            'sub_step_index' => $nextIndex,
                        ], [
                            'sub_step_label' => $label,
                            'department' => $department,
                        ]);
                        $nextIndex++;
                    }
                }

                $drug->progress = $show_step_number + 12.5;
                $drug->save();
            }
        }

        return redirect()->back()->with('success', 'อัปเดตความคืบหน้าเรียบร้อยแล้ว');
    }

    public function indexAll(Request $request)
    {
        $query = ChemicalRegistration::query();
        $query->where('new_or_old', false);

        // ค้นหาตามคำค้น
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('trade_name', 'like', '%' . $search . '%')
                    ->orWhere('chemical_name_th', 'like', '%' . $search . '%')
                    ->orWhere('registrant', 'like', '%' . $search . '%')
                    ->orWhere('registration_number', 'like', '%' . $search . '%');
            });
        }

        // ค้นหาตามวันที่
        if ($request->filled('expiry_date_from') && $request->filled('expiry_date_to')) {
            $query->whereBetween('expired_license_number', [
                $request->input('expiry_date_from'),
                $request->input('expiry_date_to'),
            ]);
        }

        // ฟิลเตอร์ตามสถานะ
        $statusFilter = $request->input('status_filter');
        $today = Carbon::now(); // ใช้ Carbon::now()
        $in180Days = Carbon::now()->addDays(180); // ใช้ Carbon::now()->addDays()

        if ($statusFilter === 'expired') {
            $query->whereDate('expired_license_number', '<', $today);
        } elseif ($statusFilter === 'soon_expired') {
            $query->whereBetween('expired_license_number', [$today, $in180Days]);
        } elseif ($statusFilter === 'new_all') {
            $query->where('new_or_old', true)
                ->where('progress', '<', 100);
        } else {
            // หากไม่มี filter หรือเป็นค่าอื่น ให้แสดงรายการปกติที่ new_or_old เป็น false
            // คุณได้กำหนด query->where('new_or_old', false); ไว้แล้ว
        }

        $paginatedProducts = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString(); // เพิ่ม withQueryString() เพื่อให้ pagination link เก็บ query parameters เดิมไว้


        $total = ChemicalRegistration::where('new_or_old', false)->count();
        $soonExpiredCount = ChemicalRegistration::where('new_or_old', false)
            ->whereDate('expired_license_number', '<=', Carbon::now()->addMonths(6))
            ->count();
        $expiredCount = ChemicalRegistration::where('new_or_old', false)
            ->whereDate('expired_license_number', '<', Carbon::now())->count();

        // สำคัญ: กำหนดค่า status ให้กับแต่ละรายการใน paginatedProducts
        foreach ($paginatedProducts as $product) {
            $expiryDate = Carbon::parse($product->expired_license_number);
            $now = Carbon::now();

            if ($expiryDate <= $today) {
                $product->status = 'หมดอายุ';
            } elseif ($expiryDate->diffInDays($now) <= 180) { // เปรียบเทียบเป็นวันสำหรับ 180 วัน
                $product->status = 'ใกล้หมด';
            } else {
                $product->status = 'ใช้งานอยู่';
            }
        }

        return view('product_all.index', [
            'total' => $total,
            'soonExpiredCount' => $soonExpiredCount,
            'expiredCount' => $expiredCount,
            'paginatedProducts' => $paginatedProducts,
        ]);
    }

    public function editAll(Request $request, $id)
    {
        $companies = Company::all();
        $registration = ChemicalRegistration::where('id', $id)->first();
        if (!$registration) {
            abort(404, 'ไม่พบข้อมูล');
        }

        $checkplan = $registration->checkPlan($id);
        if ($checkplan) {
            $checkplan = 'มี';
        } else {
            $checkplan = 'ไม่มี';
        }

        return view('product_all.edit', compact('registration', 'companies', 'checkplan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAll(Request $request, $id)
    {
        try {
            $drug = ChemicalRegistration::findOrFail($id);

            $rules = [
                'chemical_imports_id' => 'nullable|integer',
                'registration_number' => 'nullable|string',
                'registration_number_pass' => 'nullable|string',
                'registration_expiry_date' => 'nullable|date',
                'chemical_name_th' => 'nullable|string',
                'chemical_name_en' => 'nullable|string',
                'composition' => 'nullable|string',
                'manufacturer' => 'nullable|string',
                'registrant' => 'nullable|string',
                'registration_type' => 'nullable|string',
                'importer' => 'nullable|string',
                'distributor' => 'nullable|string',
                'trade_name' => 'nullable|string',
                'trade_name_at' => 'nullable|string',
                'production_license_number' => 'nullable|string',
                'production_license_expiry' => 'nullable|date',
                'production_license_quantity' => 'nullable|string',
                'possession_form_wo2' => 'nullable|string',
                'possession_form_expiry' => 'nullable|string',
                'application_received_date' => 'nullable|date',
                'expired_license_number' => 'nullable|string',
                'expired_at' => 'nullable|string',
                'old_license_quantity' => 'nullable|string',
                'packaging_size' => 'nullable|string',
                'formula_of_ratio' => 'nullable|string',
                'type_registration' => 'nullable|string',
                'common_name' => 'nullable|string',
                'packaging_size_details' => 'nullable|string',
                'type_of_use' => 'nullable|string',
                'date_submit_request' => 'nullable|date',
                'request_number_1' => 'nullable|string',
                'request_number_phase_1' => 'nullable|string',
                'date_request_phase_3' => 'nullable|date',
                'request_number_phase_3' => 'nullable|string',
                'name_position' => 'nullable|string',
                'remarks' => 'nullable|string',
                'new_or_old' => 'nullable|boolean',
                'step' => 'nullable|string',
                'chemical_type' => 'nullable|string',
                'company' => 'nullable|string',
                'store_company_1' => 'nullable|string',
                'store_company_2' => 'nullable|string',
                'status' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'is_deleted' => 'nullable|boolean',
                'image' => 'nullable|string',
                'document' => 'nullable|string',
                'progress' => 'nullable|numeric',
                'sub_progress' => 'nullable|numeric',
                'created_by' => 'nullable|string',
                'updated_by' => 'nullable|string',
            ];

            $validatedData = $request->validate($rules);

            // ตั้งค่าเพิ่มเติม (หากมีเลขทะเบียนให้ถือว่าเป็นของเก่า)
            if (!empty($validatedData['registration_number'])) {
                $validatedData['new_or_old'] = false;
            }

            $drug->fill($validatedData);
            $drug->save();

            return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ');
        } catch (\Throwable $th) {
            // Log::error('Update error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด');
        }
    }

    public function showAll(Request $request, $id)
    {
        $companies = Company::all();
        $registration = ChemicalRegistration::where('id', $id)->first();
        if (!$registration) {
            abort(404, 'ไม่พบข้อมูล');
        }

        $checkplan = $registration->checkPlan($id);
        if ($checkplan) {
            $checkplan = 'มี';
        } else {
            $checkplan = 'ไม่มี';
        }

        return view('product_all.show', compact('registration', 'companies', 'checkplan'));
    }

    private function convertDate($value)
    {
        if (!$value) return null;
        if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $value)) {
            [$dd, $mm, $yyyy] = explode('/', $value);
            if ((int)$yyyy > 2400) $yyyy -= 543;
            return sprintf('%04d-%02d-%02d', $yyyy, $mm, $dd);
        }
        return $value; // ปล่อยผ่านถ้าเป็น yyyy-mm-dd อยู่แล้ว
    }

    private function convertThaiDateToCarbon($dateString)
    {
        // ถ้าเป็น format dd/mm/yyyy (เช่น 01/10/2568)
        if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $dateString)) {
            [$day, $month, $year] = explode('/', $dateString);
            $year = (int)$year - 543; // แปลงจาก พ.ศ. → ค.ศ.
            return Carbon::createFromDate($year, $month, $day)->startOfDay();
        }

        // ถ้าเป็น yyyy-mm-dd (เช่น 2025-10-01)
        return Carbon::parse($dateString)->startOfDay();
    }
}
