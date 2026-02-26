-- ═══════════════════════════════════════════════════════════════
--  ChallengeHub — Script SQL complet
--  Base de données : challengehub
--  Encodage       : utf8mb4 (support emojis et caractères spéciaux)
-- ═══════════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS challengehub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE challengehub;

-- ───────────────────────────────────────────────────────────────
-- TABLE : users
-- ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    name        VARCHAR(100)    NOT NULL,
    email       VARCHAR(150)    NOT NULL,
    password    VARCHAR(255)    NOT NULL,           -- stocké via password_hash()
    avatar      VARCHAR(255)    NULL DEFAULT NULL,  -- nom du fichier uploadé
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ───────────────────────────────────────────────────────────────
-- TABLE : challenges
-- ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS challenges (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    user_id     INT UNSIGNED    NOT NULL,
    title       VARCHAR(200)    NOT NULL,
    description TEXT            NOT NULL,
    category    VARCHAR(100)    NOT NULL,
    deadline    DATE            NOT NULL,
    image       VARCHAR(255)    NULL DEFAULT NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_challenges_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_challenges_category (category),
    INDEX idx_challenges_deadline (deadline),
    INDEX idx_challenges_created  (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ───────────────────────────────────────────────────────────────
-- TABLE : submissions  (classe centrale)
-- ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS submissions (
    id           INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    user_id      INT UNSIGNED   NOT NULL,
    challenge_id INT UNSIGNED   NOT NULL,
    description  TEXT           NOT NULL,
    media        VARCHAR(255)   NULL DEFAULT NULL,  -- image uploadée ou lien externe
    created_at   DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_submissions_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_submissions_challenge
        FOREIGN KEY (challenge_id) REFERENCES challenges(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_submissions_challenge (challenge_id),
    INDEX idx_submissions_user      (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ───────────────────────────────────────────────────────────────
-- TABLE : comments
-- ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS comments (
    id            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    user_id       INT UNSIGNED  NOT NULL,
    submission_id INT UNSIGNED  NOT NULL,
    content       TEXT          NOT NULL,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_comments_submission
        FOREIGN KEY (submission_id) REFERENCES submissions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_comments_submission (submission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ───────────────────────────────────────────────────────────────
-- TABLE : votes
-- Contrainte UNIQUE : un user ne peut voter qu'une fois par submission
-- ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS votes (
    id            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    user_id       INT UNSIGNED  NOT NULL,
    submission_id INT UNSIGNED  NOT NULL,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_votes_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_votes_submission
        FOREIGN KEY (submission_id) REFERENCES submissions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- Empêche un double vote au niveau BDD (filet de sécurité en plus du PHP)
    UNIQUE KEY uq_votes_user_submission (user_id, submission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ═══════════════════════════════════════════════════════════════
--  DONNÉES DE TEST (à supprimer avant la démo finale)
-- ═══════════════════════════════════════════════════════════════

-- Utilisateurs (mots de passe : "password123" hashé avec bcrypt)
INSERT INTO users (name, email, password) VALUES
('Alice Martin',  'alice@test.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Bob Dupont',    'bob@test.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Clara Leroy',   'clara@test.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Défis
INSERT INTO challenges (user_id, title, description, category, deadline) VALUES
(1, 'Défi Photo Noir & Blanc',  'Prenez une photo artistique en noir et blanc.', 'Photographie', '2026-03-20'),
(2, 'Poème de 10 mots',         'Écrivez un poème percutant en exactement 10 mots.', 'Écriture', '2026-03-15'),
(1, 'Logo minimaliste',         'Créez un logo avec 3 formes maximum.', 'Design', '2026-03-25');

-- Participations
INSERT INTO submissions (user_id, challenge_id, description) VALUES
(2, 1, 'Ma photo prise au lever du soleil dans la médina.'),
(3, 1, 'Portrait en contre-jour avec un reflet dans une flaque.'),
(1, 2, 'Silence. Lumière. Un sourire. Tout recommence enfin.'),
(3, 3, 'Logo épuré : cercle, triangle, ligne. Minimalisme total.');

-- Commentaires
INSERT INTO comments (user_id, submission_id, content) VALUES
(1, 1, 'Superbe cadrage, la lumière est parfaite !'),
(3, 1, 'J\'adore l\'ambiance de cette photo.'),
(2, 3, 'Court et puissant, bravo !');

-- Votes
INSERT INTO votes (user_id, submission_id) VALUES
(1, 1),
(3, 1),
(1, 2),
(2, 3),
(3, 3),
(1, 4);
