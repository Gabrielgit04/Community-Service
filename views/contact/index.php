<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';

requireLogin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <base href="<?php echo base_url('/'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="views/assets/css/base.css" />
  <script src="views/assets/public/toasts.js"></script>
  <script src="views/assets/public/field-errors.js"></script>
  <script src="views/assets/public/transition.js"></script>
  <title>Contacto</title>
  <style>
    body {
      margin: 0;
      background: radial-gradient(1200px 500px at 10% 10%, #e6edf7 0%, var(--bg) 45%, #e2e9f4 100%);
      color: var(--text);
      font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, sans-serif;
      line-height: 1.5;
    }
    .wrap { max-width: 960px; margin: 0 auto; padding: 40px 20px 56px; }
    header { text-align: center; margin-bottom: 28px; }
    header h1 { margin: 0 0 8px; font-weight: 700; color: var(--navy-800); font-size: 2rem; }
    header p { margin: 0; color: var(--text-muted); }
    .grid { display: grid; grid-template-columns: 1.15fr 1fr; gap: 24px; }
    @media (max-width: 820px) { .grid { grid-template-columns: 1fr; } }
    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 18px;
      box-shadow: var(--shadow-lg);
      overflow: hidden;
    }
    .card .body { padding: 24px; }
    .card h2 { margin: 0 0 16px; font-size: 1.15rem; color: var(--navy-800); }
    .contact-info { padding: 18px 24px; border-top: 1px solid var(--border); display: grid; gap: 10px; }
    .contact-item { display: flex; gap: 10px; align-items: center; color: var(--text-muted); font-size: 0.95rem; }
    .contact-item strong { color: var(--text); }
    .contact-item .dot {
      width: 9px; height: 9px; border-radius: 50%;
      background: var(--primary); box-shadow: 0 0 0 4px rgba(26,53,119,.12);
      flex: 0 0 9px;
    }
    form { display: grid; gap: 14px; }
    .field { display: grid; gap: 6px; }
    label { font-size: 0.9rem; color: var(--text-muted); }
    .row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 640px) { .row { grid-template-columns: 1fr; } }
    input[type="text"], input[type="email"], input[type="tel"], select, textarea {
      width: 100%; padding: 12px;
      border-radius: 10px;
      border: 1px solid var(--border);
      background: var(--surface-2);
      color: var(--text);
      outline: none;
      transition: border-color 160ms, box-shadow 160ms;
      font-family: inherit;
    }
    textarea { min-height: 140px; resize: none; }
    input::placeholder, textarea::placeholder { color: var(--text-soft); }
    input:focus, select:focus, textarea:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(26,53,119,.12);
    }
    .checkbox { display: flex; gap: 10px; align-items: flex-start; padding-top: 4px; color: var(--text-muted); font-size: 0.9rem; }
    .actions { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-top: 6px; }
    .btn {
      display: inline-flex; align-items: center; gap: 10px; padding: 12px 18px;
      border-radius: 12px; border: none;
      background: var(--primary); color: #fff; font-weight: 600; cursor: pointer;
      box-shadow: var(--shadow-sm);
      transition: background .15s, box-shadow .15s, transform .08s;
    }
    .btn:hover { background: var(--primary-hover); box-shadow: var(--shadow); }
    .btn:active { transform: translateY(1px) scale(.995); }
    .note { color: var(--text-muted); font-size: 0.85rem; }
    .back {
      width: 40px; height: 40px; margin-left: 24px; margin-bottom: 24px;
      background: var(--surface-2); border: 1px solid var(--border); border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      transition: transform .15s;
    }
    .back:hover { transform: scale(1.08); }
    .back img { width: 20px; height: 20px; filter: brightness(0) saturate(100%) invert(34%) sepia(12%) saturate(1200%) hue-rotate(200deg) brightness(92%); }
  </style>
