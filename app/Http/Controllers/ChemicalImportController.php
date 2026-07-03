<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ChemicalImportsImport; // อย่าลืม import Import Class ที่สร้างไว้
use App\Models\ChemicalImport;
use App\Models\ChemicalImportFile;
use App\Models\Company;
use App\Models\DocumentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Log;


class ChemicalImportController extends Controller
{
    private const DOCUMENT_TYPE_IMPORT_REGISTRATION = 'import_reg';
    private const DOCUMENT_TYPE_IMPORT_APPROVAL = 'import_license';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = ChemicalImport::query();
        $query->with(['company', 'importerCompany', 'files']);

        // ส่วนของการค้นหา (search) - normalize input and compare without spaces, case-insensitive
        if ($request->filled('search')) {
            $rawSearch = (string) $request->input('search');
            $normalized = mb_strtolower(preg_replace('/\s+/', '', $rawSearch));
            $like = '%' . $normalized . '%';

            $query->where(function ($q) use ($like) {
                $q->whereRaw("REPLACE(LOWER(chemical_name_th), ' ', '') LIKE ?", [$like])
                    ->orWhereRaw("REPLACE(LOWER(chemical_name_en), ' ', '') LIKE ?", [$like])
                    ->orWhereRaw("REPLACE(LOWER(registration_number), ' ', '') LIKE ?", [$like])
                    ->orWhereHas('company', function ($q2) use ($like) {
                        $q2->whereRaw("REPLACE(LOWER(full_name), ' ', '') LIKE ?", [$like]);
                    });
            });
        }

        // ส่วนของการกรองตามช่วงวันหมดอายุ (expiry_date_from/to) ยังคงเดิม
        if ($request->filled('expiry_date_from') && $request->filled('expiry_date_to')) {
            $query->whereBetween('expired_license_date', [
                $request->input('expiry_date_from'),
                $request->input('expiry_date_to'),
            ]);
        }

        // **เพิ่มส่วนนี้: การกรองตาม status_filter**
        if ($request->filled('status_filter')) {
            $statusFilter = $request->input('status_filter');
            $now = Carbon::now();

            if ($statusFilter === 'expired') {
                $query->whereDate('expired_license_date', '<', $now);
            } elseif ($statusFilter === 'soon_expired') {
                $query->whereDate('expired_license_date', '>=', $now)
                    ->whereDate('expired_license_date', '<=', $now->copy()->addMonths(6));
            }
            // ถ้า status_filter ไม่ใช่ 'expired' หรือ 'soon_expired' (เช่น ถ้ามีค่าอื่นที่ไม่รู้จัก หรือไม่มีค่า)
            // ก็จะไม่เพิ่มเงื่อนไขการกรองนี้ ทำให้แสดงทั้งหมด (หรือตามเงื่อนไข search/date อื่นๆ)
        }

        $imports = $query->latest()->paginate(10)->withQueryString();

        // คำนวณค่าสถานะใหม่โดยใช้ Carbon เพื่อให้แม่นยำตามเวลาปัจจุบัน
        $total = ChemicalImport::count();
        $expiredCount = ChemicalImport::whereDate('expired_license_date', '<', Carbon::now())->count();
        $soonCount = ChemicalImport::whereDate('expired_license_date', '>=', Carbon::now())
            ->whereDate('expired_license_date', '<=', Carbon::now()->addMonths(6))
            ->count();

        // สำคัญ: สำหรับแต่ละรายการใน $imports เราจะกำหนดค่า status ให้ถูกต้องก่อนส่งไปยัง Blade view
        foreach ($imports as $import) {
            $expiryDate = Carbon::parse($import->expired_license_date);
            $now = Carbon::now();

            if ($expiryDate->isPast()) {
                $import->status = 'หมดอายุ';
            } elseif ($expiryDate->diffInMonths($now) <= 6) {
                $import->status = 'ใกล้หมด';
            } else {
                $import->status = 'ใช้งานอยู่';
            }
        }

