<?php
session_start();
include('../includes/config.php');
if(!isset($_SESSION['alogin'])) { header('Location: index.php'); exit(); }
$totalEmployees = $dbh->query("SELECT COUNT(*) FROM tblemployees")->fetchColumn();
$totalLeaves = $dbh->query("SELECT COUNT(*) FROM tblleaves")->fetchColumn();
$approvedLeaves = $dbh->query("SELECT COUNT(*) FROM tblleaves WHERE Status='1' OR LOWER(Status)='approved'")->fetchColumn();
$pendingLeaves = $dbh->query("SELECT COUNT(*) FROM tblleaves WHERE Status='0' OR LOWER(Status)='pending'")->fetchColumn();
$rejectedLeaves = $dbh->query("SELECT COUNT(*) FROM tblleaves WHERE Status='2' OR LOWER(Status)='rejected'")->fetchColumn();
?><!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard | OLMS</title>
<style>
body{font-family:Segoe UI,Arial;margin:0;background:#f5f7f8}.header{background:#0b4c40;color:#fff;padding:14px 24px;display:flex;justify-content:space-between;align-items:center}
.wrapper{display:flex}.sidebar{width:240px;background:#263238;color:#fff;min-height:calc(100vh - 56px);padding-top:24px}
.sidebar a{display:block;color:#fff;padding:12px 18px;text-decoration:none}.sidebar a.active{background:#00796b}
.main{flex:1}.container{padding:30px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px}
.card{background:#fff;padding:24px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06);text-align:center;cursor:pointer}
.card h3{color:#004d40;margin:0 0 6px}.card p{font-size:28px;color:#00796b;margin:0;font-weight:700}
.card.approved p{color:#43a047}.card.pending p{color:#fbc02d}.card.rejected p{color:#e53935}.footer{background:#004d40;color:#fff;text-align:center;padding:12px}
</style></head><body>
<div class="header"><div style="font-weight:700;font-size:20px">Admin Dashboard</div><div>Welcome, <?php echo htmlentities($_SESSION['alogin']); ?> | <a href="logout.php" style="color:#fff;text-decoration:underline">Logout</a></div></div>
<div class="wrapper">
<nav class="sidebar">
<a href="dashboard.php" class="active">🏠 Dashboard</a>
<a href="employees.php">👥 Employees</a>
<a href="leave-types.php">📄 Leave Types</a>
<a href="manage-leaves.php">📋 Manage Leaves</a>
<a href="logout.php">🚪 Logout</a>
</nav>
<main class="main"><div class="container"><div class="grid">
<div class="card" onclick="location='employees.php'"><h3>Total Employees</h3><p><?php echo htmlentities($totalEmployees); ?></p></div>
<div class="card" onclick="location='manage-leaves.php'"><h3>Total Leave Requests</h3><p><?php echo htmlentities($totalLeaves); ?></p></div>
<div class="card approved" onclick="location='manage-leaves.php?filter=approved'"><h3>Approved Leaves</h3><p><?php echo htmlentities($approvedLeaves); ?></p></div>
<div class="card pending" onclick="location='manage-leaves.php?filter=pending'"><h3>Pending Leaves</h3><p><?php echo htmlentities($pendingLeaves); ?></p></div>
<div class="card rejected" onclick="location='manage-leaves.php?filter=rejected'"><h3>Rejected Leaves</h3><p><?php echo htmlentities($rejectedLeaves); ?></p></div>
</div></div><div class="footer">© <?php echo date('Y'); ?> Online Leave Management System</div></main>
</div></body></html>