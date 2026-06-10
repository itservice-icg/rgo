# System Analysis: RGO Web Application

วันที่จัดทำ: 2026-05-27

## 1. ภาพรวมระบบ

RGO เป็นระบบหลังบ้านสำหรับจัดการงานทะเบียนสินค้า/สารเคมีขององค์กร โดยพัฒนาด้วย Laravel 8 และใช้ Blade เป็นส่วนแสดงผลหลัก ระบบเน้นงาน CRUD, การค้นหา/กรองข้อมูล, การติดตามวันหมดอายุใบอนุญาต, การ import ข้อมูลจาก Excel, การแนบเอกสาร และการควบคุมสิทธิ์ผู้ใช้งาน

กลุ่มงานหลักของระบบประกอบด้วย:

- จัดการผู้ใช้งาน บทบาท และสิทธิ์
- จัดการบริษัท
- จัดการทะเบียนนำเข้า
- จัดการทะเบียนผลิต
- จัดการขึ้นทะเบียนสินค้าใหม่
- ติดตามสถานะ/วันหมดอายุผ่าน Dashboard
- นำเข้าข้อมูลจากไฟล์ Excel
- จัดเก็บเอกสารแนบของทะเบียน

## 2. Technology Stack

- Backend: PHP `^7.3|^8.0`, Laravel `8.x`
- Authentication: Laravel Breeze
- Authorization: Spatie Laravel Permission
- Excel Import: Maatwebsite Excel
- Frontend: Blade, Tailwind CSS 2, Alpine.js
- Asset Build: Laravel Mix
- Database: อิงตาม Laravel config ปัจจุบันใน `config/database.php`
- Session: Laravel session config โดย `.env` ตั้ง `SESSION_LIFETIME=120`

## 3. โครงสร้างสำคัญของระบบ

### 3.1 Routes

ไฟล์ route หลักคือ `routes/web.php` และ `routes/auth.php`

- `/` redirect ไป `/admin/login`
- `/admin/login` ใช้ `AuthenticatedSessionController`
- route หลัง login ถูกครอบด้วย middleware `auth`
- กลุ่ม `/admin/*` ใช้จัดการ users, roles, permissions, posts, profile, mail setting, dashboard
- route งานทะเบียนหลักแยกตาม path เช่น `/import`, `/new/product`, `/create/product`, `/company`

### 3.2 Controllers หลัก

- `Auth\AuthenticatedSessionController`
  - แสดงหน้า login
  - login/logout
  - อัปเดต `users.last_login_at` เมื่อ login สำเร็จ

- `Admin\UserController`
  - จัดการผู้ใช้งาน
  - สร้าง/แก้ไข user และ sync role
  - ใช้ SoftDeletes ผ่าน model `User`

- `Admin\RoleController` และ `Admin\PermissionController`
  - จัดการ role/permission ด้วย Spatie
  - มี middleware `role_or_permission` ใน Role/Permission controller

- `CompanyController`
  - จัดการข้อมูลบริษัท

- `ChemicalImportController`
  - จัดการทะเบียนนำเข้า
  - ค้นหา, กรองวันหมดอายุ, สถานะหมดอายุ/ใกล้หมดอายุ
  - จัดการไฟล์แนบและไฟล์เอกสารเพิ่มเติม
  - import ข้อมูลจาก Excel

- `ProductionRegistrationController`
  - จัดการทะเบียนผลิต
  - โครงสร้างใกล้เคียงกับทะเบียนนำเข้า
  - จัดการเอกสารแนบและ import Excel

- `ChemicalRegistrationController`
  - จัดการขึ้นทะเบียนสินค้าใหม่และทะเบียนสินค้าทั้งหมด
  - มี progress/sub-progress และ step การดำเนินงาน
  - เชื่อมกับ `DrugProgressStep`

- `DashboardController`
  - สรุปจำนวนข้อมูลทั้งหมด
  - สรุปหมดอายุ และใกล้หมดอายุภายใน 6 เดือน

## 4. Data Model สำคัญ

### 4.1 users

ใช้สำหรับบัญชีผู้ใช้งานระบบ

ข้อมูลสำคัญ:

