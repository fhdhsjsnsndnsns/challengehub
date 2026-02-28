<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-4" style="max-width: 680px;">

    <h4 class="fw-bold mb-3" style="color:#1E3A5F;">🏆 Tous les défis</h4>

    <!-- Recherche et filtres -->
    <form method="GET" action="index.php" class="card border-0 shadow-sm p-3 mb-4">
        <input type="hidden" name="page" value="challenges">
        <div class="row g-2">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="🔍 Rechercher..."
                       value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <div class="col-12 col-md-4">
                <input type="text" name="categorie" class="form-control"
                       placeholder="🏷️ Catégorie..."
                       value="<?= htmlspecialchars($categorie ?? '') ?>">
            </div>
            <div class="col-12 col-md-3">
                <select name="tri" class="form-select">
                    <option value="popularite" <?= ($tri ?? '') === 'popularite' ? 'selected' : '' ?>>🔥 Popularité</option>
                    <option value="date"        <?= ($tri ?? '') === 'date'       ? 'selected' : '' ?>>🕐 Plus récent</option>
                    <option value="votes"       <?= ($tri ?? '') === 'votes'      ? 'selected' : '' ?>>⭐ Mieux notés</option>
                    <option value="participants" <?= ($tri ?? '') === 'participants' ? 'selected' : '' ?>>👥 Participants</option>
                </select>
            </div>
            <div class="col-12 col-md-1 d-grid">
                <button type="submit" class="btn btn-primary">OK</button>
            </div>
        </div>
    </form>

    <!-- Feed -->
    <?php if (empty($defis)): ?>
        <div class="text-center py-5 card border-0 shadow-sm">
            <p class="text-muted fs-5">😴 Aucun défi trouvé.</p>
            <a href="index.php?page=create" class="btn btn-warning fw-bold mx-auto" style="width:fit-content;">
                ✨ Créer le premier défi
            </a>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
        <?php foreach ($defis as $defi): ?>

            <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">

                <!-- HEADER -->
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="index.php?page=profile&id=<?= $defi['user_id'] ?>"
                           class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                            <?php if (!empty($defi['user_photo'])): ?>
                                <img src="public/uploads/<?= htmlspecialchars($defi['user_photo']) ?>"
                                     style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:2px solid #dee2e6;">
                            <?php else: ?>
                                <div style="width:44px;height:44px;border-radius:50%;
                                            background:linear-gradient(135deg,#1E3A5F,#2E6B9E);
                                            color:white;display:flex;align-items:center;
                                            justify-content:center;font-weight:bold;font-size:16px;">
                                    <?= strtoupper(substr($defi['auteur'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <div class="fw-bold" style="font-size:14px;"><?= htmlspecialchars($defi['auteur']) ?></div>
                                <div class="text-muted" style="font-size:12px;">
                                    🕐 <?= date('d/m/Y', strtotime($defi['created_at'])) ?>
                                </div>
                            </div>
                        </a>
                        <span class="badge rounded-pill"
                              style="background:#e8f4fd;color:#2E6B9E;font-size:12px;">
                            <?= htmlspecialchars($defi['categorie']) ?>
                        </span>
                    </div>
                </div>

                <!-- IMAGE -->
                <?php if (!empty($defi['image'])): ?>
                    <img src="public/uploads/<?= htmlspecialchars($defi['image']) ?>"
                         style="width:100%;max-height:380px;object-fit:cover;">
                <?php endif; ?>

                <!-- CONTENU -->
                <div class="card-body pt-2 pb-1">
                    <h6 class="fw-bold mb-1" style="color:#1E3A5F;font-size:16px;">
                        <?= htmlspecialchars($defi['titre']) ?>
                    </h6>
                    <p class="text-muted mb-1" style="font-size:14px;">
                        <?= htmlspecialchars(substr($defi['description'], 0, 120)) ?>...
                    </p>
                    <p class="mb-0" style="font-size:13px;color:#e67e22;font-weight:600;">
                        ⏰ Date limite : <?= date('d/m/Y', strtotime($defi['date_limite'])) ?>
                    </p>
                </div>

                <!-- STATS -->
                <div class="card-body py-2 border-top d-flex gap-3" style="font-size:13px;color:#888;">
                    <span>👥 <?= $defi['nb_participants'] ?> participants</span>
                    <span>⭐ <?= $defi['moyenne_votes'] ?: '—' ?>/5</span>
                    <span>💬 <?= $defi['nb_comments'] ?> commentaires</span>
                </div>

                <!-- ACTIONS -->
                <div class="card-body py-2 border-top d-flex gap-2">
                    <a href="index.php?page=challenge&id=<?= $defi['id'] ?>"
                       class="btn btn-primary btn-sm">
                         Voir le défi
                    </a>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $defi['user_id']): ?>
                        <a href="index.php?page=delete&id=<?= $defi['id'] ?>"
                           onclick="return confirm('Supprimer ce défi ?')"
                           class="btn btn-danger btn-sm">
                             Supprimer
                        </a>
                    <?php endif; ?>
                </div>

            </div>

        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>