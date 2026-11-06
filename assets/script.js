// Simple client-side form validation
document.querySelectorAll("form[data-validate]").forEach(function (form) {
  form.addEventListener("submit", function (ev) {
    var phone = form.querySelector("[name=phone]");
    if (phone && !/^[0-9]{10}$/.test(phone.value.trim())) { alert("Please enter a valid 10-digit phone number."); ev.preventDefault(); return; }
    var age = form.querySelector("[name=age]");
    if (age && (age.value < 18 || age.value > 65)) { alert("Donor age must be between 18 and 65."); ev.preventDefault(); return; }
    var p = form.querySelector("[name=password]"), c = form.querySelector("[name=confirm]");
    if (p && c && p.value !== c.value) { alert("Passwords do not match."); ev.preventDefault(); }
  });
});
document.querySelectorAll("[data-confirm]").forEach(function (el) {
  el.addEventListener("click", function (ev) { if (!confirm(el.dataset.confirm)) ev.preventDefault(); });
});
