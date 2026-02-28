<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChallengeHub 🏆</title>
    <link rel="stylesheet" href="public/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=challenges">🏆 ChallengeHub</a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a href="index.php?page=profile&id=<?= $_SESSION['user_id'] ?>"
                           class="nav-link d-flex align-items-center gap-2">
                            <?php if (!empty($_SESSION['user_photo'])): ?>
                                <img src="public/uploads/<?= htmlspecialchars($_SESSION['user_photo']) ?>"
                                     class="avatar">
                            <?php else: ?>
                                <div class="avatar-placeholder" style="width:36px;height:36px;font-size:14px;">
                                    <?= strtoupper(substr($_SESSION['user_nom'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <span><?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=create" class="btn btn-orange px-3 py-2">
                            ✚ Créer un défi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=logout" class="nav-link">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="index.php?page=login" class="nav-link">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=register" class="btn btn-orange px-3 py-2">
                            S'inscrire
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>