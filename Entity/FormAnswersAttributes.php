<?php

namespace MakingWaves\FormMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormAnswersAttributes
 *
 * @ORM\Table(name="form_answers_attributes", indexes={@ORM\Index(name="answer_id", columns={"answer_id", "attribute_id"}), @ORM\Index(name="attribute_id", columns={"attribute_id"}), @ORM\Index(name="IDX_A7798129AA334807", columns={"answer_id"})})
 * @ORM\Entity
 */
class FormAnswersAttributes
{
    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", nullable=false)
     */
    private $answer;

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
     * @ORM\ManyToOne(targetEntity="MakingWaves\FormMakerBundle\Entity\FormAttributes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attribute_id", referencedColumnName="id")
     * })
     */
    private $attribute;

    /**
     * @var \MakingWaves\FormMakerBundle\Entity\FormAnswers
     *
     * @ORM\ManyToOne(targetEntity="MakingWaves\FormMakerBundle\Entity\FormAnswers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     * })
     */
    private $answer2;



    /**
     * Set answer
     *
     * @param string $answer
     * @return FormAnswersAttributes
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
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
     * Set attribute
     *
     * @param \MakingWaves\FormMakerBundle\Entity\FormAttributes $attribute
     * @return FormAnswersAttributes
     */
    public function setAttribute(\MakingWaves\FormMakerBundle\Entity\FormAttributes $attribute = null)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get attribute
     *
     * @return \MakingWaves\FormMakerBundle\Entity\FormAttributes 
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Set answer2
     *
     * @param \MakingWaves\FormMakerBundle\Entity\FormAnswers $answer2
     * @return FormAnswersAttributes
     */
    public function setAnswer2(\MakingWaves\FormMakerBundle\Entity\FormAnswers $answer2 = null)
    {
        $this->answer2 = $answer2;

        return $this;
    }

    /**
     * Get answer2
     *
     * @return \MakingWaves\FormMakerBundle\Entity\FormAnswers 
     */
    public function getAnswer2()
    {
        return $this->answer2;
    }
}
