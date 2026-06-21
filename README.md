# SenLogis

SenLogis est une application web PHP/MySQL de gestion logistique autour des conteneurs, des demandes de livraison, des commandes et des paiements. Le projet propose une vitrine publique et plusieurs espaces connectes selon le role de l'utilisateur : client, proprietaire ou administrateur.

## Probleme resolu

Dans une activite de transport ou de location de conteneurs, il devient vite difficile de suivre les disponibilites, les demandes clients, les livraisons, les commandes et les paiements dans des fichiers separes ou par messages. SenLogis centralise ces operations dans une interface web afin de faciliter le suivi entre clients, proprietaires de conteneurs et administrateurs.

## Fonctionnalites principales

- Page vitrine pour presenter SenLogis, ses services, ses avantages, ses appels a l'action et sa FAQ.
- Inscription et connexion avec mot de passe hashe.
- Selection du role apres inscription pour les comptes client ou proprietaire.
- Tableau de bord client avec conteneurs disponibles, demandes, livraisons et paiements.
- Creation de demandes de livraison par les clients a partir d'un conteneur disponible.
- Espace proprietaire pour suivre ses conteneurs, les demandes recues et les paiements associes.
- Espace administrateur pour gerer les utilisateurs, conteneurs, livraisons, commandes, paiements et notes.
- Gestion des statuts de conteneurs, livraisons, commandes et paiements.
- Comptage simple des visites de la vitrine via un fichier JSON local.

## Stack technique

- PHP procedural
- MySQL
- PDO pour les requetes SQL
- HTML5, CSS3 et JavaScript
- Bootstrap et templates front/admin integres dans `public/template`
- Apache avec `mod_rewrite` pour les routes propres

## Structure du projet

```txt
SenLogis/
|-- controller/          # Traitement des actions utilisateur
|-- database/            # Script SQL de creation et de donnees de demo
|-- model/               # Acces aux donnees via PDO
|-- public/              # CSS, images et templates
|-- storage/             # Stockage JSON des visites vitrine
|-- views/               # Vues vitrine et vues admin
|-- .htaccess            # Routes Apache
|-- index.php            # Page vitrine
|-- login.php            # Connexion
|-- register.php         # Inscription
|-- role_selection.php   # Choix du role
|-- admin.php            # Tableau de bord administrateur
|-- client_*.php         # Pages de l'espace client
`-- owner_*.php          # Pages de l'espace proprietaire
```

## Prerequis

- PHP 8.x recommande
- MySQL ou MariaDB
- Apache
- XAMPP, WAMP, Laragon ou un environnement equivalent
- Extension PHP PDO MySQL activee
- Module Apache `mod_rewrite` active

## Installation locale

1. Cloner le depot :

```sh
git clone https://github.com/libsss01/SenLogis.git
```

2. Placer le dossier dans le repertoire web Apache, par exemple :

```txt
C:/xampp/htdocs/SenLogis
```

Le nom `SenLogis` est important, car plusieurs liens du projet utilisent le chemin absolu `/SenLogis/...`.

3. Demarrer Apache et MySQL depuis XAMPP ou votre outil local.

4. Creer la base de donnees et importer les donnees :

```sql
SOURCE database/senlogisbd.sql;
```

Vous pouvez aussi importer le fichier `database/senlogisbd.sql` depuis phpMyAdmin.

5. Verifier la configuration de connexion dans `model/Model.php` :

```php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "senlogisbd";
```

6. Ouvrir l'application :

```txt
http://localhost/SenLogis/
```

## Comptes de demo

Le script SQL fournit des comptes de test. Le mot de passe commun est :

```txt
password123
```

Comptes disponibles :

- Client : `client1@senlogis.test`
- Client : `client2@senlogis.test`
- Proprietaire : `proprietaire@senlogis.test`
- Administrateur : `admin@senlogis.test`

## Routes principales

La reecriture d'URL est definie dans `.htaccess`.

```txt
/SenLogis/                  Page vitrine
/SenLogis/login.php         Connexion
/SenLogis/register.php      Inscription
/SenLogis/clientDashboard   Tableau de bord client
/SenLogis/dashboardProprietaire
/SenLogis/admin
/SenLogis/listeUsers
/SenLogis/listeConteneurs
/SenLogis/listeLivraisons
/SenLogis/listeCommandes
/SenLogis/listePaiements
/SenLogis/listeNotes
```

## Base de donnees

La base `senlogisbd` contient les tables suivantes :

- `roles`
- `users`
- `conteneurs`
- `livraisons`
- `commandes`
- `paiements`
- `notes`

Les relations permettent de relier un client a ses livraisons et commandes, un proprietaire a ses conteneurs, une commande a une livraison, un paiement a une commande et une note a une livraison.

## Etat du projet

SenLogis est un projet web en cours d'evolution. Il contient deja une base fonctionnelle pour l'authentification, les roles, la gestion des conteneurs, le suivi des demandes et les tableaux de bord.

## Pistes d'amelioration

- Ajouter un fichier `.env` pour sortir les identifiants de base de donnees du code.
- Ajouter une couche de validation plus centralisee.
- Ajouter une protection CSRF sur les formulaires.
- Ajouter des tests fonctionnels sur les actions critiques.
- Prevoir un mode de deploiement en production avec configuration Apache documentee.
- Ajouter des captures d'ecran dans le README.

## Auteur

Mame Libasse Laye Sylla
