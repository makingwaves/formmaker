<?php

namespace MakingWaves\FormMakerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FormAttributes
 *
 * @ORM\Table(name="form_attributes", indexes={@ORM\Index(name="definition_id", columns={"definition_id"}), @ORM\Index(name="type_id", columns={"type_id"})})
 * @ORM\Entity
 */
class FormAttributes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="attr_order", type="integer", nullable=true)
     */
    private $attrOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=255, nullable=true)
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="default_value", type="string", length=255, nullable=true)
     */
    private $defaultValue;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @var integer
     *
     * @ORM\Column(name="email_receiver", type="smallint", nullable=false)
     */
    private $emailReceiver;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="css", type="string", length=255, nullable=false)
     */
    private $css;

    /**
     * @var string
     *
     * @ORM\Column(name="allowed_file_types", type="string", length=255, nullable=false)
     */
    private $allowedFileTypes;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \MakingWaves\FormMakerBundle\Entity\FormTypes
     *
     * @ORM\ManyToOne(targetEntity="MakingWaves\FormMakerBundle\Entity\FormTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @var \MakingWaves\FormMakerBundle\Entity\FormDefinitions
     *
     * @ORM\ManyToOne(targetEntity="MakingWaves\FormMakerBundle\Entity\FormDefinitions", inversedBy="attributes", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="definition_id", referencedColumnName="id")
     * })
     */
    private $definition;

    /**
     * @var ArrayCollection
     *
     * ONE TO MANY HEREREER?
     *
     * @ORM\ManyToMany(targetEntity="FormValidators", inversedBy="attributes")
     * @ORM\JoinTable(name="form_attr_valid",
     *      joinColumns={@ORM\JoinColumn(name="attribute_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="validator_id", referencedColumnName="id")}
     * )
     */
    private $validators;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="FormAttributesOptions", mappedBy="attr", cascade={"persist"})
     * @ORM\OrderBy({"optOrder"="ASC"})
     */
    private $options;

    /**
     * @var string
     *
     * @ORM\Column(name="regex", type="string", length=255, nullable=true)
     */
    private $regex;

    public function __construct()
    {
        $this->validators = new ArrayCollection();
        $this->options = new ArrayCollection();
    }


    public function setValidators(FormValidators $validator)
    {
        $this->validators->add($validator);
    }


    /**
     * @return ArrayCollection
     */
    public function getValidators()
    {
        return $this->validators;
    } // getValidators


    /**
     * @return ArrayCollection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set attrOrder
     *
     * @param integer $attrOrder
     * @return FormAttributes
     */
    public function setAttrOrder($attrOrder)
    {
        $this->attrOrder = $attrOrder;

        return $this;
    }

    /**
     * Get attrOrder
     *
     * @return integer 
     */
    public function getAttrOrder()
    {
        return $this->attrOrder;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return FormAttributes
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set defaultValue
     *
     * @param string $defaultValue
     * @return FormAttributes
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Get defaultValue
     *
     * @return string 
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return FormAttributes
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return FormAttributes
     */
    public function setLabel($label)
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

    /**
     * Set emailReceiver
     *
     * @param integer $emailReceiver
     * @return FormAttributes
     */
    public function setEmailReceiver($emailReceiver)
    {
        $this->emailReceiver = $emailReceiver;

        return $this;
    }

    /**
     * Get emailReceiver
     *
     * @return integer 
     */
    public function getEmailReceiver()
    {
        return $this->emailReceiver;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return FormAttributes
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
     * Set css
     *
     * @param string $css
     * @return FormAttributes
     */
    public function setCss($css)
    {
        $this->css = $css;

        return $this;
    }

    /**
     * Get css
     *
     * @return string 
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * Set allowedFileTypes
     *
     * @param string $allowedFileTypes
     * @return FormAttributes
     */
    public function setAllowedFileTypes($allowedFileTypes)
    {
        $this->allowedFileTypes = $allowedFileTypes;

        return $this;
    }

    /**
     * Get allowedFileTypes
     *
     * @return string 
     */
    public function getAllowedFileTypes()
    {
        return $this->allowedFileTypes;
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

    /**
     * Set type
     *
     * @param \MakingWaves\FormMakerBundle\Entity\FormTypes $type
     * @return FormAttributes
     */
    public function setType(\MakingWaves\FormMakerBundle\Entity\FormTypes $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \MakingWaves\FormMakerBundle\Entity\FormTypes 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set definition
     *
     * @param \MakingWaves\FormMakerBundle\Entity\FormDefinitions $definition
     * @return FormAttributes
     */
    public function setDefinition(\MakingWaves\FormMakerBundle\Entity\FormDefinitions $definition = null)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get definition
     *
     * @return \MakingWaves\FormMakerBundle\Entity\FormDefinitions 
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set regex
     *
     * @param string $regex
     * @return FormAttributes
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
}
