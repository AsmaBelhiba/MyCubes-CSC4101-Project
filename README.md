# MyCubes

MyCubes est une application web développée dans le cadre du projet de CSC4101.

Le projet est réalisé avec **Symfony** et **PHP**. Il a pour objectif de gérer des informations liées aux cubes Rubik's, à leurs collections et aux membres de l'application.

## Présentation du projet

L'application contient actuellement trois entités principales :

* **Cube** — représente un cube Rubik's avec sa description et sa collection.
* **CubeCollection** — représente une collection de cubes.
* **Member** — représente un membre de l'application.

Le projet utilise **Doctrine ORM** pour gérer les données de l'application et leur accès à la base de données.

## Fonctionnalités actuelles

Les premières pages publiques ont été mises en place pour l'entité `Cube`.

### Liste des cubes

La page `/cube` permet d'afficher les cubes présents dans la base de données.

Pour chaque cube, on affiche :

* son identifiant ;
* sa description.

Chaque cube peut être sélectionné pour accéder à sa page individuelle.

### Détails d'un cube

Chaque cube possède une page accessible avec l'adresse :

```text
/cube/{id}
```

Cette page affiche les informations du cube sélectionné et propose un lien permettant de revenir à la liste des cubes.

Si l'identifiant ne correspond à aucun cube, l'application retourne une erreur 404.

## Technologies utilisées

* PHP
* Symfony
* Doctrine ORM
* Twig
* HTML
* Git / GitHub

## Structure du projet

Les principaux éléments utilisés actuellement sont :

```text
src/
├── Controller/
│   └── CubeController.php
├── Entity/
│   ├── Cube.php
│   ├── CubeCollection.php
│   └── Member.php
└── Repository/

templates/
└── cube/
    └── index.html.twig
```

## Lancer le projet

Après avoir récupéré le projet et installé les dépendances, lancer le serveur Symfony avec :

```bash
symfony server:start
```

L'application est ensuite accessible à l'adresse locale indiquée par Symfony.

## Développement

Le projet est développé progressivement dans le cadre des travaux pratiques de CSC4101.

De nouvelles pages et fonctionnalités seront ajoutées au fur et à mesure de l'avancement du projet.

## Auteur

**Asma Belhiba**

Projet CSC4101 — Symfony
