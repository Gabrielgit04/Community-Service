<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <base href="<?php echo base_url('/'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="views/assets/css/base.css" />
  <script src="views/assets/public/transition.js"></script>
  <title>Contraseña actualizada</title>
  <style>
    html, body { height: 100%; margin: 0; }
    .container {
      min-height: 100%;
      display: grid;
      place-items: center;
      padding: 24px;
      background: radial-gradient(1000px 600px at 20% 10%, #e6edf7 0%, var(--bg) 55%, #e2e9f4 100%);
    }
    .card {
      width: 100%;
      max-width: 560px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 40px;
      box-shadow: var(--shadow-lg);
      text-align: center;
    }
    .icon-wrap {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: #ecfdf3;
      border: 1px solid #bbf7d0;
      margin-bottom: 16px;
    }
    .title {
      font-size: clamp(1.5rem, 2.2vw, 2rem);
      margin: 0 0 8px;
      color: var(--navy-800);
    }
    .subtitle { margin: 0 0 24px; color: var(--text-muted); font-size: 0.98rem; }
    .details {
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 16px 18px;
      margin-bottom: 24px;
      color: var(--text-muted);
      font-size: 0.95rem;
    }
    .actions { display: grid; grid-template-columns: 1fr; gap: 12px; }
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 13px 16px;
      border-radius: 10px;
      border: none;
      background: var(--primary);
      color: #fff;
      font-weight: 600;
      font-size: 0.98rem;
      text-decoration: none;
      cursor: pointer;
      box-shadow: var(--shadow-sm);
      transition: background .15s, box-shadow .2s, transform .08s;
    }
    .btn:hover { background: var(--primary-hover); box-shadow: var(--shadow); transform: translateY(-1px); }
    footer { margin-top: 20px; color: var(--text-muted); font-size: 0.9rem; }
  </style>
</head>
<body>
  <main class="container" role="main">
    <section class="card" aria-labelledby="titulo">
      <div class="icon-wrap" aria-hidden="true">
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M20 7L9 18l-5-5" stroke="var(--success)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <h1 id="titulo" class="title">Tu contraseña se actualizó correctamente</h1>
      <p class="subtitle">Por seguridad, cerramos tu sesión anterior. Usa tu nueva contraseña al iniciar sesión.</p>

      <div class="details" role="status" aria-live="polite">
        <p><strong>Estado:</strong> Cambio confirmado. Si no fuiste tú, restablece tu contraseña nuevamente y contáctanos.</p>
      </div>

      <div class="actions">
        <a class="btn" href="views/login/index.php" aria-label="Ir a iniciar sesión">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M10 17l5-5-5-5M15 12H3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M21 21V3" stroke="white" stroke-opacity="0.6" stroke-width="2" stroke-linecap="round"/>
          </svg>
          Iniciar sesión
        </a>
      </div>

      <footer>
        <span>¿Necesitas ayuda? Comunícate con soporte.</span>
      </footer>
    </section>
  </main>
</body>
</html>
