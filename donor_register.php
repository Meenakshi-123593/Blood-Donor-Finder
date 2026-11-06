<?php require "config.php"; require_login(); $page_title = "Become a Donor"; $uid = $_SESSION['user_id'];
$st = $conn->prepare("SELECT donor_id FROM donors WHERE user_id=?"); $st->bind_param("i", $uid); $st->execute();
if ($st->get_result()->num_rows) { header("Location: profile.php"); exit; }
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $age = (int)$_POST['age'];
  if ($age < 18 || $age > 65) $msg = "<div class='msg err'>Donor age must be between 18 and 65.</div>";
  elseif (!in_array($_POST['blood_group'], $BLOOD_GROUPS)) $msg = "<div class='msg err'>Choose a valid blood group.</div>";
  elseif (!preg_match('/^[0-9]{10}$/', $_POST['phone'])) $msg = "<div class='msg err'>Enter a 10-digit phone number.</div>";
  else {
    $st = $conn->prepare("INSERT INTO donors(user_id,name,age,blood_group,location,phone) VALUES(?,?,?,?,?,?)");
    $st->bind_param("isisss", $uid, $_POST['name'], $age, $_POST['blood_group'], $_POST['location'], $_POST['phone']); $st->execute();
    header("Location: profile.php?registered=1"); exit;
  }
}
include "header.php"; ?>
<form class="box card" method="post" data-validate>
  <h2>Blood Donor Registration</h2><?= $msg ?>
  <label>Full Name</label><input name="name" value="<?= e($_SESSION['name']) ?>" required>
  <label>Age</label><input type="number" name="age" min="18" max="65" required>
  <label>Blood Group</label><select name="blood_group" required><option value="">Select</option>
  <?php foreach ($BLOOD_GROUPS as $g) echo "<option>$g</option>"; ?></select>
  <label>Location (City)</label><input name="location" required>
  <label>Phone Number</label><input name="phone" maxlength="10" required>
  <button class="btn" style="margin-top:16px">Register as Donor</button>
</form>
<?php include "footer.php"; ?>
