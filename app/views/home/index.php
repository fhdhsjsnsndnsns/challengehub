<?php /** @var array $challenges, $top */ ?>
<section class="hero">
  <p class="hero-tag">✦ Plateforme collaborative</p>
  <h1>Relevez des <span>défis créatifs</span><br>et montrez votre talent</h1>
  <p>Publiez vos créations, votez pour les meilleures et grimpez dans le classement.</p>
  <div class="hero-actions">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="<?= APP_URL ?>/index.php?controller=challenge&action=index" class="btn btn-primary">Explorer les défis</a>
      <a href="<?= APP_URL ?>/index.php?controller=challenge&action=create" class="btn btn-outline">+ Créer un défi</a>
    <?php else: ?>
      <a href="<?= APP_URL ?>/index.php?controller=user&action=register" class="btn btn-primary">Rejoindre maintenant</a>
      <a href="<?= APP_URL ?>/index.php?controller=challenge&action=index" class="btn btn-outline">Voir les défis</a>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <h2 class="section-title">Derniers défis</h2>
    <a href="<?= APP_URL ?>/index.php?controller=challenge&action=index" class="btn btn-ghost btn-sm">Voir tout →</a>
  </div>
  <?php if (empty($challenges)): ?>
    <div class="empty-state"><div class="icon">🎯</div><h3>Aucun défi pour l'instant</h3><p>Soyez le premier à en créer un !</p></div>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($challenges as $c): ?>
        <div class="card">
          <?php if ($c['image']): ?>
            <img src="<?= UPLOAD_URL.'/'.htmlspecialchars($c['image'],ENT_QUOTES,'UTF-8') ?>" alt="" class="card-img">
          <?php else: ?><div class="card-img-placeholder">🎯</div><?php endif; ?>
          <div class="card-body">
            <span class="badge badge-violet"><?= htmlspecialchars($c['category'],ENT_QUOTES,'UTF-8') ?></span>
            <h3 class="card-title"><a href="<?= APP_URL ?>/index.php?controller=challenge&action=show&id=<?= $c['id'] ?>"><?= htmlspecialchars($c['title'],ENT_QUOTES,'UTF-8') ?></a></h3>
            <p class="card-meta">Par <?= htmlspecialchars($c['author_name'],ENT_QUOTES,'UTF-8') ?> · <?= date('d/m/Y',strtotime($c['created_at'])) ?></p>
          </div>
          <div class="card-footer">
            <span>📅 <?= date('d/m/Y',strtotime($c['deadline'])) ?></span>
            <span>👥 <?= $c['submission_count'] ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php if (!empty($top)): ?>
<section class="section">
  <div class="section-header">
    <h2 class="section-title">🏆 Top participations</h2>
    <a href="<?= APP_URL ?>/index.php?controller=submission&action=ranking" class="btn btn-ghost btn-sm">Classement →</a>
  </div>
  <ul class="ranking-list">
    <?php foreach ($top as $i => $s): ?>
      <li class="ranking-item">
        <span class="rank-num <?= ['gold','silver','bronze'][$i] ?? 'other' ?>">#<?= $i+1 ?></span>
        <div class="ranking-info">
          <a href="<?= APP_URL ?>/index.php?controller=submission&action=show&id=<?= $s['id'] ?>"><?= htmlspecialchars($s['challenge_title'],ENT_QUOTES,'UTF-8') ?></a>
          <small>par <?= htmlspecialchars($s['author_name'],ENT_QUOTES,'UTF-8') ?></small>
        </div>
        <span class="vote-chip">❤️ <?= $s['vote_count'] ?></span>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
<?php endif; ?>
