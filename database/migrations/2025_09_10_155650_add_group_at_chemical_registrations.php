<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupAtChemicalRegistrations extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('chemical_registrations', function (Blueprint $table) {
            $table->string('group_of_substances')->nullable(); // กลุ่มสาร
            $table->string('plant')->nullable();               // พืช
            $table->string('pests')->nullable();               // ศัตรูพืช
            $table->string('quantity')->nullable();            // ปริมาณ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('chemical_registrations', function (Blueprint $table) {
            $table->dropColumn(['group_of_substances', 'plant', 'pests', 'quantity']);
        });
    }
}
