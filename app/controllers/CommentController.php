<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Comment.php';

class CommentController extends BaseController
{
    private Comment $comment;

    public function __construct()
    {
        parent::__construct();
        $this->comment = new Comment();
    }

    public function store(): void
    {
        $this->requireLogin();
        $this->verifyCsrfToken(); // ← corrigé (était verifyCsrf)

        $submissionId = (int)($_POST['submission_id'] ?? 0);
        $content      = trim($_POST['content'] ?? '');

        if (strlen($content) < 2 || !$submissionId) {
            $this->flash('error', 'Commentaire invalide.'); // ← était setFlash
            $this->redirect('submission', 'show', $submissionId);
            return;
        }

        $this->comment->create($this->getCurrentUserId(), $submissionId, $content); // ← était currentUserId
        $this->flash('success', 'Commentaire ajouté.'); // ← était setFlash
        $this->redirect('submission', 'show', $submissionId);
    }

    public function destroy(int $id): void
    {
        $this->requireLogin();
        $this->verifyCsrfToken(); // ← était verifyCsrf

        $submissionId = (int)($_POST['submission_id'] ?? 0);

        if (!$this->comment->belongsToUser($id, $this->getCurrentUserId())) { // ← était isOwner
            $this->flash('error', 'Accès refusé.'); // ← était setFlash
        } else {
            $this->comment->delete($id);
            $this->flash('success', 'Commentaire supprimé.'); // ← était setFlash
        }

        $this->redirect('submission', 'show', $submissionId);
    }
}