<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalDocumentToProductionRegistrationTable extends Migration
{
    public function up()
    {
        Schema::table('production_registration', function (Blueprint $table) {
            $table->string('additional_document')->nullable()->after('document');
        });
    }

    public function down()
    {
        Schema::table('production_registration', function (Blueprint $table) {
            $table->dropColumn('additional_document');
        });
    }
}
