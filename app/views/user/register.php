<?php /** @var array $errors, array $old, string $csrf */ ?>
<div class="form-card">
  <h2>Créer un compte</h2>
  <p class="form-subtitle">Rejoignez la communauté ChallengeHub</p>
  <?php if (!empty($errors)): ?><ul class="error-list"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e,ENT_QUOTES,'UTF-8') ?></li><?php endforeach; ?></ul><?php endif; ?>
  <form method="POST" action="<?= APP_URL ?>/index.php?controller=user&action=register">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <div class="form-group"><label>Nom d'affichage</label><input type="text" name="name" required minlength="2" value="<?= htmlspecialchars($old['name']??'',ENT_QUOTES,'UTF-8') ?>"></div>
    <div class="form-group"><label>Email</label><input type="email" name="email" required value="<?= htmlspecialchars($old['email']??'',ENT_QUOTES,'UTF-8') ?>"></div>
    <div class="form-row">
      <div class="form-group"><label>Mot de passe</label><input type="password" name="password" placeholder="Min. 8 caractères" required minlength="8"></div>
      <div class="form-group"><label>Confirmer</label><input type="password" name="confirm" placeholder="Répétez" required></div>
    </div>
    <button type="submit" class="btn btn-primary w-full mt-1">Créer mon compte</button>
  </form>
  <p class="text-center text-muted mt-2" style="font-size:.88rem;">Déjà inscrit ? <a href="<?= APP_URL ?>/index.php?controller=user&action=login">Se connecter</a></p>
</div>
