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
$sql = "SELECT LeaveType, FromDate, ToDate, Description, Status, CreationDate FROM tblleaves WHERE empid = :empid ORDER BY id DESC";
$query = $dbh->prepare($sql); $query->bindParam(':empid', $user->id); $query->execute();
$leaves = $query->fetchAll(PDO::FETCH_OBJ);
?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leave History | OLMS Employee</title>
<style>
body{font-family:"Segoe UI",Arial;background:#f5f7f8;margin:0}.header{background:#004d40;color:#fff;padding:15px 25px;display:flex;justify-content:space-between;align-items:center;font-weight:600}
.wrapper{display:flex}.sidebar{width:240px;background:#263238;color:#fff;min-height:calc(100vh - 56px);padding-top:25px}
.sidebar a{display:block;color:#fff;padding:12px 18px;text-decoration:none;transition:.3s}.sidebar a:hover,.sidebar a.active{background:#00796b}
.main{flex:1;padding:30px}.main h2{font-size:22px;color:#004d40;margin-bottom:25px}.card{background:#fff;border-radius:12px;padding:25px;box-shadow:0 6px 14px rgba(0,0,0,0.08)}
table{width:100%;border-collapse:collapse;margin-top:15px}th,td{text-align:center;padding:12px;border-bottom:1px solid #ddd;font-size:15px}
th{background:#004d40;color:#fff;font-weight:600}tr:hover{background:#f1f1f1}.status{font-weight:bold;border-radius:6px;padding:6px 10px;display:inline-block;font-size:13px}
.approved{background:#c8e6c9;color:#256029}.pending{background:#fff9c4;color:#827717}.rejected{background:#ffcdd2;color:#b71c1c}
footer{text-align:center;margin-top:40px;padding:15px;font-size:14px;color:#004d40}
@media(max-width:768px){.sidebar{width:100%;position:relative}.main{padding:20px}th,td{font-size:13px}}
</style></head><body>
<div class="header"><div>OLMS Employee Panel</div><div>Welcome, <?php echo htmlentities($user->FullName); ?> | <a href="logout.php" style="color:#fff;text-decoration:none;">Logout</a></div></div>
<div class="wrapper"><nav class="sidebar">
<a href="dashboard.php">🏠 Dashboard</a><a href="apply-leave.php">📝 Apply Leave</a><a href="leave-history.php" class="active">📋 Leave History</a>
</nav><main class="main"><h2>📅 Your Leave History</h2><div class="card">
<table><thead><tr><th>#</th><th>Leave Type</th><th>From</th><th>To</th><th>Reason</th><th>Status</th><th>Applied On</th></tr></thead><tbody>
<?php if ($leaves) { $cnt = 1; foreach ($leaves as $leave) { $lc = strtolower($leave->Status);
echo "<tr><td>{$cnt}</td><td>".htmlentities($leave->LeaveType)."</td><td>".htmlentities($leave->FromDate)."</td><td>".htmlentities($leave->ToDate)."</td>
<td>".htmlentities($leave->Description)."</td><td><span class='status {$lc}'>".htmlentities($leave->Status)."</span></td><td>".htmlentities($leave->CreationDate)."</td></tr>"; $cnt++; } }
else { echo "<tr><td colspan='7'>No leave records found.</td></tr>"; } ?>
</tbody></table></div><footer>© <?php echo date("Y"); ?> Online Leave Management System | Employee Panel</footer></main></div></body></html>