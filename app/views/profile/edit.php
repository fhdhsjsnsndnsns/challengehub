<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h2>✏️ Modifier mon profil</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <!-- PHOTO DE PROFIL -->
    <div class="card" style="margin-bottom: 20px;">
        <h3>📸 Photo de profil</h3>
        <div class="photo-section">
            <div class="current-avatar">
                <?php if (!empty($profil['photo'])): ?>
                    <img src="public/uploads/<?= htmlspecialchars($profil['photo']) ?>" 
                         alt="Photo de profil" id="preview">
                <?php else: ?>
                    <div class="avatar-placeholder" id="preview-placeholder">
                        <?= strtoupper(substr($profil['prenom'], 0, 1) . substr($profil['nom'], 0, 1)) ?>
                    </div>
                    <img src="" alt="" id="preview" style="display:none; width:100px; height:100px; border-radius:50%; object-fit:cover;">
                <?php endif; ?>
            </div>
            <div class="photo-actions">
                <form method="POST" enctype="multipart/form-data">
                    <label class="btn" style="cursor:pointer;">
                        📁 Choisir une photo
                        <input type="file" name="photo" id="photoInput" 
                               accept="image/*" style="display:none"
                               onchange="previewPhoto(this)">
                    </label>
                    <button type="submit" name="update_photo" class="btn">
                        💾 Sauvegarder la photo
                    </button>
                </form>
                <?php if (!empty($profil['photo'])): ?>
                    <form method="POST" style="display:inline;">
                        <button type="submit" name="delete_photo" 
                                onclick="return confirm('Supprimer la photo ?')"
                                style="background:#c0392b; color:white; border:none; padding:10px 18px; border-radius:6px; cursor:pointer;">
                            🗑️ Supprimer la photo
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- MODIFIER INFOS -->
    <div class="card" style="margin-bottom: 20px;">
        <h3>✏️ Mes informations</h3>
        <form method="POST">
            <label>Nom</label>
            <input type="text" name="nom" 
                   value="<?= htmlspecialchars($profil['nom']) ?>" required>
            <label>Prénom</label>
            <input type="text" name="prenom" 
                   value="<?= htmlspecialchars($profil['prenom']) ?>" required>
            <label>Email</label>
            <input type="email" name="email" 
                   value="<?= htmlspecialchars($profil['email']) ?>" required>
            <button type="submit" name="update_info">💾 Sauvegarder</button>
        </form>
    </div>

    <!-- MODIFIER MOT DE PASSE -->
    <div class="card" style="margin-bottom: 20px;">
        <h3>🔒 Changer mon mot de passe</h3>
        <form method="POST">
            <label>Ancien mot de passe</label>
            <input type="password" name="ancien_password" required>
            <label>Nouveau mot de passe</label>
            <input type="password" name="nouveau_password" required>
            <label>Confirmer le nouveau mot de passe</label>
            <input type="password" name="confirm_password" required>
            <button type="submit" name="update_password">🔒 Modifier</button>
        </form>
    </div>

    <!-- SUPPRIMER COMPTE -->
    <div class="card" style="border-left: 4px solid #c0392b;">
        <h3 style="color: #c0392b;">⚠️ Supprimer mon compte</h3>
        <p style="color: #666; margin-bottom: 10px;">
            Action irréversible. Tous tes défis et commentaires seront supprimés.
        </p>
        <a href="index.php?page=deleteAccount"
           style="background:#c0392b; color:white; padding:10px 20px; 
                  border-radius:6px; text-decoration:none; display:inline-block;"
           onclick="return confirm('Es-tu sûr ? Cette action est irréversible !')">
            🗑️ Supprimer mon compte
        </a>
    </div>
</div>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Afficher la prévisualisation
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('preview-placeholder');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>