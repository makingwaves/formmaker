<?php

namespace MakingWaves\FormMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormTypes
 *
 * @ORM\Table(name="form_types")
 * @ORM\Entity
 */
class FormTypes
{
    /**
     * Text field string identifier
     * @var string
     */
    const TEXTLINE_ID = 'text';

    /**
     * Textarea string identifier
     * @var string
     */
    const TEXTAREA_ID = 'textarea';

    /**
     * Checkbox string identifier
     * @var string
     */
    const CHECKBOX_ID = 'checkbox';

    /**
     * Radio button string identifier
     * @var string
     */
    const RADIO_ID = 'radio';

    /**
     * Page separator string identifier
     * @var string
     */
    const SEPARATOR_ID = 'separator';

    /**
     * Field type sting identifier
     * @var string
     */
    const FILE_ID = 'file';

    /**
     * Select list string identifier
     * @var string
     */
    const SELECT_ID = 'select';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="validation", type="boolean", nullable=false)
     */
    private $validation;

    /**
     * @var integer
     *
     * @ORM\Column(name="sep_order", type="smallint", nullable=false)
     */
    private $sepOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="string_id", type="string", length=50, nullable=false, unique=true)
     */
    private $stringId;


    /**
     * Set name
     *
     * @param string $name
     * @return FormTypes
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set validation
     *
     * @param boolean $validation
     * @return FormTypes
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get validation
     *
     * @return boolean
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Set sepOrder
     *
     * @param integer $sepOrder
     * @return FormTypes
     */
    public function setSepOrder($sepOrder)
    {
        $this->sepOrder = $sepOrder;

        return $this;
    }

    /**
     * Get sepOrder
     *
     * @return integer 
     */
    public function getSepOrder()
    {
        return $this->sepOrder;
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
     * Set stringId
     *
     * @param string $stringId
     * @return FormTypes
     */
    public function setStringId( $stringId )
    {
        $this->stringId = $stringId;

        return $this;
    }

    /**
     * Get stringId
     *
     * @return string
     */
    public function getStringId()
    {
        return $this->stringId;
    }
}
