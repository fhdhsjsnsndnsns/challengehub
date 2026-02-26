<?php /** @var array $errors, string $csrf */ ?>
<div class="form-card">
  <h2>Bon retour 👋</h2>
  <p class="form-subtitle">Connectez-vous à votre compte ChallengeHub</p>
  <?php if (!empty($errors)): ?><ul class="error-list"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e,ENT_QUOTES,'UTF-8') ?></li><?php endforeach; ?></ul><?php endif; ?>
  <form method="POST" action="<?= APP_URL ?>/index.php?controller=user&action=login">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="vous@exemple.com" required value="<?= htmlspecialchars($_POST['email']??'',ENT_QUOTES,'UTF-8') ?>"></div>
    <div class="form-group"><label>Mot de passe</label><input type="password" name="password" placeholder="••••••••" required></div>
    <button type="submit" class="btn btn-primary w-full mt-1">Se connecter</button>
  </form>
  <p class="text-center text-muted mt-2" style="font-size:.88rem;">Pas encore de compte ? <a href="<?= APP_URL ?>/index.php?controller=user&action=register">S'inscrire</a></p>
</div>
