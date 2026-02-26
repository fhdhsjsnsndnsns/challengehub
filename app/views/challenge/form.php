<div class="form-page">
  <h1><?= $challenge && isset($challenge['id']) ? 'Modifier le défi' : 'Créer un défi' ?></h1>
  <form method="POST"
    action="<?= APP_URL ?>/index.php?controller=challenge&action=<?= $challenge && isset($challenge['id']) ? 'update&id=' . $challenge['id'] : 'store' ?>"
    enctype="multipart/form-data" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

    <div class="field <?= isset($errors['title']) ? 'field--error' : '' ?>">
      <label>Titre *</label>
      <input type="text" name="title" value="<?= htmlspecialchars($challenge['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Un titre accrocheur" required>
      <?php if (isset($errors['title'])): ?><span class="field__error"><?= $errors['title'] ?></span><?php endif; ?>
    </div>

    <div class="field <?= isset($errors['description']) ? 'field--error' : '' ?>">
      <label>Description *</label>
      <textarea name="description" rows="5" placeholder="Décrivez votre défi en détail..." required><?= htmlspecialchars($challenge['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
      <?php if (isset($errors['description'])): ?><span class="field__error"><?= $errors['description'] ?></span><?php endif; ?>
    </div>

    <div class="field-row">
      <div class="field <?= isset($errors['category']) ? 'field--error' : '' ?>">
        <label>Catégorie *</label>
        <input type="text" name="category" value="<?= htmlspecialchars($challenge['category'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="ex: Photographie, Design, Écriture..." required>
        <?php if (isset($errors['category'])): ?><span class="field__error"><?= $errors['category'] ?></span><?php endif; ?>
      </div>
      <div class="field <?= isset($errors['deadline']) ? 'field--error' : '' ?>">
        <label>Date limite *</label>
        <input type="date" name="deadline" value="<?= htmlspecialchars($challenge['deadline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
        <?php if (isset($errors['deadline'])): ?><span class="field__error"><?= $errors['deadline'] ?></span><?php endif; ?>
      </div>
    </div>

    <div class="field">
      <label>Image de couverture <small>(optionnel)</small></label>
      <?php if (!empty($challenge['image'])): ?>
        <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($challenge['image'], ENT_QUOTES, 'UTF-8') ?>" alt="image actuelle" style="max-height:120px;border-radius:8px;margin-bottom:8px;display:block">
      <?php endif; ?>
      <input type="file" name="image" accept="image/*">
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn--primary btn--lg"><?= $challenge && isset($challenge['id']) ? 'Mettre à jour' : 'Publier le défi' ?></button>
      <a href="<?= APP_URL ?>/index.php?controller=challenge&action=index" class="btn btn--outline">Annuler</a>
    </div>
  </form>
</div>
