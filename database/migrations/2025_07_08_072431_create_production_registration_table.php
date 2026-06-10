<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_registration', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id') // บริษัทที่ขึ้นทะเบียน
                ->nullable()
                ->constrained('companies')
                ->onDelete('set null');
            $table->string('registration_number')->nullable(); // เลขที่ทะเบียน
            $table->date('expired_license_date')->nullable(); // วันหมดอายุ
            $table->text('composition')->nullable(); // เปอร์เซ็นต์และสูตร
            $table->string('chemical_name_th')->nullable(); // ชื่อวัตถุอันตราย (ไทย)
            $table->string('chemical_name_en')->nullable(); // ชื่อวัตถุอันตราย (อังกฤษ)
            $table->text('manufacturer')->nullable(); // ผู้ผลิตและแหล่งผลิต
            $table->string('registration_type')->nullable(); // ประเภททะเบียน
            $table->string('importer')->nullable(); // ชื่อผู้นำเข้า
            $table->string('distributor')->nullable(); // ชื่อผู้จำหน่าย
            $table->string('trade_name')->nullable(); // ชื่อการค้า
            $table->string('trade_name_at')->nullable(); // ชื่อการค้าที่
            $table->string('type_production_registration')->nullable(); // ชนิดทะเบียน
            $table->string('usage_production_registration')->nullable(); // ประเภทของการใช้
            $table->string('group_of_substances')->nullable(); // กลุ่มสาร
            $table->string('plant')->nullable(); // พืช
            $table->string('pests')->nullable(); // ศัตรูพืช
            $table->string('production_license_quantity')->nullable(); // ปริมาณ
            $table->string('registration_number_pass')->nullable(); // เลขที่ใบอนุญาต
            $table->date('production_license_expiry')->nullable(); // วันหมดอายุใบอนุญาต
            $table->string('production_license_number')->nullable(); // ใบอนุญาตเลขที่เดิม
            $table->text('expired_at')->nullable(); // วันหมดอายุใบอนุญาตเดิม
            $table->string('possession_form_wo2')->nullable(); // ใบแจ้งครอบครอง วอ.2
            $table->text('possession_form_expiry')->nullable(); // วันหมดอายุใบแจ้งครอบครอง วอ.2
            $table->text('packaging_size_details')->nullable(); // รายละเอียดขนาดบรรจุ

            // No Data
            $table->string('registrant')->nullable(); // ผู้ขึ้นทะเบียน
            $table->text('registration_expiry_date')->nullable(); // เลขที่ใบอนุญาตหมดอายุ
            $table->string('status_date')->nullable(); // สถานะวัน
            $table->text('remarks')->nullable(); // อื่นๆ (ระบุ)
            $table->boolean('new_or_old')->default(true); // สถานะของข้อมูล (true = ใหม่, false = เก่า)
            $table->string('step')->nullable(); // ขั้นตอนการขึ้นทะเบียน เช่น 'initial', 'review', 'approval'
            $table->string('status')->default('pending'); // สถานะของการขึ้นทะเบียนผลิตภัณฑ์ เช่น pending, approved, rejected
            $table->boolean('is_active')->default(true); // สถานะการใช้งานของผลิตภัณฑ์ (true = ใช้งาน, false = ไม่ใช้งาน)
            $table->boolean('is_deleted')->default(false); // สถานะการลบของผลิตภัณฑ์ (true = ถูกลบ, false = ไม่ถูกลบ)
            $table->string('image')->nullable(); // ลิงก์หรือชื่อไฟล์ของภาพผลิตภัณฑ์
            $table->string('document')->nullable(); // ลิงก์หรือชื่อไฟล์ของเอกสารที่เกี่ยวข้องกับผลิตภัณฑ์
            $table->decimal('progress')->default(0)->comment('สถานะความคืบหน้าของการขึ้นทะเบียนผลิตภัณฑ์');
            $table->decimal('sub_progress')->default(0); // เพิ่มหลัง progress
            $table->string('created_by')->nullable(); // ผู้ที่สร้างข้อมูลการขึ้นทะเบียนผลิตภัณฑ์
            $table->string('updated_by')->nullable(); // ผู้ที่ปรับปรุงข้อมูลการขึ้นทะเบียนผลิตภัณฑ์
            $table->softDeletes(); // วันที่และเวลาที่ลบข้อมูลผลิตภัณฑ์ (ใช้สำหรับ soft delete)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_registration');
    }
}
