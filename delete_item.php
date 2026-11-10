<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'] ?? 'user';

    // ดึงข้อมูลรูปภาพและเจ้าของโพสต์
    $stmt = $conn->prepare("SELECT user_id, image_path FROM Items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // อนุญาตให้ลบเฉพาะ Admin หรือ เจ้าของโพสต์
        if ($role === 'admin' || $row['user_id'] == $user_id) {
            
            // ลบไฟล์รูปในโฟลเดอร์ uploads
            if (!empty($row['image_path']) && file_exists($row['image_path'])) {
                unlink($row['image_path']);
            }

            // ลบแถวข้อมูลใน Database
            $del_stmt = $conn->prepare("DELETE FROM Items WHERE id = ?");
            $del_stmt->bind_param("i", $item_id);
            $del_stmt->execute();
            $del_stmt->close();
        }
    }
    $stmt->close();
}

header("Location: index.php");
exit();
?>