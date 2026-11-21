# MyCubes

MyCubes est une application web développée dans le cadre du projet CSC4101. Elle permet de gérer des Rubik's Cubes, des collections personnelles et des galeries publiques ou privées.

## Technologies utilisées

- PHP 8 et Symfony 7.4
- Doctrine ORM et SQLite
- Twig, Bootstrap, HTML et CSS
- Symfony Security
- VichUploaderBundle
- Git et GitHub

## Modèle de données

L'application repose sur les entités suivantes :

- **Member** : membre identifié par son e-mail, avec un mot de passe et un rôle.
- **CubeCollection** : inventaire personnel d'un membre.
- **Cube** : Rubik's Cube associé à une collection, avec une description et une image.
- **CubeGallery** : galerie permettant de présenter une sélection de cubes.

### Associations

- **Member : CubeCollection** : OneToOne. Un membre possède une collection.
- **CubeCollection : Cube** : OneToMany / ManyToOne. Une collection contient plusieurs cubes.
- **Member : CubeGallery** : OneToMany / ManyToOne. Un membre peut créer plusieurs galeries.
- **Cube : CubeGallery** : ManyToMany. Un cube peut figurer dans plusieurs galeries.
- **Member : Cube** : ManyToMany. Les favoris sont enregistrés dans `member_favorite_cube`.

## Fonctionnalités implémentées

### Gestion des cubes et des collections

- Affichage des collections et des cubes associés.
- Consultation, création, modification et suppression des cubes.
- Ajout d'images avec VichUploaderBundle.
- Navigation entre les cubes et leur collection.

### Membres et authentification

- Liste des membres, consultation des profils et de leur collection.
- Connexion et déconnexion avec Symfony Security.
- Deux rôles : `ROLE_USER` et `ROLE_ADMIN`.
- Accès aux données adapté au membre connecté.

### Galeries de cubes

- Création, modification et suppression des galeries.
- Galeries publiques accessibles aux visiteurs ; galeries privées réservées au propriétaire ou à l'administrateur.
- Consultation d'un cube depuis sa galerie, avec vérification de son appartenance à celle-ci.

### Contrôle d'accès

- Un membre consulte sa collection et ses cubes ; l'administrateur peut tous les consulter.
- Les modifications et suppressions sont réservées au propriétaire.
- Vérification des autorisations côté serveur et protection CSRF des actions concernées.

### Fonctionnalités supplémentaires : partie 18

- **Messages flash** : confirmations après la création, la modification et la suppression des cubes et des galeries.
- **Favoris** : ajout et retrait de cubes présentés dans les galeries publiques des autres membres, avec une page « My Favorites ».
- **Interface personnalisée** : menu latéral et styles dans `public/css/mycubes.css`.

## Pages principales

| Fonctionnalité | URL |
| --- | --- |
| Connexion | `/login` |
| Liste des cubes | `/cube` |
| Détails d'un cube | `/cube/{id}` |
| Liste des collections | `/cube/collection` |
| Détails d'une collection | `/cube/collection/{id}` |
| Liste des membres | `/member` |
| Détails d'un membre | `/member/{id}` |
| Liste des galeries | `/cube/gallery` |
| Détails d'une galerie | `/cube/gallery/{id}` |
| Cube dans une galerie | `/cube/gallery/{cube_gallery_id}/cube/{cube_id}` |
| Mes favoris | `/favorites` |

## Structure du projet

```text
src/
├── Controller/ : Cube, CubeCollection, CubeGallery, Member, Login, Home et Favorite
├── Entity/ : Cube, CubeCollection, CubeGallery et Member
├── DataFixtures/ : AppFixtures et UserFixtures
├── Form/
└── Repository/

templates/ : cube, cube_collection, cube_gallery, member, login, favorite
public/css/mycubes.css
resources/images/
migrations/
```

## Installation et lancement

### Prérequis

- PHP compatible avec Symfony 7.4 et SQLite.
- Composer et Symfony CLI.

### Installer les dépendances

Depuis le répertoire du projet :

```bash
symfony composer install
```

### Base de données

Configurer `DATABASE_URL` pour SQLite, puis appliquer les migrations :

```bash
symfony console doctrine:migrations:migrate
```

Pour vérifier le schéma :

```bash
symfony console doctrine:schema:validate
```

Pour charger les fixtures de test, si nécessaire :

```bash
symfony console doctrine:fixtures:load
```

**Attention :** le chargement des fixtures peut remplacer les données existantes.

### Lancer le serveur

```bash
symfony server:start
```

Ouvrir ensuite l'adresse locale indiquée par Symfony CLI.

### Comptes de démonstration

- **Olivier** : `olivier@localhost` (`ROLE_ADMIN`)
- **Slash** : `slash@localhost` (`ROLE_USER`)

Les mots de passe de démonstration sont définis dans `UserFixtures.php`.

## Auteur

**Asma Belhiba**

Projet CSC4101 : Application Symfony MyCubes
