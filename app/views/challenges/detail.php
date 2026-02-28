<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-4" style="max-width:680px;">

    <!-- RETOUR -->
    <a href="index.php?page=challenges" class="btn btn-light btn-sm mb-3">
        ← Retour aux défis
    </a>

    <!-- CARD PRINCIPAL DU DÉFI -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius:14px;overflow:hidden;">

        <!-- HEADER AUTEUR -->
        <div class="card-body pb-2">
            <div class="d-flex justify-content-between align-items-center">
                <a href="index.php?page=profile&id=<?= $defi['user_id'] ?>"
                   class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                    <?php if (!empty($defi['user_photo'])): ?>
                        <img src="public/uploads/<?= htmlspecialchars($defi['user_photo']) ?>"
                             style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid #dee2e6;">
                    <?php else: ?>
                        <div style="width:48px;height:48px;border-radius:50%;
                                    background:linear-gradient(135deg,#1E3A5F,#2E6B9E);
                                    color:white;display:flex;align-items:center;
                                    justify-content:center;font-weight:bold;font-size:18px;">
                            <?= strtoupper(substr($defi['auteur'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <div class="fw-bold"><?= htmlspecialchars($defi['auteur']) ?></div>
                        <div class="text-muted" style="font-size:12px;">
                            📅 <?= date('d/m/Y', strtotime($defi['created_at'])) ?>
                        </div>
                    </div>
                </a>
                <span class="badge rounded-pill"
                      style="background:#e8f4fd;color:#2E6B9E;font-size:13px;">
                    <?= htmlspecialchars($defi['categorie']) ?>
                </span>
            </div>
        </div>

        <!-- IMAGE -->
        <?php if (!empty($defi['image'])): ?>
            <img src="public/uploads/<?= htmlspecialchars($defi['image']) ?>"
                 style="width:100%;max-height:400px;object-fit:cover;">
        <?php endif; ?>

        <!-- CONTENU -->
        <div class="card-body">
            <h5 class="fw-bold" style="color:#1E3A5F;"><?= htmlspecialchars($defi['titre']) ?></h5>
            <p class="text-muted"><?= htmlspecialchars($defi['description']) ?></p>
            <p class="fw-bold" style="color:#e67e22;font-size:14px;">
                ⏰ Date limite : <?= date('d/m/Y', strtotime($defi['date_limite'])) ?>
            </p>
        </div>

        <!-- STATS -->
        <div class="card-body border-top py-2 d-flex gap-4" style="font-size:14px;color:#666;">
            <span>👥 <strong><?= $nbPart ?></strong> participants</span>
            <span>⭐ <strong><?= $moyenne ?: '—' ?></strong>/5 (<?= $nbVotes ?> votes)</span>
            <span>💬 <strong><?= count($commentaires) ?></strong> commentaires</span>
        </div>

        <!-- BOUTONS D'ACTION -->
        <div class="card-body border-top py-2 d-flex flex-wrap gap-2">

            <?php if (isset($_SESSION['user_id']) && !$aRejoint && $_SESSION['user_id'] != $defi['user_id']): ?>
                <!-- Bouton Rejoindre → modale -->
                <button class="btn btn-success btn-sm fw-bold"
                        data-bs-toggle="modal" data-bs-target="#modalRejoindre">
                    ✅ Rejoindre ce défi
                </button>
            <?php elseif ($aRejoint): ?>
                <span class="badge bg-success p-2">✅ Tu participes !</span>
            <?php endif; ?>

            <?php if ($aRejoint && !$aVote): ?>
                <!-- Bouton Voter → modale -->
                <button class="btn btn-warning btn-sm fw-bold"
                        data-bs-toggle="modal" data-bs-target="#modalVoter">
                    ⭐ Noter ce défi
                </button>
            <?php elseif ($aVote): ?>
                <span class="badge bg-warning text-dark p-2">⭐ Déjà noté</span>
            <?php endif; ?>

            <?php if ($aRejoint): ?>
                <!-- Bouton Commenter → modale -->
                <button class="btn btn-primary btn-sm fw-bold"
                        data-bs-toggle="modal" data-bs-target="#modalCommenter">
                    💬 Commenter
                </button>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $defi['user_id']): ?>
                <!-- Bouton Supprimer → modale -->
                <button class="btn btn-danger btn-sm fw-bold"
                        data-bs-toggle="modal" data-bs-target="#modalSupprimer">
                    🗑️ Supprimer
                </button>
            <?php endif; ?>

        </div>
    </div>

    <!-- COMMENTAIRES -->
    <div class="card border-0 shadow-sm" style="border-radius:14px;">
        <div class="card-body">
            <h6 class="fw-bold mb-3" style="color:#1E3A5F;">
                💬 Expériences (<?= count($commentaires) ?>)
            </h6>

            <?php if (empty($commentaires)): ?>
                <p class="text-muted text-center py-3">
                    Aucun commentaire pour l'instant. Sois le premier !
                </p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                <?php foreach ($commentaires as $c): ?>
                    <div class="p-3 rounded-3" style="background:#f8f9fa;border:1px solid #e9ecef;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong style="color:#1E3A5F;font-size:14px;">
                                <?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?>
                            </strong>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted" style="font-size:12px;">
                                    <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?>
                                </span>
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $c['user_id']): ?>
                                    <a href="index.php?page=deleteComment&id=<?= $c['id'] ?>&challenge_id=<?= $defi['id'] ?>"
                                       onclick="return confirm('Supprimer ce commentaire ?')"
                                       class="btn btn-outline-danger btn-sm py-0 px-1"
                                       style="font-size:11px;">🗑️</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p class="mb-0" style="font-size:14px;color:#444;">
                            <?= htmlspecialchars($c['contenu']) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ═══════════════ MODALES ═══════════════ -->

<!-- MODALE REJOINDRE -->
<div class="modal fade" id="modalRejoindre" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:#1E3A5F;">
                <h5 class="modal-title text-white fw-bold">✅ Rejoindre ce défi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-5">Tu es sur le point de rejoindre :</p>
                <h5 class="fw-bold" style="color:#1E3A5F;">
                    "<?= htmlspecialchars($defi['titre']) ?>"
                </h5>
                <p class="text-muted mt-2">
                    Tu pourras ensuite voter et partager ton expérience !
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-3">
                <button class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                <a href="index.php?page=join&id=<?= $defi['id'] ?>"
                   class="btn btn-success px-4 fw-bold">
                    ✅ Confirmer
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MODALE VOTER -->
<div class="modal fade" id="modalVoter" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:#1E3A5F;">
                <h5 class="modal-title text-white fw-bold">⭐ Noter ce défi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?page=vote">
                <input type="hidden" name="challenge_id" value="<?= $defi['id'] ?>">
                <div class="modal-body text-center py-4">
                    <p class="text-muted mb-3">Quelle note donnes-tu à ce défi ?</p>
                    <div class="d-flex justify-content-center gap-3 fs-3">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input d-none" type="radio"
                                       name="note" id="note<?= $i ?>" value="<?= $i ?>" required>
                                <label class="form-check-label star-label" for="note<?= $i ?>"
                                       style="cursor:pointer;font-size:32px;color:#ccc;transition:color 0.2s;">
                                    ★
                                </label>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <p class="text-muted mt-2" id="noteLabel">Clique sur une étoile</p>
                </div>
                <div class="modal-footer border-0 justify-content-center gap-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold">
                        ⭐ Envoyer ma note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODALE COMMENTER -->
<div class="modal fade" id="modalCommenter" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:#1E3A5F;">
                <h5 class="modal-title text-white fw-bold">💬 Partage ton expérience</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?page=comment">
                <input type="hidden" name="challenge_id" value="<?= $defi['id'] ?>">
                <div class="modal-body">
                    <textarea name="contenu" class="form-control" rows="4"
                              placeholder="Comment ça se passe pour toi ? Partage tes difficultés, astuces..."
                              required></textarea>
                </div>
                <div class="modal-footer border-0 justify-content-center gap-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        💬 Publier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODALE SUPPRIMER -->
<div class="modal fade" id="modalSupprimer" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white fw-bold">🗑️ Supprimer ce défi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-5">Es-tu sûr de vouloir supprimer :</p>
                <h5 class="fw-bold text-danger">
                    "<?= htmlspecialchars($defi['titre']) ?>"
                </h5>
                <p class="text-muted mt-2">Cette action est irréversible !</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-3">
                <button class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                <a href="index.php?page=delete&id=<?= $defi['id'] ?>"
                   class="btn btn-danger px-4 fw-bold">
                    🗑️ Oui, supprimer
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JS étoiles interactives -->
<script>
const labels = document.querySelectorAll('.star-label');
const noteLabel = document.getElementById('noteLabel');
const notes = ['', 'Très mauvais 😞', 'Mauvais 😐', 'Moyen 🙂', 'Bien 😊', 'Excellent 🔥'];

labels.forEach((label, index) => {
    label.addEventListener('mouseover', () => {
        labels.forEach((l, i) => {
            l.style.color = i <= index ? '#f59e0b' : '#ccc';
        });
        noteLabel.textContent = notes[index + 1];
    });

    label.addEventListener('click', () => {
        labels.forEach((l, i) => {
            l.style.color = i <= index ? '#f59e0b' : '#ccc';
        });
        noteLabel.textContent = '✅ ' + notes[index + 1];
    });
});

document.querySelector('.modal').addEventListener('mouseleave', () => {
    const checked = document.querySelector('input[name="note"]:checked');
    if (!checked) {
        labels.forEach(l => l.style.color = '#ccc');
        noteLabel.textContent = 'Clique sur une étoile';
    }
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>