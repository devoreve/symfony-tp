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
* Ajouter le champ price sur le front-office
* Ajouter les événements PRE_SET_DATA et PRE_SUBMIT pour gérer l'enregistrement et l'affichage du prix

### Ajout au panier

* En base de données
* En session
* En localstorage (js)

#### Ajout au panier dans la session

* Création d'une interface CartInterface
* Création d'une classe CartSession
* Création du contrôleur pour ajouter/retirer/afficher les articles du panier

##### Création de la classe CartSession

La classe est constituée de la session et d'un tableau *items*. Ce tableau ressemble à quelque chose comme ça :

```php
$items = [
    123 => [
        'id' => 123,
        'quantity' => 5
    ],
    42 => [
        'id' => 42,
        'quantity' => 42
    ]   
];
```

Créer la classe *CartSession* qui implémente l'interface *CartInterface* :

* La méthode *add* permet d'ajouter un nouvel élément au tableau *items*
* La méthode *remove* permet de supprimer l'élément dans le tableau
* La méthode *clear* permet de vider complètement le tableau
* La méthode *getItems* permet de récupérer tous les éléments du panier (tous les éléments de la session)
* La méthode *get* permet de récupérer un élément particulier du panier

Lorsque vous modifiez le tableau *items*, il faut mettre à jour la session.