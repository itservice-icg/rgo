# RGO

RGO เป็นระบบเว็บแอปสำหรับจัดการข้อมูลทะเบียนวัตถุ/สินค้าเคมี การนำเข้า การขึ้นทะเบียนใหม่ ทะเบียนผลิต บริษัท และผู้ใช้งานภายในองค์กร พัฒนาด้วย Laravel 8 และใช้ Laravel Breeze สำหรับระบบยืนยันตัวตน

## เทคโนโลยีหลัก

- PHP `^7.3|^8.0`
- Laravel `8.x`
- MySQL หรือฐานข้อมูลที่ Laravel รองรับ
- Laravel Breeze สำหรับ authentication
- Spatie Laravel Permission สำหรับ role/permission
- Maatwebsite Excel สำหรับ import ข้อมูลจากไฟล์ Excel
- Laravel Mix, Tailwind CSS, Alpine.js สำหรับ frontend assets
- SweetAlert2 สำหรับ alert/confirmation บางส่วนของ UI

## ภาพรวมการทำงาน

เมื่อเข้า `/` ระบบจะ redirect ไปหน้า login ของผู้ดูแลที่ `/admin/login` หลังจาก login แล้ว route หลักทั้งหมดจะถูกครอบด้วย middleware `auth` เพื่อให้เฉพาะผู้ใช้ที่เข้าสู่ระบบแล้วเท่านั้นเข้าถึงข้อมูลได้

ระบบแบ่งการทำงานหลักเป็นส่วนต่อไปนี้:

- Dashboard: แสดงจำนวนข้อมูลสำคัญ เช่น ทะเบียนนำเข้า ทะเบียนสินค้า ทะเบียนผลิต รายการใกล้หมดอายุ และรายการหมดอายุ
- ทะเบียนนำเข้า: จัดการข้อมูลการนำเข้าวัตถุ/สินค้าเคมี ค้นหา กรองวันหมดอายุ เพิ่ม แก้ไข ดูรายละเอียด ลบ และ import จาก Excel
- ขึ้นทะเบียนสินค้าใหม่: จัดการกระบวนการขึ้นทะเบียนใหม่ พร้อมติดตาม progress/sub-step รายแผนก
- ทะเบียนสินค้าทั้งหมด: แสดงข้อมูลสินค้าที่มีเลขทะเบียนแล้ว รวมถึงสถานะใกล้หมดอายุ/หมดอายุ
- ทะเบียนผลิต: จัดการข้อมูลทะเบียนผลิต ค้นหา กรอง เพิ่ม แก้ไข ดูรายละเอียด ลบ และ import จาก Excel
- บริษัท: จัดการข้อมูลบริษัท เช่น ชื่อเต็ม ที่อยู่ email เบอร์โทร และเลขประจำตัวผู้เสียภาษี
- ตั้งค่าระบบ: จัดการผู้ใช้ บทบาท สิทธิ์ โปรไฟล์ และ mail setting

## Module สำคัญ

### Authentication และสิทธิ์ผู้ใช้

ไฟล์ route authentication อยู่ที่ `routes/auth.php` และ controller อยู่ใน `app/Http/Controllers/Auth`

ส่วน admin อยู่ภายใต้ prefix `/admin` เช่น:

- `/admin/dashboard`
- `/admin/users`
- `/admin/roles`
- `/admin/permissions`
- `/admin/profile`
- `/admin/mail`

ระบบใช้ `spatie/laravel-permission` เพื่อกำหนดสิทธิ์ เช่น role, permission และ middleware สำหรับควบคุมการเข้าถึงหน้า admin บางส่วน

### Dashboard

Controller: `app/Http/Controllers/DashboardController.php`

Dashboard ดึงข้อมูลจาก model หลัก:

- `ChemicalImport`
- `ChemicalRegistration`
- `ProductionRegistration`

แล้วคำนวณจำนวนทั้งหมด, รายการใกล้หมดอายุ และรายการหมดอายุ โดยใช้วันที่ปัจจุบันและช่วงประมาณ 6 เดือน/180 วัน

### ทะเบียนนำเข้า

Controller: `app/Http/Controllers/ChemicalImportController.php`

Model: `app/Models/ChemicalImport.php`

Route หลัก:

- `GET /import`
- `GET /import/create`
- `POST /import/store`
- `GET /import/{import}`
- `GET /import/{import}/edit`
- `PUT /import/{import}`
- `DELETE /import/{import}`

ความสามารถหลัก:

- ค้นหาด้วยชื่อสารภาษาไทย/อังกฤษ เลขทะเบียน และชื่อบริษัท
- กรองตามช่วงวันหมดอายุ
- กรองสถานะ `expired` และ `soon_expired`
- อัปโหลดรูปภาพและเอกสาร
- บันทึกผู้สร้าง/ผู้แก้ไขจากผู้ใช้ที่ login อยู่
- import ข้อมูลจากไฟล์ Excel ผ่าน `Maatwebsite Excel`

