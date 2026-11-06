<?php require "config.php"; $page_title = "Sign Up"; $msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']); $email = trim($_POST['email']); $pass = $_POST['password'];
  if (strlen($pass) < 6) { $msg = "<div class='msg err'>Password must be at least 6 characters.</div>"; }
  elseif ($pass !== $_POST['confirm']) { $msg = "<div class='msg err'>Passwords do not match.</div>"; }
  else {
    $st = $conn->prepare("SELECT user_id FROM users WHERE email=?"); $st->bind_param("s", $email); $st->execute();
    if ($st->get_result()->num_rows) { $msg = "<div class='msg err'>This email is already registered.</div>"; }
    else {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $st = $conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)"); $st->bind_param("sss", $name, $email, $hash); $st->execute();
      $_SESSION['user_id'] = $conn->insert_id; $_SESSION['name'] = $name; header("Location: profile.php"); exit;
    }
  }
}
include "header.php"; ?>
<form class="box card" method="post" data-validate>
  <h2>Create an Account</h2><?= $msg ?>
  <label>Full Name</label><input name="name" required>
  <label>Email</label><input type="email" name="email" required>
  <label>Password</label><input type="password" name="password" minlength="6" required>
  <label>Confirm Password</label><input type="password" name="confirm" required>
  <button class="btn" style="margin-top:16px">Sign Up</button>
  <p class="muted">Already have an account? <a href="login.php">Login</a></p>
</form>
<?php include "footer.php"; ?>
