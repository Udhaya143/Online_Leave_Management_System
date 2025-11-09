<?php
session_start();
include('../includes/config.php');
if(!isset($_SESSION['emplogin']) && !isset($_SESSION['eid'])) { header('location:index.php'); exit(); }
if (isset($_SESSION['eid'])) {
    $stmt = $dbh->prepare("SELECT id, FullName FROM tblemployees WHERE id=:id");
    $stmt->bindParam(':id', $_SESSION['eid']);
} else {
    $stmt = $dbh->prepare("SELECT id, FullName FROM tblemployees WHERE Email=:email");
    $stmt->bindParam(':email', $_SESSION['emplogin']);
}
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);
if (!$user) { echo "<h2 style='color:red;text-align:center;margin-top:20px;'>Employee record not found!</h2>"; exit(); }
if (isset($_POST['apply'])) {
    $leavetype = trim($_POST['leavetype']);
    $fromdate = trim($_POST['fromdate']);
    $todate = trim($_POST['todate']);
    $description = trim($_POST['description']);
    if ($fromdate > $todate) { $error = "⚠️ 'To Date' should be greater than or equal to 'From Date'."; }
    else {
        $sql = "INSERT INTO tblleaves (empid, LeaveType, FromDate, ToDate, Description, Status, CreationDate) 
                VALUES (:empid, :leavetype, :fromdate, :todate, :description, 'Pending', NOW())";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':empid', $user->id);
        $stmt->bindParam(':leavetype', $leavetype);
        $stmt->bindParam(':fromdate', $fromdate);
        $stmt->bindParam(':todate', $todate);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        $msg = "✅ Leave applied successfully!";
    }
}
?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Apply Leave | OLMS Employee</title>
<style>
body{font-family:"Segoe UI",Arial;background:#f5f7f8;margin:0}.header{background:#004d40;color:#fff;padding:15px 25px;display:flex;justify-content:space-between;align-items:center;font-weight:600}
.wrapper{display:flex}.sidebar{width:240px;background:#263238;color:#fff;min-height:calc(100vh - 56px);padding-top:25px}
.sidebar a{display:block;color:#fff;padding:12px 18px;text-decoration:none;transition:.3s}.sidebar a:hover,.sidebar a.active{background:#00796b}
.main{flex:1;padding:30px}.main h2{font-size:22px;color:#004d40;margin-bottom:25px}.card{background:#fff;border-radius:12px;padding:25px;box-shadow:0 6px 14px rgba(0,0,0,0.08);max-width:650px;margin:auto}
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:15px}input,select,textarea{width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;font-size:14px}
textarea{resize:none;grid-column:span 2}.btn-apply{background:#00796b;color:#fff;padding:12px;border:none;border-radius:8px;cursor:pointer;font-weight:600;grid-column:span 2;transition:.3s}
.btn-apply:hover{background:#00695c}.msg{background:#e0f2f1;color:#004d40;padding:10px;border-radius:6px;margin-bottom:15px;text-align:center;font-weight:500}
.error{background:#ffcdd2;color:#b71c1c;padding:10px;border-radius:6px;margin-bottom:15px;text-align:center;font-weight:500}footer{text-align:center;margin-top:40px;padding:15px;font-size:14px;color:#004d40}
@media(max-width:768px){.sidebar{width:100%;position:relative}.main{padding:20px}}
</style></head><body>
<div class="header"><div>OLMS Employee Panel</div><div>Welcome, <?php echo htmlentities($user->FullName); ?> | <a href="logout.php" style="color:#fff;text-decoration:none;">Logout</a></div></div>
<div class="wrapper">
<nav class="sidebar">
<a href="dashboard.php">🏠 Dashboard</a>
<a href="apply-leave.php" class="active">📝 Apply Leave</a>
<a href="leave-history.php">📋 Leave History</a>
</nav>
<main class="main"><h2>📝 Apply for Leave</h2><div class="card">
<?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; if(isset($error)) echo "<div class='error'>$error</div>"; ?>
<form method="POST" class="form-grid">
<select name="leavetype" required>
<option value="" disabled selected>Select Leave Type</option>
<?php $types = $dbh->query("SELECT LeaveType FROM tblleavetype ORDER BY LeaveType")->fetchAll(PDO::FETCH_OBJ); foreach ($types as $type) { echo "<option value='{$type->LeaveType}'>{$type->LeaveType}</option>"; } ?>
</select>
<input type="date" name="fromdate" required>
<input type="date" name="todate" required>
<textarea name="description" placeholder="Reason for leave..." rows="4" required></textarea>
<button type="submit" name="apply" class="btn-apply">Apply Leave</button>
</form></div><footer>© <?php echo date("Y"); ?> Online Leave Management System | Employee Panel</footer></main>
</div></body></html>