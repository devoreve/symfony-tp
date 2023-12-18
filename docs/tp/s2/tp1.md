# Gestion des tags et sauvegarde des articles

## Liens utiles

[Foundry](https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html)
[ManyToMany](https://symfonycasts.com/screencast/doctrine-relations/many-to-many)

## Divers

[Dépôt github du projet](https://github.com/devoreve/symfony-tp)
[Branche du cours](https://github.com/devoreve/symfony-tp/tree/cours-s2-j1)
[Branche de la correction](https://github.com/devoreve/symfony-tp/tree/correction-s2-j1)

## Objectifs

* Ajouter des tags lors de la création d'article
* Voir tous les tags d'un article
* Modifier les tags d'un article
* Sauvegarder un article dans les favoris de l'utilisateur
* Afficher tous les articles favoris

## Instructions

### 1. Création des entités et factory

Créer les entités nécessaires pour ajouter la gestion des tags et de la sauvegarde des articles dans les favoris utilisateur.
Un "tag" est une étiquette attribuée à un élément (article) qui va permettre de mieux cibler l'article.

Créer une factory pour les tags.
Exemples de tags à créer : php, js, symfony, laravel, dev web, dev jeux, unity, thriller, fantasy, aventure, rpg...

Un article peut avoir plusieurs tags.

Lorsque l'on enregistrera un article dans les favoris, on stockera la date (et heure) à laquelle l'article a été ajouté à nos favoris.

### 2. Ajouter/Modifier un tag sur un article

Lorsque l'on ajoute un nouvel article, on peut y ajouter des tags. 
Lorsque l'on modifie l'article, on peut ajouter/retirer des tags à cet article.

### 3. Afficher les tags des articles

Lorsque l'on va sur la page d'un article, on veut pouvoir afficher tous les tags de cet article.

### 4. Ajout d'un article en favori

Sur la page d'un article, créer un lien "Sauvegarder l'article" (qui n'apparaîtra que si l'utilisateur est connecté). 
Lorsque l'on clique dessus, on ajoute l'article aux favoris de l'utilisateur connecté ou on le retire si cet article a déjà été ajouté aux favoris.
On redirige ensuite vers l'article sur lequel on a cliqué.

En **bonus** on peut afficher une notification en haut de la page pour indiquer que l'article a bien été ajouté aux favoris ou retiré des favoris.

### 5. Affichage des articles favoris

Créer un lien "Mes articles favoris" dans la navigation (seulement si on est connecté). Lorsque l'on va sur cette page, on affiche tous les articles dans les favoris de l'utilisateur.

Sur la page d'accueil, si l'utilisateur est connecté, afficher les 3 derniers articles ajoutés dans les favoris.

### BONUS. Filtrer par tag

Créer un filtre dans une sidebar avec la liste des tags. Lorsque l'on valide ce formulaire de filtre, on affiche tous les articles aux tags sélectionnés.