<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDocumentTypeCodeToProductionRegistrationFiles extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('production_registration_files')) {
            return;
        }

        Schema::table('production_registration_files', function (Blueprint $table) {
            if (!Schema::hasColumn('production_registration_files', 'document_type_code')) {
                $table->string('document_type_code')->nullable()->after('production_registration_id');
            }
        });

        DB::table('production_registration_files')
            ->whereNull('document_type_code')
            ->orWhere('document_type_code', '')
            ->update(['document_type_code' => 'prod_reg']);
    }

    public function down()
    {
        if (!Schema::hasTable('production_registration_files')) {
            return;
        }

        Schema::table('production_registration_files', function (Blueprint $table) {
            if (Schema::hasColumn('production_registration_files', 'document_type_code')) {
                $table->dropColumn('document_type_code');
            }
        });
    }
}
