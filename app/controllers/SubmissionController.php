<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Submission.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Vote.php';

class SubmissionController extends BaseController
{
    private Submission $submission;
    private Comment    $comment;
    private Vote       $vote;

    public function __construct()
    {
        parent::__construct();
        $this->submission = new Submission();
        $this->comment    = new Comment();
        $this->vote       = new Vote();
    }

    public function show(int $id): void
    {
        $sub = $this->submission->findById($id);
        if (!$sub) {
            $this->flash('error', 'Participation introuvable.'); // ← était setFlash
            $this->redirect('challenge');
            return;
        }

        $comments = $this->comment->findBySubmission($id);
        $hasVoted = $this->isLoggedIn() && $this->vote->hasVoted($this->getCurrentUserId(), $id); // ← était currentUserId
        $isOwner  = $this->isLoggedIn() && $this->submission->belongsToUser($id, $this->getCurrentUserId()); // ← était isOwner et currentUserId

        $this->render('submission/show', [
            'pageTitle' => 'Participation',
            'sub'       => $sub,
            'comments'  => $comments,
            'hasVoted'  => $hasVoted,
            'isOwner'   => $isOwner,
            'csrf'      => $this->generateCsrfToken(), // ← était generateCsrf
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();
        $challengeId = (int)($_GET['challenge_id'] ?? 0);

        if ($this->submission->userAlreadySubmitted($this->getCurrentUserId(), $challengeId)) { // ← était currentUserId
            $this->flash('error', 'Vous avez déjà soumis une participation pour ce défi.'); // ← était setFlash
            $this->redirect('challenge', 'show', $challengeId);
            return;
        }

        $this->render('submission/form', [
            'pageTitle'   => 'Soumettre une participation',
            'challengeId' => $challengeId,
            'sub'         => null,
            'errors'      => [],
            'csrf'        => $this->generateCsrfToken(), // ← était generateCsrf
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();
        $this->verifyCsrfToken(); // OK (déjà bon)

        $challengeId = (int)($_POST['challenge_id'] ?? 0);
        $desc        = trim($_POST['description'] ?? '');
        $errors      = [];

        if (strlen($desc) < 10) $errors['description'] = 'Description trop courte (min 10 car.).';
        if (!$challengeId)      $errors['challenge']   = 'Défi invalide.';

        if ($errors) {
            $this->render('submission/form', [
                'pageTitle'   => 'Soumettre',
                'challengeId' => $challengeId,
                'sub'         => null,
                'errors'      => $errors,
                'csrf'        => $this->generateCsrfToken() // ← était generateCsrf
            ]);
            return;
        }

        // Gestion du média (image upload ou URL)
        $media = null;
        if (!empty($_FILES['media']['tmp_name'])) {
            $media = $this->uploadImage($_FILES['media'], 'submission');
        }
        if (!$media && !empty($_POST['media_url'])) {
            $media = trim($_POST['media_url']);
        }

        $id = $this->submission->create($this->getCurrentUserId(), $challengeId, $desc, $media); // ← était currentUserId
        $this->flash('success', 'Participation soumise !'); // ← était setFlash
        $this->redirect('submission', 'show', $id);
    }

    public function edit(int $id): void
    {
        $this->requireLogin();
        $sub = $this->submission->findById($id);
        if (!$sub || !$this->submission->belongsToUser($id, $this->getCurrentUserId())) { // ← était isOwner et currentUserId
            $this->flash('error', 'Accès refusé.'); // ← était setFlash
            $this->redirect('challenge');
            return;
        }
        $this->render('submission/form', [
            'pageTitle'   => 'Modifier la participation',
            'challengeId' => $sub['challenge_id'],
            'sub'         => $sub,
            'errors'      => [],
            'csrf'        => $this->generateCsrfToken() // ← était generateCsrf
        ]);
    }

    public function update(int $id): void
    {
        $this->requireLogin();
        $this->verifyCsrfToken();

        if (!$this->submission->belongsToUser($id, $this->getCurrentUserId())) { // ← était isOwner et currentUserId
            $this->flash('error', 'Accès refusé.'); // ← était setFlash
            $this->redirect('challenge');
            return;
        }

        $desc  = trim($_POST['description'] ?? '');
        if (strlen($desc) < 10) {
            $this->flash('error', 'Description trop courte.'); // ← était setFlash
            $this->redirect('submission', 'edit', $id);
            return;
        }

        $media = null;
        if (!empty($_FILES['media']['tmp_name'])) {
            $media = $this->uploadImage($_FILES['media'], 'submission');
        }
        if (!$media && !empty($_POST['media_url'])) {
            $media = trim($_POST['media_url']);
        }

        $this->submission->update($id, $desc, $media);
        $this->flash('success', 'Participation mise à jour.'); // ← était setFlash
        $this->redirect('submission', 'show', $id);
    }

    public function destroy(int $id): void
    {
        $this->requireLogin();
        $this->verifyCsrfToken();

        $sub = $this->submission->findById($id);
        if (!$sub || !$this->submission->belongsToUser($id, $this->getCurrentUserId())) { // ← était isOwner et currentUserId
            $this->flash('error', 'Accès refusé.'); // ← était setFlash
            $this->redirect('challenge');
            return;
        }

        $challengeId = $sub['challenge_id'];
        $this->submission->delete($id);
        $this->flash('success', 'Participation supprimée.'); // ← était setFlash
        $this->redirect('challenge', 'show', $challengeId);
    }

    public function ranking(): void
    {
        $top = $this->submission->getTopRanked(20);
        $this->render('submission/ranking', [
            'pageTitle' => 'Classement',
            'top'       => $top
        ]);
    }
}