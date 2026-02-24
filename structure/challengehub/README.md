# ChallengeHub — Structure du projet

## Installation (WAMPServer)

1. Cloner le repo dans `C:/wamp64/www/challengehub`
2. Importer `config/database.sql` dans phpMyAdmin
3. Vérifier `config/config.php` (DB_USER, DB_PASS, APP_URL)
4. Accéder via `http://localhost/challengehub`

## Architecture MVC

```
challengehub/
├── index.php                  ← Routeur central (point d'entrée unique)
├── config/
│   ├── config.php             ← Constantes globales (BDD, chemins, sécurité)
│   └── Database.php           ← Classe PDO Singleton
├── app/
│   ├── controllers/
│   │   ├── BaseController.php ← Classe mère (render, redirect, CSRF, session)
│   │   ├── HomeController.php ← Page d'accueil + classement      [P1]
│   │   ├── UserController.php ← Auth, profil, suppression        [P2]
│   │   ├── ChallengeController.php ← CRUD défis                  [P3]
│   │   ├── SubmissionController.php ← CRUD participations        [P4]
│   │   ├── CommentController.php ← Commentaires                  [P5]
│   │   └── VoteController.php ← Système de vote                  [P4]
│   ├── models/
│   │   ├── User.php           [P2]
│   │   ├── Challenge.php      [P3]
│   │   ├── Submission.php     [P4]
│   │   ├── Comment.php        [P5]
│   │   └── Vote.php           [P4]
│   └── views/
│       ├── layouts/
│       │   ├── main.php       ← Template HTML principal          [P1]
│       │   └── 404.php        ← Page d'erreur
│       ├── home/              [P1]
│       ├── user/              [P2]
│       ├── challenge/         [P3]
│       ├── submission/        [P4]
│       └── comment/           [P5]
└── public/
    ├── css/style.css          ← CSS global                       [P5]
    ├── js/app.js              ← JS global                        [P5]
    └── images/uploads/        ← Images uploadées par les users

## Routing

URL format : `index.php?controller=X&action=Y&id=Z`

Exemples :
- `/index.php` → HomeController::index()
- `/index.php?controller=user&action=login` → UserController::login()
- `/index.php?controller=challenge&action=show&id=5` → ChallengeController::show(5)

## Conventions GitHub

- Branches : `feature/auth`, `feature/challenges`, `feature/submissions-votes`, `feature/comments-ui`
- Commits : `[P2] feat: add login form validation`
- PR → review par P1 avant merge sur main
```
