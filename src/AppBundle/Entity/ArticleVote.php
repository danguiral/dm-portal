<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleVote
 *
 * @ORM\Table(name="article_vote")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleVoteRepository")
 */
class ArticleVote
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_accepted", type="boolean")
     */
    private $isAccepted;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Article", inversedBy="votes")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set isAccepted
     *
     * @param boolean $isAccepted
     *
     * @return ArticleVote
     */
    public function setIsValidate(bool $isAccepted): ArticleVote
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    /**
     * Get isAccepted
     *
     * @return bool
     */
    public function isAccepted(): bool
    {
        return $this->isAccepted;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return ArticleVote
     */
    public function setArticle(Article $article): ArticleVote
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \AppBundle\Entity\Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return ArticleVote
     */
    public function setUser(User $user): ArticleVote
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
