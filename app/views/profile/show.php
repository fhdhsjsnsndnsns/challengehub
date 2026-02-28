<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="profile-container">

    <!-- HEADER PROFIL -->
    <div class="profile-header">

    <!-- PHOTO -->
    <div class="profile-avatar">
        <?php if (!empty($profil['photo'])): ?>
            <img src="public/uploads/<?= htmlspecialchars($profil['photo']) ?>" alt="Photo de profil">
        <?php else: ?>
            <div class="avatar-placeholder">
                <?= strtoupper(substr($profil['prenom'], 0, 1) . substr($profil['nom'], 0, 1)) ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- INFOS -->
    <div class="profile-info">

        <!-- NOM + BOUTON sur la même ligne -->
        <div class="profile-top">
            <h2><?= htmlspecialchars($profil['prenom'] . '.' . $profil['nom']) ?></h2>
            <?php if ($isOwnProfile): ?>
                <a href="index.php?page=editProfile" class="btn-profile-action">Modifier le profil</a>
            <?php else: ?>
                <a href="index.php?page=follow&id=<?= $profil['id'] ?>" 
                   class="<?= $isFollowing ? 'btn-profile-action' : 'btn-profile-follow' ?>">
                    <?= $isFollowing ? '✅ Abonné' : '➕ Suivre' ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- STATS -->
        <div class="profile-stats">
            <div class="stat">
                <strong><?= $nbDefis ?></strong>
                <span>défis</span>
            </div>
            <div class="stat">
                <strong><?= $nbFollowers ?></strong>
                <span>abonnés</span>
            </div>
            <div class="stat">
                <strong><?= $nbFollowing ?></strong>
                <span>abonnements</span>
            </div>
        </div>

    </div>
</div>

    <!-- DÉFIS PAR CATÉGORIE -->
    <div class="profile-defis">
        <?php if (empty($defisGroupes)): ?>
            <div class="empty-state">
                <p>😴 Aucun défi publié pour l'instant.</p>
                <?php if ($isOwnProfile): ?>
                    <a href="index.php?page=create" class="btn">✨ Créer mon premier défi</a>
                <?php endif; ?>
            </div>
        <?php else: ?>

            <!-- ONGLETS CATÉGORIES -->
            <div class="category-tabs">
                <?php $first = true; foreach ($defisGroupes as $categorie => $defis): ?>
                    <button class="tab-btn <?= $first ? 'active' : '' ?>"
                            onclick="showCategory('<?= $categorie ?>', this)">
                        <?php
                            $icons = [
                                'Sport'        => '🏃',
                                'Alimentation' => '🥗',
                                'Habitudes'    => '⚡',
                                'Mental'       => '🧘'
                            ];
                            echo ($icons[$categorie] ?? '🎯') . ' ' . $categorie;
                        ?>
                        <span class="tab-count"><?= count($defis) ?></span>
                    </button>
                <?php $first = false; endforeach; ?>
            </div>

            <!-- CONTENU PAR CATÉGORIE -->
            <?php $first = true; foreach ($defisGroupes as $categorie => $defis): ?>
                <div class="category-content <?= $first ? 'active' : '' ?>" 
                     id="cat-<?= $categorie ?>">
                    <div class="defis-grid">
                        <?php foreach ($defis as $defi): ?>
                            <div class="defi-card">
                                <div class="defi-card-header">
                                    <h3><?= htmlspecialchars($defi['titre']) ?></h3>
                                    <span class="defi-date">📅 <?= $defi['date_limite'] ?></span>
                                </div>
                                <p><?= htmlspecialchars(substr($defi['description'], 0, 90)) ?>...</p>
                                <div class="defi-card-stats">
                                    <span>👥 <?= $defi['nb_participants'] ?></span>
                                    <span>⭐ <?= $defi['moyenne_votes'] ?: '—' ?>/5</span>
                                    <span>💬 <?= $defi['nb_comments'] ?></span>
                                </div>
                                <div class="defi-card-actions">
                                    <a href="index.php?page=challenge&id=<?= $defi['id'] ?>" class="btn-sm">
                                        Voir →
                                    </a>
                                    <?php if ($isOwnProfile): ?>
                                        <a href="index.php?page=delete&id=<?= $defi['id'] ?>"
                                           onclick="return confirm('Supprimer ?')" class="btn-sm btn-danger">
                                            supprimer
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php $first = false; endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<script>
function showCategory(cat, btn) {
    // Cacher tous les contenus
    document.querySelectorAll('.category-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    // Afficher le bon
    document.getElementById('cat-' + cat).classList.add('active');
    btn.classList.add('active');
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>