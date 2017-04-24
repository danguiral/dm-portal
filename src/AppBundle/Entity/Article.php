<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->insertedAt = new \Datetime;
        $this->votes = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted_at", type="datetime")
     */
    private $insertedAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ArticleVote", mappedBy="article")
     */
    private $votes;


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
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle(string $title): Article
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Article
     */
    public function setDescription(string $description): Article
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set insertedAt
     *
     * @param \DateTime $insertedAt
     *
     * @return Article
     */
    public function setInsertedAt(\DateTime $insertedAt): Article
    {
        $this->insertedAt = $insertedAt;

        return $this;
    }

    /**
     * Get insertedAt
     *
     * @return \DateTime
     */
    public function getInsertedAt(): \DateTime
    {
        return $this->insertedAt;
    }

    /**
     * Set User
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Article
     */
    public function setUser(User $user): Article
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add vote
     *
     * @param \AppBundle\Entity\ArticleVote $vote
     *
     * @return Article
     */
    public function addVote(ArticleVote $vote): Article
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote
     *
     * @param \AppBundle\Entity\ArticleVote $vote
     *
     * @return Article
     */
    public function removeVote(ArticleVote $vote): Article
    {
        $this->votes->removeElement($vote);

        return $this;
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Get accepted votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcceptedVotes()
    {
        $votes = new ArrayCollection();
        foreach ($this->votes as $vote) {
            if ($vote->isAccepted()) {
                $votes[] = $vote;
            }
        }

        return $votes;
    }

    /**
     * Get refused votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRefusedVotes()
    {
        $votes = new ArrayCollection();
        foreach ($this->votes as $vote) {
            if (!$vote->isAccepted()) {
                $votes[] = $vote;
            }
        }

        return $votes;
    }
}
