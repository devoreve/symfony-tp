<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FavoritePost::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $favoritePosts;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->posts = new ArrayCollection();
        $this->favoritePosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Récupère l'article des favoris s'il existe
     *
     * @param Post $post
     * @return FavoritePost|null
     */
    public function getFavoritePost(Post $post): ?FavoritePost
    {
        return $this->getFavoritePosts()
            ->filter(fn (FavoritePost $fp) => $fp->getPost() === $post)
            ->first() ?: null;
    }

    /**
     * Détermine si un article fait partie des favoris
     *
     * @param Post $post
     * @return bool
     */
    public function isFavoritePost(Post $post): bool
    {
        return $this->getFavoritePost($post) !== null;
    }

    /**
     * Ajoute l'article en favori s'il ne l'est pas déjà
     * et retire l'article s'il est déjà en favori
     *
     * @param Post $post
     * @return $this
     */
    public function toggleFavorite(Post $post): User
    {
        $favoritePost = $this->getFavoritePost($post);

        if ($favoritePost !== null) {
            return $this->removeFavoritePost($favoritePost);
        } else {
            $favoritePost = new FavoritePost();
            $favoritePost->setPost($post);
            return $this->addFavoritePost($favoritePost);
        }
    }

    /**
     * @return Collection<int, FavoritePost>
     */
    public function getFavoritePosts(): Collection
    {
        return $this->favoritePosts;
    }

    public function addFavoritePost(FavoritePost $favoritePost): static
    {
        if (!$this->favoritePosts->contains($favoritePost)) {
            $this->favoritePosts->add($favoritePost);
            $favoritePost->setUser($this);
        }

        return $this;
    }

    public function removeFavoritePost(FavoritePost $favoritePost): static
    {
        if ($this->favoritePosts->removeElement($favoritePost)) {
            // set the owning side to null (unless already changed)
            if ($favoritePost->getUser() === $this) {
                $favoritePost->setUser(null);
            }
        }

        return $this;
    }
}
