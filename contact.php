<?php require "config.php"; $page_title = "Contact Us"; $msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $st = $conn->prepare("INSERT INTO messages(name,email,message) VALUES(?,?,?)");
  $st->bind_param("sss", $_POST['name'], $_POST['email'], $_POST['message']); $st->execute();
  $msg = "<div class='msg ok'>Thank you! Your message has been sent.</div>";
}
include "header.php"; ?>
<div class="grid">
  <div class="card"><h2>Contact Us</h2><p>📧 support@blooddonorfinder.com<br>📞 +91 98765 43210<br>📍 Department of Computer Science, Your College</p>
  <p class="muted">For medical emergencies, call 108 immediately.</p></div>
  <form class="card" method="post"><h3>Send a Message</h3><?= $msg ?>
    <label>Name</label><input name="name" required>
    <label>Email</label><input type="email" name="email" required>
    <label>Message</label><textarea name="message" rows="4" required></textarea>
    <button class="btn" style="margin-top:12px">Send</button></form>
</div>
<?php include "footer.php"; ?>
