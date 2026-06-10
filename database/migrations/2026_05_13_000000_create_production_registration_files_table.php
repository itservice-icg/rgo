<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductionRegistrationFilesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('production_registration_files')) {
            Schema::create('production_registration_files', function (Blueprint $table) {
                $table->id();
                $table->foreignId('production_registration_id')->constrained('production_registration')->cascadeOnDelete();
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

        DB::table('production_registration')
            ->where(function ($query) {
                $query->whereNotNull('additional_document')
                    ->orWhereNotNull('document');
            })
            ->orderBy('id')
            ->get()
            ->each(function ($product) {
                $filePath = $product->additional_document ?: $product->document;

                if (!$filePath) {
                    return;
                }

                $fileExists = DB::table('production_registration_files')
                    ->where('production_registration_id', $product->id)
                    ->where('file_path', $filePath)
                    ->exists();

                if ($fileExists) {
                    return;
                }

                DB::table('production_registration_files')->insert([
                    'production_registration_id' => $product->id,
                    'file_path' => $filePath,
                    'original_name' => $product->additional_document
                        ? ($product->document ?: basename($product->additional_document))
                        : basename($product->document),
                    'stored_name' => basename($filePath),
                    'file_extension' => pathinfo($filePath, PATHINFO_EXTENSION) ?: null,
                    'created_by' => $product->updated_by ?: $product->created_by,
                    'uploaded_by' => $product->updated_by ?: $product->created_by,
                    'uploaded_at' => $product->updated_at ?: now(),
                    'created_at' => $product->updated_at ?: now(),
                    'updated_at' => $product->updated_at ?: now(),
                ]);
            });
    }

    public function down()
    {
        Schema::dropIfExists('production_registration_files');
    }
}
