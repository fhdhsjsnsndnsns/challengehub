<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h2>✨ Créer un défi</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Titre du défi</label>
        <input type="text" name="titre" placeholder="Ex: Pas de sucre pendant 7 jours" required>

        <label>Description</label>
        <textarea name="description" placeholder="Décris ton défi en détail..." rows="4" required></textarea>

        <label>Catégorie</label>
<input type="text" name="categorie" placeholder="Ex: Sport, Nutrition, Méditation, Lecture..." required>

        <label>Date limite</label>
        <input type="date" name="date_limite" required>

        <label>Image du défi (optionnel)</label>
        <input type="file" name="image" accept="image/*" onchange="previewImage(this)">
        <img id="preview" src="" alt="" 
             style="display:none; width:100%; max-height:200px; object-fit:cover; 
                    border-radius:10px; margin-top:10px;">

        <button type="submit"> Publier le défi</button>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>