// Errores por campo: muestra el mensaje debajo del input/select/textarea
// y marca el borde en rojo. API global: FieldErrors.show(name, msg), clear(name), clearAll().
(function () {
  'use strict';

  function findField(name) {
    if (!name) return null;
    return document.querySelector('[name="' + name + '"]') ||
           document.querySelector('#' + name);
  }

  function errorSelector(id) {
    return '[data-field-error-for="' + String(id).replace(/"/g, '\\"') + '"]';
  }

  function removeExisting(field) {
    var id = field.id || field.name;
    var prev = document.querySelector(errorSelector(id));
    if (prev) prev.remove();
  }

  function show(name, message) {
    var field = findField(name);
    if (!field) return;
    field.classList.add('input-error');
    removeExisting(field);
    var id = field.id || field.name;
    var error = document.createElement('small');
    error.className = 'field-error-text';
    error.setAttribute('data-field-error-for', String(id));
    error.setAttribute('role', 'alert');
    error.textContent = message;
    var anchor = (field.type === 'checkbox' || field.type === 'radio') && field.parentElement
      ? field.parentElement
      : field;
    anchor.insertAdjacentElement('afterend', error);
  }

  function clear(name) {
    var field = findField(name);
    if (!field) return;
    field.classList.remove('input-error');
    removeExisting(field);
  }

  function clearAll() {
    document.querySelectorAll('.input-error').forEach(function (el) {
      el.classList.remove('input-error');
    });
    document.querySelectorAll('.field-error-text').forEach(function (el) {
      el.remove();
    });
  }

  // Limpia el error de un campo cuando el usuario vuelve a teclear/editar.
  function attachAutoClear() {
    document.addEventListener('input', function (e) {
      var target = e.target;
      if (!target || !target.matches('input, select, textarea')) return;
      if (target.classList.contains('input-error')) {
        clear(target.name || target.id);
      }
    });
  }

  window.FieldErrors = { show: show, clear: clear, clearAll: clearAll };
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachAutoClear);
  } else {
    attachAutoClear();
  }
})();