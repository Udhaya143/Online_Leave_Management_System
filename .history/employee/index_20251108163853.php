<?php
session_start();
include('../includes/config.php');
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);
    $sql = "SELECT id, FullName FROM tblemployees WHERE Email=:email AND Password=:password";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    if ($user) {
        $_SESSION['emplogin'] = $email;
        $_SESSION['eid'] = $user->id;
        header("Location: dashboard.php"); exit();
    } else { $error = "Invalid Email or Password"; }
}
?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Employee Login | OLMS</title>
<style>
body{margin:0;font-family:"Segoe UI",Arial,sans-serif;background:linear-gradient(135deg,#004d40,#00796b);display:flex;justify-content:center;align-items:center;height:100vh}
.login-container{background:#fff;padding:40px 35px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.15);width:360px;text-align:center}
.login-container h2{color:#004d40;font-size:26px;margin-bottom:6px}.login-container p{color:#666;margin-bottom:25px;font-size:14px}
input{width:100%;padding:12px 15px;margin-bottom:15px;border:1px solid #ccc;border-radius:8px;font-size:15px;transition:border-color .3s}
input:focus{outline:none;border-color:#00796b}button{width:100%;padding:12px;background:#00796b;border:none;color:#fff;border-radius:8px;font-size:16px;cursor:pointer;transition:background .3s}
button:hover{background:#00695c}.error{background:#ffebee;color:#c62828;border-radius:6px;padding:10px;margin-bottom:15px;font-size:14px}
footer{position:absolute;bottom:15px;text-align:center;width:100%;color:#fff;font-size:14px}
</style></head><body>
<div class="login-container">
<h2>Employee Login</h2><p>Online Leave Management System</p>
<?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
<form method="POST">
<input type="email" name="email" placeholder="Enter Email" required>
<input type="password" name="password" placeholder="Enter Password" required>
<button type="submit" name="login">Login</button>
</form></div><footer>© <?php echo date("Y"); ?> Online Leave Management System</footer></body></html>