- `name`, `email`, `password`
- `prefix`, `department`, `affiliation`, `position`
- `employee_id`, `phone_number`, `employment_status`
- `profile`
- `last_login_at`
- `deleted_at`

หมายเหตุ:

- ใช้ `SoftDeletes`
- ใช้ Spatie `HasRoles`
- `last_login_at` เก็บเวลาล่าสุดที่ login สำเร็จ ไม่ใช่เวลาที่ใช้งานล่าสุด

### 4.2 companies

เก็บข้อมูลบริษัทที่เกี่ยวข้องกับทะเบียน

ข้อมูลสำคัญ:

- `name`, `full_name`
- `address`, `email`, `phone`
- `tax_id`
- `type`

### 4.3 chemical_imports

เก็บทะเบียนนำเข้า

ข้อมูลสำคัญ:

- `company_id`
- `registration_number`
- `expired_license_date`
- `chemical_name_th`, `chemical_name_en`
- `composition`, `manufacturer`
- `importer`, `distributor`
- `trade_name`
- `group_of_substances`, `plant`, `pests`
- `document`, `additional_document`
- `progress`, `sub_progress`
- `created_by`, `updated_by`

ความสัมพันธ์:

- belongsTo `Company`
- hasMany `ChemicalImportFile`

### 4.4 chemical_registrations

เก็บข้อมูลขึ้นทะเบียนสินค้าใหม่/ทะเบียนสินค้าทั้งหมด

ข้อมูลสำคัญ:

- ข้อมูลทะเบียนและใบอนุญาต
- ข้อมูลสารเคมีและการผลิต
- `new_or_old` ใช้แยกข้อมูลใหม่/เก่า
- `progress`, `sub_progress`
- `created_by`, `updated_by`
- `deleted_at`

ความสัมพันธ์:

- belongsTo `ChemicalImport`
- hasMany `DrugProgressStep`

### 4.5 production_registration

เก็บทะเบียนผลิต

ข้อมูลสำคัญใกล้เคียงกับ `chemical_imports` แต่ใช้ตารางชื่อ `production_registration`

ความสัมพันธ์:

- belongsTo `Company`
- hasMany `ProductionRegistrationFile`

### 4.6 ไฟล์เอกสาร

มี model สำหรับเอกสารแนบ:

- `ChemicalImportFile`
- `ProductionRegistrationFile`
- `DocumentType`

ใช้รองรับเอกสารหลายประเภท เช่น เอกสารทะเบียน และเอกสารอนุมัติ

## 5. Authentication และ Session

ระบบ login อยู่ที่:

