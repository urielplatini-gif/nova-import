document.addEventListener('DOMContentLoaded', function () {
  /* Menú del celular */
  var toggle = document.querySelector('.nav-toggle');
  var panel = document.getElementById('mobile-panel');
  if (toggle && panel) {
    var setOpen = function (open) {
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
      panel.hidden = !open;
      document.body.classList.toggle('menu-open', open);
    };
    toggle.addEventListener('click', function () {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });
    panel.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () { setOpen(false); });
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setOpen(false);
    });
  }

  /* Buscador por medida de la portada */
  var finder = document.querySelector('[data-finder]');
  if (finder) {
    var data = {};
    try { data = JSON.parse(finder.querySelector('[data-finder-json]').textContent); } catch (e) { data = {}; }
    var tabs = finder.querySelectorAll('[data-finder-tipo]');
    var rubroSel = finder.querySelector('[data-finder-rubro]');
    var medidaSel = finder.querySelector('[data-finder-medida]');
    var filtroLabel = finder.querySelector('[data-finder-filtro-label]');
    var go = finder.querySelector('[data-finder-go]');
    var tipo = tabs.length ? tabs[0].getAttribute('data-finder-tipo') : 'bici';

    var updateGo = function () { if (medidaSel.value) go.href = medidaSel.value; };
    var fillMedidas = function () {
      var r = (data[tipo] || [])[rubroSel.value];
      medidaSel.innerHTML = '';
      if (!r) return;
      filtroLabel.textContent = r.filtro;
      var all = document.createElement('option');
      all.value = r.all;
      all.textContent = 'Todas';
      medidaSel.appendChild(all);
      r.options.forEach(function (op) {
        var o = document.createElement('option');
        o.value = op.url;
        o.textContent = op.label;
        medidaSel.appendChild(o);
      });
      updateGo();
    };
    var fillRubros = function () {
      rubroSel.innerHTML = '';
      (data[tipo] || []).forEach(function (r, i) {
        var o = document.createElement('option');
        o.value = i;
        o.textContent = r.rubro;
        rubroSel.appendChild(o);
      });
      fillMedidas();
    };

    tabs.forEach(function (btn) {
      btn.addEventListener('click', function () {
        tipo = btn.getAttribute('data-finder-tipo');
        tabs.forEach(function (b) {
          b.classList.toggle('active', b === btn);
          b.setAttribute('aria-selected', b === btn ? 'true' : 'false');
        });
        fillRubros();
      });
    });
    rubroSel.addEventListener('change', fillMedidas);
    medidaSel.addEventListener('change', updateGo);
    fillRubros();
  }
  /* Checkout/carrito: Vía Cargo se paga en destino, no figura como "Gratis" en el resumen */
  var fixViaCargo = function () {
    document.querySelectorAll('.wc-block-components-totals-shipping .wc-block-components-totals-item').forEach(function (item) {
      var label = item.querySelector('.wc-block-components-totals-item__label');
      var value = item.querySelector('.wc-block-components-totals-item__value');
      if (label && value && /V[ií]a Cargo/i.test(label.textContent) && value.textContent.trim() !== 'Se abona en destino') {
        value.textContent = 'Se abona en destino';
      }
    });
  };
  if (document.querySelector('.wp-block-woocommerce-checkout, .wp-block-woocommerce-cart')) {
    new MutationObserver(fixViaCargo).observe(document.body, { childList: true, subtree: true, characterData: true });
    fixViaCargo();
  }
});
