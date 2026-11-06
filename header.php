<?php $base = isset($in_admin) ? "../" : ""; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title ?? "Blood Donor Finder") ?></title>
<link rel="stylesheet" href="<?= $base ?>assets/style.css">
</head>
<body>
<nav class="nav">
  <a class="logo" href="<?= $base ?>index.php">🩸 Blood Donor Finder</a>
  <input type="checkbox" id="menu"><label for="menu" class="burger">☰</label>
  <div class="links">
    <a href="<?= $base ?>index.php">Home</a>
    <a href="<?= $base ?>search.php">Find Donors</a>
    <a href="<?= $base ?>requests.php">Requests</a>
    <a href="<?= $base ?>request.php">Request Blood</a>
    <a href="<?= $base ?>contact.php">Contact</a>
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="<?= $base ?>profile.php">My Profile</a>
      <a href="<?= $base ?>logout.php">Logout</a>
    <?php else: ?>
      <a href="<?= $base ?>login.php">Login</a>
      <a class="btn small" href="<?= $base ?>register.php">Sign Up</a>
    <?php endif; ?>
  </div>
</nav>
<main class="container">