- `routes/auth.php`
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Requests/Auth/LoginRequest.php`

Flow login:

1. User เปิด `/admin/login`
2. ส่ง POST ไป `/admin/login`
3. `LoginRequest::authenticate()` ใช้ `Auth::attempt()`
4. ถ้าสำเร็จ อัปเดต `last_login_at = now()`
5. regenerate session
6. redirect ไป `RouteServiceProvider::ADMIN_HOME`

Flow logout:

1. `Auth::guard('web')->logout()`
2. invalidate session
3. regenerate CSRF token
4. redirect ไป `/`

Session lifetime ปัจจุบัน:

- `.env`: `SESSION_LIFETIME=120`
- เท่ากับ idle session ประมาณ 120 นาที

## 6. Authorization

ระบบใช้ Spatie Laravel Permission

ส่วนที่ใช้งานชัดเจน:

- RoleController มี middleware ตรวจ permission
- PermissionController มี middleware ตรวจ permission
- Blade ใช้ `@can(...)` ในหลายจุด
- User model ใช้ trait `HasRoles`

ข้อสังเกต:

- `Admin\UserController` มี middleware permission แต่ถูก comment ไว้
- route หลายส่วนอยู่ใต้ middleware `auth` แต่ไม่ได้บังคับ permission ระดับ controller ทุก module
- ควรตรวจสอบ policy/permission ให้สม่ำเสมอ โดยเฉพาะ create/update/delete ของข้อมูลทะเบียน

## 7. Business Flow หลัก

### 7.1 จัดการผู้ใช้งาน

- Admin เปิดหน้ารายการ users
- ระบบ paginate 10 รายการ
- แสดงชื่อ, email, role, login ล่าสุด, สถานะใช้งาน
- create/update user
- sync role จาก dropdown
- delete เป็น Soft Delete

### 7.2 ทะเบียนนำเข้า

- แสดงรายการทะเบียนนำเข้า
- ค้นหาด้วยชื่อสาร, เลขทะเบียน, บริษัท
- กรองตามช่วงวันหมดอายุ
- กรองตามสถานะหมดอายุ/ใกล้หมดอายุ
- เพิ่ม/แก้ไขข้อมูล
- แนบเอกสาร
- import จาก Excel

### 7.3 ทะเบียนผลิต

- Flow คล้ายทะเบียนนำเข้า
- ใช้ตาราง `production_registration`
- มีไฟล์แนบแยกเป็น `production_registration_files`

### 7.4 ขึ้นทะเบียนสินค้าใหม่

- เก็บข้อมูลใน `chemical_registrations`
- ใช้ `new_or_old = true` สำหรับรายการใหม่
- มี progress/sub-progress
- มี step/sub-step ผ่าน `DrugProgressStep`
- มีการคำนวณสถานะจากวันหมดอายุและ progress

### 7.5 Dashboard

Dashboard รวมตัวเลขหลัก:

- จำนวนทะเบียนนำเข้าทั้งหมด
- ทะเบียนนำเข้าใกล้หมดอายุ/หมดอายุ
- จำนวนทะเบียนสินค้าทั้งหมด
- ทะเบียนสินค้าใกล้หมดอายุ/หมดอายุ
- จำนวนทะเบียนผลิตทั้งหมด
- ทะเบียนผลิตใกล้หมดอายุ/หมดอายุ
- จำนวนงานขึ้นทะเบียนใหม่

## 8. File Upload และ Storage

ระบบใช้ Laravel Storage disk `public` สำหรับ upload หลัก เช่น:

- `production_images`
- `production_documents`

มี validation พื้นฐาน:

- image สูงสุดประมาณ 2 MB
- document รองรับ `pdf, doc, docx` สูงสุดประมาณ 5 MB

ข้อควรระวัง:

- ควรตรวจสอบว่า production มี `php artisan storage:link`
- ควรแยกเอกสารที่เป็นข้อมูลอ่อนไหวออกจาก public disk หากต้องการควบคุมสิทธิ์การเปิดไฟล์
- ควรมี route/controller สำหรับตรวจสิทธิ์ก่อน download เอกสารสำคัญ

## 9. จุดแข็งของระบบ

- โครงสร้างเป็น Laravel มาตรฐาน เข้าใจง่าย
- แยก controller/model/view ตาม pattern Laravel
- ใช้ Spatie Permission ซึ่งเหมาะกับระบบหลังบ้าน
- มี Excel import รองรับงานข้อมูลจำนวนมาก
- มี dashboard สำหรับ tracking วันหมดอายุ
- เริ่มมี model เอกสารแนบแบบแยกตาราง ทำให้รองรับไฟล์หลายประเภทได้ดีขึ้น
- เพิ่ม `last_login_at` แล้ว ช่วยตรวจสอบการใช้งาน user ได้ง่าย

## 10. จุดที่ควรปรับปรุง

### 10.1 Validation ยังไม่สม่ำเสมอ

บาง controller มี validation ครบ แต่บางส่วนถูก comment ไว้ เช่น `UserController`

ข้อเสนอ:

- เปิด validation สำหรับ user create/update
- แยกเป็น FormRequest เมื่อ form ใหญ่
- ตรวจ unique email/employee_id ให้ชัดเจน

### 10.2 Permission ยังไม่ครอบคลุมทุก module

บาง module มีเพียง middleware `auth`

ข้อเสนอ:

- ใส่ middleware permission ให้ create/update/delete ของข้อมูลทะเบียน
- ตรวจ `@can` ใน Blade ให้ตรงกับ route/controller
- ลดโอกาสที่ user ยิง URL ตรงได้

### 10.3 Controller บางไฟล์ใหญ่และมี business logic มาก

เช่น `ChemicalRegistrationController`, `ChemicalImportController`, `ProductionRegistrationController`

ข้อเสนอ:

- แยก service สำหรับ upload file
- แยก service สำหรับคำนวณสถานะหมดอายุ
- แยก query filter เป็น scope หรือ query object

### 10.4 ชื่อ field และชนิดข้อมูลบางจุดยังไม่ชัด

ตัวอย่าง:

- `expired_license_number` ดูเหมือนชื่อเลขที่ใบอนุญาต แต่ถูกใช้เทียบวันที่ในบาง query
- `created_by`, `updated_by` เป็น string ทั้งที่เก็บ `Auth::id()`
- `is_deleted` ซ้ำแนวคิดกับ SoftDeletes

ข้อเสนอ:

- ทบทวน naming และ data type ใน migration ใหม่
- ใช้ `foreignId` สำหรับ user reference ในอนาคต
- ลดการใช้ flag `is_deleted` ถ้าใช้ SoftDeletes อยู่แล้ว

### 10.5 Encoding/ข้อความภาษาไทยในบางไฟล์

จากการอ่านผ่าน terminal พบข้อความไทยบางส่วนแสดงเป็น mojibake เช่น `เธ...`

ข้อเสนอ:

- ตรวจ encoding ของไฟล์ให้เป็น UTF-8
- ระวัง editor/terminal ที่บันทึกไฟล์ผิด encoding
- หาก refactor ข้อความไทย ควรทำทีละส่วนและตรวจหน้าเว็บจริง

### 10.6 Test Coverage ยังน้อย

มี test จาก Laravel Breeze/example เป็นหลัก

ข้อเสนอ:

- เพิ่ม Feature test สำหรับ login
- เพิ่ม test สำหรับ CRUD สำคัญ เช่น company, import, production registration
- เพิ่ม test permission สำหรับ role ที่มี/ไม่มีสิทธิ์

## 11. ความเสี่ยงที่ควรติดตาม

- ข้อมูลทะเบียนเป็นข้อมูลสำคัญ ควรควบคุมสิทธิ์ download/view เอกสาร
- การ import Excel อาจสร้างข้อมูลซ้ำ ถ้าไม่มี unique key หรือ duplicate detection
- Query ค้นหาใช้ `whereRaw REPLACE(LOWER(...))` อาจกระทบ performance เมื่อข้อมูลเยอะ
- การคำนวณสถานะบางส่วนทำใน loop หลัง paginate อาจซ้ำ logic หลายจุด
- ตารางข้อมูลหลักมี field จำนวนมาก ควรระวังการแก้ไข form/migration ไม่ให้กระทบข้อมูลเก่า
- มี worktree ที่มีไฟล์แก้ไข/ลบจำนวนมาก ควรระวังก่อน merge หรือ deploy

## 12. ข้อเสนอแนะลำดับถัดไป

1. ทำ permission matrix ให้ชัดเจนสำหรับแต่ละเมนู
2. เปิด validation สำหรับ UserController และ module สำคัญ
3. เพิ่ม audit log แบบ event-based สำหรับ create/update/delete
4. ปรับ file access ให้ผ่าน controller หากเอกสารไม่ควร public
5. แยก logic คำนวณสถานะวันหมดอายุเป็น helper/service เดียว
6. เพิ่ม index ให้ field ที่ค้นหาบ่อย เช่น `registration_number`, `expired_license_date`, `company_id`
7. เพิ่ม test สำหรับ flow สำคัญก่อน refactor ใหญ่

## 13. สรุป

ระบบ RGO เป็น Laravel admin application ที่มีโครงสร้างหลักพร้อมใช้งานสำหรับงานทะเบียนสินค้า/สารเคมี มี module สำคัญครบทั้งผู้ใช้งาน บริษัท ทะเบียนนำเข้า ทะเบียนผลิต ขึ้นทะเบียนสินค้าใหม่ dashboard และ import Excel

ภาพรวมระบบเหมาะกับการพัฒนาต่อ แต่ควรให้ความสำคัญกับ 4 เรื่องหลักคือ permission, validation, file security และการลด business logic ที่กระจุกใน controller ขนาดใหญ่ เมื่อจัดระเบียบส่วนนี้แล้ว ระบบจะดูแลง่ายขึ้น ปลอดภัยขึ้น และรองรับข้อมูลจำนวนมากได้ดีขึ้น
