<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? APP_NAME, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
</head>
<body>
<nav class="navbar">
  <a href="<?= APP_URL ?>" class="navbar-brand"><span><?= APP_NAME ?></span></a>
  <ul class="navbar-links">
    <li><a href="<?= APP_URL ?>/index.php?controller=challenge&action=index">Défis</a></li>
    <li><a href="<?= APP_URL ?>/index.php?controller=submission&action=ranking">Classement</a></li>
    <?php if (isset($_SESSION['user_id'])): ?>
      <li><a href="<?= APP_URL ?>/index.php?controller=challenge&action=create">+ Créer</a></li>
      <li><a href="<?= APP_URL ?>/index.php?controller=user&action=profile"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Profil', ENT_QUOTES, 'UTF-8') ?></a></li>
      <li><a href="<?= APP_URL ?>/index.php?controller=user&action=logout">Déconnexion</a></li>
    <?php else: ?>
      <li><a href="<?= APP_URL ?>/index.php?controller=user&action=login">Connexion</a></li>
      <li><a href="<?= APP_URL ?>/index.php?controller=user&action=register" class="btn-nav">S'inscrire</a></li>
    <?php endif; ?>
  </ul>
</nav>
<main class="container">
  <?php if (!empty($_SESSION['flash'])): $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="alert alert-<?= htmlspecialchars($f['type'], ENT_QUOTES, 'UTF-8') ?>">
      <?= $f['type'] === 'success' ? '✓' : '✕' ?>
      <?= htmlspecialchars($f['message'], ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>
  <?php require $content; ?>
</main>
<footer class="footer">&copy; <?= date('Y') ?> <?= APP_NAME ?> — Plateforme collaborative de défis créatifs</footer>
<script src="<?= APP_URL ?>/public/js/app.js"></script>
</body>
</html>
