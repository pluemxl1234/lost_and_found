<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $university = trim($_POST['university'] ?? 'มหาวิทยาลัยบูรพา');

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'] ?? $user['username'];
                $_SESSION['role'] = $user['role'] ?? 'student';
                $_SESSION['university'] = $university;

                header("Location: index.php");
                exit();
            } else {
                $error = "รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            $error = "ไม่พบชื่อผู้ใช้งานนี้ในระบบ";
        }
        $stmt->close();
    } else {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ศูนย์แจ้งของหายและพบของ</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-card { width: 100%; max-width: 440px; background: rgba(30, 41, 59, 0.75); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px; padding: 40px 30px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5); }
        .login-card h2 { font-size: 1.8rem; font-weight: 700; text-align: center; margin-bottom: 25px; background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 0.9rem; color: #cbd5e1; }
        .form-input { width: 100%; padding: 12px 16px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px; color: #f8fafc; font-size: 0.95rem; outline: none; }
        .form-input option { background: #0f172a; color: #f8fafc; }
        .btn-login-submit { width: 100%; padding: 14px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .login-footer { text-align: center; margin-top: 20px; font-size: 0.88rem; color: #94a3b8; }
        .login-footer a { color: #818cf8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <h2>เข้าสู่ระบบ</h2>

            <?php if (!empty($error)): ?>
                <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; padding: 10px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-size: 0.88rem;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>ชื่อผู้ใช้ (Username):</label>
                    <input type="text" name="username" class="form-input" required placeholder="กรอกชื่อผู้ใช้">
                </div>
                <div class="form-group">
                    <label>รหัสผ่าน (Password):</label>
                    <input type="password" name="password" class="form-input" required placeholder="กรอกรหัสผ่าน">
                </div>
                <div class="form-group">
                    <label>เลือกมหาวิทยาลัย:</label>
                    <select name="university" class="form-input">
                        <option value="มหาวิทยาลัยบูรพา">มหาวิทยาลัยบูรพา</option>
                        <option value="จุฬาลงกรณ์มหาวิทยาลัย">จุฬาลงกรณ์มหาวิทยาลัย</option>
                        <option value="มหาวิทยาลัยเกษตรศาสตร์">มหาวิทยาลัยเกษตรศาสตร์</option>
                        <option value="มหาวิทยาลัยธรรมศาสตร์">มหาวิทยาลัยธรรมศาสตร์</option>
                        <option value="มหาวิทยาลัยมหิดล">มหาวิทยาลัยมหิดล</option>
                        <option value="มหาวิทยาลัยเชียงใหม่">มหาวิทยาลัยเชียงใหม่</option>
                        <option value="มหาวิทยาลัยขอนแก่น">มหาวิทยาลัยขอนแก่น</option>
                        <option value="มหาวิทยาลัยสงขลานครินทร์">มหาวิทยาลัยสงขลานครินทร์</option>
                        <option value="มหาวิทยาลัยศรีนครินทรวิโรฒ">มหาวิทยาลัยศรีนครินทรวิโรฒ</option>
                        <option value="มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี">มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี</option>
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
                </div>
                <button type="submit" class="btn-login-submit">เข้าสู่ระบบ</button>
            </form>

            <div class="login-footer">
                ยังไม่มีบัญชีผู้ใช้? <a href="register.php">สมัครสมาชิกที่นี่</a>
            </div>
        </div>
    </div>
</body>
</html>