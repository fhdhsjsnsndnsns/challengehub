<?php
require_once __DIR__ . '/../models/User.php';

class ProfileController {

    public function show(): void {
    $userId = (int)($_GET['id'] ?? $_SESSION['user_id'] ?? 0);
    if (!$userId) {
        header('Location: index.php?page=login'); exit;
    }

    $user      = new User();
    $profil    = $user->getById($userId);
    if (!$profil) { echo "Utilisateur introuvable."; exit; }

    require_once __DIR__ . '/../models/Follow.php';
    $follow     = new Follow();
    $nbFollowers  = $follow->countFollowers($userId);
    $nbFollowing  = $follow->countFollowing($userId);
    $nbDefis      = $user->countDefis($userId);
    $defisGroupes = $user->getDefisParCategorie($userId);
    $isFollowing  = isset($_SESSION['user_id'])
                    ? $follow->isFollowing($_SESSION['user_id'], $userId)
                    : false;
    $isOwnProfile = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId;

    require __DIR__ . '/../views/profile/show.php';
}

public function follow(): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login'); exit;
    }
    $userId = (int)($_GET['id'] ?? 0);
    require_once __DIR__ . '/../models/Follow.php';
    $follow = new Follow();

    if ($follow->isFollowing($_SESSION['user_id'], $userId)) {
        $follow->unfollow($_SESSION['user_id'], $userId);
    } else {
        $follow->follow($_SESSION['user_id'], $userId);
    }
    header('Location: index.php?page=profile&id=' . $userId); exit;
}
public function edit(): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login'); exit;
    }
    $error   = '';
    $success = '';
    $user    = new User();
    $profil  = $user->getById($_SESSION['user_id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // ── Modifier photo ──────────────────────────────
        if (isset($_POST['update_photo'])) {
            if (!empty($_FILES['photo']['name'])) {
                $ext      = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($ext, $allowed)) {
                    $error = 'Format non autorisé. Utilise jpg, png ou gif.';
                } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
                    $error = 'Image trop lourde (2MB max).';
                } else {
                    // Supprimer l'ancienne photo
                    if (!empty($profil['photo']) && file_exists('public/uploads/' . $profil['photo'])) {
                        unlink('public/uploads/' . $profil['photo']);
                    }
                    $filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
                    move_uploaded_file($_FILES['photo']['tmp_name'], 'public/uploads/' . $filename);
                    $user->updatePhoto($_SESSION['user_id'], $filename);
                    $profil  = $user->getById($_SESSION['user_id']);
                    $success = 'Photo mise à jour !';
                    $_SESSION['user_photo'] = $filename;
                }
            } else {
                $error = 'Aucune photo sélectionnée.';
            }
        }

        // ── Supprimer photo ─────────────────────────────
        if (isset($_POST['delete_photo'])) {
            if (!empty($profil['photo']) && file_exists('public/uploads/' . $profil['photo'])) {
                unlink('public/uploads/' . $profil['photo']);
            }
            $user->updatePhoto($_SESSION['user_id'], null);
            $profil  = $user->getById($_SESSION['user_id']);
            $success = 'Photo supprimée.';
            $_SESSION['user_photo'] = null;
        }

        // ── Modifier infos ──────────────────────────────
        if (isset($_POST['update_info'])) {
            $nom    = htmlspecialchars(trim($_POST['nom']));
            $prenom = htmlspecialchars(trim($_POST['prenom']));
            $email  = htmlspecialchars(trim($_POST['email']));

            if (empty($nom) || empty($prenom) || empty($email)) {
                $error = 'Tous les champs sont obligatoires.';
            } else {
                $user->update($_SESSION['user_id'], $nom, $prenom, $email);
                $_SESSION['user_nom'] = $nom;
                $profil  = $user->getById($_SESSION['user_id']);
                $success = 'Profil mis à jour !';
            }
        }

        // ── Modifier mot de passe ───────────────────────
        if (isset($_POST['update_password'])) {
            $ancien  = $_POST['ancien_password'];
            $nouveau = $_POST['nouveau_password'];
            $confirm = $_POST['confirm_password'];

            if (!password_verify($ancien, $profil['motdepasse'])) {
                $error = 'Ancien mot de passe incorrect.';
            } elseif (strlen($nouveau) < 6) {
                $error = 'Mot de passe trop court (6 car. min).';
            } elseif ($nouveau !== $confirm) {
                $error = 'Les mots de passe ne correspondent pas.';
            } else {
                $user->updatePassword($_SESSION['user_id'], $nouveau);
                $success = 'Mot de passe modifié !';
            }
        }
    }

    require __DIR__ . '/../views/profile/edit.php';
}
    

    
    

    public function delete(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login'); exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
            $user = new User();
            $user->delete($_SESSION['user_id']);
            session_destroy();
            header('Location: index.php?page=register'); exit;
        }
        require __DIR__ . '/../views/profile/delete.php';
    }
}