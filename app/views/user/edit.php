<?php /** @var array $user, array $errors, string $csrf */ ?>
<div class="form-card" style="max-width:560px;">
  <h2>Modifier mon profil</h2>
  <p class="form-subtitle">Mettez à jour vos informations</p>
  <?php if (!empty($errors)): ?><ul class="error-list"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e,ENT_QUOTES,'UTF-8') ?></li><?php endforeach; ?></ul><?php endif; ?>
  <form method="POST" action="<?= APP_URL ?>/index.php?controller=user&action=edit" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <div class="form-group"><label>Nom</label><input type="text" name="name" required value="<?= htmlspecialchars($user['name'],ENT_QUOTES,'UTF-8') ?>"></div>
    <div class="form-group"><label>Email</label><input type="email" name="email" required value="<?= htmlspecialchars($user['email'],ENT_QUOTES,'UTF-8') ?>"></div>
    <div class="form-group"><label>Avatar (optionnel)</label><input type="file" name="avatar" accept="image/*"><p class="hint">JPG, PNG, GIF ou WEBP · Max 5 Mo</p></div>
    <hr class="divider">
    <p style="font-size:.85rem;color:var(--muted);margin-bottom:1rem;">Laissez vide pour garder votre mot de passe actuel.</p>
    <div class="form-group"><label>Ancien mot de passe</label><input type="password" name="old_password" placeholder="••••••••"></div>
    <div class="form-group"><label>Nouveau mot de passe</label><input type="password" name="new_password" placeholder="Min. 8 caractères"></div>
    <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <a href="<?= APP_URL ?>/index.php?controller=user&action=profile" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
