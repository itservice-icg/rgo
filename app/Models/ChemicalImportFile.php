<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChemicalImportFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chemical_imports_file';

    protected $fillable = [
        'chemical_import_id',
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

    public function chemicalImport()
    {
        return $this->belongsTo(ChemicalImport::class, 'chemical_import_id');
    }
}
