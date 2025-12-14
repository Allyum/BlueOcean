<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
#[ORM\Entity]
class Comment
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $content;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $is_Deleted = false;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user_id;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id')]
    private ?Post $post_id;

    #[ORM\ManyToOne(targetEntity: Comment::class)]
    #[ORM\JoinColumn(name: 'comment_id', referencedColumnName: 'id')]
    private ?Comment $comment_id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'upvote_user_id', referencedColumnName: 'id')]
    private ?User $upvote_user_id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'downvote_user_id', referencedColumnName: 'id')]
    private ?User $downvote_user_id;

    #[ORM\ManyToOne(targetEntity: File::class)]
    #[ORM\JoinColumn(name: 'file_id', referencedColumnName: 'id')]
    private ?File $file_id;

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getIsDeleted(): bool
    {
        return $this->is_Deleted;
    }

    public function setIsDeleted(bool $is_Deleted): self
    {
        $this->is_Deleted = $is_Deleted;
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

    public function getPostId(): ?Post
    {
        return $this->post_id;
    }

    public function setPostId(?Post $post_id): self
    {
        $this->post_id = $post_id;
        return $this;
    }

    public function getCommentId(): ?Comment
    {
        return $this->comment_id;
    }

    public function setCommentId(?Comment $comment_id): self
    {
        $this->comment_id = $comment_id;
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

    public function getFileId(): ?File
    {
        return $this->file_id;
    }

    public function setFileId(?File $file_id): self
    {
        $this->file_id = $file_id;
        return $this;
    }
}
