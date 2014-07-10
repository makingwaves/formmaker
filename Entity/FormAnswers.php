<?php

namespace MakingWaves\FormMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormAnswers
 *
 * @ORM\Table(name="form_answers", indexes={@ORM\Index(name="definition_id", columns={"definition_id"})})
 * @ORM\Entity
 */
class FormAnswers
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="answer_date", type="datetime", nullable=false)
     */
    private $answerDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \MakingWaves\FormMakerBundle\Entity\FormDefinitions
     *
     * @ORM\ManyToOne(targetEntity="MakingWaves\FormMakerBundle\Entity\FormDefinitions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="definition_id", referencedColumnName="id")
     * })
     */
    private $definition;



    /**
     * Set answerDate
     *
     * @param \DateTime $answerDate
     * @return FormAnswers
     */
    public function setAnswerDate($answerDate)
    {
        $this->answerDate = $answerDate;

        return $this;
    }

    /**
     * Get answerDate
     *
     * @return \DateTime 
     */
    public function getAnswerDate()
    {
        return $this->answerDate;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return FormAnswers
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
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
     * Set definition
     *
     * @param \MakingWaves\FormMakerBundle\Entity\FormDefinitions $definition
     * @return FormAnswers
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
}
