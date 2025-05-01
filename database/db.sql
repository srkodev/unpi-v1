-- ============================================================
--  Script de création de la base et des tables (MySQL 8)
--  Change le mot de passe avant mise en production !
-- ============================================================

-- ---------- Base de données ----------
DROP DATABASE IF EXISTS fdpci;
CREATE DATABASE fdpci
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE fdpci;

-- ---------- Utilisateur dédié ----------
DROP USER IF EXISTS 'fdpci_user'@'localhost';
CREATE USER 'fdpci_user'@'localhost' IDENTIFIED BY 'ChangeMe#2025';
GRANT ALL PRIVILEGES ON fdpci.* TO 'fdpci_user'@'localhost';
FLUSH PRIVILEGES;

-- ============================================================
--  Tables
-- ============================================================

-- ---------- Biens ----------
CREATE TABLE biens (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre         VARCHAR(255)                            NOT NULL,
  type          ENUM('vente','location','location_etudiante')    NOT NULL,
  adresse       VARCHAR(255),
  surface_m2    INT,
  chambres      TINYINT,
  salles_eau    TINYINT,
  prix          DECIMAL(12,2),
  description   TEXT,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE bien_images (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  bien_id     BIGINT UNSIGNED                       NOT NULL,
  url         VARCHAR(512)                          NOT NULL,
  is_primary  TINYINT(1) DEFAULT 0,   -- 1 = image principale
  position    SMALLINT UNSIGNED DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (bien_id) REFERENCES biens(id) ON DELETE CASCADE,
  INDEX (bien_id),
  INDEX (bien_id, is_primary)
);

-- ---------- Actualités ----------
CREATE TABLE actualites (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre       VARCHAR(255)                          NOT NULL,
  slug        VARCHAR(255) UNIQUE,
  categorie   ENUM('juridique','formation','evenement','autre') NOT NULL,
  extrait     TEXT,
  contenu     LONGTEXT,
  publie_le   DATE,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE actualite_images (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  actualite_id  BIGINT UNSIGNED                       NOT NULL,
  url           VARCHAR(512)                          NOT NULL,
  is_primary    TINYINT(1) DEFAULT 0,   -- 1 = image principale
  position      SMALLINT UNSIGNED DEFAULT 0,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (actualite_id) REFERENCES actualites(id) ON DELETE CASCADE,
  INDEX (actualite_id),
  INDEX (actualite_id, is_primary)
);

-- ---------- Partenaires ----------
CREATE TABLE partenaires (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom         VARCHAR(255) NOT NULL,
  logo_url    VARCHAR(512),
  site_url    VARCHAR(512),
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- ---------- Administrateurs ----------
CREATE TABLE administrateurs (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email       VARCHAR(255) NOT NULL UNIQUE,
  password    VARCHAR(255) NOT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Modification de la table biens pour les nouveaux types
ALTER TABLE biens 
    MODIFY COLUMN type VARCHAR(20) NOT NULL;

-- Mise à jour des types existants
UPDATE biens SET type = 'vente' WHERE type IN ('appartement', 'maison', 'local');

-- Modification finale de la colonne type
ALTER TABLE biens 
    MODIFY COLUMN type ENUM('vente','location','location_etudiante') NOT NULL;

-- ---------- Ajout d'un administrateur par défaut ----------
-- Attention : Ce compte est à modifier en production !
INSERT INTO administrateurs (email, password, created_at) 
VALUES ('admin@fdcpi.fr', '$2y$10$Oa1HSgiJEVivJ.vWgf85iOYsUGp34QYJt.cGl9qDlHDQkTTGQyWUu', NOW());
-- Le mot de passe haché correspond à 'admin'
