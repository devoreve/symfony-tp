# Symfony

## Présentation

Symfony est un framework MVC (Model-View-Controller). Il va nous permettre de travailler plus rapidement et plus efficacement sur nos applications web.

Symfony est un des 2 gros framework de PHP avec Laravel au niveau d'utilisation. La force de Symfony c'est qu'il est découpé en composants indepéndants réutilisables. Par exemple Laravel utilise ces composants (HttpFoundation...).

Comme tout framework, toutes les informations concernant le framework seront à trouver sur la [documentation](https://symfony.com/doc/current/index.html).

## Installation

[Documentation](https://symfony.com/doc/current/setup.html)

Pour installer Symfony il y a 2 méthodes :
* avec un exécutable fourni par Symfony
* avec composer

Procédure d'installation :
* Installer composer pour ceux qui ne l'ont pas
* Dans votre terminal se déplacer jusqu'à la racine de votre serveur web
* Taper la commande suivante : ``` composer create-project symfony/skeleton:"6.3.*" symfony```
* Une fois les dépendances installées, se déplacer dans le dossier *symfony* créé
* Taper la commande ``` composer require webapp```
* Lancer le serveur web local (soit avec l'outil symfony soit avec le serveur fourni par php)
* Commande pour lancer le serveur fourni par php : ``` php -S localhost:8000 -t public```

## Arborescence des dossiers

Un projet Symfony se compose des dossiers suivants :
* bin (contient les exécutables de symfony)
* config (contient des fichiers de configuration)
* migrations (contient des fichiers créés par Symfony pour la base de données)
* public (contient nos fichiers publics tels que index.php, *.js, *.css)
* src (contient les fichiers avec notre code php)
* templates (contient les fichiers avec le moteur de template twig, principalement du html)
* translations (contient les fichiers pour la traduction)
* var (contient les fichiers temporaires)
* vendor (contient les dépendances du projet)

## Créer une page

[Documentation](https://symfony.com/doc/current/page_creation.html)

Pour créer une page, il faut créer une route (url de l'action) et un contrôleur (action à exécuter).

Les contrôleurs doivent retourner à chaque fois une instance de la classe *Response* de Symfony.

## Base de données

Pour manipuler les bases de données, il est possible d'utiliser la classe native de PHP PDO mais Symfony propose plusieurs outils qui vont nous permettre de travailler plus efficacement :
* la bibliothèque DBAL qui est une surcouche de PDO
* l'ORM Doctrine qui va nous permettre de faire le lien entre nos classes et les tables dans la base de données

### Doctrine

[Documentation](https://symfony.com/doc/current/doctrine.html)

#### Installation

Lancer les commandes composer pour installer l'ORM Doctrine.

``` composer require symfony/orm-pack```
``` composer require --dev symfony/maker-bundle```

#### Configuration

Dans le fichier *.env* (à la racine de votre projet), préciser les informations de connexion à votre base de données en ajoutant la ligne suivante :

*DATABASE_URL="mysql://nom-utilisateur:mot-de-passe@adresse-du-serveur:port/symfony-blog?serverVersion=5.7"*

**NB :** La base de données *symfony-blog* n'existe en théorie pas chez vous mais elle sera créée grâce à Doctrine.

#### Création de la base de données

Dans le terminal, taper la commande suivante :
``` php bin/console doctrine:database:create```

Vérifier sur le serveur de base de données que la base a bien été créée.

#### Créer une entité

Une entité est une classe PHP qui va faire le lien avec une table dans la base de données.

Taper la commande ``` php bin/console make:entity``` puis créer la classe *Post*.

Créer les propriétés suivantes :
* title (type string, taille 100, obligatoire)
* content (type text, obligatoire)
* createdAt (type DateTime_Immutable, obligatoire)

Lorsque les propriétés ont été créées, appuyer sur *entrée* pour finir la commande du terminal.

#### Mettre à jour le schéma

Créer une migration avec la commande ``` php bin/console make:migration```. Cette commande va créer un fichier dans le dossier *migrations* avec les requêtes SQL pour mettre à jour le schéma de la base de données.

Lorsque la migration a été créée, taper la commande ``` php bin/console doctrine:migrations:migrate```. Cette commande va mettre à jour le schéma de la base de données à partir des fichiers dans le dossier *migrations*.

#### Ajouter une entrée dans la base de données

On peut maintenant utiliser l'entité créée pour ajouter des données dans la base.

```php
#[Route(path: '/post/create', name: 'app_post_create')]
public function create(EntityManagerInterface $entityManager): Response
{
    // Créer un article grâce à notre entité Post
    $post = new Post();
    $post->setTitle('Hello world')
        ->setContent('Lorem ipsum...');

    // Ajout dans la base de données
    $entityManager->persist($post);
    $entityManager->flush();

    return new Response("L'article n°{$post->getId()} a bien été ajouté");
}
```

#### Récupérer des informations dans la base de données

[Documentation](https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database)

Grâce à la classe *Repository* créée lors de la création de l'entité, on peut récupérer les différentes informations.

```php
#[Route(path: '/post', name: 'app_post_index')]
public function index(PostRepository $postRepository): Response
{
    // Tous les articles
    $posts = $postRepository->findAll();
    
    // Un article en particulier
    $post = $postRepository->find(1);

    return $this->render('post/index.html.twig', [
        'posts' => $posts
    ]);
}
```

#### Relations

* oneToOne
* oneToMany (et manyToOne)
* manyToMany

##### Relation oneToMany

Cardinalité : 1 -> n

**Exemple : les articles et les commentaires**

* Un article peut avoir 0 ou plusieurs commentaires (n commentaires)
* Un commentaire est attribué/affecté à un seul article

Conséquence dans la base de données : un champ *post_id* est créé dans la table *comment*.

**Exemple 2 : les catégories et les articles**

* Une catégorie peut se retrouver sur 0 ou plusieurs articles (n articles)
* Une catégorie est attribuée à un seul article

Conséquence dans la base de données : un champ *category_id* est créé dans la table *post*.

Les clés étrangères (*post_id*, *category_id*) sont gérées par doctrine lorsque l'on met en place la relation. Doctrine s'occupe de créer les champs nécessaires.

##### Relation manyToMany

Cardinalité : n -> n

**Exemple : les catégories et les articles**

On pourrait également concevoir notre base de données avec cette relation là pour les catégories :
* Une catégorie peut se retrouve sur 0 ou plusieurs articles (n articles)
* Un article peut avoir 1 ou plusieurs catégories (n catégories)

Conséquence dans la base de données : création d'une table intermédiaire (table pivot) qui va contenir le numéro de l'article et le numéro de la catégorie. Doctrine s'occupe de gérer la création de cette table intermédiaire.

*post (id, title...)*
*category_post (category_id, post_id)*
*category (id, name)*

**Exemple 2 : les détails de commande pour un site e-commerce**

Dans un site e-commerce, on retrouve souvent les tables *product* et *order*. Entre nos 2 tables il existe une relation manyToMany :
* un produit peut se retrouver dans plusieurs commandes (n commandes)
* une commande peut contenir 1 ou plusieurs produits (n produits)

Conséquence dans la base de données : création d'une table intermédiaire (souvent appelée orderDetail ou orderLine) avec l'id du produit et l'id de la commande.

Détail de commande :
id commande : 1| id produit : 1 | quantité : 6 | nom produit : Banane
id commande : 1| id produit : 2 | quantité : 2 | nom produit : Jus de fruit
id commande : 1| id produit : 3 | quantité : 3 | nom produit : Bouteilles d'eau

Au niveau de la base de données, on rajoute simplement ce champ dans la table intermédiaire. Mais comment l'ORM le gère ?

* avec Eloquent (Laravel), le champ est géré directement sur cette table intermédiaire (pivot)
* avec Doctrine (Symfony), il va falloir créer une entité supplémentaire (par exemple OrderDetail) dans ce cas spécifique (relation manyToMany avec un champ supplémentaire) et remplacer la relation manyToMany par 2 relations manyToOne vers cette entité créée

Relation oneToMany entre la commande et les détails de commande :
* une commande peut avoir 1 ou plusieurs détail de commande (n détails de commande)
* un détail de commande fait référence à une seule commande

Relation oneToMany entre les produits et les détails de commande :
* un produit peut se retrouver dans 0 ou plusieurs détails de commande (n détails de commande)
* un détail de commande fait référence à un seul produit

#### Fixtures

[Documentation](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)

C'est une fonctionnalité de Symfony qui va permettre de générer des données dans notre base de données.

##### Installation

Commencer par installer le bundle des fixtures avec la commande ``` composer require --dev orm-fixtures```.

Un fichier *AppFixtures.php* a normalement été généré. On va modifier ce fichier là de ce manière :

```php
public function load(ObjectManager $manager): void
{
    // Création de 10 articles
    for ($i = 1; $i <= 10; $i++) {
        $post = new Post();
        $post->setTitle("Article n°$i")
            ->setContent("Contenu de l'article n°$i");

        $manager->persist($post);
    }

    $manager->flush();
}
```

Taper ensuite la commande ``` php bin/console doctrine:fixtures:load``` pour modifier la base de données (ne pas oublier de choisir l'option "yes").

##### Utilisation de la bibliothèque faker

[Documentation](https://fakerphp.github.io/)

Installer la bibliothèque avec la commande ``` composer require fakerphp/faker```

L'utiliser dans les fixtures :

```php
private Generator $faker;

public function __construct()
{
    $this->faker = Factory::create();
}

public function load(ObjectManager $manager): void
{
    for ($i = 1; $i <= 100; $i++) {
        $post = new Post();
        $post->setTitle($this->faker->words(mt_rand(3, 5), true))
            ->setContent($this->faker->paragraphs(mt_rand(3, 5), true));

        $manager->persist($post);
    }

    $manager->flush();
}
```

##### Utilisation du bundle foundry

Foundry est un bundle (une extension) de Symfony qui va permettre de gérer plus facilement les fixtures, en créant des "factory" pour nos entités.

* Installation ``` composer require zenstruck/foundry --dev```
* Créer les factory ``` php bin/console make:factory```
* Utiliser une factory pour créer des données dans la base de données
```php
PostFactory::createMany(100);
```
* Relancer les fixtures

### Formulaires

[Documentation](https://symfony.com/doc/current/forms.html)

Dans Symfony il y a une fonctionnalité qui permet de gérer les formulaires : le *form builder*.

#### Installation

Taper la commande ``` composer require symfony/form```. Configurer éventuellement le thème dans le fichier *config/packages/twig.yaml*.

```
twig:
    [...]
    form_themes: ['bootstrap_5_layout.html.twig']
```

#### Création de la classe Form

Taper la commande ``` php bin/console make:form``` et préciser quelle sera l'entité en relation avec ce formulaire.

Une classe pour le formulaire a été créée. La modifier selon les besoins.

```php
public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('nickname', TextType::class, [
            'label' => 'Pseudo'
        ])
        ->add('content', TextareaType::class, [
            'label' => 'Commentaire',
            'attr' => [
                'class' => 'custom-class'
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Ajouter'
        ])
    ;
}
```

#### Affichage du formulaire

Dans le contrôleur, créer le formulaire à partir de cette classe FormType.

```php
// Création du formulaire pour les commentaires
$form = $this->createForm(CommentType::class, new Comment());

// Affichage du détail de l'article et des commentaires
return $this->render('post/show.html.twig', [
    'post' => $post,
    'comments' => $comments,
    'form' => $form
]);
```

L'afficher dans le template twig.

```html
{{ form(form) }}
```

Il est possible de personnaliser l'affichage du formulaire, grâce au thème et aux fonctions de formulaire de twig.

[Personnalisation de formulaire](https://symfony.com/doc/current/form/form_customization.html)

#### Gestion de la soumission du formulaire

```php
// Création d'un commentaire vide
$comment = new Comment();

// Création du formulaire pour les commentaires avec l'entité liée
$form = $this->createForm(CommentType::class, $comment);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    // L'entité commentaire a été mise à jour avec les données du formulaire
    // Je rattache le commentaire à l'article
    $comment->setPost($post);

    // Mise à jour de la base de données
    $entityManager->persist($comment);
    $entityManager->flush();

    // Redirection sur la page qui affiche le détail de l'article et les commentaires
    return $this->redirectToRoute('app_post_show', ['id' => $id]);
}
```

### Authentification

[Documentation](https://symfony.com/doc/current/security.html)

#### Mise en place

1. Créer l'entité *User* (avec la commande *make:user*)
2. Ajouter éventuellement des champs supplémentaires à l'entité puis faire/lancer la migration
3. Créer le formulaire d'inscription (avec la commande *make:registration-form*) puis modifier le formulaire selon les besoins
4. Modifier la configuration du *security.yaml* pour la connexion
5. Récupérer le code du *LoginController*
6. Créer le formulaire de connexion et le modifier selon les besoins
7. Modifier la configuration du *security.yaml* pour la déconnexion (ne pas oublier la méthode vide dans le contrôleur)

#### Récupérer l'utilisateur connecté

Dans les contrôleurs, il est possible de récupérer l'utilisateur connecté à l'aide de la méthode *getUser()*.

Dans les templates on peut utiliser le code suivant pour récupérer l'utilisateur :

```html
{% if is_granted('IS_AUTHENTICATED_FULLY') %}
    <p>Email: {{ app.user.email }}</p>
{% endif %}
```

## Session

### Session flash

La session flash est un mécanisme, lié aux sessions, permettant de garder des informations mais seulement pour un affichage (on les affiche une fois puis on les retire de la session).

```php
// Dans le contrôleur
public function save(): Response
{
    // ...
    $this->addFlash('notice', "La ressource a bien été ajoutée");
}
```

```html
{% if app.session.flashBag.has('notice') %}
    <aside class="alert alert-info">
        {% for message in app.flashes('notice') %}
            {{ message }}
        {% endfor %}
    </aside>
{% endif %}
```

## Pagination

### Mise en œuvre générale

* Récupérer un certain nombre d'articles (grâce au mot-clé *limit*) au lieu de tous les récupérer
* Récupérer les articles en fonction de leur position dans la table (grâce au mot-clé *offset*)
* Récupérer le numéro de la page depuis l'url pour pouvoir modifier l'*offset* dynamiquement
* Récupérer le nombre d'articles pour déterminer combien on aura de pages

```sql
SELECT * 
FROM post
LIMIT 10 OFFSET 0
```

En général l'offset est calculé de la manière suivante : (numéro de page - 1) * nombre par pages.
Pour déterminer le nombre de pages, là encore on effectue un calcul que l'on retrouve un peu dans toutes les mises en œuvre : nombre total / nombre par page et on arrondit ce résultat à l'entier supérieur.

### Mise en œuvre dans Symfony sans bundle

```php
public function findLatestByPage(int $page = 1, int $postsPerPage = 10): array
{
    return $this->createQueryBuilder('p')
        ->setMaxResults($postsPerPage)
        ->setFirstResult(($page - 1) * $postsPerPage)
        ->getQuery()
        ->getResult()
        ;
}

public function totalPosts(): ?int
{
    return $this->createQueryBuilder('p')
        ->select('COUNT(p.id) AS total')
        ->getQuery()
        ->getSingleScalarResult();
}
```

### Mise en œuvre dans Symfony avec knp paginator

[Documentation bundle](https://github.com/KnpLabs/KnpPaginatorBundle)

## Slug

Le slug est une chaîne de caractères basée sur le titre dont l'objectif est d'être affichée dans l'url pour des url plus "SEO friendly".

Il est possible de mettre ça en place manuellement, à chaque fois que l'on ajoute/modifie le titre d'un article on peut créer un slug basé sur ce titre.
Mais on peut également passer par un bundle (*stof doctrine extensions*) qui va gérer ça pour nous.

### Mise en place de la propriété slug

Il faut tout de même créer le champ en base de données. Rajouter une propriété slug (titre, 100 caractères) à l'entité *Post*, cette propriété doit être unique dans la table.

```php
#[ORM\Column(length: 100, unique: true)]
private ?string $slug = null;
```

Créer la migration puis l'exécuter en base de données. Comme le champ est obligatoire, des erreurs risquent de se produire. Dans ce cas on va réinitialiser la base de données :
``` php bin/console doctrine:database:drop --force```
``` php bin/console doctrine:database:create```
``` php bin/console doctrine:migrations:migrate```

### Installation du bundle

``` composer require stof/doctrine-extensions-bundle``` (saisir "yes" pour l'exécution de la recette)

Modifier la configuration du bundle :

```yaml
stof_doctrine_extensions:
    default_locale: en_US
    orm:
        default:
            sluggable: true
```

### Configurer le champ slug sur l'entité

```php
use Gedmo\Mapping\Annotation\Slug;
// [...]
#[ORM\Column(length: 100, unique: true)]
#[Slug(fields: ['title'])]
private ?string $slug = null;
```

### Relancer les fixtures

Relancer les fixtures et regarder en base de données si le slug a bien été modifié.

### Modifier tous les liens

Remplacer tous les liens vers l'id et remplacer par "slug" (aussi bien dans le contrôleur que dans les templates).

## Front

### Webpack encore

### AssetMapper

Composant php permettant de gérer les assets (css, js...).
Penser à vérifier dans votre console que tout se charge correctement.

#### Installation

Taper la commande suivante pour installer AssetMapper ``` composer require symfony/asset-mapper symfony/asset symfony/twig-pack```

### Stimulus Bundle

Installer StimulusBundle avec la commande suivante ``` composer require symfony/stimulus-bundle```

#### UXAutocomplete

[Documentation](https://symfony.com/bundles/ux-autocomplete/current/index.html)

Une fois que StimulusBundle est installé, on peut mettre en place le composant UXAutocomplete avec la commande ``` composer require symfony/ux-autocomplete``` (lancer les commandes indiquées dans la doc si vous utilisez webpack encore).

##### Mise à jour du formulaire

Une fois que ce composant est installé, on peut rajouter la clé *autocomplete* à true sur nos champs de type select.