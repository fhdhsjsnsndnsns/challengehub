<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function register(): void {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom    = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom'])); 
        $email  = htmlspecialchars(trim($_POST['email']));
        $pass   = $_POST['password'];

        $user = new User();

        if (empty($nom) || empty($prenom) || empty($email) || empty($pass)) {
            $error = 'Tous les champs sont obligatoires.';
        } elseif ($user->emailExists($email)) {
            $error = 'Cet email est déjà utilisé.';
        } elseif (strlen($pass) < 6) {
            $error = 'Mot de passe trop court (6 caractères min).';
        } else {
            $user->create($nom, $prenom, $email, $pass);
            header('Location: index.php?page=login');
            exit;
        }
    }
    require __DIR__ . '/../views/auth/register.php';
}

    public function login(): void {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $pass  = $_POST['password'];
            $user  = new User();
            $data  = $user->findByEmail($email);

            if ($data && password_verify($pass, $data['motdepasse'])) {
                $_SESSION['user_id']  = $data['id'];
                $_SESSION['user_nom'] = $data['nom'];
                $_SESSION['user_photo'] = $data['photo'];
                header('Location: index.php?page=challenges');
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect.';
            }
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}