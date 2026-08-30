// Transición suave de salida entre módulos: al hacer clic en un enlace
// interno se aplica un fundido (clase .page-leaving) antes de navegar.
(function () {
  'use strict';

  var DELAY = 160;
  var leaving = false;

  function isSkippable(link) {
    var href = link.getAttribute('href') || '';
    if (!href || href.charAt(0) === '#') return true;
    if (href.indexOf('javascript:') === 0 || href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) return true;
    if (link.hasAttribute('download') || link.target === '_blank' || link.target === '_new') return true;
    if (link.hostname && link.hostname !== location.hostname) return true;
    return false;
  }

  function leaveAndGo(link) {
    if (leaving) return;
    leaving = true;
    document.documentElement.classList.add('page-leaving');
    setTimeout(function () {
      window.location.href = link.href;
    }, DELAY);
  }

  document.addEventListener('click', function (e) {
    if (e.defaultPrevented || e.button !== 0) return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    var link = e.target && e.target.closest ? e.target.closest('a') : null;
    if (!link) return;
    if (isSkippable(link)) return;
    e.preventDefault();
    leaveAndGo(link);
  });
})();