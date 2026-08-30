// Sistema de notificaciones toast estilo React.
// Auto-detecta parámetros flash en la URL (Ejecutado/Error/success/error/...) y
// expone la API global: Toast.success(msg), Toast.error(msg), Toast.info(msg), Toast.warning(msg).
(function () {
  'use strict';

  var DURATION = 4500;
  var container = null;

  var ICONS = {
    success: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>',
    error: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>',
    warning: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg>',
    info: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></svg>'
  };

  // Parámetros de "flash message" reconocidos en la URL y su tipo de toast.
  var FLASH_KEYS = {
    Ejecutado: 'success',
    success: 'success',
    ok: 'success',
    Error: 'error',
    error: 'error',
    warning: 'warning',
    info: 'info',
    message: 'info',
    Mensaje: 'info',
    mensaje: 'info'
  };

  function ensureContainer() {
    if (container) return container;
    container = document.createElement('div');
    container.className = 'toast-container';
    container.setAttribute('popover', 'manual');
    container.tabIndex = -1;
    container.setAttribute('aria-live', 'polite');
    document.body.appendChild(container);
    return container;
  }

  // Sube la capa de toasts al "top layer" para quedar por encima
  // de cualquier <dialog> abierto (los dialogs ignora el z-index).
  function raiseLayer() {
    if (typeof container.showPopover !== 'function') return;
    var prevFocus = document.activeElement;
    var dialogOpen = !!document.querySelector('dialog[open]');
    try {
      if (dialogOpen && container.matches(':popover-open')) {
        container.hidePopover();
      }
      container.showPopover();
    } catch (e) {}
    if (prevFocus && prevFocus !== document.body && document.contains(prevFocus)) {
      prevFocus.focus({ preventScroll: true });
    }
  }

  function closeLayerIfEmpty() {
    if (!container || typeof container.hidePopover !== 'function') return;
    if (!container.querySelector('.toast')) {
      try { container.hidePopover(); } catch (e) {}
    }
  }

  function dismissToast(toast) {
    toast.classList.add('toast-leaving');
    toast.addEventListener('animationend', function () {
      toast.remove();
      closeLayerIfEmpty();
    }, { once: true });
  }

  // Pausa el temporizador de cierre (por ejemplo al hacer hover).
  function showToast(message, type) {
    type = type || 'success';
    if (!message) return;

    var el = ensureContainer();
    var toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

    toast.innerHTML =
      '<span class="toast-icon">' + ICONS[type] + '</span>' +
      '<p class="toast-message"></p>' +
      '<button type="button" class="toast-close" aria-label="Cerrar notificación">' +
        '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>' +
      '</button>' +
      '<span class="toast-progress"></span>';

    toast.querySelector('.toast-message').textContent = message;

    var timer = setTimeout(function () {
      dismissToast(toast);
    }, DURATION);

    var progress = toast.querySelector('.toast-progress');
    var onMouseEnter = function () {
      clearTimeout(timer);
      progress.style.animationPlayState = 'paused';
    };
    var onMouseLeave = function () {
      progress.style.animationPlayState = 'running';
      timer = setTimeout(function () {
        dismissToast(toast);
      }, 800);
    };

    toast.addEventListener('mouseenter', onMouseEnter);
    toast.addEventListener('mouseleave', onMouseLeave);

    toast.querySelector('.toast-close').addEventListener('click', function () {
      toast.removeEventListener('mouseenter', onMouseEnter);
      toast.removeEventListener('mouseleave', onMouseLeave);
      clearTimeout(timer);
      dismissToast(toast);
    });

    el.appendChild(toast);

    // Asegurar que la capa esté por encima de cualquier dialog abierto
    raiseLayer();

    // Fundido de entrada
    requestAnimationFrame(function () {
      toast.classList.add('toast-visible');
    });
  }

  // Lee mensajes flash desde la query string al cargar la página.
  function readFlashMessages() {
    var params = new URLSearchParams(location.search);
    Object.keys(FLASH_KEYS).forEach(function (key) {
      var value = params.get(key);
      if (value) showToast(value, FLASH_KEYS[key]);
    });
  }

  window.Toast = {
    success: function (msg) { showToast(msg, 'success'); },
    error: function (msg) { showToast(msg, 'error'); },
    info: function (msg) { showToast(msg, 'info'); },
    warning: function (msg) { showToast(msg, 'warning'); },
    show: showToast
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', readFlashMessages);
  } else {
    readFlashMessages();
  }
})();