### ขึ้นทะเบียนสินค้าใหม่และทะเบียนสินค้าทั้งหมด

Controller: `app/Http/Controllers/ChemicalRegistrationController.php`

Model หลัก:

- `app/Models/ChemicalRegistration.php`
- `app/Models/DrugProgressStep.php`

Route หลักของทะเบียนใหม่:

- `GET /new/product`
- `GET /new/product/create`
- `POST /new/product/store`
- `GET /new/product/show/{registrationNumber}`
- `GET /new/product/edit/{registrationNumber}`
- `PUT /new/product/update/{registrationNumber}`
- `PUT /newregis/{drug}/update-subprogress`

Route หลักของทะเบียนสินค้าทั้งหมด:

- `GET /new/productall`
- `GET /new/productall/{newregi}/show`
- `GET /new/productall/{newregi}/edit`
- `PUT /new/productall/{newregi}`

แนวคิดสำคัญ:

- field `new_or_old` ใช้แยกข้อมูลขึ้นทะเบียนใหม่กับข้อมูลทะเบียนเดิม
- ข้อมูลขึ้นทะเบียนใหม่มี progress และ sub-progress
- เมื่อสร้างรายการใหม่ ระบบสร้าง sub-step เริ่มต้นให้ในตาราง `drug_progress_steps`
- การอัปเดต sub-progress ใช้ department/role ของผู้ใช้เพื่อกำหนดสิทธิ์การ tick งาน
- หากขั้นตอนปัจจุบันครบ ระบบสามารถสร้างขั้นตอนถัดไปและเพิ่ม progress ได้

### ทะเบียนผลิต

Controller: `app/Http/Controllers/ProductionRegistrationController.php`

Model: `app/Models/ProductionRegistration.php`

Route หลัก:

- `GET /create/product`
- `GET /insert/product`
- `POST /store/product`
- `GET /show/product/{productionRegistration}`
- `GET /edit/product/{productionRegistration}`
- `PUT /import2/{import}`
- `DELETE /createproducts/{id}`

ความสามารถคล้ายทะเบียนนำเข้า:

- ค้นหาเลขทะเบียน ชื่อสาร และชื่อบริษัท
- กรองช่วงวันหมดอายุ
- แสดงสถานะใช้งานอยู่/ใกล้หมดอายุ/หมดอายุ
- อัปโหลดรูปภาพและเอกสาร
- import ข้อมูลทะเบียนผลิตจาก Excel

### บริษัท

Controller: `app/Http/Controllers/CompanyController.php`

Model: `app/Models/Company.php`

Route ใช้ `Route::resource('company', CompanyController::class)` รองรับ CRUD มาตรฐาน เช่น index, create, store, edit, update, destroy

ข้อมูลบริษัทถูกอ้างอิงจากทะเบียนนำเข้าและทะเบียนผลิตผ่าน `company_id`

## โครงสร้างไฟล์สำคัญ

```text
app/
  Http/Controllers/        Controller ของแต่ละ module
  Imports/                 Class สำหรับ import Excel
  Models/                  Eloquent models
  Providers/               Service providers
  Traits/                  Trait สำหรับ helper เฉพาะระบบ

config/
  queue.php                ตั้งค่า queue connection
  permission.php           ตั้งค่า Spatie Permission

database/
  migrations/              โครงสร้างตารางฐานข้อมูล
  seeders/                 Seeder เริ่มต้น เช่น AdminSeeder, CompanySeeder

resources/
  views/                   Blade templates
  css/, js/                Frontend source

routes/
  web.php                  Route หลักของระบบ
  auth.php                 Route authentication จาก Breeze

public/
  js/, css/                Asset ที่ build แล้ว
```

## การติดตั้ง

ติดตั้ง PHP dependencies:

```bash
composer install
```

ติดตั้ง JavaScript dependencies:

```bash
npm install
```

สร้างไฟล์ `.env`:

```bash
cp .env.example .env
```

สร้าง application key:

```bash
php artisan key:generate
```

ตั้งค่าฐานข้อมูลใน `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rgo_db
DB_USERNAME=root
DB_PASSWORD=
```

รัน migration และ seed:

```bash
php artisan migrate --seed
```

สร้าง storage link สำหรับไฟล์ upload:

```bash
php artisan storage:link
```

Build frontend assets:

```bash
npm run dev
```

หรือ build สำหรับ production:

```bash
npm run prod
```

รัน local server:

```bash
php artisan serve
```

จากนั้นเข้าใช้งานที่:

```text
http://127.0.0.1:8000
```

## Seeder เริ่มต้น

`DatabaseSeeder` เรียกใช้:

