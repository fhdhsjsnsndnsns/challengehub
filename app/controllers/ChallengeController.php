<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once APP_PATH . '/models/Challenge.php';
require_once APP_PATH . '/models/Submission.php';

class ChallengeController extends BaseController
{
    private Challenge $challengeModel;

    public function __construct()
    {
        parent::__construct();
        $this->challengeModel = new Challenge();
    }

    /**
     * Liste des défis avec filtres et pagination
     */
    public function index(): void
    {
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $category = $_GET['category'] ?? null;
        $search   = $_GET['search'] ?? null;
        $sort     = $_GET['sort'] ?? 'recent';

        $challenges = $this->challengeModel->findAll($page, $category, $search, $sort);
        $total      = $this->challengeModel->countAll($category, $search);
        $totalPages = (int) ceil($total / ITEMS_PER_PAGE);
        $categories = $this->challengeModel->getCategories();

        $this->render('challenge/index', [
            'pageTitle'   => 'Les Défis',
            'challenges'  => $challenges,
            'categories'  => $categories,
            'totalPages'  => $totalPages,
            'page'        => $page,
            'category'    => $category,
            'search'      => $search,
            'sort'        => $sort,
        ]);
    }

    /**
     * Détail d'un défi
     */
    public function show(int $id): void
    {
        $challenge = $this->challengeModel->findById($id);
        if (!$challenge) {
            $this->flash('error', 'Défi introuvable.');
            $this->redirect('challenge');
            return;
        }

        $sort        = $_GET['sort'] ?? 'recent';
        $submissions = (new Submission())->findByChallenge($id, $sort);

        // Déterminer si l'utilisateur connecté est le propriétaire ou a déjà participé
        $alreadySubmitted = false;
        $isOwner          = false;

        if ($this->isLoggedIn()) {
            $alreadySubmitted = (new Submission())->userAlreadySubmitted($this->getCurrentUserId(), $id);
            $isOwner          = ($challenge['user_id'] == $this->getCurrentUserId());
        }

        $this->render('challenge/show', [
            'pageTitle'        => $challenge['title'],
            'challenge'        => $challenge,
            'submissions'      => $submissions,
            'sort'             => $sort,
            'alreadySubmitted' => $alreadySubmitted,
            'isOwner'          => $isOwner,
            'csrf'             => $this->generateCsrfToken(),
        ]);
    }

    /**
     * Formulaire de création d'un défi
     */
    public function create(): void
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();

            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category    = trim($_POST['category'] ?? '');
            $deadline    = $_POST['deadline'] ?? '';
            $errors      = [];

            if (strlen($title) < 3) {
                $errors[] = 'Le titre doit contenir au moins 3 caractères.';
            }
            if (strlen($description) < 10) {
                $errors[] = 'La description doit contenir au moins 10 caractères.';
            }
            if (empty($category)) {
                $errors[] = 'La catégorie est requise.';
            }
            if (empty($deadline) || strtotime($deadline) < time()) {
                $errors[] = 'La date limite doit être dans le futur.';
            }

            $image = null;
            if (!empty($_FILES['image']['tmp_name'])) {
                $image = $this->uploadImage($_FILES['image'], 'challenge');
                if (!$image) {
                    $errors[] = 'L\'image est invalide (format ou taille non autorisés).';
                }
            }

            if (empty($errors)) {
                $id = $this->challengeModel->create(
                    $this->getCurrentUserId(),
                    $title,
                    $description,
                    $category,
                    $deadline,
                    $image
                );
                $this->flash('success', 'Défi créé avec succès !');
                $this->redirect('challenge', 'show', $id);
                return;
            }

            $this->render('challenge/form', [
                'pageTitle' => 'Créer un défi',
                'errors'    => $errors,
                'old'       => $_POST,
                'challenge' => null,
                'csrf'      => $this->generateCsrfToken(),
            ]);
            return;
        }

        $this->render('challenge/form', [
            'pageTitle' => 'Créer un défi',
            'errors'    => [],
            'old'       => [],
            'challenge' => null,
            'csrf'      => $this->generateCsrfToken(),
        ]);
    }

    /**
     * Formulaire d'édition d'un défi
     */
    public function edit(int $id): void
    {
        $this->requireLogin();

        $challenge = $this->challengeModel->findById($id);
        if (!$challenge || !$this->challengeModel->belongsToUser($id, $this->getCurrentUserId())) {
            $this->flash('error', 'Vous n\'êtes pas autorisé à modifier ce défi.');
            $this->redirect('challenge');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();

            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category    = trim($_POST['category'] ?? '');
            $deadline    = $_POST['deadline'] ?? '';
            $errors      = [];

            if (strlen($title) < 3) {
                $errors[] = 'Le titre doit contenir au moins 3 caractères.';
            }
            if (strlen($description) < 10) {
                $errors[] = 'La description doit contenir au moins 10 caractères.';
            }
            if (empty($category)) {
                $errors[] = 'La catégorie est requise.';
            }
            if (empty($deadline)) {
                $errors[] = 'La date limite est requise.';
            }

            $image = null;
            if (!empty($_FILES['image']['tmp_name'])) {
                $image = $this->uploadImage($_FILES['image'], 'challenge');
                if (!$image) {
                    $errors[] = 'L\'image est invalide.';
                }
            }

            if (empty($errors)) {
                $this->challengeModel->update($id, $title, $description, $category, $deadline, $image);
                $this->flash('success', 'Défi mis à jour avec succès.');
                $this->redirect('challenge', 'show', $id);
                return;
            }

            $this->render('challenge/form', [
                'pageTitle' => 'Modifier le défi',
                'errors'    => $errors,
                'old'       => $_POST,
                'challenge' => $challenge,
                'csrf'      => $this->generateCsrfToken(),
            ]);
            return;
        }

        $this->render('challenge/form', [
            'pageTitle' => 'Modifier le défi',
            'errors'    => [],
            'old'       => $challenge,
            'challenge' => $challenge,
            'csrf'      => $this->generateCsrfToken(),
        ]);
    }

    /**
     * Suppression d'un défi (POST uniquement)
     */
    public function delete(int $id): void
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();

            if ($this->challengeModel->belongsToUser($id, $this->getCurrentUserId())) {
                $this->challengeModel->delete($id);
                $this->flash('success', 'Défi supprimé.');
            } else {
                $this->flash('error', 'Action non autorisée.');
            }
        }

        $this->redirect('challenge');
    }
}