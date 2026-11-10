<?php
session_start();
require_once 'db.php';

$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT Items.*, Users.name AS user_name 
                        FROM Items 
                        LEFT JOIN Users ON Items.user_id = Users.id 
                        WHERE Items.id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($item['title']); ?> - ศูนย์แจ้งของหาย</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="max-width: 800px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <a href="index.php" style="text-decoration: none; color: #666;">← กลับหน้าแรก</a>
        
        <h1 style="margin-top: 15px;"><?php echo htmlspecialchars($item['title']); ?></h1>

        <?php if (!empty($item['image_path'])): ?>
            <div style="text-align: center; margin: 20px 0;">
                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" style="max-width: 100%; max-height: 400px; border-radius: 8px;">
            </div>
        <?php endif; ?>

        <h3>รายละเอียด</h3>
        <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
        
        <p><strong>สถานที่:</strong> <?php echo htmlspecialchars($item['location'] ?? 'ไม่ระบุ'); ?></p>

        <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">

        <h3>ข้อมูลการติดต่อ</h3>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p><strong>ผู้โพสต์:</strong> <?php echo htmlspecialchars($item['user_name'] ?? 'ไม่ระบุ'); ?></p>
        <?php else: ?>
            <p style="color: #e67e22;">🔒 กรุณา <a href="login.php">เข้าสู่ระบบ</a> เพื่อดูข้อมูลติดต่อ</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id']) && (($_SESSION['role'] ?? '') === 'admin' || $_SESSION['user_id'] == $item['user_id'])): ?>
            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <a href="edit_item.php?id=<?php echo $item['id']; ?>" 
                   style="background-color: #ffc107; color: #000; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                    ✏️ แก้ไขโพสต์
                </a>
                <a href="delete_item.php?id=<?php echo $item['id']; ?>" 
                   onclick="return confirm('คุณต้องการลบโพสต์นี้ใช่หรือไม่? ข้อมูลและรูปภาพจะถูกลบทันที');" 
                   style="background-color: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                    🗑️ ลบโพสต์นี้
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>