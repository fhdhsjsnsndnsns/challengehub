<?php /** @var array $user, array $submissions, string $csrf */ ?>
<div class="profile-header">
  <?php if ($user['avatar']): ?>
    <img src="<?= UPLOAD_URL.'/'.htmlspecialchars($user['avatar'],ENT_QUOTES,'UTF-8') ?>" alt="" class="avatar avatar-lg">
  <?php else: ?><div class="avatar-placeholder lg"><?= strtoupper(substr($user['name'],0,1)) ?></div><?php endif; ?>
  <div class="profile-info">
    <h2><?= htmlspecialchars($user['name'],ENT_QUOTES,'UTF-8') ?></h2>
    <p><?= htmlspecialchars($user['email'],ENT_QUOTES,'UTF-8') ?></p>
    <div class="profile-stats"><div class="profile-stat"><strong><?= count($submissions) ?></strong><span>Participations</span></div></div>
  </div>
  <div style="margin-left:auto;display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-start;">
    <a href="<?= APP_URL ?>/index.php?controller=user&action=edit" class="btn btn-outline btn-sm">✏️ Modifier</a>
    <form method="POST" action="<?= APP_URL ?>/index.php?controller=user&action=delete" onsubmit="return confirm('Supprimer définitivement votre compte ?')">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <button type="submit" class="btn btn-danger btn-sm">🗑 Supprimer</button>
    </form>
  </div>
</div>
<section class="section">
  <h2 class="section-title">Mes participations</h2>
  <?php if (empty($submissions)): ?>
    <div class="empty-state"><div class="icon">📭</div><h3>Aucune participation</h3><p style="margin:.5rem 0 1rem;">Explorez les défis !</p><a href="<?= APP_URL ?>/index.php?controller=challenge&action=index" class="btn btn-primary">Voir les défis</a></div>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($submissions as $s): ?>
        <div class="card"><div class="card-body">
          <span class="badge badge-violet"><?= htmlspecialchars($s['challenge_title'],ENT_QUOTES,'UTF-8') ?></span>
          <p class="card-meta mt-1"><?= date('d/m/Y',strtotime($s['created_at'])) ?></p>
          <p style="margin:.75rem 0;font-size:.9rem;color:var(--text);"><?= htmlspecialchars(substr($s['description'],0,100),ENT_QUOTES,'UTF-8') ?>...</p>
        </div>
        <div class="card-footer">
          <span class="vote-chip">❤️ <?= $s['vote_count'] ?></span>
          <a href="<?= APP_URL ?>/index.php?controller=submission&action=show&id=<?= $s['id'] ?>" class="btn btn-ghost btn-sm">Voir →</a>
        </div></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
