<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Suggestion
 *
 * @ORM\Table(name="suggestion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SuggestionRepository")
 */
class Suggestion
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
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Expression(
     *     expression="this.getDescription() != null || this.getFile() != null",
     *     message="suggestions.form.not.blank.description.and.file"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Expression(
     *     expression="this.getDescription() != null || this.getFile() != null",
     *     message="suggestions.form.not.blank.description.and.file"
     * )
     */
    private $file;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Suggestion
     */
    public function setDescription($description)
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

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}

