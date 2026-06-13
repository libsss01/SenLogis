CREATE DATABASE IF NOT EXISTS senlogisbd
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE senlogisbd;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS notes;
DROP TABLE IF EXISTS paiements;
DROP TABLE IF EXISTS commandes;
DROP TABLE IF EXISTS livraisons;
DROP TABLE IF EXISTS conteneurs;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(120) NOT NULL,
  prenom VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  motDePasse VARCHAR(255) NOT NULL,
  telephone VARCHAR(30) NULL,
  etat ENUM('Actif', 'Bloque') NOT NULL DEFAULT 'Actif',
  role_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_roles
    FOREIGN KEY (role_id) REFERENCES roles(id)
    ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE conteneurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(80) NOT NULL UNIQUE,
  statut ENUM('disponible', 'reserve', 'en_livraison', 'maintenance') NOT NULL DEFAULT 'disponible',
  position VARCHAR(150) NOT NULL,
  proprietaire_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_conteneurs_proprietaires
    FOREIGN KEY (proprietaire_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE livraisons (
  id INT AUTO_INCREMENT PRIMARY KEY,
  adresse VARCHAR(255) NOT NULL,
  dateLivraison DATE NOT NULL,
  statut ENUM('en_attente', 'validee', 'en_cours', 'livree', 'annulee') NOT NULL DEFAULT 'en_attente',
  user_id INT NOT NULL,
  conteneur_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_livraisons_users
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_livraisons_conteneurs
    FOREIGN KEY (conteneur_id) REFERENCES conteneurs(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE commandes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATE NOT NULL,
  statut ENUM('en_attente', 'confirmee', 'payee', 'annulee') NOT NULL DEFAULT 'en_attente',
  methode ENUM('en_ligne', 'agence', 'telephone') NOT NULL DEFAULT 'agence',
  user_id INT NOT NULL,
  livraison_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_commandes_users
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_commandes_livraisons
    FOREIGN KEY (livraison_id) REFERENCES livraisons(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE paiements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  montant DECIMAL(12,2) NOT NULL,
  methode ENUM('Wave', 'Orange Money', 'Especes', 'Virement') NOT NULL,
  statut ENUM('en_attente', 'valide', 'echoue') NOT NULL DEFAULT 'en_attente',
  reference VARCHAR(80) NOT NULL UNIQUE,
  commande_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_paiements_commandes
    FOREIGN KEY (commande_id) REFERENCES commandes(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  note INT NOT NULL,
  numLivraison INT NOT NULL,
  user_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT chk_notes_note CHECK (note BETWEEN 1 AND 5),
  CONSTRAINT fk_notes_livraisons
    FOREIGN KEY (numLivraison) REFERENCES livraisons(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_notes_users
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO roles (id, nom) VALUES
  (1, 'client'),
  (2, 'proprietaire'),
  (3, 'admin');

-- Mot de passe commun pour les comptes de demo: password123
INSERT INTO users (id, nom, prenom, email, motDePasse, telephone, etat, role_id) VALUES
  (1, 'Diop', 'Aminata', 'client1@senlogis.test', '$2y$10$ngYm7dmQR0mZA3p.BnXnOec6uqkqmkwV28vyzvdeqZJcF/Jd4eFBC', '+221 77 100 00 01', 'Actif', 1),
  (2, 'Fall', 'Moussa', 'client2@senlogis.test', '$2y$10$ngYm7dmQR0mZA3p.BnXnOec6uqkqmkwV28vyzvdeqZJcF/Jd4eFBC', '+221 77 100 00 02', 'Actif', 1),
  (3, 'Sarr', 'Ibrahima', 'proprietaire@senlogis.test', '$2y$10$ngYm7dmQR0mZA3p.BnXnOec6uqkqmkwV28vyzvdeqZJcF/Jd4eFBC', '+221 77 200 00 01', 'Actif', 2),
  (4, 'SenLogis', 'Administrateur', 'admin@senlogis.test', '$2y$10$ngYm7dmQR0mZA3p.BnXnOec6uqkqmkwV28vyzvdeqZJcF/Jd4eFBC', '+221 77 300 00 01', 'Actif', 3);

INSERT INTO conteneurs (id, nom, statut, position, proprietaire_id) VALUES
  (1, 'CONT-DKR-001', 'en_livraison', 'Port autonome de Dakar', 3),
  (2, 'CONT-DKR-002', 'disponible', 'Rufisque', 3),
  (3, 'CONT-DKR-003', 'reserve', 'Diamniadio', 3),
  (4, 'CONT-DKR-004', 'maintenance', 'Thies', 3),
  (5, 'CONT-DKR-005', 'en_livraison', 'Mbour', 3);

INSERT INTO livraisons (id, adresse, dateLivraison, statut, user_id, conteneur_id) VALUES
  (1, 'Plateau, Dakar', '2026-06-12', 'en_cours', 1, 1),
  (2, 'Rufisque Centre', '2026-06-13', 'validee', 2, 3),
  (3, 'Zone industrielle de Thies', '2026-06-15', 'en_attente', 1, 2),
  (4, 'Mbour Escale', '2026-06-16', 'en_cours', 2, 5),
  (5, 'Kaolack Medina Baye', '2026-06-18', 'livree', 1, 1);

INSERT INTO commandes (id, date, statut, methode, user_id, livraison_id) VALUES
  (1, '2026-06-09', 'payee', 'en_ligne', 1, 1),
  (2, '2026-06-09', 'confirmee', 'agence', 2, 2),
  (3, '2026-06-10', 'en_attente', 'telephone', 1, 3),
  (4, '2026-06-10', 'payee', 'en_ligne', 2, 4),
  (5, '2026-06-08', 'payee', 'agence', 1, 5);

INSERT INTO paiements (id, montant, methode, statut, reference, commande_id) VALUES
  (1, 150000.00, 'Wave', 'valide', 'PAY-20260609-001', 1),
  (2, 225000.00, 'Orange Money', 'valide', 'PAY-20260610-002', 4),
  (3, 175000.00, 'Especes', 'valide', 'PAY-20260608-003', 5);

INSERT INTO notes (id, note, numLivraison, user_id) VALUES
  (1, 5, 5, 1),
  (2, 4, 1, 1),
  (3, 4, 4, 2);