        return view('import.index', [
            'imports' => $imports,
            'total' => $total,
            'expiredCount' => $expiredCount,
            'soonCount' => $soonCount,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('import.create', compact('companies'));
        // return view('import.create');
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
            $maxFileSizeKilobytes = $this->maxFileSizeKilobytes();

            // 1. Validation ข้อมูลจากฟอร์ม
            $validatedData = $request->validate([
                'company_id' => 'nullable|exists:companies,id', // ตรวจสอบว่า company_id มีอยู่ในตาราง companies
                'registration_number' => 'nullable|string|max:255',
                'expired_license_date' => 'nullable|date',
                'chemical_name_th' => 'nullable|string|max:255',
                'chemical_name_en' => 'nullable|string|max:255',
                'composition' => 'nullable|string|max:1000',
                'manufacturer' => 'nullable|string|max:255',
                'registrant' => 'nullable|string|max:255',
                'registration_type' => 'nullable|string|max:255',
                'importer' => 'nullable|string|max:255',
                'distributor' => 'nullable|string|max:255',
                'trade_name' => 'nullable|string|max:255',
                'trade_name_at' => 'nullable|string|max:255',
                'type_production_registration' => 'nullable|string|max:255',
                'usage_production_registration' => 'nullable|string|max:255',
                'group_of_substances' => 'nullable|string|max:255',
                'plant' => 'nullable|string|max:255',
                'pests' => 'nullable|string|max:255',
                'production_license_number' => 'nullable|string|max:255',
                'production_license_expiry' => 'nullable|date',
                'production_license_quantity' => 'nullable|string|max:255',
                'possession_form_wo2' => 'nullable|string|max:255',
                'possession_form_expiry' => 'nullable|string',
                'packaging_size_details' => 'nullable|string|max:1000',
                'registration_number_pass' => 'nullable|string|max:255',
                'registration_expiry_date' => 'nullable|date',
                'expired_at' => 'nullable|string',
                'status_date' => 'nullable|string|max:255',
                'remarks' => 'nullable|string|max:1000',
                'image' => 'nullable|image|max:2048', // ตัวอย่าง: อนุญาตเฉพาะไฟล์ภาพ ขนาดไม่เกิน 2MB
                'document' => 'nullable|file|mimes:pdf|mimetypes:application/pdf|max:' . $maxFileSizeKilobytes,
                'progress' => 'nullable|numeric|min:0|max:100',
                'sub_progress' => 'nullable|numeric|min:0|max:100',

            ]);

            // 2. Map ข้อมูลและกำหนดค่าเริ่มต้น/เพิ่มเติม
            $dataToSave = $validatedData; // เริ่มต้นด้วยข้อมูลที่ผ่านการ Validation

            // กำหนดค่าเริ่มต้นสำหรับฟิลด์ที่ไม่ได้มาจากฟอร์มโดยตรงหรือต้องการค่า default
            $dataToSave['new_or_old'] = $request->has('new_or_old') ? true : false; // สมมติว่าเป็น checkbox
            $dataToSave['step'] = $request->input('step', 'initial'); // ใช้ค่าจากฟอร์ม ถ้าไม่มี ให้ 'initial'
            $dataToSave['status'] = $request->input('status', 'pending'); // ใช้ค่าจากฟอร์ม ถ้าไม่มี ให้ 'pending'
            $dataToSave['is_active'] = $request->has('is_active') ? true : false; // สมมติว่าเป็น checkbox
            $dataToSave['is_deleted'] = false; // ควรตั้งเป็น false เสมอเมื่อสร้างใหม่

            // กำหนด created_by โดยใช้ ID ของผู้ใช้งานที่ล็อกอินอยู่
            if (Auth::check()) {
                $dataToSave['created_by'] = Auth::id(); // หรือ Auth::user()->name หากต้องการชื่อ
            } else {
                $dataToSave['created_by'] = null; // หรือกำหนดเป็นค่าอื่นหากผู้ใช้ไม่ได้ล็อกอิน
            }

            // การจัดการไฟล์ (Image และ Document)
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('production_images', 'public'); // เก็บใน storage/app/public/production_images
                $dataToSave['image'] = $imagePath;
            }

            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('production_documents', 'public'); // เก็บใน storage/app/public/production_documents
                $dataToSave['document'] = $documentPath;
            }

