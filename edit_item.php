<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$item_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

// หากเป็น Admin ให้สามารถดึงโพสต์ใดๆ มาแก้ไขได้เลย
if ($is_admin) {
    $stmt = $conn->prepare("SELECT * FROM Items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
} else {
    $stmt = $conn->prepare("SELECT * FROM Items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $item_id, $user_id);
}

$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    header("Location: index.php");
    exit;
}

$categories = $conn->query("SELECT * FROM Categories");
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $category_id = $_POST['category_id'];
    $type = $_POST['type'];
    $location = trim($_POST['location']);
    $contact_info = trim($_POST['contact_info']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    $image_path = $item['image_path'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '_' . uniqid() . '.' . $ext;
        $target = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image_path = $target;
        }
    }

    // คำสั่ง SQL อัปเดตข้อมูลโพสต์
    $sql = "UPDATE Items SET category_id = ?, title = ?, description = ?, image_path = ?, location = ?, contact_info = ?, type = ?, status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($sql);
    $update_stmt->bind_param("isssssssi", $category_id, $title, $description, $image_path, $location, $contact_info, $type, $status, $item_id);

    if ($update_stmt->execute()) {
        header("Location: item_detail.php?id=" . $item_id);
        exit;
    } else {
        $error = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขประกาศ - <?= htmlspecialchars($item['title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container container-sm">
        <h2>✏️ แก้ไขข้อมูลประกาศ <?= $is_admin ? '<span style="color:#2563eb;">(โหมด Admin)</span>' : '' ?></h2>
        <div class="uni-info">
            <a href="item_detail.php?id=<?= $item_id ?>">← ยกเลิกและกลับ</a>
        </div>

        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <p>
                <label>สถานะประกาศ:</label>
                <select name="status" style="border: 2px solid #2b5c55; font-weight: bold;" required>
                    <option value="active" <?= $item['status'] == 'active' ? 'selected' : '' ?>>
                        🟢 ประกาศอยู่ (<?= $item['type'] == 'lost' ? 'กำลังตามหา' : 'กำลังหาเจ้าของ' ?>)
                    </option>
                    <option value="completed" <?= $item['status'] == 'completed' ? 'selected' : '' ?>>
                        🎉 ส่งคืน/พบเจ้าของแล้ว (ปิดประกาศ)
                    </option>
                </select>
            </p>
            <p>
                <label>ประเภทประกาศ:</label>
                <select name="type" required>
                    <option value="lost" <?= $item['type'] == 'lost' ? 'selected' : '' ?>>ของหาย (ผู้ตามหาเป็นเจ้าของ)</option>
                    <option value="found" <?= $item['type'] == 'found' ? 'selected' : '' ?>>พบของ (ผู้โพสต์เป็นผู้เก็บได้)</option>
                </select>
            </p>
            <p>
                <label>ชื่อสิ่งของ:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
            </p>
            <p>
                <label>หมวดหมู่:</label>
                <select name="category_id" required>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>" <?= $item['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </p>
            <p>
                <label>สถานที่ (ที่หาย/ที่พบ):</label>
                <input type="text" name="location" value="<?= htmlspecialchars($item['location']) ?>" required>
            </p>
            <p>
                <label>ข้อมูลติดต่อ (เบอร์โทร/Line ID):</label>
                <input type="text" name="contact_info" value="<?= htmlspecialchars($item['contact_info']) ?>" required>
            </p>
            <p>
                <label>รายละเอียดเพิ่มเติม:</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($item['description']) ?></textarea>
            </p>
            <p>
                <label>เปลี่ยนรูปภาพประกอบ (หากต้องการเปลี่ยน):</label>
                <input type="file" name="image" accept="image/*">
            </p>
            <p style="margin-bottom: 0;">
                <button type="submit">บันทึกการแก้ไข</button>
            </p>
        </form>
    </div>
</body>
</html>