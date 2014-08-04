<?php

namespace MakingWaves\FormMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormAttributesOptions
 *
 * @ORM\Table(name="form_attributes_options", indexes={@ORM\Index(name="attr_id", columns={"attr_id"})})
 * @ORM\Entity
 */
class FormAttributesOptions
{
    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var integer
     *
     * @ORM\Column(name="opt_order", type="smallint", nullable=false)
     */
    private $optOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \MakingWaves\FormMakerBundle\Entity\FormAttributes
     *
     * @ORM\ManyToOne(targetEntity="MakingWaves\FormMakerBundle\Entity\FormAttributes", inversedBy="options")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attr_id", referencedColumnName="id")
     * })
     */
    private $attr;



    /**
     * Set label
     *
     * @param string $label
     * @return FormAttributesOptions
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
     * Set optOrder
     *
     * @param integer $optOrder
     * @return FormAttributesOptions
     */
    public function setOptOrder($optOrder)
    {
        $this->optOrder = $optOrder;

        return $this;
    }

    /**
     * Get optOrder
     *
     * @return integer 
     */
    public function getOptOrder()
    {
        return $this->optOrder;
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
     * Set attr
     *
     * @param \MakingWaves\FormMakerBundle\Entity\FormAttributes $attr
     * @return FormAttributesOptions
     */
    public function setAttr(\MakingWaves\FormMakerBundle\Entity\FormAttributes $attr = null)
    {
        $this->attr = $attr;

        return $this;
    }

    /**
     * Get attr
     *
     * @return \MakingWaves\FormMakerBundle\Entity\FormAttributes 
     */
    public function getAttr()
    {
        return $this->attr;
    }
}
