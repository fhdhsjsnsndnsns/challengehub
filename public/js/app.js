// ChallengeHub — JavaScript

document.addEventListener('DOMContentLoaded', () => {

  // ── Auto-hide flash messages ──────────────────────
  document.querySelectorAll('.flash').forEach(el => {
    setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .5s'; setTimeout(() => el.remove(), 500); }, 4000);
  });

  // ── Vote (AJAX toggle) ────────────────────────────
  document.querySelectorAll('.vote-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id   = btn.dataset.id;
      const csrf = btn.dataset.csrf;
      if (!id || btn.disabled) return;

      btn.disabled = true;

      try {
        const form = new FormData();
        form.append('submission_id', id);
        form.append('csrf_token', csrf);

        const res  = await fetch(`${window.location.origin}/challengehub_full/index.php?controller=vote&action=toggle`, {
          method: 'POST', body: form
        });
        const data = await res.json();

        // Update all vote buttons for this submission
        document.querySelectorAll(`.vote-btn[data-id="${id}"]`).forEach(b => {
          b.querySelector('.vote-count').textContent = data.count;
          b.classList.toggle('vote-btn--active', data.action === 'added');
        });
      } catch (e) {
        console.error('Vote error:', e);
      } finally {
        btn.disabled = false;
      }
    });
  });

});