            $chemicalImport = ChemicalImport::create($dataToSave);
            if ($request->hasFile('document')) {
                $document = $request->file('document');
                $chemicalImport->files()->create([
                    'document_type_code' => $this->defaultDocumentTypeCode(),
                    'file_path' => $dataToSave['document'],
                    'original_name' => $document->getClientOriginalName(),
                    'stored_name' => basename($dataToSave['document']),
                    'file_extension' => $document->getClientOriginalExtension(),
                    'mime_type' => $document->getClientMimeType(),
                    'file_size' => $document->getSize(),
                    'created_by' => Auth::id(),
                    'uploaded_by' => Auth::id(),
                    'uploaded_at' => now(),
                ]);
            }
            return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ChemicalImport $import)
    {

        $import->load('files');
        $product = $import;
        $companies = Company::all();
        return view('import.show', compact('product', 'companies'));
        // return view('import.show', compact('import'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ChemicalImport $import)
    {
        $import->load('files');
        $companies = Company::all();
        $maxFileColumn = $this->maxFileColumn();
        $maxFileSizeMegabytes = (int) ($this->maxFileSizeKilobytes() / 1024);

        return view('import.edit', compact('import', 'companies', 'maxFileColumn', 'maxFileSizeMegabytes'));
        // return view('import.edit', compact('import'));
    }

    public function additionalDocument(ChemicalImport $import)
    {
        $file = $import->files()->first();

        if ($file) {
            return $this->serveChemicalImportFile($file);
        }

        $filePath = $import->additional_document ?: $import->document;

        abort_unless($filePath, 404);

        $disk = Storage::disk('public');
        $documentPath = $filePath;

        if (!$disk->exists($documentPath) && $import->document && $disk->exists($import->document)) {
            $documentPath = $import->document;
        }

        abort_unless($disk->exists($documentPath), 404);

        $path = $disk->path($documentPath);
        $fileName = $import->document ?: basename($documentPath);
        $fallbackFileName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($fileName)) ?: 'document.pdf';
        $encodedFileName = rawurlencode($fileName);

        return response()->file($path, [
            'Content-Type' => $disk->mimeType($documentPath) ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fallbackFileName . '"; filename*=UTF-8\'\'' . $encodedFileName,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
        ]);
    }

    public function file(ChemicalImport $import, ChemicalImportFile $file)
    {
        abort_unless((int) $file->chemical_import_id === (int) $import->id, 404);

        return $this->serveChemicalImportFile($file);
    }

    public function destroyFile(ChemicalImport $import, ChemicalImportFile $file)
    {
        abort_unless((int) $file->chemical_import_id === (int) $import->id, 404);

        if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->forceDelete();

        $latestFile = $import->files()->first();
        $import->update([
            'additional_document' => $latestFile?->file_path,
            'document' => $latestFile?->original_name,
        ]);

        return redirect()->back()
            ->with('success', 'ลบเอกสารสำเร็จ')
            ->with('file_deleted', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChemicalImport $import)
    {
            if (auth()->check()) {
                $user = auth()->user();
                $additionalDocument = $request->file('additional_document');

                \Log::info('Current login import permissions', [
                    'user_id' => $user->id,
                    'import_id' => $import->id,
                    'additional_document' => $additionalDocument ? $additionalDocument->getClientOriginalName() : null, // ตรวจสอบชื่อไฟล์ที่ส่งมาใน request
                ]);
            } else {
                \Log::warning('No authenticated user found during update operation');
            }
        try {
            $maxFileColumn = $this->maxFileColumn();
            $maxFileSizeKilobytes = $this->maxFileSizeKilobytes();

            $request->merge([
                'expired_license_date'     => $this->convertDate($request->input('expired_license_date')), // <<== เพิ่มตรงนี้
                'registration_expiry_date'  => $this->convertDate($request->input('registration_expiry_date')),
                'production_license_expiry' => $this->convertDate($request->input('production_license_expiry')),
                // 'possession_form_expiry'    => $this->convertDate($request->input('possession_form_expiry')),
                'application_received_date' => $this->convertDate($request->input('application_received_date')),
                // 'expired_at'                => $this->convertDate($request->input('expired_at')),
                'date_submit_request'       => $this->convertDate($request->input('date_submit_request')),
                'date_request_phase_3'      => $this->convertDate($request->input('date_request_phase_3')),
            ]);
            // 1. Validation ข้อมูลจากฟอร์ม
            $validatedData = $request->validate([
                'company_id' => 'nullable|exists:companies,id',
                'registration_number' => 'nullable|string|max:255',
                'expired_license_date' => 'nullable|date',
                'chemical_name_th' => 'nullable|string|max:255',
                'chemical_name_en' => 'nullable|string|max:255',
                'composition' => 'nullable|string|max:1000',
                'manufacturer' => 'nullable|string|max:255',
                'registrant' => 'nullable|string|max:255',
                'registration_type' => 'nullable|string|max:255',
                'importer' => 'nullable|string|max:255',
                'distributor' => 'nullable|string|max:255',
                'trade_name' => 'nullable|string|max:255',
                'trade_name_at' => 'nullable|string|max:255',
                'type_production_registration' => 'nullable|string|max:255',
                'usage_production_registration' => 'nullable|string|max:255',
                'group_of_substances' => 'nullable|string|max:255',
                'plant' => 'nullable|string|max:255',
                'pests' => 'nullable|string|max:255',
                'production_license_number' => 'nullable|string|max:255',
                'production_license_expiry' => 'nullable',
                'production_license_quantity' => 'nullable|string|max:255',
                'possession_form_wo2' => 'nullable|string|max:255',
                'possession_form_expiry' => 'nullable|string',
                'packaging_size_details' => 'nullable|string|max:1000',
                'registration_number_pass' => 'nullable|string|max:255',
                'registration_expiry_date' => 'nullable|date',
                'expired_at' => 'nullable|string',
                'status_date' => 'nullable|string|max:255',
                'remarks' => 'nullable|string|max:1000',
                'image' => 'nullable|image|max:2048', // optional: 'image' if you want to allow changing image
                'additional_document' => 'nullable|file|mimes:pdf|mimetypes:application/pdf|max:' . $maxFileSizeKilobytes,
                'additional_documents' => 'nullable|array',
                'additional_documents.*' => 'file|mimes:pdf|mimetypes:application/pdf|max:' . $maxFileSizeKilobytes,
                'import_registration_documents' => 'nullable|array|max:' . $maxFileColumn,
                'import_registration_documents.*' => 'file|mimes:pdf|mimetypes:application/pdf|max:' . $maxFileSizeKilobytes,
                'import_approval_documents' => 'nullable|array|max:' . $maxFileColumn,
                'import_approval_documents.*' => 'file|mimes:pdf|mimetypes:application/pdf|max:' . $maxFileSizeKilobytes,
                'progress' => 'nullable|numeric|min:0|max:100',
                'sub_progress' => 'nullable|numeric|min:0|max:100'
                

                // หมายเหตุ: สำหรับฟิลด์ที่ไม่จำเป็นต้องเปลี่ยนผ่านฟอร์ม (เช่น created_by, is_deleted) ไม่ต้องใส่ใน validation rules
            ], [
                'import_registration_documents.max' => 'อัปโหลดทะเบียนนำเข้าได้ไม่เกิน ' . $maxFileColumn . ' ไฟล์',
                'import_approval_documents.max' => 'อัปโหลดใบอนุญาตนำเข้าได้ไม่เกิน ' . $maxFileColumn . ' ไฟล์',
            ]);

            $newRegistrationFileCount = $this->uploadedFileCount($request, 'import_registration_documents')
                + $this->uploadedFileCount($request, 'additional_documents')
                + $this->uploadedFileCount($request, 'additional_document');
            $newApprovalFileCount = $this->uploadedFileCount($request, 'import_approval_documents');

            $this->ensureImportFileLimit(
                $import,
                self::DOCUMENT_TYPE_IMPORT_REGISTRATION,
                $newRegistrationFileCount,
                $maxFileColumn,
                'import_registration_documents',
                'ทะเบียนนำเข้า'
            );
            $this->ensureImportFileLimit(
                $import,
                self::DOCUMENT_TYPE_IMPORT_APPROVAL,
                $newApprovalFileCount,
                $maxFileColumn,
                'import_approval_documents',
                'ใบอนุญาตนำเข้า'
            );

            // 2. Map ข้อมูลและกำหนดค่าเพิ่มเติม (คล้ายกับ Store แต่ไม่มี created_by)
            $dataToUpdate = $validatedData;
            unset(
                $dataToUpdate['additional_document'],
                $dataToUpdate['additional_documents'],
                $dataToUpdate['import_registration_documents'],
                $dataToUpdate['import_approval_documents']
            );

            // กำหนดค่าสำหรับ Checkbox หรือค่า default
            $dataToUpdate['new_or_old'] = $request->has('new_or_old') ? true : false;
            $dataToUpdate['is_active'] = $request->has('is_active') ? true : false;
            // ไม่ต้อง update 'status', 'step' ถ้าฟอร์มไม่ได้ส่งมา หรือถ้ามี logic เฉพาะ
            // $dataToUpdate['step'] = $request->input('step', $productionRegistration->step);
            // $dataToUpdate['status'] = $request->input('status', $productionRegistration->status);

            // กำหนด updated_by โดยใช้ ID ของผู้ใช้งานที่ล็อกอินอยู่
            if (Auth::check()) {
                $dataToUpdate['updated_by'] = Auth::id(); // หรือ Auth::user()->name หากต้องการชื่อ
            } else {
                $dataToUpdate['updated_by'] = null;
            }

            // การจัดการไฟล์ (Image และ Document)
            // ถ้ามีการอัปโหลดไฟล์ใหม่ ให้ลบไฟล์เก่าก่อน (ถ้ามี)
            if ($request->hasFile('image')) {
                // ลบรูปเก่า (ถ้ามีและไม่ใช่ default image)
                if ($import->image && Storage::disk('public')->exists($import->image)) {
                    Storage::disk('public')->delete($import->image);
                }
                $imagePath = $request->file('image')->store('production_images', 'public');
                $dataToUpdate['image'] = $imagePath;
            }

            $uploadedFileGroups = [
                self::DOCUMENT_TYPE_IMPORT_REGISTRATION => [],
                self::DOCUMENT_TYPE_IMPORT_APPROVAL => [],
            ];

            if ($request->hasFile('import_registration_documents')) {
                $uploadedFileGroups[self::DOCUMENT_TYPE_IMPORT_REGISTRATION] = array_merge(
                    $uploadedFileGroups[self::DOCUMENT_TYPE_IMPORT_REGISTRATION],
                    $request->file('import_registration_documents')
                );
            }

            if ($request->hasFile('import_approval_documents')) {
                $uploadedFileGroups[self::DOCUMENT_TYPE_IMPORT_APPROVAL] = array_merge(
                    $uploadedFileGroups[self::DOCUMENT_TYPE_IMPORT_APPROVAL],
                    $request->file('import_approval_documents')
                );
            }

            if ($request->hasFile('additional_documents')) {
                $uploadedFileGroups[self::DOCUMENT_TYPE_IMPORT_REGISTRATION] = array_merge(
                    $uploadedFileGroups[self::DOCUMENT_TYPE_IMPORT_REGISTRATION],
                    $request->file('additional_documents')
                );
            }

            if ($request->hasFile('additional_document')) {
                $uploadedFileGroups[self::DOCUMENT_TYPE_IMPORT_REGISTRATION][] = $request->file('additional_document');
            }

            foreach ($uploadedFileGroups as $documentTypeCode => $uploadedFiles) {
                foreach ($uploadedFiles as $additionalDocument) {
                    $uploadedFile = $this->storeChemicalImportFile($import, $additionalDocument, $documentTypeCode);
                    $dataToUpdate['additional_document'] = $uploadedFile->file_path;
                    $dataToUpdate['document'] = $uploadedFile->original_name;
                }
            }

            // 3. อัปเดต Record ในฐานข้อมูล
            $import->update($dataToUpdate);

            // 4. ส่งกลับ Response หรือ Redirect ไปยังหน้าอื่น
            // return redirect()->route('production-registrations.index')->with('success', 'แก้ไขข้อมูลการขึ้นทะเบียนผลิตเรียบร้อยแล้ว!');
            return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error("Error update product: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(), // ข้อมูลฟอร์มที่ส่งมา
            ]);
        
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChemicalImport $import)
    {

        $import->delete();
        return redirect()->back()->withSuccess('Deleted !!!');
    }

    /**
     * แสดงหน้าฟอร์มอัปโหลดไฟล์ Excel.
     *
     * @return \Illuminate\View\View
     */
    public function showImportForm()
    {
        return view('chemical_imports.import'); // ต้องสร้าง view นี้ใน resources/views/chemical_imports/import.blade.php
    }

    /**
     * จัดการการนำเข้าข้อมูลจากไฟล์ Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', // กำหนดให้ไฟล์ต้องเป็น Excel และมีขนาดไม่เกิน 2MB
        ]);

        try {
            Excel::import(new ChemicalImportsImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . ' (' . $failure->attribute() . ' = ' . $failure->values()[$failure->attribute()] . ')';
            }

            return back()->with('import_errors', $errorMessages);
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาดในการนำเข้าข้อมูล: ' . $e->getMessage());
        }


        return back()->with('success', 'นำเข้าข้อมูลวัตถุอันตรายเรียบร้อยแล้ว!');
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

    private function storeChemicalImportFile(ChemicalImport $import, $file, ?string $documentTypeCode = null): ChemicalImportFile
    {
        $path = $file->store('import_additional_documents', 'public');

        return $import->files()->create([
            'document_type_code' => $documentTypeCode ?: $this->defaultDocumentTypeCode(),
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => basename($path),
            'file_extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'created_by' => Auth::id(),
            'uploaded_by' => Auth::id(),
            'uploaded_at' => now(),
        ]);
    }

    private function maxFileColumn(): int
    {
        $value = env('MAX_FILE_COLUM', env('MAX_FILE_COLUMN', 3));

        return max(1, (int) $value);
    }

    private function maxFileSizeKilobytes(): int
    {
        $value = env('MAX_FILE_SIZE_MB', 20);

        return max(1, (int) $value) * 1024;
    }

    private function uploadedFileCount(Request $request, string $inputName): int
    {
        if (!$request->hasFile($inputName)) {
            return 0;
        }

        $files = $request->file($inputName);

        return is_array($files) ? count($files) : 1;
    }

    private function ensureImportFileLimit(
        ChemicalImport $import,
        string $documentTypeCode,
        int $newFileCount,
        int $maxFileColumn,
        string $inputName,
        string $label
    ): void {
        if ($newFileCount <= 0) {
            return;
        }

        $existingFileCount = $this->existingImportFileCount($import, $documentTypeCode);

        if (($existingFileCount + $newFileCount) <= $maxFileColumn) {
            return;
        }

        $remaining = max(0, $maxFileColumn - $existingFileCount);

        throw ValidationException::withMessages([
            $inputName => 'อัปโหลด' . $label . 'ได้สูงสุด ' . $maxFileColumn . ' ไฟล์ ตอนนี้มีอยู่แล้ว '
                . $existingFileCount . ' ไฟล์ สามารถเพิ่มได้อีก ' . $remaining . ' ไฟล์',
        ]);
    }

    private function existingImportFileCount(ChemicalImport $import, string $documentTypeCode): int
    {
        $query = $import->files();

        if ($documentTypeCode === self::DOCUMENT_TYPE_IMPORT_REGISTRATION) {
            return (int) $query
                ->where(function ($q) {
                    $q->whereNull('document_type_code')
                        ->orWhere('document_type_code', '<>', self::DOCUMENT_TYPE_IMPORT_APPROVAL);
                })
                ->count();
        }

        return (int) $query->where('document_type_code', $documentTypeCode)->count();
    }

    private function defaultDocumentTypeCode(): string
    {
        if (!Schema::hasTable('document_types')) {
            return self::DOCUMENT_TYPE_IMPORT_REGISTRATION;
        }

        return DocumentType::where('is_active', true)
            ->where('module', 'chemical_imports')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->value('code')
            ?: self::DOCUMENT_TYPE_IMPORT_REGISTRATION;
    }

    private function serveChemicalImportFile(ChemicalImportFile $file)
    {
        $disk = Storage::disk('public');

        abort_unless($disk->exists($file->file_path), 404);

        $fileName = $file->original_name ?: basename($file->file_path);
        $fallbackFileName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($fileName)) ?: 'document.pdf';
        $encodedFileName = rawurlencode($fileName);

        return response()->file($disk->path($file->file_path), [
            'Content-Type' => $file->mime_type ?: ($disk->mimeType($file->file_path) ?: 'application/pdf'),
            'Content-Disposition' => 'inline; filename="' . $fallbackFileName . '"; filename*=UTF-8\'\'' . $encodedFileName,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
        ]);
    }
}
