document.addEventListener('DOMContentLoaded', function () {
  var modeButtons = document.querySelectorAll('[data-mode-btn]');
  modeButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var mode = btn.getAttribute('data-mode-btn');
      document.body.classList.toggle('mode-mayorista', mode === 'mayorista');
      document.body.setAttribute('data-mode', mode);
      modeButtons.forEach(function (b) { b.classList.toggle('active', b === btn); });
    });
  });

  function recalcRow(input) {
    var row = input.closest('tr');
    var price = parseInt(row.children[2].textContent.replace(/[^0-9]/g, ''), 10);
    var qty = parseInt(input.value, 10) || 0;
    row.children[4].textContent = '$' + (price * qty).toLocaleString('es-AR');
    recalcTotal();
  }
  function recalcTotal() {
    var rows = document.querySelectorAll('.bulk-table tbody tr:not(.bulk-total-row)');
    var total = 0;
    rows.forEach(function (r) {
      total += parseInt(r.children[4].textContent.replace(/[^0-9]/g, ''), 10) || 0;
    });
    var totalCell = document.querySelector('.bulk-total-row td:last-child');
    if (totalCell) totalCell.textContent = '$' + total.toLocaleString('es-AR');
  }
  document.querySelectorAll('.qty-input').forEach(function (inp) {
    inp.addEventListener('input', function () { recalcRow(inp); });
  });
});
