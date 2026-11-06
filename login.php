<?php require "config.php"; $page_title = "Login"; $msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $st = $conn->prepare("SELECT * FROM users WHERE email=?"); $st->bind_param("s", $_POST['email']); $st->execute();
  $u = $st->get_result()->fetch_assoc();
  if ($u && password_verify($_POST['password'], $u['password'])) {
    $_SESSION['user_id'] = $u['user_id']; $_SESSION['name'] = $u['name']; header("Location: profile.php"); exit;
  }
  $msg = "<div class='msg err'>Invalid email or password.</div>";
}
include "header.php"; ?>
<form class="box card" method="post">
  <h2>Login</h2><?= $msg ?>
  <label>Email</label><input type="email" name="email" required>
  <label>Password</label><input type="password" name="password" required>
  <button class="btn" style="margin-top:16px">Login</button>
  <p class="muted">New here? <a href="register.php">Create an account</a> · <a href="admin/login.php">Admin login</a></p>
</form>
<?php include "footer.php"; ?>
