# Interface d'administration du blog

## Liens utiles

[Documentation](https://symfony.com/bundles/EasyAdminBundle/current/index.html)
[SymfonyCast](https://symfonycasts.com/screencast/easyadminbundle)

## Divers

[Branche du cours](https://github.com/devoreve/symfony-tp/tree/cours-s2-j3)
[Branche de la correction](https://github.com/devoreve/symfony-tp/tree/correction-s2-j3)

## Objectifs

* Travailler avec le QueryBuilder de Symfony
* Utiliser le bundle EasyAdmin

## Instructions

### 1. Article suivant et article précédent (côté front office)

Afficher sur la page de détail d'un article un lien qui emmène vers l'article suivant (l'article qui a été créé après celui sur lequel on est) et un lien qui emmène l'article précédent (l'article qui est a été créé avant celui sur lequel on est).

### 2. Créer un lien vers le front office

Dans le menu de navigation, créer un lien vers la page d'accueil du front office.

### 3. Afficher les commentaires et les articles en fonction de la date

Au lieu de l'affichage généré par défaut par le CRUD controller, faire en sorte d'afficher les articles du plus récent au plus ancien et les commentaires du plus ancien au plus récent.

### 4. Ajouter le CRUD pour les utilisateurs

Ajouter le CRUD pour les utilisateurs avec la possibilité de pouvoir changer le rôle d'un utilisateur.

### 5. Traduction des éléments vers le français

Utiliser le système de traduction de Symfony pour traduire les différents éléments en français.

### BONUS. Dupliquer un article

Lorsque l'on modifie un article, ajouter une option qui permet de dupliquer l'article modifié (créer un article avec les mêmes données sauf l'id et la date de création).