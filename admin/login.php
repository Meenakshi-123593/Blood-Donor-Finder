<?php require "../config.php"; $in_admin = true; $page_title = "Admin Login"; $msg = "";
// First run: create default admin (username: admin, password: admin123)
if ($conn->query("SELECT COUNT(*) c FROM admin")->fetch_assoc()['c'] == 0) {
  $h = password_hash("admin123", PASSWORD_DEFAULT);
  $st = $conn->prepare("INSERT INTO admin(username,password) VALUES('admin',?)"); $st->bind_param("s", $h); $st->execute();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $st = $conn->prepare("SELECT * FROM admin WHERE username=?"); $st->bind_param("s", $_POST['username']); $st->execute();
  $a = $st->get_result()->fetch_assoc();
  if ($a && password_verify($_POST['password'], $a['password'])) { $_SESSION['admin_id'] = $a['admin_id']; header("Location: dashboard.php"); exit; }
  $msg = "<div class='msg err'>Invalid admin username or password.</div>";
}
include "../header.php"; ?>
<form class="box card" method="post"><h2>Admin Login</h2><?= $msg ?>
  <label>Username</label><input name="username" required>
  <label>Password</label><input type="password" name="password" required>
  <button class="btn" style="margin-top:16px">Login</button></form>
<?php include "../footer.php"; ?>
