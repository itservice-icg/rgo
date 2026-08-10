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

if (! function_exists('normalize_department_name')) {
    /**
     * Normalize department values so the UI can match both Thai and English names.
     */
    function normalize_department_name($department): string
    {
        if (empty($department)) {
            return '';
        }

        $value = trim((string) $department);
        $map = [
            'registration' => 'แผนกทะเบียน',
            'register' => 'แผนกทะเบียน',
            'ทะเบียน' => 'แผนกทะเบียน',
            'internationalprocurement' => 'จัดซื้อต่างประเทศ',
            'international procurement' => 'จัดซื้อต่างประเทศ',
            'foreign_purchase' => 'จัดซื้อต่างประเทศ',
            'จัดซื้อต่างประเทศ' => 'จัดซื้อต่างประเทศ',
            'salesdepartment' => 'ฝ่ายขาย',
            'sales department' => 'ฝ่ายขาย',
            'sales' => 'ฝ่ายขาย',
            'ฝ่ายขาย' => 'ฝ่ายขาย',
            'researchanddevelopment' => 'วิจัยและพัฒนา',
            'research and development' => 'วิจัยและพัฒนา',
            'research' => 'วิจัยและพัฒนา',
            'วิจัยและพัฒนา' => 'วิจัยและพัฒนา',
            'academic' => 'แผนกวิชาการ',
            'วิชาการ' => 'แผนกวิชาการ',
            'it' => 'เทคโนโลยีสารสนเทศ',
            'เทคโนโลยีสารสนเทศ' => 'เทคโนโลยีสารสนเทศ',
        ];

        $lower = mb_strtolower($value, 'UTF-8');

        return $map[$lower] ?? $value;
    }
}

if (! function_exists('can_manage_new_registration_steps')) {
    /**
     * Determine whether the user can view every department section in the workflow.
     * Only admins and Registration-department users should see all sections.
     */
    function can_manage_new_registration_steps($user): bool
    {
        if (! $user) {
            return false;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('head Registration')) {
            return true;
        }

        return normalize_department_name($user->department ?? '') === 'แผนกทะเบียน';
    }
}

if (! function_exists('can_update_new_registration_progress')) {
    /**
     * Determine whether the user can update registration progress for their own department.
     */
    function can_update_new_registration_progress($user): bool
    {
        if (! $user) {
            return false;
        }

        if (method_exists($user, 'can') && $user->can('RegisterNew update')) {
            return true;
        }

        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole(['admin', 'manager', 'head Registration', 'head']);
        }

        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('admin')
                || $user->hasRole('manager')
                || $user->hasRole('head Registration')
                || $user->hasRole('head');
        }

        return false;
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
