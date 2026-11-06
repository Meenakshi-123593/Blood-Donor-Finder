<?php require "config.php"; $page_title = "Find Blood Donors";
$bg = $_GET['blood_group'] ?? ""; $loc = trim($_GET['location'] ?? ""); $results = null;
if ($bg !== "" || $loc !== "") {
  $like = "%$loc%";
  if ($bg !== "") { $st = $conn->prepare("SELECT * FROM donors WHERE available=1 AND blood_group=? AND location LIKE ? ORDER BY name"); $st->bind_param("ss", $bg, $like); }
  else { $st = $conn->prepare("SELECT * FROM donors WHERE available=1 AND location LIKE ? ORDER BY name"); $st->bind_param("s", $like); }
  $st->execute(); $results = $st->get_result();
}
include "header.php"; ?>
<h2>Search Blood Donors</h2>
<form class="card inline" method="get">
  <div><label>Blood Group</label><select name="blood_group"><option value="">Any</option>
  <?php foreach ($BLOOD_GROUPS as $g) echo "<option".($g===$bg?" selected":"").">$g</option>"; ?></select></div>
  <div><label>Location</label><input name="location" value="<?= e($loc) ?>" placeholder="e.g. Chennai"></div>
  <div><button class="btn">Search</button></div>
</form>
<?php if ($results !== null): ?>
  <p class="muted"><?= $results->num_rows ?> donor(s) found</p>
  <div class="grid">
  <?php while ($d = $results->fetch_assoc()): ?>
    <div class="card"><span class="badge"><?= e($d['blood_group']) ?></span>
      <h3><?= e($d['name']) ?></h3><p>📍 <?= e($d['location']) ?><br>Age: <?= e($d['age']) ?><br>📞 <a href="tel:<?= e($d['phone']) ?>"><?= e($d['phone']) ?></a></p></div>
  <?php endwhile; ?></div>
  <p class="muted">Note: Please confirm donor availability and medical eligibility with a doctor before donation.</p>
<?php endif; ?>
<?php include "footer.php"; ?>
