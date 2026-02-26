<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Challenge.php';
require_once __DIR__ . '/../models/Submission.php';

class HomeController extends BaseController
{
    public function index(): void
    {
        $challenge  = new Challenge();
        $submission = new Submission();
        $this->render('home/index', [
            'pageTitle'        => APP_NAME . ' — Défis créatifs',
            'latestChallenges' => $challenge->getLatest(6),
            'topSubmissions'   => $submission->getTopRanked(5),
        ]);
    }
}
