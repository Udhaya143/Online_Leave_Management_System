<?php
session_start();
include('../includes/config.php');

if (isset($_POST['login'])) {
    $uname = trim($_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT id FROM admin WHERE UserName=:uname AND Password=:password LIMIT 1";
    $q = $dbh->prepare($sql);
    $q->bindParam(':uname', $uname, PDO::PARAM_STR);
    $q->bindParam(':password', $password, PDO::PARAM_STR);
    $q->execute();

    if ($q->rowCount() > 0) {
        $_SESSION['alogin'] = $uname;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "❌ Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | OLMS</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0;font-family:"Segoe UI",Arial,sans-serif}
  body{
    min-height:100vh;
    background:linear-gradient(135deg,#00695c,#004d40);
    display:flex;
    align-items:center;
    justify-content:center;
  }
  .card{
    width:100%;
    max-width:380px;
    background:#fff;
    border-radius:12px;
    padding:36px 32px;
    box-shadow:0 10px 25px rgba(0,0,0,.18);
    text-align:center;
    animation:fadeIn .6s ease;
  }
  @keyframes fadeIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
  h2{color:#004d40;margin-bottom:6px}
  p.sub{color:#666;margin-bottom:22px;font-size:14px}
  input{
    width:100%;padding:12px 14px;margin:8px 0 12px;
    border:1px solid #cfd8dc;border-radius:8px;outline:none;transition:.25s;
  }
  input:focus{border-color:#00796b;box-shadow:0 0 0 3px rgba(0,121,107,.12)}
  button{
    width:100%;padding:12px;border:none;border-radius:8px;
    background:#00796b;color:#fff;font-weight:600;cursor:pointer;transition:.25s
  }
  button:hover{background:#00695c}
  .error{
    background:#ffebee;color:#b71c1c;border-radius:8px;padding:10px;margin-bottom:12px;font-size:14px
  }
  footer{
    position:fixed;bottom:12px;width:100%;text-align:center;color:#fff;opacity:.9;font-size:13px
  }
</style>
</head>
<body>
  <div class="card">
    <h2>OLMS Admin Login</h2>
    <p class="sub">Sign in to manage leaves & employees</p>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="post" autocomplete="off">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
    </form>
  </div>

  <footer>© <?php echo date('Y'); ?> Online Leave Management System</footer>
</body>
</html>
