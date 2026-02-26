<div class="form-page">
  <h1><?= $sub ? 'Modifier ma participation' : 'Soumettre une participation' ?></h1>
  <form method="POST"
    action="<?= APP_URL ?>/index.php?controller=submission&action=<?= $sub ? 'update&id=' . $sub['id'] : 'store' ?>"
    enctype="multipart/form-data" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <input type="hidden" name="challenge_id" value="<?= $challengeId ?>">

    <div class="field <?= isset($errors['description']) ? 'field--error' : '' ?>">
      <label>Description de votre participation *</label>
      <textarea name="description" rows="6" placeholder="Décrivez votre participation, votre démarche créative..." required><?= htmlspecialchars($sub['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
      <?php if (isset($errors['description'])): ?><span class="field__error"><?= $errors['description'] ?></span><?php endif; ?>
    </div>

    <div class="field">
      <label>Image <small>(optionnel)</small></label>
      <?php if (!empty($sub['media']) && !filter_var($sub['media'], FILTER_VALIDATE_URL)): ?>
        <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($sub['media'], ENT_QUOTES, 'UTF-8') ?>" alt="média actuel" style="max-height:120px;border-radius:8px;margin-bottom:8px;display:block">
      <?php endif; ?>
      <input type="file" name="media" accept="image/*">
    </div>

    <div class="field">
      <label>Ou lien externe <small>(URL vers votre travail)</small></label>
      <input type="url" name="media_url" value="<?= !empty($sub['media']) && filter_var($sub['media'], FILTER_VALIDATE_URL) ? htmlspecialchars($sub['media'], ENT_QUOTES, 'UTF-8') : '' ?>" placeholder="https://...">
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn--primary btn--lg"><?= $sub ? 'Mettre à jour' : 'Soumettre' ?></button>
      <a href="<?= APP_URL ?>/index.php?controller=challenge&action=show&id=<?= $challengeId ?>" class="btn btn--outline">Annuler</a>
    </div>
  </form>
</div>
