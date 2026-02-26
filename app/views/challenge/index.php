<div class="page-header">
  <div class="page-header__title">
    <h1>🚀 Les Défis</h1>
    <p class="text-muted">Explorez les défis créatifs de la communauté</p>
  </div>
  <?php if (!empty($_SESSION['user_id'])): ?>
    <a href="<?= APP_URL ?>/index.php?controller=challenge&action=create" class="btn btn-primary">
      <span>+</span> Créer un défi
    </a>
  <?php endif; ?>
</div>

<!-- Filtres améliorés -->
<div class="filters-card">
  <form method="GET" action="<?= APP_URL ?>/index.php" class="filters-form">
    <input type="hidden" name="controller" value="challenge">
    <input type="hidden" name="action" value="index">

    <div class="filters-group">
      <div class="filter-item search-wrapper">
        <input type="search" name="search" id="search" class="filter-input"
               value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>"
               placeholder="Rechercher un défi...">
        <span class="search-icon">🔍</span>
      </div>

      <div class="filter-item">
        <select name="category" id="category" class="filter-select">
          <option value="">Toutes les catégories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>" 
              <?= ($category ?? '') === $cat ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="filter-item">
        <select name="sort" id="sort" class="filter-select">
          <option value="recent" <?= ($sort ?? 'recent') === 'recent' ? 'selected' : '' ?>>Plus récents</option>
          <option value="popular" <?= ($sort ?? '') === 'popular' ? 'selected' : '' ?>>Plus populaires</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary filter-submit">Filtrer</button>

      <?php if (!empty($search) || !empty($category)): ?>
        <a href="<?= APP_URL ?>/index.php?controller=challenge&action=index" class="btn btn-ghost filter-reset">
          Réinitialiser
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Grille des défis -->
<?php if (!empty($challenges)): ?>
  <div class="card-grid">
    <?php foreach ($challenges as $challenge): ?>
      <a href="<?= APP_URL ?>/index.php?controller=challenge&action=show&id=<?= $challenge['id'] ?>" class="card-link">
        <article class="card">
          <!-- Image ou placeholder -->
          <div class="card-image">
            <?php if (!empty($challenge['image'])): ?>
              <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($challenge['image'], ENT_QUOTES, 'UTF-8') ?>" 
                   alt="" class="card-img" loading="lazy">
            <?php else: ?>
              <div class="card-img-placeholder">🎯</div>
            <?php endif; ?>
          </div>

          <!-- Contenu -->
          <div class="card-body">
            <div class="card-meta-top">
              <span class="badge badge-violet"><?= htmlspecialchars($challenge['category'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
              <span class="card-deadline">
                📅 <?= date('d/m/Y', strtotime($challenge['deadline'] ?? 'now')) ?>
              </span>
            </div>

            <h3 class="card-title"><?= htmlspecialchars($challenge['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
            
            <p class="card-description">
              <?= htmlspecialchars(mb_substr($challenge['description'] ?? '', 0, 100), ENT_QUOTES, 'UTF-8') ?>…
            </p>

            <div class="card-footer">
              <div class="card-author">
                <span class="author-avatar">👤</span>
                <span><?= htmlspecialchars($challenge['author_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
              </div>
              <div class="card-stats">
                <span class="stat-item" title="Participations">
                  📝 <?= (int) ($challenge['submission_count'] ?? 0) ?>
                </span>
              </div>
            </div>
          </div>
        </article>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <?php if (($totalPages ?? 1) > 1): ?>
    <div class="pagination">
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="<?= APP_URL ?>/index.php?controller=challenge&action=index&page=<?= $p ?>&search=<?= urlencode($search ?? '') ?>&category=<?= urlencode($category ?? '') ?>&sort=<?= $sort ?? 'recent' ?>"
           class="pagination__item <?= ($p === ($page ?? 1)) ? 'active' : '' ?>">
          <?= $p ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>

<?php else: ?>
  <div class="empty-state">
    <div class="empty-icon">📭</div>
    <h3>Aucun défi trouvé</h3>
    <?php if (!empty($search)): ?>
      <p>Désolé, aucun résultat pour « <?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?> ».</p>
    <?php else: ?>
      <p>Soyez le premier à créer un défi !</p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="<?= APP_URL ?>/index.php?controller=challenge&action=create" class="btn btn-primary">
        Créer un défi
      </a>
    <?php else: ?>
      <a href="<?= APP_URL ?>/index.php?controller=user&action=register" class="btn btn-primary">
        Rejoindre maintenant
      </a>
    <?php endif; ?>
  </div>
<?php endif; ?>