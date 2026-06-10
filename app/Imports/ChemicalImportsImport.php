<?php

namespace App\Imports;

use App\Models\ChemicalImport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ChemicalImportsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $parseDate = function ($dateValue) {
            if (is_numeric($dateValue) && $dateValue > 0) {
                try {
                    return Carbon::createFromTimestamp(Date::excelToTimestamp($dateValue))->toDateString();
                } catch (\Exception $e) {
                    return null;
                }
            } elseif (is_string($dateValue) && !empty($dateValue)) {
                try {
                    return Carbon::parse($dateValue)->toDateString();
                } catch (\Exception $e) {
                    return null;
                }
            }
            return null;
        };

        return new ChemicalImport(
            [
                'company_id'                  => $row['company_id'] ?? null, // คุณต้องมีคอลัมน์ company_id ใน Excel หรือหา id จากชื่อบริษัท
                'registration_number'         => $row['registration_number'] ?? null, // เลขที่ทะเบียน
                'expired_license_date'        => $parseDate($row['expired_license_date'] ?? null), // วันหมดอายุ
                'chemical_name_th'            => $row['chemical_name_th'] ?? null, // ชื่อวัตถุอันตราย (ไทย)
                'chemical_name_en'            => $row['chemical_name_en'] ?? null, // ชื่อวัตถุอันตราย (อังกฤษ)
                'composition'                 => $row['composition'] ?? null, // เปอร์เซ็นต์และสูตร
                'manufacturer'                => $row['manufacturer'] ?? null, // ผู้ผลิตและแหล่งผลิต
                'registrant'                  => $row['registrant'] ?? null, // ผู้ขึ้นทะเบียน
                'registration_type'           => $row['registration_type'] ?? null, // ประเภททะเบียน
                'importer'                    => $row['importer'] ?? null, // ชื่อผู้นำเข้า
                'distributor'                 => $row['distributor'] ?? null, // ชื่อผู้จำหน่าย
                'trade_name'                  => $row['trade_name'] ?? null, // ชื่อการค้า
                'trade_name_at'               => $row['trade_name_at'] ?? null, // ชื่อการค้าที่
                'type_production_registration'  => $row['type_production_registration'] ?? null, // ชนิดทะเบียน
                'usage_production_registration' => $row['usage_production_registration'] ?? null, // ประเภทของการใช้
                'group_of_substances'         => $row['group_of_substances'] ?? null, // กลุ่มสาร
                'plant'                       => $row['plant'] ?? null, // พืช
                'pests'                       => $row['pests'] ?? null, // ศัตรูพืช
                'registration_number_pass'    => $row['registration_number_pass'] ?? null, // เลขที่ใบอนุญาต
                'production_license_expiry'   => $parseDate($row['production_license_expiry'] ?? null), // วันหมดอายุใบอนุญาต
                'production_license_quantity' => $row['production_license_quantity'] ?? null, // ปริมาณ
                'possession_form_wo2'         => $row['possession_form_wo2'] ?? null, // ใบแจ้งครอบครอง วอ.2
                // 'possession_form_expiry'         => $row['possession_form_expiry'] ?? null, // วันหมดอายุใบแจ้งครอบครอง
                'possession_form_expiry'      => $parseDate($row['possession_form_expiry'] ?? null), // วันหมดอายุใบแจ้งครอบครอง วอ.2
                'packaging_size_details'      => $row['packaging_size_details'] ?? null, // รายละเอียดขนาดบรรจุ
                'production_license_number'   => $row['production_license_number'] ?? null, // ใบอนุญาตเลขที่เดิม
                // 'expired_at'   => $row['expired_at'] ?? null,  // วันหมดอายุใบอนุญาตเดิม
                'expired_at'                  => $parseDate($row['expired_at'] ?? null), // วันหมดอายุใบอนุญาตเดิม
            ]
        );
    }

    public function headingRow(): int
    {
        return 1; // ให้แพ็กเกจใช้แถวแรกเป็นชื่อคอลัมน์
    }
}
