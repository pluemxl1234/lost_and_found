# Lost and Found System (ระบบแจ้งของหายและตามหาเจ้าของ)

## 1. Project Name

**Lost and Found System** (ระบบศูนย์กลางแจ้งของหายและประกาศพบของ)

## 2. Project Description

เว็บไซต์ระบบศูนย์กลางสำหรับแจ้งของหายและประกาศตามหาเจ้าของ พัฒนาขึ้นเพื่อช่วยเป็นสื่อกลางให้ผู้ใช้งานสามารถโพสต์ประกาศสิ่งของที่หายหรือเก็บได้ โดยแยกระบบการแสดงผลและการค้นหาข้อมูลตามรายชื่อสถาบัน/มหาวิทยาลัย เพื่อความสะดวกในการใช้งานของผู้เรียนและบุคลากรในแต่ละแห่ง

## 3. Features

* **Authentication System:** สมัครสมาชิก (Register) และเข้าสู่ระบบ (Login) พร้อมตัวเลือกสถาบันการศึกษา

* **University-Based Filtering:** แสดงผลและจัดหมวดหมู่ประกาศแยกตามมหาวิทยาลัยที่ผู้ใช้เลือก

* **Item Management:**

  * โพสต์แจ้งของหาย / ประกาศพบของ พร้อมอัปโหลดรูปภาพประกอบ

  * แก้ไขข้อมูลประกาศ (Edit Item)

  * ลบประกาศสิ่งของ (Delete Item)

* **Status Tracking:** ระบบติดตามสถานะของสิ่งของ (กำลังตามหา / กำลังหาเจ้าของ / ส่งคืนเจ้าของแล้ว)

* **FileZilla Integration:** บริหารจัดการอัปโหลดไฟล์ PHP, CSS และไฟล์สื่อสื่ออัปโหลดขึ้นเซิร์ฟเวอร์ผ่าน SFTP/FTP

## 4. Technologies Used

* **Frontend:** HTML5, CSS3

* **Backend:** PHP

* **Database:** MySQL / MariaDB

* **File Transfer:** FileZilla Client (SFTP / FTP)

* **Server Environment:** Linux / Apache Web Server (Angsila Server)

## 5. Installation (การอัปโหลดไฟล์ด้วย FileZilla)

1. ดาวน์โหลดและติดตั้งโปรแกรม [FileZilla Client](https://filezilla-project.org/) ลงในเครื่องคอมพิวเตอร์

2. เปิดโปรแกรม FileZilla แล้วทำการเชื่อมต่อเข้ากับเซิร์ฟเวอร์:

   * **Host:** `angsila.informatics.buu.ac.th` (หรือ Host IP ของเซิร์ฟเวอร์)

   * **Username:** รหัสนักศึกษา (เช่น `68160406`)

   * **Password:** รหัสผ่านสำหรับเข้าใช้งานเซิร์ฟเวอร์

   * **Port:** `22` (สำหรับ SFTP) หรือ `21` (สำหรับ FTP)

3. เมื่อเชื่อมต่อสำเร็จ ให้เปิดไปยังโฟลเดอร์ปลายทางบนเซิร์ฟเวอร์ (ฝั่งขวา) เช่น `/home/BUU/68160406/public_html`

4. อัปโหลดไฟล์โค้ด PHP ทั้งหมด, `style.css` และโฟลเดอร์ `uploads/` จากคอมพิวเตอร์ (ฝั่งซ้าย) ขึ้นไปยังเซิร์ฟเวอร์ (ฝั่งขวา)

## 6. Database Setup

1. ส่งออกฐานข้อมูลเดิมจากเซิร์ฟเวอร์ หรือเตรียมไฟล์สคริปต์ `backup.sql`

2. นำเข้าฐานข้อมูลผ่านทาง **phpMyAdmin** ของเซิร์ฟเวอร์ โดยเลือกแถบ **Import** -> เลือกไฟล์ `backup.sql` แล้วกด **Go**

3. ตรวจสอบและแก้ไขไฟล์ `db.php` บนเซิร์ฟเวอร์ ให้เชื่อมต่อกับฐานข้อมูลอย่างถูกต้อง:

   ```
   <?php
   $host = "localhost";
   $user = "68160406";
   $pass = "your_password";
   $dbname = "s68160406";
   
   $conn = new mysqli($host, $user, $pass, $dbname);
   $conn->set_charset("utf8mb4");
   
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   ?>
   
   ```

## 7. How to Run

* **การเข้าใช้งานผ่านเว็บเซิร์ฟเวอร์ (Angsila / Remote Host):**
  เปิดเว็บเบราว์เซอร์บนคอมพิวเตอร์หรือโทรศัพท์มือถือ แล้วพิมพ์ URL:

  ```
  http://angsila.informatics.buu.ac.th/~68160406/
  
  ```

 

## 8. Developer / Team

* 68160057 สิทธิพร แสงสว่าง 
* 68160178 กฤษณล ภูทองบ่อ 

* 68160406 อภิณัฐ ตรีนิตย์ 

* 68160392 พลพล เทียนทองดี

* **สถาบัน:** มหาวิทยาลัยบูรพา (BUU)
