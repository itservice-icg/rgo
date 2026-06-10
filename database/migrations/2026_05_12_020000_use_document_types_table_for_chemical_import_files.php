<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UseDocumentTypesTableForChemicalImportFiles extends Migration
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

        if (Schema::hasTable('document_type')) {
            DB::table('document_type')->orderBy('id')->get()->each(function ($type) {
                DB::table('document_types')->updateOrInsert(
                    ['code' => $type->code],
                    [
                        'name' => $type->name,
                        'description' => $type->description ?? null,
                        'module' => $type->module ?? 'chemical_imports',
                        'sort_order' => $type->sort_order ?? 0,
                        'is_required' => $type->is_required ?? false,
                        'is_active' => $type->is_active ?? true,
                        'created_at' => $type->created_at ?? now(),
                        'updated_at' => $type->updated_at ?? now(),
                    ]
                );
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
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $fallbackCode = DB::table('document_types')
            ->where('module', 'chemical_imports')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->value('code') ?: 'import_reg';

        if (Schema::hasTable('chemical_imports_file') && Schema::hasColumn('chemical_imports_file', 'document_type_code')) {
            DB::table('chemical_imports_file')
                ->leftJoin('document_types', 'chemical_imports_file.document_type_code', '=', 'document_types.code')
                ->whereNull('document_types.id')
                ->update(['chemical_imports_file.document_type_code' => $fallbackCode]);
        }

        if (Schema::hasTable('document_type')) {
            Schema::drop('document_type');
        }
    }

    public function down()
    {
        if (!Schema::hasTable('document_type')) {
            Schema::create('document_type', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }
}
