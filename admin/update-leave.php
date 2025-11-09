<?php
session_start();
include('../includes/config.php');
if(!isset($_SESSION['alogin'])){ header('Location:index.php'); exit(); }
if(!isset($_GET['leaveid'])){ header('Location:manage-leaves.php'); exit(); }
$lid = intval($_GET['leaveid']);
$sql = "SELECT l.*, COALESCE(e.FullName,'Unknown') AS Employee, COALESCE(e.Email,'-') AS Email 
        FROM tblleaves l LEFT JOIN tblemployees e ON e.id = l.empid WHERE l.id=:lid";
$q = $dbh->prepare($sql); $q->execute([':lid'=>$lid]); $row = $q->fetch();
if(!$row){ echo "Not found"; exit; }
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title>Leave Details</title></head>
<body style="font-family:Segoe UI,Arial;padding:20px">
<h2>Leave Details</h2>
<p><strong>Employee:</strong> <?php echo htmlentities($row->Employee);?></p>
<p><strong>Email:</strong> <?php echo htmlentities($row->Email);?></p>
<p><strong>Leave Type:</strong> <?php echo htmlentities($row->LeaveType);?></p>
<p><strong>From:</strong> <?php echo htmlentities($row->FromDate);?></p>
<p><strong>To:</strong> <?php echo htmlentities($row->ToDate);?></p>
<p><strong>Description:</strong> <?php echo nl2br(htmlentities($row->Description));?></p>
<p><strong>Status:</strong> <?php echo htmlentities($row->Status);?></p>
<a href="manage-leaves.php">Back</a>
</body></html>