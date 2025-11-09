<?php
session_start();
include('../includes/config.php');
if (!isset($_SESSION['alogin'])) { header('Location:index.php'); exit(); }
if (isset($_POST['add'])) {
    $fullname = trim($_POST['FullName']);
    $email = trim($_POST['Email']);
    $gender = trim($_POST['Gender']);
    $department = trim($_POST['Department']);
    $password = md5('12345');
    $sql = "INSERT INTO tblemployees (FullName, Email, Password) VALUES (:fullname, :email, :password)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $msg = "✅ Employee added successfully!";
}
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $del = $dbh->prepare("DELETE FROM tblemployees WHERE id=:id");
    $del->bindParam(':id', $id);
    $del->execute();
    $msg = "🗑️ Employee deleted successfully!";
}
$rows = $dbh->query("SELECT * FROM tblemployees ORDER BY id DESC")->fetchAll(PDO::FETCH_OBJ);
?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Employees | OLMS Admin</title>
<style>
body{font-family:'Segoe UI',Arial;background:#f5f7f8;margin:0}.header{background:#004d40;color:#fff;padding:14px 24px;display:flex;justify-content:space-between;align-items:center;font-weight:600}
.wrapper{display:flex}.sidebar{width:240px;background:#263238;color:#fff;min-height:calc(100vh - 56px);padding-top:20px}
.sidebar a{display:block;color:#fff;padding:12px 18px;text-decoration:none;transition:.3s}.sidebar a:hover,.sidebar a.active{background:#00796b}
.main{flex:1;padding:30px}.card{background:#fff;padding:25px;border-radius:12px;box-shadow:0 6px 14px rgba(0,0,0,.08);margin-bottom:30px}
.card h2{font-size:20px;color:#004d40;margin-bottom:20px}.employee-form{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;align-items:center}
.employee-form input,.employee-form select{padding:10px;border:1px solid #ccc;border-radius:6px;width:100%}
.btn-add{grid-column:span 2;padding:10px;background:#00796b;color:#fff;border:none;border-radius:6px;cursor:pointer;transition:.3s}.btn-add:hover{background:#00695c}
.msg{background:#e0f2f1;padding:10px;border-radius:6px;color:#00695c;margin-bottom:15px;font-weight:500}
table{width:100%;border-collapse:collapse;margin-top:15px}th,td{padding:12px;text-align:center;border-bottom:1px solid #ddd}th{background:#004d40;color:#fff}
.btn-del{background:#e53935;color:#fff;border:none;border-radius:6px;padding:6px 10px;cursor:pointer}.btn-del:hover{background:#c62828}
@media(max-width:768px){.sidebar{width:100%;position:relative}.main{padding:15px}.btn-add{grid-column:span 1}}
</style></head><body>
<div class="header"><div>OLMS Admin Panel</div><div>Welcome, <?php echo htmlentities($_SESSION['alogin']); ?> | <a href="logout.php" style="color:#fff;text-decoration:none;">Logout</a></div></div>
<div class="wrapper">
<nav class="sidebar">
<a href="dashboard.php">🏠 Dashboard</a>
<a href="employees.php" class="active">👥 Employees</a>
<a href="leave-types.php">📄 Leave Types</a>
<a href="manage-leaves.php">📋 Manage Leaves</a>
</nav>
<main class="main">
<div class="card">
<h2>➕ Add New Employee</h2>
<?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>
<form method="POST" class="employee-form">
<input type="text" name="FullName" placeholder="Full Name" required>
<input type="email" name="Email" placeholder="Email" required>
<select name="Gender" required><option value="" disabled selected>Gender</option><option value="Male">Male</option><option value="Female">Female</option></select>
<input type="text" name="Department" placeholder="Department" required>
<button type="submit" name="add" class="btn-add">Add Employee</button>
</form>
</div>
<div class="card">
<h2>📋 All Employees</h2>
<table><thead><tr><th>#</th><th>Full Name</th><th>Email</th><th>Reg. Date</th><th>Action</th></tr></thead><tbody>
<?php if ($rows) { $cnt = 1; foreach ($rows as $row) { echo "<tr>
<td>{$cnt}</td><td>".htmlentities($row->FullName)."</td><td>".htmlentities($row->Email)."</td><td>".htmlentities($row->RegDate)."</td>
<td><a href='employees.php?del={$row->id}' onclick='return confirm(\"Delete this employee?\")'><button class=\"btn-del\">Delete</button></a></td>
</tr>"; $cnt++; } } else { echo "<tr><td colspan='5'>No employee records found.</td></tr>"; } ?>
</tbody></table>
</div>
</main></div></body></html>