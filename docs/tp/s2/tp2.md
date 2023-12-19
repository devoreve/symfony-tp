# Gestion des tags et sauvegarde des articles

## Divers

[Branche du cours](https://github.com/devoreve/symfony-tp/tree/cours-s2-j2)
[Branche de la correction](https://github.com/devoreve/symfony-tp/tree/correction-s2-j2)

## Objectifs

* Gérer les droits utilisateurs
* Manipuler l'orm et le query builder

## Instructions

### Mise à niveau

* Installer le composant autocomplete et l'ajouter pour les catégories et les tags
* Créer le voter pour la gestion de l'édition des articles
* Créer la page "Liste des favoris" et afficher les favoris en première page

### 1. Mettre en avant un article

Sur les articles, rajouter une propriété *featured* qui sera un booléen. 
Au niveau de l'ajout et de l'édition d'un article, ce sera une case à cocher qui indiquera si l'article est mis en avant ou non.
Seul l'administrateur (ROLE_SUPER_ADMIN) peut indiquer qu'un article est mis en avant (la case à cocher n'apparaît que chez lui.

### 2. Articles mise en avant sur l'accueil

Sur la page des articles, afficher d'abord les articles mis en avant (utiliser une icône/couleur différente pour que cette information soit plus visuelle) puis les autres articles.

### 3. Créer une pagination pour la liste des articles

Mettre en place une pagination (sans utiliser de bundle) pour la liste des articles (afficher 10 articles par page).

### BONUS. Article sauvegardé localement

Lorsque l'on commence à écrire un article, le contenu va être sauvegardé périodiquement en local pour éviter de tout perdre en cas de mauvaise manipulation (ou si l'on veut reprendre la saisie plus tard).