</head>
<body>
  <main class="wrap">
    <header>
      <h1>Hablemos</h1>
      <p>Cuéntanos qué necesitas y te responderemos lo antes posible.</p>
    </header>

    <div class="grid">
      <section class="card" aria-labelledby="form-title">
        <div class="body">
          <h2 id="form-title">Formulario de contacto</h2>

          <form action="https://formspree.io/f/xovrzvra" method="post" novalidate>
            <div class="row">
              <div class="field">
                <label for="name">Nombre completo</label>
                <input id="name" name="name" type="text" placeholder="Tu nombre" autocomplete="name" required />
              </div>
              <div class="field">
                <label for="email">Correo electrónico</label>
                <input id="email" name="email" type="email" placeholder="tucorreo@ejemplo.com" autocomplete="email" required />
              </div>
            </div>

            <div class="row">
              <div class="field">
                <label for="phone">Teléfono (opcional)</label>
                <input id="phone" name="phone" type="tel" placeholder="+58 400 000 0000" autocomplete="tel" />
              </div>
              <div class="field">
                <label for="subject">Asunto</label>
                <select id="subject" name="subject" required>
                  <option value="" disabled selected>Selecciona una opción</option>
                  <option>Consulta</option>
                  <option>Soporte</option>
                  <option>Colaboración</option>
                  <option>Otro</option>
                </select>
              </div>
            </div>

            <div class="field">
              <label for="message">Mensaje</label>
              <textarea id="message" name="message" placeholder="Cuéntanos los detalles..." required></textarea>
            </div>

            <div class="checkbox">
              <input id="privacy" name="privacy" type="checkbox" required aria-describedby="privacy-desc" />
              <label for="privacy">Acepto el uso de mis datos para responder a esta solicitud.</label>
            </div>
            <p id="privacy-desc" class="note">Tus datos no se compartirán con terceros.</p>

            <div class="actions">
              <button class="btn" type="submit">Enviar mensaje</button>
              <span class="note">Tiempo de respuesta habitual: 24–48 h.</span>
            </div>
          </form>
        </div>
      </section>

      <aside class="card" aria-labelledby="info-title">
        <div class="body">
          <h2 id="info-title">Información</h2>
          <p class="note">Si prefieres, también puedes escribirnos directamente o visitar nuestras redes.</p>

          <div class="contact-info" role="list">
            <div class="contact-item" role="listitem">
              <span class="dot" aria-hidden="true"></span>
              <span><strong>Email:</strong> gabrielmoises1202@gmail.com</span>
            </div>
            <div class="contact-item" role="listitem">
              <span class="dot" aria-hidden="true"></span>
              <span><strong>Teléfono:</strong> +58 412 656 2412</span>
            </div>
            <div class="contact-item" role="listitem">
              <span class="dot" aria-hidden="true"></span>
              <span><strong>Dirección:</strong> Cardón, Falcón, Venezuela</span>
            </div>
          </div>
        </div>
        <a href="views/main-menu/index.php" class="back">
          <img src="views/assets/imgs/icons/nav/arrow-left.svg" alt="volver">
        </a>
      </aside>
    </div>
  </main>

  <script>
    document.querySelector('form').addEventListener('submit', function (e) {
      FieldErrors.clearAll();
      let invalid = false;

      const name = document.getElementById('name');
      if (!name.value.trim() || name.value.trim().length < 2) {
        FieldErrors.show('name', 'Ingrese su nombre completo.');
        invalid = true;
      }
      const email = document.getElementById('email');
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email.value.trim())) {
        FieldErrors.show('email', 'Ingrese un correo electrónico válido.');
        invalid = true;
      }
      const subject = document.getElementById('subject');
      if (!subject.value) {
        FieldErrors.show('subject', 'Seleccione un asunto.');
        invalid = true;
      }
      const message = document.getElementById('message');
      if (!message.value.trim() || message.value.trim().length < 10) {
        FieldErrors.show('message', 'Escriba un mensaje de al menos 10 caracteres.');
        invalid = true;
      }
      const privacy = document.getElementById('privacy');
      if (!privacy.checked) {
        FieldErrors.show('privacy', 'Debe aceptar el uso de sus datos.');
        invalid = true;
      }

      if (invalid) {
        e.preventDefault();
        Toast.error('Revisa los campos marcados en el formulario.');
      } else {
        Toast.info('Enviando tu mensaje…');
      }
    });
  </script>
</body>
</html>
