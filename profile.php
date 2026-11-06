<?php require "config.php"; require_login(); $page_title = "My Profile"; $uid = $_SESSION['user_id']; $msg = "";
if (isset($_GET['registered'])) $msg = "<div class='msg ok'>Thank you for registering as a donor!</div>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
  $av = isset($_POST['available']) ? 1 : 0; $age = (int)$_POST['age'];
  if (!in_array($_POST['blood_group'], $BLOOD_GROUPS) || !preg_match('/^[0-9]{10}$/', $_POST['phone']) || $age < 18 || $age > 65) {
    $msg = "<div class='msg err'>Please check your details.</div>";
  } else {
    $st = $conn->prepare("UPDATE donors SET name=?,age=?,blood_group=?,location=?,phone=?,available=? WHERE user_id=?");
    $st->bind_param("sisssii", $_POST['name'], $age, $_POST['blood_group'], $_POST['location'], $_POST['phone'], $av, $uid); $st->execute();
    $msg = "<div class='msg ok'>Profile updated.</div>";
  }
}
if (isset($_GET['cancel'])) {
  $rid = (int)$_GET['cancel']; $st = $conn->prepare("UPDATE blood_requests SET status='Cancelled' WHERE request_id=? AND user_id=?");
  $st->bind_param("ii", $rid, $uid); $st->execute();
}
$st = $conn->prepare("SELECT * FROM donors WHERE user_id=?"); $st->bind_param("i", $uid); $st->execute(); $d = $st->get_result()->fetch_assoc();
$st = $conn->prepare("SELECT * FROM blood_requests WHERE user_id=? ORDER BY created_at DESC"); $st->bind_param("i", $uid); $st->execute(); $reqs = $st->get_result();
include "header.php"; ?>
<h2>Welcome, <?= e($_SESSION['name']) ?></h2><?= $msg ?>
<?php if ($d): ?>
<form class="card" method="post" data-validate style="max-width:520px">
  <h3>Donor Profile <span class="badge <?= $d['available'] ? 'green' : 'grey' ?>"><?= $d['available'] ? 'Available' : 'Not available' ?></span></h3>
  <label>Name</label><input name="name" value="<?= e($d['name']) ?>" required>
  <label>Age</label><input type="number" name="age" value="<?= e($d['age']) ?>" required>
  <label>Blood Group</label><select name="blood_group"><?php foreach ($BLOOD_GROUPS as $g) echo "<option".($g===$d['blood_group']?" selected":"").">$g</option>"; ?></select>
  <label>Location</label><input name="location" value="<?= e($d['location']) ?>" required>
  <label>Phone</label><input name="phone" value="<?= e($d['phone']) ?>" required>
  <label style="display:flex;gap:8px;align-items:center"><input type="checkbox" name="available" style="width:auto" <?= $d['available'] ? 'checked' : '' ?>> I am available to donate</label>
  <button class="btn" name="update" value="1" style="margin-top:12px">Save Changes</button>
</form>
<?php else: ?>
<div class="card"><p>You are not registered as a donor yet.</p><a class="btn" href="donor_register.php">Become a Donor</a></div>
<?php endif; ?>
<h3>My Blood Requests</h3>
<div class="table-wrap"><table><tr><th>Blood</th><th>Patient</th><th>Hospital</th><th>Emergency</th><th>Status</th><th></th></tr>
<?php while ($r = $reqs->fetch_assoc()): ?>
<tr><td><?= e($r['blood_group']) ?></td><td><?= e($r['patient_name']) ?></td><td><?= e($r['hospital']) ?></td>
<td><?= $r['is_emergency'] ? 'Yes' : 'No' ?></td><td><?= e($r['status']) ?></td>
<td><?php if ($r['status']==='Pending'): ?><a href="?cancel=<?= $r['request_id'] ?>" data-confirm="Cancel this request?">Cancel</a><?php endif; ?></td></tr>
<?php endwhile; ?></table></div>
<?php include "footer.php"; ?>
