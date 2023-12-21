# Mettre en place une fonctionnalité e-commerce

## Liens utiles

[MoneyField](https://symfony.com/bundles/EasyAdminBundle/current/fields/MoneyField.html)

## Description

On va rendre certains articles premium et qui devront donc être achetés par les utilisateurs.
Un système de panier simple (sans quantité, juste les articles que l'on souhaite acheter) sera mise en place ainsi qu'un système de paiement (Stripe).

## Notions abordées

* Services Symfony
* Événements
* Session

## Instructions

### Ajouter la fonctionnalité premium

#### Champ premium

* Ajouter un champ *premium* (Faux par défaut lors de la création) sur les articles
* Depuis l'interface d'administration, ajouter l'affichage de ce champ et rajouter quelques articles premium
* Depuis l'affichage de l'article, si ce dernier est "premium" n'afficher qu'une partie du contenu (100 premiers caractères)

#### Champ price

* Ajouter un champ *price* (qui n'est pas obligatoire) de type integer
* Depuis l'interface d'administration, ajouter l'affichage de ce champ (MoneyField en euros et stocké en centime)
* Sur les articles premium, ajouter quelques prix (si vous mettez le prix 40 vérifier en base de données que le prix s'affiche avec la valeur 4000)