- `AdminSeeder`
- `CompanySeeder`

หลังจาก `php artisan migrate --seed` ระบบจะมีข้อมูลเริ่มต้นสำหรับผู้ดูแลและบริษัทตามที่กำหนดไว้ใน seeder

## การ import Excel

ระบบมี import class อยู่ที่:

- `app/Imports/ChemicalImportsImport.php`
- `app/Imports/ProductionRegistrationImport.php`

Route ที่เกี่ยวข้อง:

- `GET /chemical-imports/import`
- `POST /chemical-imports/import`
- `GET /test-import`
- `POST /import/production-registration`

ไฟล์ที่รองรับโดยทั่วไปคือ `.xlsx` และ `.xls` โดย controller กำหนดขนาดไฟล์สูงสุดประมาณ 2 MB สำหรับการ import

## การอัปโหลดไฟล์

ทะเบียนนำเข้าและทะเบียนผลิตรองรับการอัปโหลด:

- รูปภาพ: เก็บใน disk `public` ภายใต้ `production_images`
- เอกสาร: เก็บใน disk `public` ภายใต้ `production_documents`

ควรรัน `php artisan storage:link` ก่อนใช้งาน เพื่อให้ไฟล์ใน `storage/app/public` ถูกเข้าถึงผ่าน `public/storage`

## คำสั่งตรวจสอบที่ใช้บ่อย

ดู route ทั้งหมด:

```bash
php artisan route:list
```

ล้าง cache:

```bash
php artisan optimize:clear
```

ตรวจสอบ Blade view:

```bash
php artisan view:cache
php artisan view:clear
```
php artisan cache:clear
php artisan optimize:clear

รัน test:

```bash
php artisan test
```

หมายเหตุ: test บางส่วนต้องเชื่อมต่อฐานข้อมูล หาก `.env` ชี้ไปที่ database ที่เข้าไม่ได้ test จะ fail จาก database connection ก่อน

## หมายเหตุสำหรับการ deploy

ก่อน deploy production ควรตรวจสอบรายการต่อไปนี้:

- ตั้งค่า `.env` ให้เป็น production
- ตั้งค่า `APP_KEY`, `APP_ENV`, `APP_DEBUG=false`, `APP_URL`
- ตั้งค่า database, mail และ storage ให้ถูกต้อง
- รัน `composer install --no-dev --optimize-autoloader`
- รัน `npm run prod`
- รัน `php artisan migrate --force`
- รัน `php artisan config:cache`
- รัน `php artisan route:cache`
- รัน `php artisan view:cache`

## สถานะของ repository

โปรเจกต์นี้มีไฟล์ Laravel, migration, seeder, view และ asset ที่จำเป็นสำหรับระบบจัดการทะเบียนครบถ้วน การรันระบบจริงขึ้นอยู่กับการตั้งค่า `.env`, การเชื่อมต่อฐานข้อมูล และการ build asset ให้ตรงกับ environment ที่ใช้งาน


## Nginx redirect เมื่อ deploy ใต้ `/RGO`

กรณี deploy ระบบไว้ใต้ path `/RGO` แต่ผู้ใช้หรือ link เดิมเรียก path จาก root เช่น `/admin/login`, `/import`, `/new/product` ให้เพิ่ม rule นี้ใน server block ของ Nginx เพื่อ redirect path หลักของระบบไปยัง `/RGO` อัตโนมัติ

```nginx
location ~ ^/(admin|login|logout|dashboard|profile|booking|bookings|users|product|products|reports|settings|api|sanctum|new|newregis|chemical-imports|company|import|create|insert|store|show|edit|import2|createproducts|renew|manufactture|test-import)(/.*)?$ {
    return 308 /RGO$request_uri;
}
```

คำอธิบาย:

- `location ~` ใช้ regular expression เพื่อจับ path ที่ขึ้นต้นด้วย module หลักของระบบ
- `return 308` เป็น permanent redirect ที่ยังคง HTTP method เดิมไว้ เหมาะกับ route ที่อาจเป็น `POST`, `PUT`, `DELETE`
- `/RGO$request_uri` เติม prefix `/RGO` โดยเก็บ path และ query string เดิมไว้ เช่น `/import?search=test` จะถูกส่งไป `/RGO/import?search=test`
- regex นี้ครอบคลุม route หลักจาก `php artisan route:list` เช่น admin, company, import, new registration, production registration, API และ import Excel
- ถ้าต้องการ redirect root path `/` ไป `/RGO/` ด้วย ให้เพิ่ม rule แยกต่างหาก:

```nginx
location = / {
    return 308 /RGO/;
}
```



composer install
npm install
php artisan optimize:clear
php artisan storage:link
php artisan migrate
npm run dev
php artisan serve --host=127.0.0.1 --port=8000
