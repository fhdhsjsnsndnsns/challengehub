<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h2>🏆 Tous les défis</h2>

    <!-- Recherche et filtres -->
    <form method="GET" action="index.php">
        <input type="hidden" name="page" value="challenges">
        <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
        <select name="categorie">
            <option value="">Toutes les catégories</option>
            <option value="Sport"        <?= $categorie === 'Sport' ? 'selected' : '' ?>>Sport</option>
            <option value="Alimentation" <?= $categorie === 'Alimentation' ? 'selected' : '' ?>>Alimentation</option>
            <option value="Habitudes"    <?= $categorie === 'Habitudes' ? 'selected' : '' ?>>Habitudes</option>
            <option value="Mental"       <?= $categorie === 'Mental' ? 'selected' : '' ?>>Mental</option>
        </select>
        <button type="submit">Filtrer</button>
    </form>

    <!-- Liste des défis -->
    <?php if (empty($defis)): ?>
        <p>Aucun défi trouvé. <a href="index.php?page=create">Sois le premier à en créer un !</a></p>
    <?php else: ?>
        <div class="grid">
        <?php foreach ($defis as $defi): ?>
            <div class="card">
                <h3><?= htmlspecialchars($defi['titre']) ?></h3>
                <span class="badge"><?= htmlspecialchars($defi['categorie']) ?></span>
                <p><?= htmlspecialchars(substr($defi['description'], 0, 100)) ?>...</p>
                <p>👤 <?= htmlspecialchars($defi['auteur']) ?> &nbsp;|&nbsp;
                   📅 <?= $defi['date_limite'] ?> &nbsp;|&nbsp;
                   👥 <?= $defi['nb_participants'] ?> participants &nbsp;|&nbsp;
                   ❤️ <?= $defi['nb_votes'] ?> votes</p>
                <a href="index.php?page=challenge&id=<?= $defi['id'] ?>">Voir le défi →</a>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $defi['user_id']): ?>
                    <a href="index.php?page=delete&id=<?= $defi['id'] ?>"
                       onclick="return confirm('Supprimer ce défi ?')">🗑️ Supprimer</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>