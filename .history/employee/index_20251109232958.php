<?php
session_start();
include('../includes/config.php');
if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);
    $sql ="SELECT * FROM tblemployees WHERE Email=:email AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':password',$password,PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if($result){
        $_SESSION['emplogin'] = $result['Email'];
        $_SESSION['eid'] = $result['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Login | OLMS</title>
<style>
    *{margin:0;padding:0;box-sizing:border-box;font-family:"Segoe UI",sans-serif;}
    body{
        background:linear-gradient(135deg,#00695c,#004d40);
        height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
    }
    .login-container{
        background:#fff;
        padding:40px 35px;
        border-radius:12px;
        width:100%;
        max-width:380px;
        box-shadow:0 10px 25px rgba(0,0,0,0.2);
        text-align:center;
        animation:fadeIn 0.8s ease;
    }
    @keyframes fadeIn{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
    h2{color:#004d40;margin-bottom:8px;}
    p.sub{color:#777;margin-bottom:25px;font-size:14px;}
    input{
        width:100%;
        padding:12px;
        margin:10px 0;
        border:1px solid #ccc;
        border-radius:6px;
        outline:none;
        transition:0.3s;
    }
    input:focus{border-color:#00796b;box-shadow:0 0 5px rgba(0,121,107,0.3);}
    button{
        width:100%;
        background:#00796b;
        color:#fff;
        padding:12px;
        border:none;
        border-radius:6px;
        font-size:16px;
        cursor:pointer;
        transition:0.3s;
        margin-top:10px;
    }
    button:hover{background:#00695c;}
    .error{
        background:#ffebee;
        color:#b71c1c;
        padding:10px;
        border-radius:8px;
        margin-bottom:15px;
    }
    footer{
        text-align:center;
        position:fixed;
        bottom:12px;
        width:100%;
        color:#fff;
        font-size:13px;
        opacity:0.9;
    }
</style>
</head>
<body>
    <div class="login-container">
        <h2>Employee Login</h2>
        <p class="sub">Online Leave Management System</p>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
    <footer>© <?php echo date("Y"); ?> Online Leave Management System</footer>
</body>
</html>
