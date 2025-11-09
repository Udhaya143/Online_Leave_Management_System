<?php
session_start();
include('../includes/config.php');
if(!isset($_SESSION['alogin'])){ header('Location:index.php'); exit(); }
if(isset($_POST['add'])) {
    $lt = trim($_POST['leavetype']);
    $desc = trim($_POST['description']);
    if($lt!='') {
        $dbh->prepare("INSERT INTO tblleavetype(LeaveType,Description) VALUES(:lt,:desc)")->execute([':lt'=>$lt,':desc'=>$desc]);
        $msg = "Leave type added";
    } else $error = "Enter leave type";
}
if(isset($_GET['del'])) {
    $dbh->prepare("DELETE FROM tblleavetype WHERE id=:id")->execute([':id'=>intval($_GET['del'])]);
    $msg = "Deleted";
}
$types = $dbh->query("SELECT * FROM tblleavetype ORDER BY id DESC")->fetchAll();
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width">
<title>Leave Types | Admin</title>
<style>
body{font-family:Segoe UI,Arial;margin:0;background:#f5f7f8}.header{background:#0b4c40;color:#fff;padding:14px 24px}.wrapper{display:flex}
.sidebar{width:240px;background:#263238;color:#fff;padding-top:20px}.sidebar a{display:block;color:#fff;padding:12px 18px;text-decoration:none}
.main{flex:1;padding:30px}.card{background:#fff;padding:20px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06)}
input,textarea{width:100%;padding:10px;margin-bottom:10px;border-radius:8px;border:1px solid #ccc}.btn{background:#00796b;color:#fff;padding:10px;border-radius:8px;border:0}
.table{width:100%;border-collapse:collapse}.table th{background:#00796b;color:#fff;padding:12px}.table td{padding:10px;border-bottom:1px solid #eee;text-align:center}
.del{background:#e53935;color:#fff;border:0;padding:6px 10px;border-radius:6px}.msg{padding:10px;margin-bottom:10px;border-radius:8px}.success{background:#e8f5e9;color:#2e7d32}.error{background:#ffebee;color:#c62828}
</style></head><body>
<div class="header"><div style="font-weight:700">Admin Dashboard</div></div>
<div class="wrapper">
<nav class="sidebar">
<a href="dashboard.php">🏠 Dashboard</a>
<a href="employees.php">👥 Employees</a>
<a href="leave-types.php" class="active">📄 Leave Types</a>
<a href="manage-leaves.php">📋 Manage Leaves</a>
</nav>
<main class="main">
<div class="card"><h2>Add Leave Type</h2>
<?php if(isset($msg)) echo "<div class='msg success'>".htmlentities($msg)."</div>";?>
<?php if(isset($error)) echo "<div class='msg error'>".htmlentities($error)."</div>";?>
<form method="post">
<input name="leavetype" placeholder="Leave type (e.g. Casual Leave)" required>
<textarea name="description" placeholder="Description (optional)"></textarea>
<button class="btn" name="add">Add</button>
</form></div>
<div class="card" style="margin-top:16px"><h2>Existing Leave Types</h2>
<table class="table"><tr><th>#</th><th>Type</th><th>Description</th><th>Created</th><th>Action</th></tr>
<?php $c=1; if($types){ foreach($types as $t){ echo "<tr><td>{$c}</td><td>".htmlentities($t->LeaveType)."</td><td>".htmlentities($t->Description)."</td><td>".htmlentities($t->CreationDate)."</td>
<td><a href='leave-types.php?del={$t->id}' onclick='return confirm(\"Delete?\")'><button class=\"del\">Delete</button></a></td></tr>"; $c++; } } else { echo '<tr><td colspan="5">No leave types.</td></tr>'; } ?>
</table></div>
</main></div></body></html>