<?php
session_start();

require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/ChallengeController.php';
require_once 'app/controllers/ProfileController.php';
require_once 'app/controllers/ProfileController.php';


$page = $_GET['page'] ?? 'challenges';

switch ($page) {
    case 'register':   (new AuthController())->register();      break;
    case 'login':      (new AuthController())->login();         break;
    case 'logout':     (new AuthController())->logout();        break;
    case 'challenges': (new ChallengeController())->list();     break;
    case 'challenge':  (new ChallengeController())->detail();   break;
    case 'create':     (new ChallengeController())->create();   break;
    case 'delete':     (new ChallengeController())->delete();   break;
    case 'join':          (new ChallengeController())->join();    break;
    case 'vote':          (new ChallengeController())->vote();    break;
    case 'comment':       (new ChallengeController())->comment(); break;
    case 'deleteAccount': (new ProfileController())->delete(); break;
    case 'profile':     (new ProfileController())->show();   break;
    case 'editProfile': (new ProfileController())->edit();   break;
    case 'follow':      (new ProfileController())->follow(); break;
    case 'deleteAccount': (new ProfileController())->delete(); break;
    case 'deleteComment': 
        $id = (int)($_GET['id'] ?? 0);
        $cid = (int)($_GET['challenge_id'] ?? 0);
        $comment = new Comment();
        require_once 'app/models/Comment.php';
        $comment->delete($id, $_SESSION['user_id']);
        header('Location: index.php?page=challenge&id=' . $cid); exit;
    break;
    default:           (new ChallengeController())->list();     break;
}