<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h2>⚠️ Supprimer mon compte</h2>
    <p>Es-tu vraiment sûr ? Tous tes défis, votes et commentaires seront supprimés définitivement.</p>
    <form method="POST">
        <button type="submit" name="confirm_delete" 
                style="background:#c0392b; color:white; padding:12px 24px; border:none; border-radius:6px; cursor:pointer; font-size:16px;">
            ✅ Oui, supprimer mon compte
        </button>
        <a href="index.php?page=profile" 
           style="margin-left:15px; color:#2E6B9E;">
            ← Annuler
        </a>
    </form>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>