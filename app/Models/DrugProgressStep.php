<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugProgressStep extends Model
{
    protected $fillable = [
        'chemical_registrations_id',
        'step_number',
        'sub_step_index',
        'sub_step_label',
        'department',
        'checked_at',
        'created_by',
        'remark',
    ];

    public function drug()
    {
        return $this->belongsTo(ChemicalRegistration::class);
    }
}
