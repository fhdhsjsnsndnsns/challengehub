<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Vote.php';

class VoteController extends BaseController
{
    private Vote $vote;

    public function __construct()
    {
        parent::__construct();
        $this->vote = new Vote();
    }

    public function toggle(): void
    {
        $this->requireLogin();
        $submissionId = (int)($_POST['submission_id'] ?? 0);
        if (!$submissionId) {
            $this->jsonResponse(['error' => 'Invalid'], 400);
            return;
        }

        // CSRF check for AJAX
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            $this->jsonResponse(['error' => 'CSRF'], 403);
            return;
        }

        $result = $this->vote->toggle($this->getCurrentUserId(), $submissionId); // ← était currentUserId
        $this->jsonResponse($result);
    }
}