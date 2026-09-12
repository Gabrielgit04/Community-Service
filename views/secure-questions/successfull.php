<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <base href="<?php echo base_url('/'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="views/assets/css/base.css" />
  <script src="views/assets/public/toasts.js"></script>
  <script src="views/assets/public/transition.js"></script>
  <title>Registro exitoso</title>
  <style>
    html, body { height: 100%; margin: 0; }
    .container {
      min-height: 100%;
      display: grid;
      place-items: center;
      padding: 24px;
      background: radial-gradient(1000px 600px at 20% 10%, #e6edf7 0%, var(--bg) 55%, #e2e9f4 100%);
      color: var(--text);
      font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .card {
      width: 100%;
      max-width: 560px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 36px;
      box-shadow: var(--shadow-lg);
    }
    .status { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .badge {
      width: 52px; height: 52px; border-radius: 50%;
      display: grid; place-items: center;
      background: #ecfdf3;
      border: 1px solid #bbf7d0;
      flex: 0 0 auto;
    }
    .badge svg { width: 26px; height: 26px; }
    .badge path { stroke: var(--success); }
    h1 { margin: 0; font-size: 1.5rem; color: var(--navy-800); }
    p { margin: 8px 0 0; color: var(--text-muted); font-size: 0.95rem; }
    .details {
      margin-top: 20px;
      padding: 14px 16px;
      border: 1px dashed var(--border-strong);
      border-radius: 12px;
      background: var(--surface-2);
      color: var(--text-muted);
      font-size: 0.92rem;
    }
    .details strong { color: var(--primary); }
    .actions { display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap; }
    .btn-primary {
      display: inline-flex; align-items: center; gap: 10px; padding: 12px 18px;
      background: var(--primary); color: #fff; border-radius: 10px;
      font-weight: 600; text-decoration: none; box-shadow: var(--shadow-sm);
      transition: background .15s, box-shadow .2s, transform .08s;
    }
    .btn-primary:hover { background: var(--primary-hover); box-shadow: var(--shadow); transform: translateY(-1px); }
    footer { margin-top: 18px; color: var(--text-muted); font-size: 0.85rem; display: flex; gap: 8px; align-items: center; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--primary); box-shadow: 0 0 0 6px rgba(26,53,119,.12); }
    @media (max-width: 420px) {
      .card { padding: 24px; }
      .badge { width: 46px; height: 46px; }
      h1 { font-size: 1.35rem; }
    }
  </style>
</head>
<body>
  <main class="container">
    <section class="card" role="status" aria-live="polite">
      <div class="status">
        <div class="badge" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M20 6L9 17l-5-5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div>
          <h1>¡Registro exitoso!</h1>
          <p>Tu cuenta ha sido creada correctamente. Ya puedes iniciar sesión y comenzar.</p>
        </div>
      </div>

      <div class="details">
        ID de usuario: <strong><?php echo $_SESSION["idRegistered"] ?></strong> · Correo verificado: <strong>sí</strong><br>
        Si no reconoces este registro, contáctanos para ayudarte.
      </div>

      <div class="actions">
        <a class="btn-primary" href="views/login/index.php" aria-label="Ir a iniciar sesión">Iniciar sesión</a>
      </div>

      <footer>
        <span class="dot" aria-hidden="true"></span>
        <span>Consejo: guarda tu correo de bienvenida, contiene pasos y enlaces útiles.</span>
      </footer>
    </section>
  </main>
</body>
</html>
