<?php
session_start();
require_once 'db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $contact_info = trim($_POST['contact_info'] ?? '');
    $status_type = $_POST['status_type'] ?? 'found';
    $user_id = $_SESSION['user_id'];
    $category_id = intval($_POST['category_id'] ?? 1);
    
    // บันทึกมหาวิทยาลัยตาม Session ของผู้ใช้ที่กำลังล็อกอิน
    $university = $_SESSION['university'] ?? 'มหาวิทยาลัยบูรพา';

    // จัดการอัปโหลดรูปภาพ
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_filename = rand() . '_' . md5(time()) . '.' . $file_ext;
        $image_path = $upload_dir . $new_filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    if (!empty($title)) {
        try {
            $stmt = $conn->prepare("INSERT INTO Items (user_id, category_id, title, description, image_path, location, status, university, contact_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("iisssssss", $user_id, $category_id, $title, $description, $image_path, $location, $status_type, $university, $contact_info);
                if ($stmt->execute()) {
                    $stmt->close();
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "บันทึกข้อมูลไม่สำเร็จ: " . $stmt->error;
                }
            } else {
                $error = "SQL Error: " . $conn->error;
            }
        } catch (Exception $e) {
            $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
        }
    } else {
        $error = "กรุณากรอกชื่อสิ่งของ";
    }
}

// ดึงหมวดหมู่
$categories = $conn->query("SELECT * FROM Categories");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โพสต์แจ้งของหาย/พบของ - <?php echo htmlspecialchars($_SESSION['university'] ?? ''); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container { max-width: 500px; margin: 40px auto; padding: 30px; background: rgba(30, 41, 59, 0.75); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 500; color: #cbd5e1; }
        .form-control { width: 100%; padding: 10px 14px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #f8fafc; font-size: 0.95rem; box-sizing: border-box; }
        .form-control option { background: #0f172a; color: #f8fafc; }
        .btn-submit { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; border: none; padding: 12px; border-radius: 20px; font-weight: bold; width: 100%; cursor: pointer; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 style="text-align: center; margin-bottom: 20px; color: #fff;">+ โพสต์แจ้งของหาย/พบของ</h2>
        <p style="text-align: center; color: #818cf8; margin-bottom: 20px; font-size: 0.9rem;">
            🏫 สถาบัน: <strong><?php echo htmlspecialchars($_SESSION['university'] ?? 'ไม่ระบุ'); ?></strong>
        </p>
        
        <?php if (!empty($error)): ?>
            <div style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4); padding: 12px; border-radius: 8px; margin-bottom: 15px; text-align: center;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>ประเภทประกาศ:</label>
                <select name="status_type" class="form-control">
                    <option value="found">พบของ (กำลังหาเจ้าของ)</option>
                    <option value="lost">ของหาย (กำลังตามหา)</option>
                </select>
            </div>
            <div class="form-group">
                <label>ชื่อสิ่งของ:</label>
                <input type="text" name="title" class="form-control" required placeholder="เช่น กระเป๋าสตางค์, โทรศัพท์มือถือ">
            </div>
            <div class="form-group">
                <label>หมวดหมู่:</label>
                <select name="category_id" class="form-control">
                    <?php if ($categories && $categories->num_rows > 0): ?>
                        <?php while($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name'] ?? $cat['category_name'] ?? 'ทั่วไป'); ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="1">ทั่วไป</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>สถานที่ (ที่หาย/ที่พบ):</label>
                <input type="text" name="location" class="form-control" placeholder="เช่น หน้าอาคารเรียน, โรงอาหารกลาง">
            </div>
            <div class="form-group">
                <label>ข้อมูลติดต่อ (เบอร์โทร/Line ID):</label>
                <input type="text" name="contact_info" class="form-control" placeholder="เช่น 081-234-5678 / Line: user123">
            </div>
            <div class="form-group">
                <label>รายละเอียดเพิ่มเติม:</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>รูปภาพสิ่งของ:</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn-submit">บันทึกโพสต์</button>
        </form>
        <p style="text-align: center; margin-top: 15px;"><a href="index.php" style="color: #94a3b8; text-decoration: none;">← กลับหน้าแรก</a></p>
    </div>
</body>
</html>