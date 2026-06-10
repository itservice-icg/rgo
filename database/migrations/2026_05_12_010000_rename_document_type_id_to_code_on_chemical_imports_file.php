<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameDocumentTypeIdToCodeOnChemicalImportsFile extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('document_types')) {
            Schema::create('document_types', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('module')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_required')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        DB::table('document_types')->updateOrInsert(
            ['code' => 'import_reg'],
            [
                'name' => 'ทะเบียนนำเข้า',
                'description' => 'เอกสารทะเบียนนำเข้าสารเคมี',
                'module' => 'chemical_imports',
                'sort_order' => 1,
                'is_required' => true,
                'is_active' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        if (!Schema::hasTable('chemical_imports_file')) {
            return;
        }

        Schema::table('chemical_imports_file', function (Blueprint $table) {
            if (!Schema::hasColumn('chemical_imports_file', 'document_type_code')) {
                $table->string('document_type_code')->nullable()->after('chemical_import_id');
            }
        });

        if (Schema::hasColumn('chemical_imports_file', 'document_type_id')) {
            DB::table('chemical_imports_file')
                ->leftJoin('document_types', 'chemical_imports_file.document_type_id', '=', 'document_types.id')
                ->whereNotNull('chemical_imports_file.document_type_id')
                ->update([
                    'chemical_imports_file.document_type_code' => DB::raw('document_types.code'),
                ]);
        }

        DB::table('chemical_imports_file')
            ->whereNull('document_type_code')
            ->orWhere('document_type_code', '')
            ->update(['document_type_code' => 'import_reg']);

        if (Schema::hasColumn('chemical_imports_file', 'document_type_id')) {
            $foreignKeys = DB::select(
                "SELECT CONSTRAINT_NAME
                 FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = ?
                   AND COLUMN_NAME = ?
                   AND REFERENCED_TABLE_NAME IS NOT NULL",
                ['chemical_imports_file', 'document_type_id']
            );

            foreach ($foreignKeys as $foreignKey) {
                DB::statement('ALTER TABLE `chemical_imports_file` DROP FOREIGN KEY `' . $foreignKey->CONSTRAINT_NAME . '`');
            }

            Schema::table('chemical_imports_file', function (Blueprint $table) {
                $table->dropColumn('document_type_id');
            });
        }
    }

    public function down()
    {
        Schema::table('chemical_imports_file', function (Blueprint $table) {
            if (!Schema::hasColumn('chemical_imports_file', 'document_type_id')) {
                $table->unsignedBigInteger('document_type_id')->nullable()->after('chemical_import_id');
            }
        });

        if (Schema::hasColumn('chemical_imports_file', 'document_type_code')) {
            Schema::table('chemical_imports_file', function (Blueprint $table) {
                $table->dropColumn('document_type_code');
            });
        }
    }
}
