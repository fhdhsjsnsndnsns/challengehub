<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h2>Créer un compte</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text"     name="nom"      placeholder=" Nom"    required> 
        <input type="text"     name="prenom"      placeholder=" Pénom"    required>
        <input type="email"    name="email"    placeholder="Email"      required>
        <input type="password" name="password" placeholder="Mot de passe (6 car. min)" required>
        <button type="submit">S'inscrire</button>
    </form>
    <a href="index.php?page=login">Déjà un compte ? Connecte-toi</a>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>