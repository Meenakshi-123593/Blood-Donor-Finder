<?php require "config.php"; $page_title = "Blood Requests";
$res = $conn->query("SELECT * FROM blood_requests WHERE status='Pending' ORDER BY is_emergency DESC, created_at DESC");
include "header.php"; ?>
<h2>Open Blood Requests</h2><p class="muted">Emergency requests are shown first.</p>
<div class="grid">
<?php while ($r = $res->fetch_assoc()): ?>
  <div class="card <?= $r['is_emergency'] ? 'urgent' : '' ?>">
    <span class="badge"><?= e($r['blood_group']) ?></span> <?= $r['is_emergency'] ? '<b>🚨 EMERGENCY</b>' : '' ?>
    <h3><?= e($r['patient_name']) ?></h3>
    <p>🏥 <?= e($r['hospital']) ?><br>📍 <?= e($r['location']) ?><br>📞 <a href="tel:<?= e($r['phone']) ?>"><?= e($r['phone']) ?></a><br>
    <small class="muted"><?= e($r['created_at']) ?></small></p></div>
<?php endwhile; if (!$res->num_rows) echo "<p>No open requests right now.</p>"; ?>
</div>
<?php include "footer.php"; ?>
