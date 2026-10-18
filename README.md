# MyCubes

MyCubes est une application web développée dans le cadre du projet CSC4101. Elle permet de gérer des cubes Rubik's, des collections, des membres et des galeries publiques de cubes.

## Technologies utilisées

- PHP 8
- Symfony 7.4
- Doctrine ORM
- Twig
- SQLite
- Bootstrap
- Git et GitHub

## Modèle de données

L'application repose sur les entités suivantes :

- **Member** : représente un membre identifié par son adresse e-mail.
- **CubeCollection** : représente l'inventaire personnel d'un membre.
- **Cube** : représente un cube Rubik's associé à une collection.
- **CubeGallery** : représente une galerie permettant de présenter des cubes.

### Associations

- **CubeCollection – Cube** : OneToMany / ManyToOne. Une collection peut contenir plusieurs cubes.
- **Member – CubeCollection** : OneToOne. Un membre est associé à un inventaire.
- **Cube – CubeGallery** : ManyToMany. Une galerie peut présenter plusieurs cubes et un cube peut apparaître dans plusieurs galeries.

## Fonctionnalités implémentées

### Gestion des cubes

- Affichage de la liste des cubes.
- Consultation des détails d'un cube.
- Création, modification et suppression des cubes à travers les formulaires générés.

### Gestion des collections

- Affichage de la liste des collections.
- Consultation des détails d'une collection et de ses cubes.
- Création, modification et suppression des collections à travers les formulaires générés.
- Navigation entre les cubes et leurs collections.

### Membres

- Affichage de la liste des membres.
- Consultation des informations d'un membre.
- Accès à l'inventaire associé à un membre.
- Navigation entre l'inventaire et la page du membre.

### Galeries de cubes

- Affichage des galeries et consultation de leurs détails.
- Affichage des informations d'un cube dans le contexte de la galerie.
- Vérification que le cube consulté appartient bien à la galerie concernée.

## Pages principales

| Fonctionnalité | URL |
|---|---|
| Liste des cubes | `/cube` |
| Détails d'un cube | `/cube/{id}` |
| Liste des collections | `/cube-collection` |
| Détails d'une collection | `/cube-collection/{id}` |
| Liste des membres | `/member` |
| Détails d'un membre | `/member/{id}` |

Les routes des galeries sont définies dans `CubeGalleryController.php`.

## Structure du projet

```text
src/
├── Controller/
│   ├── CubeController.php
│   ├── CubeCollectionController.php
│   ├── CubeGalleryController.php
│   └── MemberController.php
├── Entity/
│   ├── Cube.php
│   ├── CubeCollection.php
│   ├── CubeGallery.php
│   └── Member.php
├── Form/
└── Repository/

templates/
├── cube/
├── cube_collection/
├── cube_gallery/
└── member/

migrations/
```

## Installation et lancement

### Prérequis

- PHP 8 avec les extensions nécessaires à Symfony et SQLite.
- Composer.
- Symfony CLI.

### Installer les dépendances

Depuis le répertoire du projet, exécuter :

```bash
symfony composer install
```

### Lancer le serveur

```bash
symfony server:start
```

Ouvrir ensuite l'adresse locale indiquée par Symfony CLI dans le navigateur.

### Base de données

La configuration du projet utilise SQLite. Les migrations Doctrine se trouvent dans le répertoire `migrations/`.

Pour vérifier l'état du schéma de base de données :

```bash
symfony console doctrine:schema:validate
```

Pour charger les fixtures de test, si nécessaire :

```bash
symfony console doctrine:fixtures:load
```

**Attention :** le chargement des fixtures peut remplacer les données existantes.

## Auteur

**Asma Belhiba**

Projet CSC4101 — Application Symfony MyCubes
