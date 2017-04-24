<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleStatus
 *
 * @ORM\Table(name="article_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleStatusRepository")
 */
class ArticleStatus
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
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, unique=true)
     */
    private $label;


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
     * Set label
     *
     * @param string $label
     *
     * @return ArticleStatus
     */
    public function setLabel(string $label): ArticleStatus
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
