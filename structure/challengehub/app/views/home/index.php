<?php /** @var array $latestChallenges, $topSubmissions */ ?>

<section class="hero">
    <h1>Bienvenue sur <?= APP_NAME ?></h1>
    <p>Relevez des défis créatifs, soumettez vos œuvres, votez pour les meilleures.</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="<?= APP_URL ?>/index.php?controller=user&action=register" class="btn btn-primary">
            Rejoindre la communauté
        </a>
    <?php else: ?>
        <a href="<?= APP_URL ?>/index.php?controller=challenge&action=index" class="btn btn-primary">
            Explorer les défis
        </a>
    <?php endif; ?>
</section>

<!-- ── Derniers défis ── -->
<section class="section">
    <h2>Derniers défis publiés</h2>
    <div class="card-grid">
        <?php foreach ($latestChallenges as $challenge): ?>
            <div class="card">
                <?php if ($challenge['image']): ?>
                    <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($challenge['image'], ENT_QUOTES, 'UTF-8') ?>" 
                         alt="Image du défi" class="card-img">
                <?php endif; ?>
                <div class="card-body">
                    <span class="badge"><?= htmlspecialchars($challenge['category'], ENT_QUOTES, 'UTF-8') ?></span>
                    <h3 class="card-title">
                        <a href="<?= APP_URL ?>/index.php?controller=challenge&action=show&id=<?= $challenge['id'] ?>">
                            <?= htmlspecialchars($challenge['title'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </h3>
                    <p class="card-meta">
                        Par <?= htmlspecialchars($challenge['author_name'], ENT_QUOTES, 'UTF-8') ?>
                        · <?= date('d/m/Y', strtotime($challenge['created_at'])) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center">
        <a href="<?= APP_URL ?>/index.php?controller=challenge&action=index" class="btn btn-secondary">
            Voir tous les défis →
        </a>
    </div>
</section>

<!-- ── Classement ── -->
<?php if (!empty($topSubmissions)): ?>
<section class="section">
    <h2>🏆 Top participations</h2>
    <ol class="ranking-list">
        <?php foreach ($topSubmissions as $i => $sub): ?>
            <li class="ranking-item">
                <span class="rank">#<?= $i + 1 ?></span>
                <div class="ranking-info">
                    <a href="<?= APP_URL ?>/index.php?controller=submission&action=show&id=<?= $sub['id'] ?>">
                        <?= htmlspecialchars($sub['challenge_title'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                    <small>par <?= htmlspecialchars($sub['author_name'], ENT_QUOTES, 'UTF-8') ?></small>
                </div>
                <span class="vote-count">❤️ <?= $sub['vote_count'] ?></span>
            </li>
        <?php endforeach; ?>
    </ol>
</section>
<?php endif; ?>
