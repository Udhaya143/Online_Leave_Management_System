<?php
session_start();
include('../includes/config.php');
if(!isset($_SESSION['alogin'])) { header('Location:index.php'); exit(); }

// Add employee
if(isset($_POST['add'])) {
    $fullname   = trim($_POST['fullname']);
    $email      = trim($_POST['email']);
    $gender     = trim($_POST['gender']);      // (not stored in DB in current schema)
    $department = trim($_POST['department']);  // (not stored in DB in current schema)
    $password   = md5($_POST['password']);     // hash to match login

    if ($fullname !== '' && $email !== '' && $_POST['password'] !== '') {
        $sql = "INSERT INTO tblemployees (FullName, Email, Password)
                VALUES (:fullname, :email, :password)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email',    $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $msg = "✅ Employee added successfully";
    } else {
        $error = "⚠️ Please fill in all required fields.";
    }
}

// Delete employee
if(isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $del = $dbh->prepare("DELETE FROM tblemployees WHERE id=:id");
    $del->bindParam(':id', $id);
    $del->execute();
    $msg = "🗑️ Employee record deleted successfully";
}

// Fetch all employees as associative arrays (so we can use $row['...'])
$rows = $dbh->query("SELECT id, FullName, Email, RegDate FROM tblemployees ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Employees | OLMS Admin</title>
<style>
body{font-family:'Segoe UI',Arial;margin:0;background:#f5f7f8}
.header{background:#004d40;color:#fff;padding:14px 24px;display:flex;justify-content:space-between;align-items:center}
.wrapper{display:flex}
.sidebar{width:240px;background:#263238;color:#fff;min-height:calc(100vh - 56px);padding-top:24px}
.sidebar a{display:block;color:#fff;padding:12px 18px;text-decoration:none}
.sidebar a:hover,.sidebar a.active{background:#00796b}
.main{flex:1;padding:30px}
.card{background:#fff;padding:25px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06);margin-bottom:25px}
form.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px}
input,select{padding:10px;border-radius:8px;border:1px solid #ccc}
button{padding:10px;border-radius:8px;border:0;background:#00796b;color:#fff;cursor:pointer}
button:hover{background:#00695c}
.table{width:100%;border-collapse:collapse;margin-top:16px}
.table th{background:#00796b;color:#fff;padding:12px}
.table td{padding:10px;border-bottom:1px solid #eee;text-align:center}
.msg{padding:10px;background:#e0f2f1;color:#004d40;border-radius:8px;margin-bottom:10px;text-align:center}
.error{padding:10px;background:#ffebee;color:#b71c1c;border-radius:8px;margin-bottom:10px;text-align:center}
.del{background:#e53935;padding:6px 10px;color:#fff;border-radius:6px;border:0;cursor:pointer}
</style>
</head>
<body>
<div class="header">
  <div style="font-weight:700">OLMS Admin Panel</div>
  <div>Welcome, <?php echo htmlentities($_SESSION['alogin']); ?> | <a href="logout.php" style="color:#fff;text-decoration:none;">Logout</a></div>
</div>

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
      <?php
        if(isset($msg))   echo "<div class='msg'>".htmlentities($msg)."</div>";
        if(isset($error)) echo "<div class='error'>".htmlentities($error)."</div>";
      ?>
      <form method="post" class="grid">
        <input name="fullname"    placeholder="Full Name" required>
        <input name="email"       type="email" placeholder="Email" required>
        <select name="gender"     required>
          <option value="">Gender</option>
          <option>Male</option>
          <option>Female</option>
        </select>
        <input name="department"  placeholder="Department" required>
        <input type="password" name="password" placeholder="Set Password" required>
        <button name="add" type="submit" style="grid-column:span 2;">Add Employee</button>
      </form>
    </div>

    <div class="card">
      <h2>📋 All Employees</h2>
      <table class="table">
        <thead>
          <tr><th>#</th><th>Full Name</th><th>Email</th><th>Reg Date</th><th>Action</th></tr>
        </thead>
        <tbody>
          <?php
          if ($rows) {
              $cnt = 1;
              foreach ($rows as $row) {
                  $regDate = !empty($row['RegDate']) ? $row['RegDate'] : date('Y-m-d H:i:s');
                  echo '<tr>'.
                       '<td>'. $cnt .'</td>'.
                       '<td>'. htmlentities($row['FullName']) .'</td>'.
                       '<td>'. htmlentities($row['Email']) .'</td>'.
                       '<td>'. htmlentities($regDate) .'</td>'.
                       '<td><a href="employees.php?del='. intval($row['id']) .'" onclick="return confirm(\'Delete this employee?\')"><button class="del">Delete</button></a></td>'.
                       '</tr>';
                  $cnt++;
              }
          } else {
              echo "<tr><td colspan='5'>No employees found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
