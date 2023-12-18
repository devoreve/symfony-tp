# TP 3

## Objectif

Le but de l'exercice est de savoir utiliser les relations entre les entités (oneToMany) ainsi que le système de formulaire intégré à Symfony.

## Liens utiles

[Formulaires](https://symfony.com/doc/current/forms.html)
[Fixtures](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)
[Relations](https://symfony.com/doc/current/doctrine/associations.html)
[Doctrine](https://symfony.com/doc/current/doctrine.html)

## Divers

[Url du dépôt](https://github.com/devoreve/symfony-tp)
[Branche du cours](https://github.com/devoreve/symfony-tp/tree/cours-j3)
[Branche de la correction](https://github.com/devoreve/symfony-tp/tree/correction-j3)

## Instructions

### 1. Ajout d'article

Utiliser les formulaires de Symfony pour réaliser l'ajout d'un article. Au niveau de la validation, le titre de l'article ne peut être vide et doit être constituée d'au-moins 5 caractères.

### 2. Ajout de catégories

* Créer une entité *Category* (name de type string, taille 100, obligatoire)
* Créer une relation entre les catégories et les articles (un article peut avoir une seule catégorie, une catégorie se retrouve sur un ou plusieurs articles)
* Modifier les fixtures pour créer plusieurs catégories (Voyage, Cinéma, Musique, Cuisine, Voiture) et ajouter une catégorie sur tous les articles créés
* Modifier le formulaire d'ajout d'articles pour afficher un menu déroulant avec toutes les catégories
* Enregistrer l'article avec la catégorie sélectionnée

### 3. Modification d'un article

Sur la page détail de l'article, afficher en-dessous de la date de création le nom de la catégorie de l'article.

Ajouter un lien "Modifier l'article" sur la page détail de l'article. Lorsque l'on clique sur ce lien, on arrive sur le formulaire d'édition de l'article (avec les champs pré-remplis contenant les informations de l'article) et lorsque l'on soumet ce formulaire, cela modifie l'article en base de données.

### [BONUS 1] Ajout du slug dans l'url

Créer un "slug" ("Bonjour tout le monde" -> "bonjour-tout-le-monde") sur chacun des articles. Au lieu de récupérer le détail de l'article à partir de l'id, récupérer le slug.

### [BONUS 2] Ajouter le bouton suivant et précédent

Sur le page de détail de l'article, créer des liens "Suivant" et "Précédent" qui vous emmènent respectivement vers l'article suivant ou l'article précédent (les articles sont triés par rapport à leur date de création).