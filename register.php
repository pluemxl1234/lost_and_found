<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $university = $_POST['university'];

    $check_stmt = $conn->prepare("SELECT id FROM Users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error = "ชื่อผู้ใช้งานนี้ถูกใช้ไปแล้ว กรุณาลองใช้ชื่ออื่น";
    } else {
        $stmt = $conn->prepare("INSERT INTO Users (name, email, university, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $university, $username, $password);

        if ($stmt->execute()) {
            $success = "สมัครสมาชิกสำเร็จ! กำลังพาท่านไปหน้าเข้าสู่ระบบ...";
            header("refresh:2;url=login.php");
        } else {
            $error = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - ระบบแจ้งของหาย</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container container-sm">
        <h2>สมัครสมาชิก</h2>
        <div class="uni-info" style="text-align: center;">
            มีบัญชีผู้ใช้แล้ว? <a href="login.php">เข้าสู่ระบบที่นี่</a>
        </div>

        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="error-msg" style="background-color: #c6f6d5; color: #22543d;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <p>
                <label>ชื่อ-นามสกุล:</label>
                <input type="text" name="name" placeholder="เช่น สมชาย ใจดี" required>
            </p>
            <p>
                <label>อีเมล:</label>
                <input type="text" name="email" placeholder="เช่น example@email.com" required>
            </p>
            <p>
                <label>มหาวิทยาลัยสังกัด:</label>
                <select name="university" required>
                    <option value="" disabled selected>-- เลือกมหาวิทยาลัย --</option>
                    <option value="จุฬาลงกรณ์มหาวิทยาลัย">จุฬาลงกรณ์มหาวิทยาลัย</option>
                    <option value="มหาวิทยาลัยธรรมศาสตร์">มหาวิทยาลัยธรรมศาสตร์</option>
                    <option value="มหาวิทยาลัยมหิดล">มหาวิทยาลัยมหิดล</option>
                    <option value="มหาวิทยาลัยเกษตรศาสตร์">มหาวิทยาลัยเกษตรศาสตร์</option>
                    <option value="มหาวิทยาลัยเชียงใหม่">มหาวิทยาลัยเชียงใหม่</option>
                    <option value="มหาวิทยาลัยขอนแก่น">มหาวิทยาลัยขอนแก่น</option>
                    <option value="มหาวิทยาลัยสงขลานครินทร์">มหาวิทยาลัยสงขลานครินทร์</option>
                    <option value="มหาวิทยาลัยศรีนครินทรวิโรฒ">มหาวิทยาลัยศรีนครินทรวิโรฒ</option>
                    <option value="มหาวิทยาลัยศิลปากร">มหาวิทยาลัยศิลปากร</option>
                    <option value="มหาวิทยาลัยบูรพา">มหาวิทยาลัยบูรพา</option>
                    <option value="มหาวิทยาลัยนเรศวร">มหาวิทยาลัยนเรศวร</option>
                    <option value="มหาวิทยาลัยแม่ฟ้าหลวง">มหาวิทยาลัยแม่ฟ้าหลวง</option>
                    <option value="มหาวิทยาลัยเทคโนโลยีสุรนารี">มหาวิทยาลัยเทคโนโลยีสุรนารี</option>
                    <option value="มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี">มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี</option>
                    <option value="มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าพระนครเหนือ">มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าพระนครเหนือ</option>
                    <option value="สถาบันเทคโนโลยีพระจอมเกล้าเจ้าคุณทหารลาดกระบัง">สถาบันเทคโนโลยีพระจอมเกล้าเจ้าคุณทหารลาดกระบัง</option>
                    <option value="มหาวิทยาลัยรามคำแหง">มหาวิทยาลัยรามคำแหง</option>
                    <option value="มหาวิทยาลัยกรุงเทพ">มหาวิทยาลัยกรุงเทพ</option>
                    <option value="มหาวิทยาลัยรังสิต">มหาวิทยาลัยรังสิต</option>
                    <option value="มหาวิทยาลัยอัสสัมชัญ">มหาวิทยาลัยอัสสัมชัญ</option>
                    <option value="มหาวิทยาลัยสวนดุสิต">มหาวิทยาลัยสวนดุสิต</option>
                    <option value="มหาวิทยาลัยราชภัฏสวนสุนันทา">มหาวิทยาลัยราชภัฏสวนสุนันทา</option>
                    <option value="มหาวิทยาลัยราชภัฏพระนคร">มหาวิทยาลัยราชภัฏพระนคร</option>
                    <option value="มหาวิทยาลัยราชภัฏจันทรเกษม">มหาวิทยาลัยราชภัฏจันทรเกษม</option>
                    <option value="มหาวิทยาลัยราชภัฏบ้านสมเด็จเจ้าพระยา">มหาวิทยาลัยราชภัฏบ้านสมเด็จเจ้าพระยา</option>
                    <option value="มหาวิทยาลัยราชภัฏธนบุรี">มหาวิทยาลัยราชภัฏธนบุรี</option>
                    <option value="มหาวิทยาลัยราชภัฏเชียงใหม่">มหาวิทยาลัยราชภัฏเชียงใหม่</option>
                    <option value="มหาวิทยาลัยราชภัฏเชียงราย">มหาวิทยาลัยราชภัฏเชียงราย</option>
                    <option value="มหาวิทยาลัยราชภัฏนครปฐม">มหาวิทยาลัยราชภัฏนครปฐม</option>
                    <option value="มหาวิทยาลัยราชภัฏนครราชสีมา">มหาวิทยาลัยราชภัฏนครราชสีมา</option>
                    <option value="มหาวิทยาลัยราชภัฏอุบลราชธานี">มหาวิทยาลัยราชภัฏอุบลราชธานี</option>
                    <option value="มหาวิทยาลัยราชภัฏมหาสารคาม">มหาวิทยาลัยราชภัฏมหาสารคาม</option>
                    <option value="มหาวิทยาลัยราชภัฏบุรีรัมย์">มหาวิทยาลัยราชภัฏบุรีรัมย์</option>
                    <option value="มหาวิทยาลัยราชภัฏสุราษฎร์ธานี">มหาวิทยาลัยราชภัฏสุราษฎร์ธานี</option>
                    <option value="มหาวิทยาลัยราชภัฏภูเก็ต">มหาวิทยาลัยราชภัฏภูเก็ต</option>
                    <option value="มหาวิทยาลัยวลัยลักษณ์">มหาวิทยาลัยวลัยลักษณ์</option>
                    <option value="มหาวิทยาลัยพะเยา">มหาวิทยาลัยพะเยา</option>
                    <option value="มหาวิทยาลัยแม่โจ้">มหาวิทยาลัยแม่โจ้</option>
                    <option value="มหาวิทยาลัยอุบลราชธานี">มหาวิทยาลัยอุบลราชธานี</option>
                    <option value="มหาวิทยาลัยทักษิณ">มหาวิทยาลัยทักษิณ</option>
                </select>
            </p>
            <p>
                <label>ชื่อผู้ใช้ (Username):</label>
                <input type="text" name="username" placeholder="สำหรับใช้เข้าสู่ระบบ" required>
            </p>
            <p>
                <label>รหัสผ่าน (Password):</label>
                <input type="password" name="password" placeholder="อย่างน้อย 4 ตัวอักษร" required>
            </p>
            <p style="margin-bottom: 0;">
                <button type="submit">ยืนยันการสมัครสมาชิก</button>
            </p>
        </form>
    </div>
</body>
</html>