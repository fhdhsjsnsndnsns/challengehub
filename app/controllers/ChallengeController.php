<?php
require_once __DIR__ . '/../models/Challenge.php';
require_once __DIR__ . '/../models/Submission.php';
require_once __DIR__ . '/../models/Vote.php';
require_once __DIR__ . '/../models/Comment.php';

class ChallengeController {

    public function list(): void {
        $search    = htmlspecialchars(trim($_GET['search'] ?? ''));
        $categorie = htmlspecialchars(trim($_GET['categorie'] ?? ''));
        $challenge = new Challenge();
        $defis     = $challenge->getAll($search, $categorie);
        require __DIR__ . '/../views/challenges/list.php';
    }
    public function create(): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login'); exit;
    }
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = htmlspecialchars(trim($_POST['titre']));
        $desc  = htmlspecialchars(trim($_POST['description']));
        $cat   = htmlspecialchars(trim($_POST['categorie']));
        $date  = $_POST['date_limite'];
        $image = null;

        if (empty($titre) || empty($desc) || empty($cat) || empty($date)) {
            $error = 'Tous les champs sont obligatoires.';
        } else {
            // Gérer l'upload image
            if (!empty($_FILES['image']['name'])) {
                $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($ext, $allowed)) {
                    $error = 'Format image non autorisé.';
                } elseif ($_FILES['image']['size'] > 3 * 1024 * 1024) {
                    $error = 'Image trop lourde (3MB max).';
                } else {
                    $image = 'challenge_' . time() . '_' . $_SESSION['user_id'] . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], 'public/uploads/' . $image);
                }
            }

            if (empty($error)) {
                $challenge = new Challenge();
                $challenge->create($titre, $desc, $cat, $date, $_SESSION['user_id'], $image);
                header('Location: index.php?page=challenges'); exit;
            }
        }
    }
    require __DIR__ . '/../views/challenges/create.php';
}

   

    public function detail(): void {
        $id         = (int)($_GET['id'] ?? 0);
        $challenge  = new Challenge();
        $submission = new Submission();
        $vote       = new Vote();
        $comment    = new Comment();

        $defi        = $challenge->getById($id);
        if (!$defi) { echo "Défi introuvable."; exit; }

        $aRejoint    = isset($_SESSION['user_id']) ? $submission->hasJoined($_SESSION['user_id'], $id) : false;
        $aVote       = isset($_SESSION['user_id']) ? $vote->hasVoted($_SESSION['user_id'], $id) : false;
        $moyenne     = $vote->getAverage($id);
        $nbVotes     = $vote->getCount($id);
        $nbPart      = $submission->countByChallengeId($id);
        $commentaires = $comment->getByChallengeId($id);

        require __DIR__ . '/../views/challenges/detail.php';
    }

    public function join(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login'); exit;
        }
        $id = (int)($_GET['id'] ?? 0);
        $submission = new Submission();
        $submission->join($_SESSION['user_id'], $id);
        header('Location: index.php?page=challenge&id=' . $id); exit;
    }

    public function vote(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login'); exit;
        }
        $id   = (int)($_POST['challenge_id'] ?? 0);
        $note = (int)($_POST['note'] ?? 0);
        if ($note < 1 || $note > 5) {
            header('Location: index.php?page=challenge&id=' . $id); exit;
        }
        $vote = new Vote();
        $vote->add($_SESSION['user_id'], $id, $note);
        header('Location: index.php?page=challenge&id=' . $id); exit;
    }

    public function comment(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login'); exit;
        }
        $id      = (int)($_POST['challenge_id'] ?? 0);
        $contenu = htmlspecialchars(trim($_POST['contenu'] ?? ''));
        if (!empty($contenu)) {
            $comment = new Comment();
            $comment->add($_SESSION['user_id'], $id, $contenu);
        }
        header('Location: index.php?page=challenge&id=' . $id); exit;
    }

    public function delete(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login'); exit;
        }
        $id = (int)($_GET['id'] ?? 0);
        $challenge = new Challenge();
        $challenge->delete($id, $_SESSION['user_id']);
        header('Location: index.php?page=challenges'); exit;
    }
}