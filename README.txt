Projet CSC4101 - myCubes
Auteur : Asma Belhiba

THEME DU PROJET
Application web Symfony de gestion de collections de cubes de Rubik.

MODELE DE DONNEES
- Member : membre de l'application, identifie par son adresse email.
- CubeCollection : collection de cubes, avec une description.
- Cube : cube appartenant a une collection.

ASSOCIATIONS
- CubeCollection et Cube : association OneToMany / ManyToOne.
  Une collection peut contenir plusieurs cubes.
  Chaque cube appartient a une collection.
- Member et CubeCollection : association OneToOne.

FONCTIONNALITES IMPLEMENTEES
- Affichage de la liste des cubes.
- Consultation des caracteristiques d'un cube.
- Affichage de la liste des collections.
- Consultation d'une collection et de ses cubes.
- Navigation d'une collection vers ses cubes.
- Navigation d'un cube vers sa collection.

TECHNOLOGIES
- PHP 8
- Symfony 7.4
- Doctrine ORM
- Twig
- SQLite
- Bootstrap

BASE DE DONNEES
La configuration par defaut utilise SQLite.
Le fichier de base de donnees de developpement est situe dans var/data_dev.db.

INSTRUCTIONS POUR INSTALLER ET LANCER LE PROJET

Prerequis :
- PHP 8 avec les extensions necessaires a Symfony et SQLite.
- Composer.
- Symfony CLI.

1. Ouvrir un terminal dans le repertoire du projet extrait.

2. Installer les dependances :
   symfony composer install

   Si l'installation signale un probleme lie au cache,
   verifier les messages affiches par Composer et Symfony.

3. Lancer le serveur de developpement :
   symfony server:start

4. Ouvrir dans un navigateur l'adresse indiquee par Symfony CLI,
   generalement :
   https://127.0.0.1:8000
   ou
   http://127.0.0.1:8000

5. Pages principales :
   - Liste des collections : /cube-collection
   - Liste des cubes : /cube
   - Consultation d'une collection : /cube-collection/{id}
   - Consultation d'un cube : /cube/{id}

BASE DE DONNEES ET DONNEES DE TEST

La configuration par defaut utilise SQLite avec une base de
developpement situee dans var/data_dev.db.

Commande de chargement des fixtures :
   symfony console doctrine:fixtures:load

Attention : cette commande peut remplacer les donnees existantes.
Ne l'executer que si cela est approprie pour l'environnement de test.
