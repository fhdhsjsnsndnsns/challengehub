<div class="page-header">
  <h1>🏆 Classement</h1>
  <p>Les meilleures participations de la communauté</p>
</div>

<?php if (!empty($top)): ?>
  <ul class="ranking-list">
    <?php foreach ($top as $index => $submission): ?>
      <?php
        // Sécurisation et préparation des données
        $rank          = $index + 1;
        $submissionId  = (int) ($submission['id'] ?? 0);
        $challengeTitle = htmlspecialchars($submission['challenge_title'] ?? '', ENT_QUOTES, 'UTF-8');
        $authorName     = htmlspecialchars($submission['author_name'] ?? '', ENT_QUOTES, 'UTF-8');
        $description    = htmlspecialchars(mb_substr($submission['description'] ?? '', 0, 80), ENT_QUOTES, 'UTF-8');
        $voteCount      = (int) ($submission['vote_count'] ?? 0);
        $media          = $submission['media'] ?? null;
        $hasImage       = !empty($media) && !filter_var($media, FILTER_VALIDATE_URL);
      ?>
      <li class="ranking-item">
        <a href="<?= APP_URL ?>/index.php?controller=submission&action=show&id=<?= $submissionId ?>" class="ranking-link">
          <span class="ranking-position <?= $rank <= 3 ? 'top-' . $rank : '' ?>"><?= $rank ?></span>

          <?php if ($hasImage): ?>
            <div class="ranking-thumb" style="background-image:url('<?= UPLOAD_URL . '/' . htmlspecialchars($media, ENT_QUOTES, 'UTF-8') ?>')"></div>
          <?php endif; ?>

          <div class="ranking-info">
            <strong><?= $challengeTitle ?></strong>
            <small>par <?= $authorName ?></small>
            <p><?= $description ?>…</p>
          </div>

          <span class="ranking-votes">❤️ <?= $voteCount ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <div class="empty-state">
    <p>Aucune participation pour le moment. Soyez le premier à participer !</p>
  </div>
<?php endif; ?>