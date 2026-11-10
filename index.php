<?php
session_start();
require_once 'db.php';

// ดึงชื่อมหาวิทยาลัยจาก Session ที่ผู้ใช้เลือกตอน Login
$user_university = $_SESSION['university'] ?? '';

if (!empty($user_university)) {
    // ถ้าล็อกอินแล้ว: ดึงเฉพาะโพสต์ที่ตรงกับมหาวิทยาลัยนั้นๆ แบบเด็ดขาด
    $stmt = $conn->prepare("SELECT Items.*, Users.name AS user_name 
                            FROM Items 
                            LEFT JOIN Users ON Items.user_id = Users.id 
                            WHERE Items.university = ?
                            ORDER BY Items.id DESC");
    $stmt->bind_param("s", $user_university);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // ถ้ายังไม่ล็อกอิน: แสดงรายการทั้งหมด
    $sql = "SELECT Items.*, Users.name AS user_name 
            FROM Items 
            LEFT JOIN Users ON Items.user_id = Users.id 
            ORDER BY Items.id DESC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ศูนย์แจ้งของหายและพบของ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar-glass">
        <!-- แสดงชื่อมหาวิทยาลัยที่ล็อกอินเข้ามาที่ Header ด้านบน -->
        <h2>✨ ศูนย์แจ้งของหายและพบของ 
            <span style="font-size: 1.05rem; color: #818cf8; font-weight: 400;">
                (<?php echo !empty($user_university) ? htmlspecialchars($user_university) : 'ทุกมหาวิทยาลัย'; ?>)
            </span>
        </h2>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="user-badge">สวัสดี, <strong><?php echo htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'] ?? 'ผู้ใช้งาน'); ?></strong></span>
                <a href="add_item.php" class="btn-primary-gradient">+ โพสต์แจ้งของหาย/พบของ</a>
                <a href="logout.php" class="btn-logout">ออกจากระบบ</a>
            <?php else: ?>
                <a href="login.php" class="btn-primary-gradient">เข้าสู่ระบบ</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <h3 style="text-align: center; margin-top: 40px; font-size: 1.6rem; font-weight: 600; color: #f8fafc; letter-spacing: 0.5px;">
            ประกาศล่าสุด <?php echo !empty($user_university) ? '- ' . htmlspecialchars($user_university) : ''; ?>
        </h3>
        
        <div class="card-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <div>
                            <a href="item_detail.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                                <div class="card-image-wrapper">
                                    <?php if (!empty($row['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                    <?php else: ?>
                                        <span class="no-img-placeholder">📷 ไม่มีรูปภาพประกอบ</span>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="item_detail.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </a>
                                    <?php if (isset($_SESSION['user_id']) && (($_SESSION['role'] ?? '') === 'admin' || $_SESSION['user_id'] == $row['user_id'])): ?>
                                        <a href="delete_item.php?id=<?php echo $row['id']; ?>" 
                                           onclick="return confirm('คุณต้องการลบโพสต์นี้หรือไม่?');" 
                                           class="btn-delete-inline">[ลบ]</a>
                                    <?php endif; ?>
                                </h3>
                                <p class="card-meta">
                                    <?php echo htmlspecialchars($row['user_name'] ?? 'ผู้ดูแลระบบ'); ?> • <?php echo date('d ก.พ. Y', strtotime($row['created_at'] ?? 'now')); ?>
                                </p>
                                <p class="card-contact">
                                    <strong>📍 สถานที่:</strong> <?php echo htmlspecialchars($row['location'] ?? 'ไม่ระบุ'); ?>
                                    <br><small style="color: #94a3b8; display: inline-block; margin-top: 4px;">🏫 <?php echo htmlspecialchars($row['university'] ?? 'ไม่ระบุสถาบัน'); ?></small>
                                </p>
                            </div>
                        </div>

                        <!-- ป้ายแสดงสถานะ (Pill Badges) -->
                        <div class="card-status-wrapper">
                            <?php 
                                $status = mb_strtolower(trim($row['status'] ?? $row['type'] ?? 'lost'));
                                
                                if (in_array($status, ['found', 'find_owner', 'พบของ', 'ตามหาเจ้าของ', '2'])) {
                                    echo '<span class="status-pill status-found">🔍 กำลังหาเจ้าของ</span>';
                                } elseif (in_array($status, ['returned', 'success', 'ส่งคืนแล้ว', 'ส่งคืนเจ้าของแล้ว', '3'])) {
                                    echo '<span class="status-pill status-returned">✅ ส่งคืนเจ้าของแล้ว</span>';
                                } else {
                                    echo '<span class="status-pill status-lost">🚨 กำลังตามหา</span>';
                                }
                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; color: #94a3b8; margin-top: 40px; width: 100%;">
                    <p style="font-size: 1.1rem;">📭 ยังไม่มีรายการประกาศของ <?php echo htmlspecialchars($user_university); ?></p>
                    <p style="font-size: 0.9rem; margin-top: 8px;">คลิกปุ่ม <strong>"+ โพสต์แจ้งของหาย/พบของ"</strong> ด้านบนเพื่อสร้างประกาศแรกได้เลย!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>