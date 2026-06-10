<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionRegistrationFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'production_registration_files';

    protected $fillable = [
        'production_registration_id',
        'document_type_code',
        'original_name',
        'stored_name',
        'file_path',
        'file_extension',
        'mime_type',
        'file_size',
        'created_by',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function productionRegistration()
    {
        return $this->belongsTo(ProductionRegistration::class, 'production_registration_id');
    }
}
