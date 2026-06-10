<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Don't forget to use SoftDeletes

class ProductionRegistration extends Model
{
    use HasFactory, SoftDeletes; // Add SoftDeletes to the use statement

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_registration'; // Explicitly define the table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'registration_number',
        'expired_license_date',
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
        'type_production_registration',
        'usage_production_registration',
        'group_of_substances',
        'plant',
        'pests',
        'production_license_number',
        'production_license_expiry',
        'production_license_quantity',
        'possession_form_wo2',
        'possession_form_expiry',
        'packaging_size_details',
        'registration_number_pass',
        'registration_expiry_date',
        'expired_at',
        'status_date',
        'remarks',
        'new_or_old',
        'step',
        'status',
        'is_active',
        'is_deleted',
        'image',
        'document',
        'additional_document',
        'progress',
        'sub_progress',
        'created_by',
        'updated_by',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expired_license_date' => 'date',
        // 'production_license_expiry' => 'date',
        'new_or_old' => 'boolean',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'progress' => 'decimal:2', // Cast to decimal with 2 precision
        'sub_progress' => 'decimal:2', // Cast to decimal with 2 precision
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function importerCompany()
    {
        return $this->belongsTo(Company::class, 'importer'); // 'store_company_1' is the foreign key
    }

    public function distributorCompany()
    {
        return $this->belongsTo(Company::class, 'distributor'); // 'store_company_1' is the foreign key
    }

    public function files()
    {
        return $this->hasMany(ProductionRegistrationFile::class, 'production_registration_id')->latest('id');
    }
}
