<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h2>Connexion</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="email"    name="email"    placeholder="Email"       required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    <a href="index.php?page=register">Pas encore de compte ? S'inscrire</a>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>