# Mini-réseau social

## Objectif

Créer un mini-réseau social (publication, commentaire, like, invitation amis) avec Symfony.

## Instructions

### Inscription/Connexion (3 points)

Mettre en place un système d'inscription/connexion sur le site. On se connecte à l'aide d'une adresse email.
Chaque utilisateur aura un pseudo qui sera affiché. La date de la dernière connexion de l'utilisateur (idéalement en utilisant le système d'événement de Symfony).

Dans une factory, créer plusieurs utilisateurs.

### Catégories (2 points)

Les catégories sont rattachées aux publications (plusieurs catégories à une même publication). Dans une factory, créer quelques catégories. 

### Publication (3 points)

Un utilisateur connecté peut écrire des publications. Ces publications contiendront un titre, un contenu, une heure de création, une ou plusieurs catégories.
Lorsque l'on va sur la page d'un utilisateur, on affiche toutes les publications de cet utilisateur avec un système de pagination (10 publications par page).
L'utilisateur peut bien sûr modifier sa propre publication depuis la publication elle-même.

Dans une factory, créer plusieurs publications.

### Commentaires (2 points)

Sur chaque publication, il y aura un espace commentaire. N'importe quel utilisateur connecté peut écrire un commentaire sur une publication. Les commentaires contiendront un contenu, l'utilisateur du site qui a écrit le commentaire et une date de création.
Dans une factory, créer quelques commentaires.

### Like (2.5 points)

Un utilisateur peut "liker" la publication d'un autre utilisateur. Il faut bien sûr être connecté pour pouvoir "liker" une publication. Si on clique de nouveau sur le bouton qui permet de "liker", ça retire le "like" de la publication.

### Invitations (2.5 points)

Un utilisateur peut faire une demande d'ami à un autre utilisateur qui peut accepter cette demande.
Les publications d'un utilisateur ne sont visibles que par ses amis.

### Commentaire d'un commentaire (2.5 points)

Faire en sorte qu'il soit possible de commenter à un commentaire (le commentaire du commentaire sera indenté par rapport à ce dernier).

### Interface d'administration (2.5 points)

Mettre en place une interface d'administration avec *easyadmin* accessible uniquement aux *ROLE_ADMIN* et aux *ROLE_MODERATOR*.
Le rôle modérateur peut gérer les publications et les commentaires mais il n'a pas accès aux utilisateurs. Le rôle admin peut tout faire et ajouter/modifier de nouveaux rôles aux utilisateurs.

## BONUS

Des points bonus seront accordés en fonction de la propreté du code (lisibilité, phpdoc, découpage en service).