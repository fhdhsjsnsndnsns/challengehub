<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? APP_NAME, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
</head>
<body>

    <!-- ── Navigation ── -->
    <nav class="navbar">
        <a href="<?= APP_URL ?>" class="navbar-brand"><?= APP_NAME ?></a>
        <ul class="navbar-links">
            <li><a href="<?= APP_URL ?>/index.php?controller=challenge&action=index">Défis</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?= APP_URL ?>/index.php?controller=challenge&action=create">+ Créer un défi</a></li>
                <li><a href="<?= APP_URL ?>/index.php?controller=user&action=profile">Mon profil</a></li>
                <li><a href="<?= APP_URL ?>/index.php?controller=user&action=logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="<?= APP_URL ?>/index.php?controller=user&action=login">Connexion</a></li>
                <li><a href="<?= APP_URL ?>/index.php?controller=user&action=register">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- ── Contenu principal ── -->
    <main class="container">
        <?php
        // Flash messages (succès / erreur)
        if (!empty($_SESSION['flash'])):
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
        ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php require $content; ?>
    </main>

    <!-- ── Footer ── -->
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?> — Plateforme collaborative de défis créatifs</p>
    </footer>

    <script src="<?= APP_URL ?>/public/js/app.js"></script>
</body>
</html>
