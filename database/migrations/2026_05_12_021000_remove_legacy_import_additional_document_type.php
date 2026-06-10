<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveLegacyImportAdditionalDocumentType extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('document_types')) {
            return;
        }

        $fallbackCode = DB::table('document_types')
            ->where('module', 'chemical_imports')
            ->where('is_active', true)
            ->where('code', '<>', 'IMPORT_ADDITIONAL_DOCUMENT')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->value('code') ?: 'import_reg';

        if (Schema::hasTable('chemical_imports_file') && Schema::hasColumn('chemical_imports_file', 'document_type_code')) {
            DB::table('chemical_imports_file')
                ->where('document_type_code', 'IMPORT_ADDITIONAL_DOCUMENT')
                ->update(['document_type_code' => $fallbackCode]);
        }

        DB::table('document_types')
            ->where('code', 'IMPORT_ADDITIONAL_DOCUMENT')
            ->delete();
    }

    public function down()
    {
        if (!Schema::hasTable('document_types')) {
            return;
        }

        DB::table('document_types')->updateOrInsert(
            ['code' => 'IMPORT_ADDITIONAL_DOCUMENT'],
            [
                'name' => 'เอกสารเพิ่มเติมทะเบียนนำเข้า',
                'description' => 'เอกสารเพิ่มเติมทะเบียนนำเข้า',
                'module' => 'chemical_imports',
                'sort_order' => 99,
                'is_required' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
