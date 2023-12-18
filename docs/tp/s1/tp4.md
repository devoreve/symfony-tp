# TP 4

## Objectif

Le but de l'exercice est de savoir utiliser le système d'authentification de Symfony.

## Liens utiles

[Securité](https://symfony.com/doc/current/security.html)

## Divers

[Url du dépôt](https://github.com/devoreve/symfony-tp)
[Branche du cours](https://github.com/devoreve/symfony-tp/tree/cours-j4)
[Branche de la correction](https://github.com/devoreve/symfony-tp/tree/correction-j4)

## Instructions

### 1. Inscription/Connexion

Mettre en place le système d'inscription/connexion sur le site.

### 2. Ajout d'informations sur l'utilisateur

Mettre à jour l'entité *User* comme ceci :
* Créer une propriété createdAt (qui sera la date du jour par défaut)
* Créer une propriété name (qui sera renseigné dans le formulaire d'inscription)

Effectuer les modifications en base de données.

### 3. Navigation

Ajouter les liens "Inscription" et "Connexion" dans la barre de navigation lorsque l'utilisateur n'est pas connecté. Si l'utilisateur est connecté, afficher le lien "Déconnexion". Afficher le nom de l'utilisateur dans la barre de navigation.

### 4. Rattacher un article à l'utilisateur

* Créer une relation entre les utilisateurs et les articles (ne pas oublier la mise à jour du schéma de la base de données)
* Modifier les fixtures pour ajouter quelques utilisateurs et les rattacher aux articles
* Lorsqu'un article est créé, rattacher l'utilisateur connecté à cet article

### 5. Vérification de l'authentification

Lorsque l'utilisateur souhaite créer un article, il faut vérifier au préalable qu'il est bien connecté. S'il n'est pas connecté, il sera renvoyé sur la page de connexion.

### 6. Vérification de l'autorisation

Lorsque l'utilisateur souhaite modifier un article, il faut vérifier au préalable qu'il est bien connecté ET qu'il est l'auteur de cet article.