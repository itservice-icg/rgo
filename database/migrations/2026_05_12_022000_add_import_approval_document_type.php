<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddImportApprovalDocumentType extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('document_types')) {
            return;
        }

        DB::table('document_types')->updateOrInsert(
            ['code' => 'import_license'],
            [
                'name' => 'ใบอนุมัตินำเข้า',
                'description' => 'เอกสารใบอนุมัตินำเข้าสารเคมี',
                'module' => 'chemical_imports',
                'sort_order' => 2,
                'is_required' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down()
    {
        if (!Schema::hasTable('document_types')) {
            return;
        }

        DB::table('document_types')
            ->where('code', 'import_license')
            ->delete();
    }
}
