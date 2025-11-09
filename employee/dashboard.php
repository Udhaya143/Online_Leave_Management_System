<?php
session_start();
include('../includes/config.php');
if(!isset($_SESSION['emplogin']) && !isset($_SESSION['eid'])) { header('location:index.php'); exit(); }
if (isset($_SESSION['eid'])) {
    $stmt = $dbh->prepare("SELECT id, FullName, Email FROM tblemployees WHERE id=:id");
    $stmt->bindParam(':id', $_SESSION['eid']);
} else {
    $stmt = $dbh->prepare("SELECT id, FullName, Email FROM tblemployees WHERE Email=:email");
    $stmt->bindParam(':email', $_SESSION['emplogin']);
}
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);
if (!$user) { echo "<h2 style='color:red;text-align:center;margin-top:20px;'>Employee record not found!</h2>"; exit(); }
$total = $dbh->query("SELECT COUNT(*) FROM tblleaves WHERE empid={$user->id}")->fetchColumn();
$approved = $dbh->query("SELECT COUNT(*) FROM tblleaves WHERE empid={$user->id} AND (Status='1' OR LOWER(Status)='approved')")->fetchColumn();
$pending = $dbh->query("SELECT COUNT(*) FROM tblleaves WHERE empid={$user->id} AND (Status='0' OR LOWER(Status)='pending')")->fetchColumn();
$rejected = $dbh->query("SELECT COUNT(*) FROM tblleaves WHERE empid={$user->id} AND (Status='2' OR LOWER(Status)='rejected')")->fetchColumn();
?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Employee Dashboard | OLMS</title>
<style>
body{font-family:"Segoe UI",Arial;background:#f5f7f8;margin:0}.header{background:#004d40;color:#fff;padding:15px 25px;display:flex;justify-content:space-between;align-items:center;font-weight:600}
.wrapper{display:flex}.sidebar{width:240px;background:#263238;color:#fff;min-height:calc(100vh - 56px);padding-top:25px}
.sidebar a{display:block;color:#fff;padding:12px 18px;text-decoration:none;transition:.3s}.sidebar a:hover,.sidebar a.active{background:#00796b}
.main{flex:1;padding:30px}.welcome{font-size:22px;color:#004d40;margin-bottom:10px}.subtext{color:#555;margin-bottom:30px}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:20px}.card{background:#fff;border-radius:12px;box-shadow:0 6px 14px rgba(0,0,0,0.08);padding:25px;text-align:center;transition:.3s}
.card:hover{transform:translateY(-5px);box-shadow:0 8px 18px rgba(0,0,0,0.12)}.card h3{font-size:18px;color:#004d40;margin-bottom:8px}.card p{font-size:28px;font-weight:bold;margin:0}
.total{color:#00796b}.approved{color:#43a047}.pending{color:#fbc02d}.rejected{color:#e53935}footer{text-align:center;padding:15px;margin-top:40px;color:#004d40;font-size:14px}
@media(max-width:768px){ .sidebar{width:100%;position:relative} .main{padding:20px} }
</style></head><body>
<div class="header"><div>OLMS Employee Panel</div><div>Welcome, <?php echo htmlentities($user->FullName); ?> | <a href="logout.php" style="color:#fff;text-decoration:none;">Logout</a></div></div>
<div class="wrapper">
<nav class="sidebar">
<a href="dashboard.php" class="active">🏠 Dashboard</a>
<a href="apply-leave.php">📝 Apply Leave</a>
<a href="leave-history.php">📋 Leave History</a>
</nav>
<main class="main">
<div class="welcome">👋 Hello, <?php echo htmlentities($user->FullName); ?>!</div>
<div class="subtext">Here’s a quick overview of your leave activity.</div>
<div class="cards">
<div class="card"><h3>Total Leave Requests</h3><p class="total"><?php echo $total; ?></p></div>
<div class="card"><h3>Approved Leaves</h3><p class="approved"><?php echo $approved; ?></p></div>
<div class="card"><h3>Pending Leaves</h3><p class="pending"><?php echo $pending; ?></p></div>
<div class="card"><h3>Rejected Leaves</h3><p class="rejected"><?php echo $rejected; ?></p></div>
</div>
<footer>© <?php echo date("Y"); ?> Online Leave Management System | Employee Panel</footer>
</main></div></body></html>