<?php

namespace App\Http\Controllers;

use App\Models\ProductionRegistration; // Added automatically by --model flag
use App\Models\ProductionRegistrationFile;
use App\Models\ChemicalImport; // Added automatically by --model flag
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Log;
use Carbon\Carbon;


class ProductionRegistrationController extends Controller
{
    private const DOCUMENT_TYPE_PRODUCTION_REGISTRATION = 'prod_reg';
    private const DOCUMENT_TYPE_PRODUCTION_APPROVAL = 'prod_license';

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        $query = ProductionRegistration::query();
        $query->with('company');

        if ($request->filled('search')) {
            // Normalize: remove whitespace and lowercase the search term for flexible matching
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
        }



        $imports = $query->latest()->paginate(10)->withQueryString();
        $total = ProductionRegistration::count();
        $expiredCount = ProductionRegistration::whereDate('expired_license_date', '<', Carbon::now())->count();
        $soonCount = ProductionRegistration::whereDate('expired_license_date', '>=', Carbon::now())
            ->whereDate('expired_license_date', '<=', Carbon::now()->addMonths(6))
            ->count();

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

        return view('production_registrations.index', [
            'imports' => $imports,
            'total' => $total,
            'expiredCount' => $expiredCount,
            'soonCount' => $soonCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        return view('production_registrations.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        try {
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
                'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // ตัวอย่าง: อนุญาตเฉพาะ PDF, DOC, DOCX ขนาดไม่เกิน 5MB
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

            ProductionRegistration::create($dataToSave);
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
     */
    public function show(ProductionRegistration $productionRegistration)
    {
        // The $productionRegistration instance is automatically resolved by Route Model Binding
        $productionRegistration->load('files');
        $product = $productionRegistration;
        $companies = Company::all();
        return view('production_registrations.show', compact('product', 'companies'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionRegistration $productionRegistration)
    {
        $productionRegistration->load('files');
        $companies = Company::all();
        // ถ้าต้องการใช้ชื่อ $import ใน Blade ก็ map เพิ่ม
        $import = $productionRegistration;
        return view('production_registrations.edit', compact('import', 'companies'));
    }

    public function additionalDocument(ProductionRegistration $productionRegistration)
    {
        $file = $productionRegistration->files()->first();

        if ($file) {
            return $this->serveProductionRegistrationFile($file);
        }

        $filePath = $productionRegistration->additional_document ?: $productionRegistration->document;

        abort_unless($filePath, 404);

        $disk = Storage::disk('public');
        abort_unless($disk->exists($filePath), 404);

        $path = $disk->path($filePath);
        $fileName = basename($filePath);

        return response()->file($path, [
            'Content-Type' => $disk->mimeType($filePath) ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . addslashes($fileName) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
        ]);
    }

    public function file(ProductionRegistration $productionRegistration, ProductionRegistrationFile $file)
    {
        abort_unless((int) $file->production_registration_id === (int) $productionRegistration->id, 404);

        return $this->serveProductionRegistrationFile($file);
    }

    public function destroyFile(ProductionRegistration $productionRegistration, ProductionRegistrationFile $file)
    {
        abort_unless((int) $file->production_registration_id === (int) $productionRegistration->id, 404);

        if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->forceDelete();

        $latestFile = $productionRegistration->files()->first();
        $productionRegistration->update([
            'additional_document' => $latestFile?->file_path,
            'document' => $latestFile?->original_name,
        ]);

        return redirect()->back()
            ->with('success', 'ลบเอกสารสำเร็จ')
            ->with('file_deleted', true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductionRegistration $import)
    {

        // dd( $request->all());
        try {
            $maxFileColumn = $this->maxFileColumn();

            $request->merge([
                'expired_license_date'  => $this->convertDate($request->input('expired_license_date')),
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
                'manufacturer' => 'nullable|string|max:1000',
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
                'additional_document' => 'nullable|array',
                'additional_document.*' => 'file|mimes:pdf,doc,docx|max:5120',
                'production_registration_documents' => 'nullable|array|max:' . $maxFileColumn,
                'production_registration_documents.*' => 'file|mimes:pdf,doc,docx|max:5120',
                'production_approval_documents' => 'nullable|array|max:' . $maxFileColumn,
                'production_approval_documents.*' => 'file|mimes:pdf,doc,docx|max:5120',
                'progress' => 'nullable|numeric|min:0|max:100',
                'sub_progress' => 'nullable|numeric|min:0|max:100',
                // หมายเหตุ: สำหรับฟิลด์ที่ไม่จำเป็นต้องเปลี่ยนผ่านฟอร์ม (เช่น created_by, is_deleted) ไม่ต้องใส่ใน validation rules
            ], [
                'production_registration_documents.max' => 'อัปโหลดไฟล์ทะเบียนผลิตได้ไม่เกิน ' . $maxFileColumn . ' ไฟล์',
                'production_approval_documents.max' => 'อัปโหลดไฟล์ใบอนุญาตผลิตได้ไม่เกิน ' . $maxFileColumn . ' ไฟล์',
            ]);

            // 2. Map ข้อมูลและกำหนดค่าเพิ่มเติม (คล้ายกับ Store แต่ไม่มี created_by)
            $newRegistrationFileCount = $this->uploadedFileCount($request, 'production_registration_documents')
                + $this->uploadedFileCount($request, 'additional_document');
            $newApprovalFileCount = $this->uploadedFileCount($request, 'production_approval_documents');

            $this->ensureProductionFileLimit(
                $import,
                self::DOCUMENT_TYPE_PRODUCTION_REGISTRATION,
                $newRegistrationFileCount,
                $maxFileColumn,
                'production_registration_documents',
                'ไฟล์ทะเบียนผลิต'
            );
            $this->ensureProductionFileLimit(
                $import,
                self::DOCUMENT_TYPE_PRODUCTION_APPROVAL,
                $newApprovalFileCount,
                $maxFileColumn,
                'production_approval_documents',
                'ไฟล์ใบอนุญาตผลิต'
            );

            $dataToUpdate = $validatedData;
            unset(
                $dataToUpdate['additional_document'],
                $dataToUpdate['production_registration_documents'],
                $dataToUpdate['production_approval_documents']
            );
            // กำหนดค่าสำหรับ Checkbox หรือค่า default
            $dataToUpdate['new_or_old'] = $request->has('new_or_old') ? true : false;
            $dataToUpdate['is_active'] = $request->has('is_active') ? true : false;
            // กำหนด updated_by โดยใช้ ID ของผู้ใช้งานที่ล็อกอินอยู่
            if (Auth::check()) {
                $dataToUpdate['updated_by'] = Auth::id(); // หรือ Auth::user()->name หากต้องการชื่อ
            } else {
                $dataToUpdate['updated_by'] = null;
            }

            // ถ้ามีการอัปโหลดไฟล์ใหม่ ให้ลบไฟล์เก่าก่อน (ถ้ามี)
            if ($request->hasFile('image')) {
                // ลบรูปเก่า (ถ้ามีและไม่ใช่ default image)
                if ($import->image && \Storage::disk('public')->exists($import->image)) {
                    \Storage::disk('public')->delete($import->image);
                }
                $imagePath = $request->file('image')->store('production_images', 'public');
                $dataToUpdate['image'] = $imagePath;
            }

            if ($request->hasFile('additional_document')) {
                foreach ($request->file('additional_document') as $additionalDocument) {
                    $uploadedFile = $this->storeProductionRegistrationFile($import, $additionalDocument, self::DOCUMENT_TYPE_PRODUCTION_REGISTRATION);
                    $dataToUpdate['additional_document'] = $uploadedFile->file_path;
                    $dataToUpdate['document'] = $uploadedFile->original_name;
                }
            }

            if ($request->hasFile('production_registration_documents')) {
                foreach ($request->file('production_registration_documents') as $additionalDocument) {
                    $uploadedFile = $this->storeProductionRegistrationFile($import, $additionalDocument, self::DOCUMENT_TYPE_PRODUCTION_REGISTRATION);
                    $dataToUpdate['additional_document'] = $uploadedFile->file_path;
                    $dataToUpdate['document'] = $uploadedFile->original_name;
                }
            }

            if ($request->hasFile('production_approval_documents')) {
                foreach ($request->file('production_approval_documents') as $additionalDocument) {
                    $uploadedFile = $this->storeProductionRegistrationFile($import, $additionalDocument, self::DOCUMENT_TYPE_PRODUCTION_APPROVAL);
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
     */
    public function destroy($id)
    {
        $product = ProductionRegistration::findOrFail($id); // เปลี่ยน YourProductModel เป็นชื่อ Model ของคุณ
        $product->delete();

        return redirect()->route('createproduct.index')->with('success', 'ลบข้อมูลทะเบียนผลิตเรียบร้อยแล้ว');
    }

    public function showImportForm()
    {
        return view('chemical_imports.import_product'); // ต้องสร้าง view นี้ใน resources/views/chemical_imports/import.blade.php
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', // กำหนดให้ไฟล์ต้องเป็น Excel และมีขนาดไม่เกิน 2MB
        ]);

        try {
            Excel::import(new ChemicalImportsImport(), $request->file('file'));
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
    private function maxFileColumn(): int
    {
        $value = env('MAX_FILE_COLUM', env('MAX_FILE_COLUMN', 3));

        return max(1, (int) $value);
    }

    private function uploadedFileCount(Request $request, string $inputName): int
    {
        if (!$request->hasFile($inputName)) {
            return 0;
        }

        $files = $request->file($inputName);

        return is_array($files) ? count($files) : 1;
    }

    private function ensureProductionFileLimit(
        ProductionRegistration $productionRegistration,
        string $documentTypeCode,
        int $newFileCount,
        int $maxFileColumn,
        string $inputName,
        string $label
    ): void {
        if ($newFileCount <= 0) {
            return;
        }

        $existingFileCount = $this->existingProductionFileCount($productionRegistration, $documentTypeCode);

        if (($existingFileCount + $newFileCount) <= $maxFileColumn) {
            return;
        }

        $remaining = max(0, $maxFileColumn - $existingFileCount);

        throw ValidationException::withMessages([
            $inputName => 'อัปโหลด' . $label . 'ได้สูงสุด ' . $maxFileColumn . ' ไฟล์ ตอนนี้มีอยู่แล้ว '
                . $existingFileCount . ' ไฟล์ สามารถเพิ่มได้อีก ' . $remaining . ' ไฟล์',
        ]);
    }

    private function existingProductionFileCount(ProductionRegistration $productionRegistration, string $documentTypeCode): int
    {
        $query = $productionRegistration->files();

        if ($documentTypeCode === self::DOCUMENT_TYPE_PRODUCTION_REGISTRATION) {
            return (int) $query
                ->where(function ($q) {
                    $q->whereNull('document_type_code')
                        ->orWhere('document_type_code', '<>', self::DOCUMENT_TYPE_PRODUCTION_APPROVAL);
                })
                ->count();
        }

        return (int) $query->where('document_type_code', $documentTypeCode)->count();
    }

    private function storeProductionRegistrationFile(ProductionRegistration $productionRegistration, $file, ?string $documentTypeCode = null): ProductionRegistrationFile
    {
        $path = $file->store('production_additional_documents', 'public');

        return $productionRegistration->files()->create([
            'document_type_code' => $documentTypeCode ?: self::DOCUMENT_TYPE_PRODUCTION_REGISTRATION,
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

    private function serveProductionRegistrationFile(ProductionRegistrationFile $file)
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
