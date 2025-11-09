<?php
session_start();
include('../includes/config.php');
if(isset($_POST['login'])) {
    $uname = trim($_POST['username']);
    $password = md5($_POST['password']);
    $sql = "SELECT id FROM admin WHERE UserName=:uname AND Password=:password LIMIT 1";
    $q = $dbh->prepare($sql);
    $q->bindParam(':uname', $uname, PDO::PARAM_STR);
    $q->bindParam(':password', $password, PDO::PARAM_STR);
    $q->execute();
    if($q->rowCount() > 0) {
        $_SESSION['alogin'] = $uname;
        header('Location: dashboard.php'); exit();
    } else { $error = "Invalid username or password."; }
}
?><!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login | OLMS</title>
<style>
body{margin:0;font-family:Segoe UI,Arial;background:linear-gradient(135deg,#004d40,#00796b);display:flex;align-items:center;justify-content:center;height:100vh}
.card{width:380px;background:#fff;padding:36px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.15);text-align:center}
h2{color:#004d40;margin-bottom:6px}p.sub{color:#666;margin-bottom:20px}
input{width:100%;padding:12px;margin:8px 0;border:1px solid #ccc;border-radius:8px}
button{width:100%;padding:12px;border:0;border-radius:8px;background:#00796b;color:#fff;font-weight:600;cursor:pointer}
.msg{padding:10px;border-radius:8px;margin-bottom:10px}.error{background:#ffebee;color:#c62828}
</style></head><body>
<div class="card">
<h2>OLMS Admin Login</h2><p class="sub">Sign in to manage leaves & employees</p>
<?php if(!empty($error)){ echo "<div class='msg error'>".htmlentities($error)."</div>"; } ?>
<form method="post">
<input type="text" name="username" placeholder="Username" required autocomplete="off">
<input type="password" name="password" placeholder="Password" required autocomplete="off">
<button type="submit" name="login">Login</button>
</form></div></body></html>