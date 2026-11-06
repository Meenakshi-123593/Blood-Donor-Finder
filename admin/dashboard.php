<?php require "../config.php"; $in_admin = true; $page_title = "Admin Dashboard";
if (empty($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
if (isset($_GET['del_user'])) { $id=(int)$_GET['del_user']; $st=$conn->prepare("DELETE FROM users WHERE user_id=?"); $st->bind_param("i",$id); $st->execute(); }
if (isset($_GET['del_donor'])) { $id=(int)$_GET['del_donor']; $st=$conn->prepare("DELETE FROM donors WHERE donor_id=?"); $st->bind_param("i",$id); $st->execute(); }
if (isset($_GET['status'], $_GET['rid']) && in_array($_GET['status'], ['Pending','Fulfilled','Cancelled'])) {
  $id=(int)$_GET['rid']; $st=$conn->prepare("UPDATE blood_requests SET status=? WHERE request_id=?"); $st->bind_param("si",$_GET['status'],$id); $st->execute();
}
$c = fn($q) => $conn->query($q)->fetch_assoc()['c'];
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$donors = $conn->query("SELECT * FROM donors ORDER BY created_at DESC");
$reqs = $conn->query("SELECT * FROM blood_requests ORDER BY is_emergency DESC, created_at DESC");
$msgs = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
include "../header.php"; ?>
<h2>Admin Dashboard <a class="btn small grey" href="logout.php">Logout</a></h2>
<div class="stats">
  <div class="card"><b><?= $c("SELECT COUNT(*) c FROM users") ?></b>Users</div>
  <div class="card"><b><?= $c("SELECT COUNT(*) c FROM donors") ?></b>Donors</div>
  <div class="card"><b><?= $c("SELECT COUNT(*) c FROM blood_requests WHERE status='Pending'") ?></b>Pending requests</div>
  <div class="card"><b><?= $c("SELECT COUNT(*) c FROM blood_requests WHERE is_emergency=1 AND status='Pending'") ?></b>Emergencies</div>
</div>
<h3>Blood Requests</h3><div class="table-wrap"><table><tr><th>ID</th><th>Blood</th><th>Patient</th><th>Hospital</th><th>City</th><th>Phone</th><th>Urgent</th><th>Status</th><th>Action</th></tr>
<?php while ($r = $reqs->fetch_assoc()): ?><tr><td><?= $r['request_id'] ?></td><td><?= e($r['blood_group']) ?></td><td><?= e($r['patient_name']) ?></td><td><?= e($r['hospital']) ?></td><td><?= e($r['location']) ?></td><td><?= e($r['phone']) ?></td><td><?= $r['is_emergency']?'🚨':'' ?></td><td><?= e($r['status']) ?></td>
<td><a href="?rid=<?= $r['request_id'] ?>&status=Fulfilled">Fulfilled</a> | <a href="?rid=<?= $r['request_id'] ?>&status=Cancelled">Cancel</a></td></tr><?php endwhile; ?></table></div>
<h3>Donors</h3><div class="table-wrap"><table><tr><th>ID</th><th>Name</th><th>Age</th><th>Blood</th><th>City</th><th>Phone</th><th>Available</th><th></th></tr>
<?php while ($d = $donors->fetch_assoc()): ?><tr><td><?= $d['donor_id'] ?></td><td><?= e($d['name']) ?></td><td><?= e($d['age']) ?></td><td><?= e($d['blood_group']) ?></td><td><?= e($d['location']) ?></td><td><?= e($d['phone']) ?></td><td><?= $d['available']?'Yes':'No' ?></td>
<td><a href="?del_donor=<?= $d['donor_id'] ?>" data-confirm="Delete this donor?">Delete</a></td></tr><?php endwhile; ?></table></div>
<h3>Users</h3><div class="table-wrap"><table><tr><th>ID</th><th>Name</th><th>Email</th><th>Joined</th><th></th></tr>
<?php while ($u = $users->fetch_assoc()): ?><tr><td><?= $u['user_id'] ?></td><td><?= e($u['name']) ?></td><td><?= e($u['email']) ?></td><td><?= e($u['created_at']) ?></td>
<td><a href="?del_user=<?= $u['user_id'] ?>" data-confirm="Delete this user and their data?">Delete</a></td></tr><?php endwhile; ?></table></div>
<h3>Contact Messages</h3><div class="table-wrap"><table><tr><th>Name</th><th>Email</th><th>Message</th><th>Date</th></tr>
<?php while ($m = $msgs->fetch_assoc()): ?><tr><td><?= e($m['name']) ?></td><td><?= e($m['email']) ?></td><td><?= e($m['message']) ?></td><td><?= e($m['created_at']) ?></td></tr><?php endwhile; ?></table></div>
<?php include "../footer.php"; ?>
