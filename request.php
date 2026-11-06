<?php require "config.php"; require_login(); $page_title = "Request Blood"; $msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!in_array($_POST['blood_group'], $BLOOD_GROUPS) || !preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
    $msg = "<div class='msg err'>Please check the blood group and phone number.</div>";
  } else {
    $em = isset($_POST['is_emergency']) ? 1 : 0;
    $st = $conn->prepare("INSERT INTO blood_requests(user_id,patient_name,blood_group,hospital,location,phone,is_emergency) VALUES(?,?,?,?,?,?,?)");
    $st->bind_param("isssssi", $_SESSION['user_id'], $_POST['patient_name'], $_POST['blood_group'], $_POST['hospital'], $_POST['location'], $_POST['phone'], $em);
    $st->execute(); $msg = "<div class='msg ok'>Your blood request has been submitted. <a href='search.php?blood_group=".urlencode($_POST['blood_group'])."'>See matching donors</a></div>";
  }
}
include "header.php"; ?>
<form class="box card" method="post" data-validate>
  <h2>Blood Request</h2><?= $msg ?>
  <label>Patient Name</label><input name="patient_name" required>
  <label>Required Blood Group</label><select name="blood_group" required><option value="">Select</option>
  <?php foreach ($BLOOD_GROUPS as $g) echo "<option>$g</option>"; ?></select>
  <label>Hospital Name</label><input name="hospital" required>
  <label>Location (City)</label><input name="location" required>
  <label>Contact Phone</label><input name="phone" maxlength="10" required>
  <label style="display:flex;gap:8px;align-items:center"><input type="checkbox" name="is_emergency" style="width:auto"> 🚨 This is an emergency</label>
  <button class="btn" style="margin-top:16px">Submit Request</button>
</form>
<?php include "footer.php"; ?>
