<?php
/**
 * ChallengeHub - HomeController
 * Gère la page d'accueil et le classement général
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';

class HomeController extends BaseController
{
    public function index(): void
    {
        // Récupère les 6 derniers défis publiés
        $stmt = $this->db->prepare(
            'SELECT c.*, u.name AS author_name 
             FROM challenges c
             JOIN users u ON c.user_id = u.id
             ORDER BY c.created_at DESC
             LIMIT 6'
        );
        $stmt->execute();
        $latestChallenges = $stmt->fetchAll();

        // Récupère le top 5 des participations les mieux votées
        $stmt = $this->db->prepare(
            'SELECT s.*, u.name AS author_name, c.title AS challenge_title,
                    COUNT(v.id) AS vote_count
             FROM submissions s
             JOIN users u ON s.user_id = u.id
             JOIN challenges c ON s.challenge_id = c.id
             LEFT JOIN votes v ON v.submission_id = s.id
             GROUP BY s.id
             ORDER BY vote_count DESC
             LIMIT 5'
        );
        $stmt->execute();
        $topSubmissions = $stmt->fetchAll();

        $this->render('home/index', [
            'pageTitle'       => 'Accueil - ' . APP_NAME,
            'latestChallenges' => $latestChallenges,
            'topSubmissions'  => $topSubmissions,
        ]);
    }
}
