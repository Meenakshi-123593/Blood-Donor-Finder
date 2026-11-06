<?php require "config.php"; $page_title = "Blood Donor Finder – Home";
$donors = $conn->query("SELECT COUNT(*) c FROM donors WHERE available=1")->fetch_assoc()['c'];
$reqs = $conn->query("SELECT COUNT(*) c FROM blood_requests WHERE status='Pending'")->fetch_assoc()['c'];
$urgent = $conn->query("SELECT * FROM blood_requests WHERE is_emergency=1 AND status='Pending' ORDER BY created_at DESC LIMIT 3");
include "header.php"; ?>
<section class="hero">
  <h1>Donate Blood, Save Lives</h1>
  <p>Find blood donors by blood group and location in seconds. Register as a donor and help someone in an emergency.</p>
  <a class="btn" href="search.php">Find a Donor</a> <a class="btn" href="donor_register.php">Become a Donor</a>
</section>
<div class="stats" style="margin-top:20px">
  <div class="card"><b><?= $donors ?></b>Available donors</div>
  <div class="card"><b><?= $reqs ?></b>Open blood requests</div>
  <div class="card"><b>8</b>Blood groups supported</div>
</div>
<?php if ($urgent->num_rows): ?><h2>🚨 Emergency Requests</h2><div class="grid">
<?php while ($r = $urgent->fetch_assoc()): ?>
  <div class="card urgent"><span class="badge"><?= e($r['blood_group']) ?></span> <b>URGENT</b>
  <p><?= e($r['hospital']) ?>, <?= e($r['location']) ?><br>📞 <?= e($r['phone']) ?></p></div>
<?php endwhile; ?></div><?php endif; ?>
<h2>Why donate blood?</h2>
<div class="grid">
  <div class="card"><h3>❤️ Save up to 3 lives</h3><p class="muted">One donation can help several patients in accidents, surgeries and illness.</p></div>
  <div class="card"><h3>⏱ Only takes an hour</h3><p class="muted">The donation itself takes about 10 minutes.</p></div>
  <div class="card"><h3>✅ Who can donate?</h3><p class="muted">Healthy people aged 18–65 weighing above 45 kg. Eligibility is confirmed by doctors.</p></div>
</div>
<?php include "footer.php"; ?>
