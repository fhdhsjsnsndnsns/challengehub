<div class="challenge-detail">
  <?php
    $expired = strtotime($challenge['deadline']) < time();
  ?>
  <div class="challenge-detail__header">
    <?php if (!empty($challenge['image'])): ?>
      <div class="challenge-detail__banner" style="background-image:url('<?= UPLOAD_URL . '/' . htmlspecialchars($challenge['image'], ENT_QUOTES, 'UTF-8') ?>')"></div>
    <?php endif; ?>
    <div class="challenge-detail__meta">
      <span class="tag tag--lg"><?= htmlspecialchars($challenge['category'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
      <h1><?= htmlspecialchars($challenge['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h1>
      <p class="challenge-detail__desc"><?= nl2br(htmlspecialchars($challenge['description'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
      <div class="challenge-detail__info">
        <span>👤 <?= htmlspecialchars($challenge['author_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        <span>📅 Deadline : <?= date('d/m/Y', strtotime($challenge['deadline'] ?? 'now')) ?></span>
        <span>📝 <?= count($submissions) ?> participation(s)</span>
      </div>
      <div class="challenge-detail__actions">
        <?php if (!empty($_SESSION['user_id']) && !$alreadySubmitted && !$isOwner && !$expired): ?>
          <a href="<?= APP_URL ?>/index.php?controller=submission&action=create&challenge_id=<?= $challenge['id'] ?>" class="btn btn--primary">Participer</a>
        <?php elseif ($expired): ?>
          <span class="tag tag--expired">Défi terminé</span>
        <?php elseif ($alreadySubmitted): ?>
          <span class="tag tag--done">Vous avez participé ✓</span>
        <?php endif; ?>
        <?php if ($isOwner): ?>
          <a href="<?= APP_URL ?>/index.php?controller=challenge&action=edit&id=<?= $challenge['id'] ?>" class="btn btn--outline">Modifier</a>
          <form method="POST" action="<?= APP_URL ?>/index.php?controller=challenge&action=delete&id=<?= $challenge['id'] ?>" onsubmit="return confirm('Supprimer ce défi ?')" style="display:inline">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <button class="btn btn--danger">Supprimer</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="challenge-detail__submissions">
    <div class="section__header">
      <h2>Participations</h2>
      <div class="sort-links">
        <a href="?controller=challenge&action=show&id=<?= $challenge['id'] ?>&sort=recent" class="<?= ($sort ?? 'recent') === 'recent' ? 'active' : '' ?>">Récentes</a>
        <a href="?controller=challenge&action=show&id=<?= $challenge['id'] ?>&sort=popular" class="<?= ($sort ?? '') === 'popular' ? 'active' : '' ?>">Populaires</a>
      </div>
    </div>
    <?php if (!empty($submissions)): ?>
    <div class="submissions-grid">
      <?php foreach ($submissions as $s): ?>
      <div class="sub-card">
        <?php if (!empty($s['media']) && filter_var($s['media'], FILTER_VALIDATE_URL)): ?>
          <a href="<?= htmlspecialchars($s['media'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="sub-card__link">🔗 Voir le média</a>
        <?php elseif (!empty($s['media'])): ?>
          <div class="sub-card__img" style="background-image:url('<?= UPLOAD_URL . '/' . htmlspecialchars($s['media'], ENT_QUOTES, 'UTF-8') ?>')"></div>
        <?php endif; ?>
        <div class="sub-card__body">
          <p><?= nl2br(htmlspecialchars(mb_substr($s['description'] ?? '', 0, 120), ENT_QUOTES, 'UTF-8')) ?>...</p>
          <div class="sub-card__footer">
            <span>👤 <?= htmlspecialchars($s['author_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <div class="sub-card__actions">
              <button class="vote-btn"
                data-id="<?= $s['id'] ?>" data-csrf="<?= $csrf ?>"
                <?= empty($_SESSION['user_id']) ? 'disabled title="Connectez-vous pour voter"' : '' ?>>
                ❤️ <span class="vote-count"><?= $s['vote_count'] ?? 0 ?></span>
              </button>
              <span>💬 <?= $s['comment_count'] ?? 0 ?></span>
              <a href="<?= APP_URL ?>/index.php?controller=submission&action=show&id=<?= $s['id'] ?>" class="btn btn--sm btn--outline">Voir</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <div class="empty"><p>Aucune participation pour le moment.</p></div>
    <?php endif; ?>
  </div>
</div>