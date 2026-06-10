<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShortenDocumentTypeCodes extends Migration
{
    private const CODE_MAP = [
        'chemical_import_registration' => 'import_reg',
        'chemical_import_approval' => 'import_license',
        'IMPORT_ADDITIONAL_DOCUMENT' => 'import_reg',
        'production_registration_document' => 'prod_reg',
        'production_approval_document' => 'prod_license',
    ];

    public function up()
    {
        $this->updateDocumentTypes(self::CODE_MAP);
        $this->updateFileCodes('chemical_imports_file', array_slice(self::CODE_MAP, 0, 3));
        $this->updateFileCodes('production_registration_files', array_slice(self::CODE_MAP, 3));

        if (Schema::hasTable('production_registration_files') && Schema::hasColumn('production_registration_files', 'document_type_code')) {
            DB::table('production_registration_files')
                ->whereNull('document_type_code')
                ->orWhere('document_type_code', '')
                ->update(['document_type_code' => 'prod_reg']);
        }
    }

    public function down()
    {
        $reverseMap = [
            'import_reg' => 'chemical_import_registration',
            'import_license' => 'chemical_import_approval',
            'prod_reg' => 'production_registration_document',
            'prod_license' => 'production_approval_document',
        ];

        $this->updateFileCodes('chemical_imports_file', array_slice($reverseMap, 0, 2));
        $this->updateFileCodes('production_registration_files', array_slice($reverseMap, 2));
        $this->updateDocumentTypes(array_slice($reverseMap, 0, 2));
    }

    private function updateFileCodes(string $table, array $codeMap): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'document_type_code')) {
            return;
        }

        foreach ($codeMap as $oldCode => $newCode) {
            DB::table($table)
                ->where('document_type_code', $oldCode)
                ->update(['document_type_code' => $newCode]);
        }
    }

    private function updateDocumentTypes(array $codeMap): void
    {
        if (!Schema::hasTable('document_types')) {
            return;
        }

        foreach ($codeMap as $oldCode => $newCode) {
            $oldRow = DB::table('document_types')->where('code', $oldCode)->first();

            if (!$oldRow) {
                continue;
            }

            $newExists = DB::table('document_types')->where('code', $newCode)->exists();

            if ($newExists) {
                DB::table('document_types')->where('code', $oldCode)->delete();
                continue;
            }

            DB::table('document_types')
                ->where('code', $oldCode)
                ->update([
                    'code' => $newCode,
                    'updated_at' => now(),
                ]);
        }
    }
}
