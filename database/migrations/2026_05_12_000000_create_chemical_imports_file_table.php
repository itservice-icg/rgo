<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChemicalImportsFileTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('chemical_imports_file')) {
            Schema::create('chemical_imports_file', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chemical_import_id')->constrained('chemical_imports')->cascadeOnDelete();
                $table->string('document_type_code')->nullable();
                $table->string('original_name')->nullable();
                $table->string('stored_name');
                $table->string('file_path');
                $table->string('file_extension')->nullable();
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->string('created_by')->nullable();
                $table->string('uploaded_by')->nullable();
                $table->timestamp('uploaded_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        Schema::table('chemical_imports_file', function (Blueprint $table) {
            if (!Schema::hasColumn('chemical_imports_file', 'document_type_code')) {
                $table->string('document_type_code')->nullable()->after('chemical_import_id');
            }

            if (!Schema::hasColumn('chemical_imports_file', 'mime_type')) {
                $table->string('mime_type')->nullable()->after('original_name');
            }

            if (!Schema::hasColumn('chemical_imports_file', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable()->after('mime_type');
            }

            if (!Schema::hasColumn('chemical_imports_file', 'created_by')) {
                $table->string('created_by')->nullable()->after('file_size');
            }

            if (!Schema::hasColumn('chemical_imports_file', 'uploaded_by')) {
                $table->string('uploaded_by')->nullable()->after('created_by');
            }

            if (!Schema::hasColumn('chemical_imports_file', 'uploaded_at')) {
                $table->timestamp('uploaded_at')->nullable()->after('uploaded_by');
            }

            if (!Schema::hasColumn('chemical_imports_file', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('created_by');
            }

            if (!Schema::hasColumn('chemical_imports_file', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }

            if (!Schema::hasColumn('chemical_imports_file', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        DB::table('chemical_imports')
            ->where(function ($query) {
                $query->whereNotNull('additional_document')
                    ->orWhereNotNull('document');
            })
            ->orderBy('id')
            ->get()
            ->each(function ($import) {
                $filePath = $import->additional_document ?: $import->document;

                if (!$filePath) {
                    return;
                }

                $fileExists = DB::table('chemical_imports_file')
                    ->where('chemical_import_id', $import->id)
                    ->where('file_path', $filePath)
                    ->exists();

                if ($fileExists) {
                    return;
                }

                DB::table('chemical_imports_file')->insert([
                    'chemical_import_id' => $import->id,
                    'document_type_code' => 'import_reg',
                    'file_path' => $filePath,
                    'original_name' => $import->additional_document
                        ? ($import->document ?: basename($import->additional_document))
                        : basename($import->document),
                    'stored_name' => basename($filePath),
                    'file_extension' => pathinfo($filePath, PATHINFO_EXTENSION) ?: null,
                    'created_by' => $import->updated_by ?: $import->created_by,
                    'uploaded_by' => $import->updated_by ?: $import->created_by,
                    'uploaded_at' => $import->updated_at ?: now(),
                    'created_at' => $import->updated_at ?: now(),
                    'updated_at' => $import->updated_at ?: now(),
                ]);
            });
    }

    public function down()
    {
        Schema::dropIfExists('chemical_imports_file');
    }
}
