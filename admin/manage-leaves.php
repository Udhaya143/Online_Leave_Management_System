<?php
session_start();
include('../includes/config.php');
if (!isset($_SESSION['alogin'])) { header('Location: index.php'); exit(); }
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if ($action === 'approve') { $status = '1'; }
    elseif ($action === 'reject') { $status = '2'; }
    else { $status = null; }
    if ($status !== null) {
        $stmt = $dbh->prepare("UPDATE tblleaves SET Status = :st WHERE id = :id");
        $stmt->execute([':st' => $status, ':id' => $id]);
        $msg = "Leave status updated.";
    }
}
$filter = isset($_GET['filter']) ? trim($_GET['filter']) : '';
$whereSql = '';
if ($filter === 'pending') { $whereSql = "WHERE (l.Status = '0' OR LOWER(l.Status) = 'pending')"; }
elseif ($filter === 'approved') { $whereSql = "WHERE (l.Status = '1' OR LOWER(l.Status) = 'approved')"; }
elseif ($filter === 'rejected') { $whereSql = "WHERE (l.Status = '2' OR LOWER(l.Status) = 'rejected')"; }
$sql = "
    SELECT
        l.id,
        COALESCE(e.FullName, 'Unknown') AS Employee,
        COALESCE(e.Email, '-') AS Email,
        l.LeaveType,
        l.FromDate,
        l.ToDate,
        l.Description,
        l.Status,
        COALESCE(l.CreationDate, l.FromDate) AS CreationDate
    FROM tblleaves l
    LEFT JOIN tblemployees e ON e.id = l.empid
    $whereSql
    ORDER BY l.id DESC
";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Leaves | Admin</title>
<style>
body{font-family:Segoe UI,Arial;margin:0;background:#f5f7f8}.header{background:#0b4c40;color:#fff;padding:14px 24px}
.wrapper{display:flex}.sidebar{width:240px;background:#263238;color:#fff;padding-top:20px;min-height:calc(100vh - 56px)}
.sidebar a{display:block;color:#fff;padding:12px 18px;text-decoration:none}.sidebar a.active{background:#00796b}
.main{flex:1;padding:30px}.card{background:#fff;padding:20px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06)}
.table{width:100%;border-collapse:collapse;margin-top:12px}.table th{background:#00796b;color:#fff;padding:12px}
.table td{padding:10px;border-bottom:1px solid #eee;text-align:center}.btn{padding:8px 12px;border-radius:6px;border:0;color:#fff;cursor:pointer}
.approve{background:#43a047}.reject{background:#e53935}.filter-buttons button{margin-right:8px;padding:8px 12px;border-radius:6px;border:0;background:#00796b;color:#fff;cursor:pointer}
.msg{padding:10px;background:#e8f5e9;color:#2e7d32;border-radius:8px;margin-bottom:10px}
</style></head><body>
<div class="header"><strong>Admin Dashboard</strong></div>
<div class="wrapper">
<nav class="sidebar">
<a href="dashboard.php">🏠 Dashboard</a>
<a href="employees.php">👥 Employees</a>
<a href="leave-types.php">📄 Leave Types</a>
<a href="manage-leaves.php" class="active">📋 Manage Leaves</a>
<a href="logout.php">🚪 Logout</a>
</nav>
<main class="main">
<div class="card">
<h2>All Leave Applications</h2>
<?php if (isset($msg)) echo "<div class='msg'>".htmlentities($msg)."</div>"; ?>
<div class="filter-buttons" style="margin-bottom:12px">
<a href="manage-leaves.php"><button>All</button></a>
<a href="manage-leaves.php?filter=pending"><button>Pending</button></a>
<a href="manage-leaves.php?filter=approved"><button>Approved</button></a>
<a href="manage-leaves.php?filter=rejected"><button>Rejected</button></a>
</div>
<table class="table"><tr>
<th>#</th><th>Employee</th><th>Email</th><th>Leave Type</th><th>From</th><th>To</th><th>Applied On</th><th>Status</th><th>Actions</th>
</tr>
<?php
if (count($results) > 0) { $i = 1;
foreach ($results as $r) {
    $st = (string)$r->Status;
    if ($st === '0' || strtolower($st) === 'pending') { $label = 'Pending'; }
    elseif ($st === '1' || strtolower($st) === 'approved') { $label = 'Approved'; }
    elseif ($st === '2' || strtolower($st) === 'rejected') { $label = 'Rejected'; }
    else { $label = htmlentities($st); }
    echo "<tr>";
    echo "<td>" . $i++ . "</td>";
    echo "<td>" . htmlentities($r->Employee) . "</td>";
    echo "<td>" . htmlentities($r->Email) . "</td>";
    echo "<td>" . htmlentities($r->LeaveType) . "</td>";
    echo "<td>" . htmlentities($r->FromDate) . "</td>";
    echo "<td>" . htmlentities($r->ToDate) . "</td>";
    echo "<td>" . htmlentities($r->CreationDate) . "</td>";
    echo "<td>" . htmlentities($label) . "</td>";
    echo "<td>";
    if (strtolower($label) === 'pending') {
        echo '<a href="manage-leaves.php?action=approve&id=' . intval($r->id) . '"><button class="btn approve">Approve</button></a> ';
        echo '<a href="manage-leaves.php?action=reject&id=' . intval($r->id) . '" onclick="return confirm(\'Reject this leave?\')"><button class="btn reject">Reject</button></a>';
    } else { echo '-'; }
    echo "</td></tr>";
}
} else { echo '<tr><td colspan="9">No leave records found.</td></tr>'; }
?>
</table>
</div></main></div></body></html>