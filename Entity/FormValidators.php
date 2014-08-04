<?php

namespace MakingWaves\FormMakerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FormValidators
 *
 * @ORM\Table(name="form_validators")
 * @ORM\Entity
 */
class FormValidators
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="regex", type="string", length=255, nullable=false)
     */
    private $regex;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="FormAttributes", mappedBy="validators")
     */
    private $attributes;


    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->description;
    }


    /**
     * Set type
     *
     * @param string $type
     * @return FormValidators
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return FormValidators
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

    /**
     * Set regex
     *
     * @param string $regex
     * @return FormValidators
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Get regex
     *
     * @return string 
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
