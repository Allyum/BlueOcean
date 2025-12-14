<?php

namespace App\Entity;

use App\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Post
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id;

    #[ORM\Column(type: Types::STRING)]
    private ?string $title;

    #[ORM\Column(type: Types::STRING)]
    private ?string $content;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $is_deleted = false;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user_id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'upvote_user_id', referencedColumnName: 'id')]
    private ?User $upvote_user_id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'downvote_user_id', referencedColumnName: 'id')]
    private ?User $downvote_user_id;

    #[ORM\OneToMany(targetEntity: Post_file::class, mappedBy: 'post_id')]
    private Collection $postFiles;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post_id')]
    private Collection $comments;

    public function __construct()
    {
        $this->postFiles = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): self
    {
        $this->is_deleted = $is_deleted;
        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getUpvoteUserId(): ?User
    {
        return $this->upvote_user_id;
    }

    public function setUpvoteUserId(?User $upvote_user_id): self
    {
        $this->upvote_user_id = $upvote_user_id;
        return $this;
    }

    public function getDownvoteUserId(): ?User
    {
        return $this->downvote_user_id;
    }

    public function setDownvoteUserId(?User $downvote_user_id): self
    {
        $this->downvote_user_id = $downvote_user_id;
        return $this;
    }

    /**
     * @return Collection<int, Post_file>
     */
    public function getPostFiles(): Collection
    {
        return $this->postFiles;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }
}

