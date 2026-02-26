<div class="sub-detail">
  <div class="sub-detail__header">
    <a href="<?= APP_URL ?>/index.php?controller=challenge&action=show&id=<?= $sub['challenge_id'] ?>" class="back-link">← <?= htmlspecialchars($sub['challenge_title'], ENT_QUOTES, 'UTF-8') ?></a>
    <h1>Participation de <?= htmlspecialchars($sub['author_name'], ENT_QUOTES, 'UTF-8') ?></h1>
    <span class="muted"><?= date('d/m/Y à H:i', strtotime($sub['created_at'])) ?></span>
  </div>

  <?php if ($sub['media'] && filter_var($sub['media'], FILTER_VALIDATE_URL)): ?>
    <a href="<?= htmlspecialchars($sub['media'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn--outline" style="margin-bottom:1rem">🔗 Voir le lien externe</a>
  <?php elseif ($sub['media']): ?>
    <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($sub['media'], ENT_QUOTES, 'UTF-8') ?>" alt="média" class="sub-detail__img">
  <?php endif; ?>

  <div class="sub-detail__desc"><?= nl2br(htmlspecialchars($sub['description'], ENT_QUOTES, 'UTF-8')) ?></div>

  <div class="sub-detail__actions">
    <button id="voteBtn" class="vote-btn vote-btn--lg <?= $hasVoted ? 'vote-btn--active' : '' ?>"
      data-id="<?= $sub['id'] ?>" data-csrf="<?= $csrf ?>"
      <?= empty($_SESSION['user_id']) ? 'disabled title="Connectez-vous pour voter"' : '' ?>>
      ❤️ <span class="vote-count"><?= $sub['vote_count'] ?></span> vote(s)
    </button>
    <?php if ($isOwner): ?>
      <a href="<?= APP_URL ?>/index.php?controller=submission&action=edit&id=<?= $sub['id'] ?>" class="btn btn--outline">Modifier</a>
      <form method="POST" action="<?= APP_URL ?>/index.php?controller=submission&action=destroy&id=<?= $sub['id'] ?>" onsubmit="return confirm('Supprimer ?')" style="display:inline">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <button class="btn btn--danger">Supprimer</button>
      </form>
    <?php endif; ?>
  </div>

  <!-- Comments -->
  <section class="comments">
    <h2>💬 Commentaires (<?= count($comments) ?>)</h2>

    <?php if ($comments): ?>
    <div class="comments__list">
      <?php foreach ($comments as $c): ?>
      <div class="comment">
        <div class="comment__avatar"><?= strtoupper(substr($c['author_name'], 0, 1)) ?></div>
        <div class="comment__body">
          <div class="comment__header">
            <strong><?= htmlspecialchars($c['author_name'], ENT_QUOTES, 'UTF-8') ?></strong>
            <span class="muted"><?= date('d/m/Y à H:i', strtotime($c['created_at'])) ?></span>
          </div>
          <p><?= nl2br(htmlspecialchars($c['content'], ENT_QUOTES, 'UTF-8')) ?></p>
          <?php if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] == $c['user_id']): ?>
          <form method="POST" action="<?= APP_URL ?>/index.php?controller=comment&action=destroy&id=<?= $c['id'] ?>" onsubmit="return confirm('Supprimer ce commentaire ?')">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">
            <button class="btn btn--sm btn--danger" style="margin-top:4px">Supprimer</button>
          </form>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <p class="muted">Aucun commentaire pour l'instant.</p>
    <?php endif; ?>

    <!-- Add comment -->
    <?php if (!empty($_SESSION['user_id'])): ?>
    <form method="POST" action="<?= APP_URL ?>/index.php?controller=comment&action=store" class="comment-form">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">
      <textarea name="content" rows="3" placeholder="Laissez un commentaire..." required></textarea>
      <button type="submit" class="btn btn--primary">Commenter</button>
    </form>
    <?php else: ?>
      <p class="muted"><a href="<?= APP_URL ?>/index.php?controller=user&action=login">Connectez-vous</a> pour commenter.</p>
    <?php endif; ?>
  </section>
</div>
