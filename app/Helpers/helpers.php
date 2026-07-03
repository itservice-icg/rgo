<?php

if (! function_exists('thai_date')) {
    /**
     * Format a date value for Thai Buddhist Era display.
     */
    function thai_date($value, string $format = 'd/m/Y')
    {
        if (empty($value)) {
            return null;
        }

        $timestamp = strtotime($value);

        if ($timestamp === false) {
            return null;
        }

        $date = date($format, $timestamp);
        $year = (int) date('Y', $timestamp) + 543;

        return str_replace(date('Y', $timestamp), (string) $year, $date);
    }
}

if (! function_exists('pdf_tiled_watermark_config')) {
    /**
     * PDF tiled watermark settings.
     */
    function pdf_tiled_watermark_config(): array
    {
        return [
            // เปิดหรือปิดลายน้ำแบบเรียงต่อกันใน PDF
            'enabled' => true,
            // แสดงโลโก้ลายน้ำเป็นสี ถ้าตั้งเป็น false จะแสดงเป็นขาวดำ
            'color' => true,
            // ความโปร่งใสของลายน้ำ ค่าอยู่ระหว่าง 0.01 ถึง 1
            'opacity' => 0.08,
            // 'opacity' => 1,
            // path ของโลโก้ใต้โฟลเดอร์ public
            'logo_path' => 'images/Logo_Inter_Crop_2017.png',
            // ขนาดด้านที่ยาวที่สุดของโลโก้ หน่วยเป็น px โดยยังรักษาสัดส่วนเดิม
            'logo_size' => 140,
            // ระยะห่างแนวนอนระหว่างลายน้ำแต่ละอัน หน่วยเป็น px
            'gap_x' => 60,
            // ระยะห่างแนวตั้งระหว่างลายน้ำแต่ละอัน หน่วยเป็น px
            'gap_y' => 60,
            // มุมเอียงของลายน้ำ หน่วยเป็นองศา เช่น -30, 0, 45
            'angle' => -30,
        ];
    }
}
