// assets/js/main.js

document.addEventListener('DOMContentLoaded', function() {
  // AOS Animate On Scroll
  if (typeof AOS !== 'undefined') {
    AOS.init();
  }

  // Checkout Success Alert
  const checkoutBtn = document.querySelector('[type="submit"]');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', function() {
      setTimeout(function(){ alert('Checkout sukses!'); }, 400);
    });
  }

  // Konfirmasi Hapus Data Admin
  const deleteButtons = document.querySelectorAll('.admin-delete');
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Hapus data ini?')) e.preventDefault();
    });
  });

  // Dark/Light mode toggle opsional
  const toggle = document.querySelector('.darkmode-toggle');
  if (toggle) {
    toggle.addEventListener('click', function() {
      document.body.classList.toggle('dark');
    });
  }
});
