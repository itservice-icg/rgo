<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChemicalRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chemical_imports_id',
        'registration_number',
        'registration_number_pass',
        'registration_expiry_date',
        'chemical_name_th',
        'chemical_name_en',
        'composition',
        'manufacturer',
        'registrant',
        'registration_type',
        'importer',
        'distributor',
        'trade_name',
        'trade_name_at',
        'production_license_number',
        'production_license_expiry',
        'production_license_quantity',
        'possession_form_wo2',
        'possession_form_expiry',
        'application_received_date',
        'expired_license_number',
        'expired_at',
        'old_license_quantity',
        'packaging_size',
        'formula_of_ratio',
        'type_registration',
        'common_name',
        'packaging_size_details',
        'type_of_use',
        'date_submit_request',
        'request_number_1',
        'request_number_phase_1',
        'date_request_phase_3',
        'request_number_phase_3',
        'name_position',
        'remarks',
        'new_or_old',
        'step',
        'chemical_type',
        'company',
        'store_company_1',
        'store_company_2',
        'status',
        'is_active',
        'is_deleted',
        'image',
        'document',
        'progress',
        'sub_progress',
        'created_by',
        'updated_by',
        'group_of_substances',  // กลุ่มสาร
        'plant',                // พืช
        'pests',                // ศัตรูพืช
        'quantity',             // ปริมาณ
        'created_at',             
    ];

    // Optional: สำหรับวันที่ที่เป็น Carbon instance เช่น soft delete, timestamps
    protected $dates = [
        'new_or_old' => 'boolean',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'registration_expiry_date' => 'date',
        'production_license_expiry' => 'date',
        // 'possession_form_expiry' => 'date',
        'application_received_date' => 'date',
        // 'expired_at' => 'date',
        'date_request_phase_3' => 'date',
        'progress' => 'float',
        'sub_progress' => 'float',
        'date_submit_request' => 'datetime', // เพิ่มบรรทัดนี้
    ];

    // Optional: ความสัมพันธ์กับตารางอื่น (ถ้า chemical_imports มี model)
    public function chemicalImport()
    {
        return $this->belongsTo(ChemicalImport::class, 'chemical_imports_id');
    }

    public function progressSteps()
    {
        return $this->hasMany(DrugProgressStep::class);
    }

    public function stepSubSteps($stepNumber)
    {
        return $this->hasMany(DrugProgressStep::class, 'chemical_registrations_id')
            ->where('step_number', $stepNumber);
    }

    public function checkPlan($id)
    {
        return DrugProgressStep::where('chemical_registrations_id', $id)
            ->where('created_by', 'มี')->exists();
    }
}
