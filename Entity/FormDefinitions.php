<?php

namespace MakingWaves\FormMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * FormDefinitions
 *
 * @ORM\Table(name="form_definitions", indexes={@ORM\Index(name="owner_user_id", columns={"owner_user_id"})})
 * @ORM\Entity
 *
 *
 * @Assert\Callback(methods={"validateRecipients"})
 */
class FormDefinitions
{
    const RECIPIENTS_SEPARATORS = ';';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message = "form.name.not_blank")
     * @Assert\Length(
     *      min = "2",
     *      max = "255",
     *      minMessage = "form.name.min.length",
     *      maxMessage = "form.name.max.length"
     * )
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime", nullable=true)
     * @Assert\DateTime(message = "form.create_date.type")
     */
    private $createDate;

    /**
     * @var string
     *
     * @ORM\Column(name="recipients", type="text", nullable=true)
     * Validated by callback function
     * @Assert\NotBlank(message="form.email.recipients",
     *                  groups="email_recipients_group"
     * )
     */
    private $recipients;

    /**
     * @var string
     *
     * @ORM\Column(name="email_title", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="form.data.email",
     *                  groups="email_recipients_group"
     * )
     */
    private $emailTitle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="summary_page", type="boolean", nullable=true)
     */
    private $summaryPage;

    /**
     * @var string
     *
     * @ORM\Column(name="summary_label", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "form.conf.page.label",
     *                  groups="summary_page_group"
     * )
     */
    private $summaryLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="summary_body", type="text", nullable=true)
     * @Assert\NotBlank(message = "form.conf.page.body",
     *                  groups="summary_page_group"
     * )
     */
    private $summaryBody;

    /**
     * @var string
     *
     * @ORM\Column(name="first_page", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message = "form.first_page.not_blank")
     */
    private $firstPage;

    /**
     * @var string
     *
     * @ORM\Column(name="receipt_label", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message = "form.receipt_label.not_blank")
     */
    private $receiptLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="receipt_intro", type="text", nullable=true)
     */
    private $receiptIntro;

    /**
     * @var string
     *
     * @ORM\Column(name="receipt_body", type="text", nullable=true)
     */
    private $receiptBody;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_action", type="boolean", nullable=true)
     */
    private $emailAction;

    /**
     * @var boolean
     *
     * @ORM\Column(name="store_action", type="boolean", nullable=true)
     */
    private $storeAction;

    /**
     * @var boolean
     *
     * @ORM\Column(name="object_action", type="boolean", nullable=true)
     */
    private $objectAction;

    /**
     * @var string
     *
     * @ORM\Column(name="process_class", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "form.process.class",
     *                  groups="process_class_name"
     * )
     */
    private $processClass;

    /**
     * @var string
     *
     * @ORM\Column(name="css_class", type="string", length=255, nullable=true)
     */
    private $cssClass;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="owner_user_id", type="integer")
     */
    private $ownerUser;

    /**
     * @var string
     *
     * @ORM\Column(name="view_type", type="string", length=100, nullable=false)
     */
    private $viewType;

    /**
     * @ORM\OneToMany(targetEntity="FormAttributes", mappedBy="definition", cascade={"ALL"})
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $attributes;


    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    } // __construct


    public function getAttributes()
    {
        return $this->attributes;
    } // getAttributes


    /**
     * Set name
     *
     * @param string $name
     * @return FormDefinitions
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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return FormDefinitions
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set recipients
     *
     * @param string $recipients
     * @return FormDefinitions
     */
    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * Get recipients
     *
     * @return string 
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Set emailTitle
     *
     * @param string $emailTitle
     * @return FormDefinitions
     */
    public function setEmailTitle($emailTitle)
    {
        $this->emailTitle = $emailTitle;

        return $this;
    }

    /**
     * Get emailTitle
     *
     * @return string 
     */
    public function getEmailTitle()
    {
        return $this->emailTitle;
    }

    /**
     * Set summaryPage
     *
     * @param integer $summaryPage
     * @return FormDefinitions
     */
    public function setSummaryPage($summaryPage)
    {
        $this->summaryPage = $summaryPage;

        return $this;
    }

    /**
     * Get summaryPage
     *
     * @return integer 
     */
    public function getSummaryPage()
    {
        return $this->summaryPage;
    }

    /**
     * Set summaryLabel
     *
     * @param string $summaryLabel
     * @return FormDefinitions
     */
    public function setSummaryLabel($summaryLabel)
    {
        $this->summaryLabel = $summaryLabel;

        return $this;
    }

    /**
     * Get summaryLabel
     *
     * @return string 
     */
    public function getSummaryLabel()
    {
        return $this->summaryLabel;
    }

    /**
     * Set summaryBody
     *
     * @param string $summaryBody
     * @return FormDefinitions
     */
    public function setSummaryBody($summaryBody)
    {
        $this->summaryBody = $summaryBody;

        return $this;
    }

    /**
     * Get summaryBody
     *
     * @return string 
     */
    public function getSummaryBody()
    {
        return $this->summaryBody;
    }

    /**
     * Set firstPage
     *
     * @param string $firstPage
     * @return FormDefinitions
     */
    public function setFirstPage($firstPage)
    {
        $this->firstPage = $firstPage;

        return $this;
    }

    /**
     * Get firstPage
     *
     * @return string 
     */
    public function getFirstPage()
    {
        return $this->firstPage;
    }

    /**
     * Set receiptLabel
     *
     * @param string $receiptLabel
     * @return FormDefinitions
     */
    public function setReceiptLabel($receiptLabel)
    {
        $this->receiptLabel = $receiptLabel;

        return $this;
    }

    /**
     * Get receiptLabel
     *
     * @return string 
     */
    public function getReceiptLabel()
    {
        return $this->receiptLabel;
    }

    /**
     * Set receiptIntro
     *
     * @param string $receiptIntro
     * @return FormDefinitions
     */
    public function setReceiptIntro($receiptIntro)
    {
        $this->receiptIntro = $receiptIntro;

        return $this;
    }

    /**
     * Get receiptIntro
     *
     * @return string 
     */
    public function getReceiptIntro()
    {
        return $this->receiptIntro;
    }

    /**
     * Set receiptBody
     *
     * @param string $receiptBody
     * @return FormDefinitions
     */
    public function setReceiptBody($receiptBody)
    {
        $this->receiptBody = $receiptBody;

        return $this;
    }

    /**
     * Get receiptBody
     *
     * @return string 
     */
    public function getReceiptBody()
    {
        return $this->receiptBody;
    }

    /**
     * Set emailAction
     *
     * @param boolean $emailAction
     * @return FormDefinitions
     */
    public function setEmailAction($emailAction)
    {
        $this->emailAction = $emailAction;

        return $this;
    }

    /**
     * Get emailAction
     *
     * @return boolean 
     */
    public function getEmailAction()
    {
        return $this->emailAction;
    }

    /**
     * Set storeAction
     *
     * @param boolean $storeAction
     * @return FormDefinitions
     */
    public function setStoreAction($storeAction)
    {
        $this->storeAction = $storeAction;

        return $this;
    }

    /**
     * Get storeAction
     *
     * @return boolean 
     */
    public function getStoreAction()
    {
        return $this->storeAction;
    }

    /**
     * Set objectAction
     *
     * @param boolean $objectAction
     * @return FormDefinitions
     */
    public function setObjectAction($objectAction)
    {
        $this->objectAction = $objectAction;

        return $this;
    }

    /**
     * Get objectAction
     *
     * @return boolean 
     */
    public function getObjectAction()
    {
        return $this->objectAction;
    }

    /**
     * Set processClass
     *
     * @param string $processClass
     * @return FormDefinitions
     */
    public function setProcessClass($processClass)
    {
        $this->processClass = $processClass;

        return $this;
    }

    /**
     * Get processClass
     *
     * @return string 
     */
    public function getProcessClass()
    {
        return $this->processClass;
    }

    /**
     * Set cssClass
     *
     * @param string $cssClass
     * @return FormDefinitions
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;

        return $this;
    }

    /**
     * Get cssClass
     *
     * @return string 
     */
    public function getCssClass()
    {
        return $this->cssClass;
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
     * Set ownerUser
     *
     * @param integer $ownerUser
     * @return FormDefinitions
     */
    public function setOwnerUser($ownerUser = null)
    {
        $this->ownerUser = $ownerUser;

        return $this;
    }

    /**
     * Get ownerUser
     *
     * @return integer
     */
    public function getOwnerUser()
    {
        return $this->ownerUser;
    }

    /**
     * Get viewType
     *
     * @return string
     */
    public function getViewType()
    {
        return $this->viewType;
    }

    /**
     * Set viewType
     *
     * @param string $viewType
     * @return $this
     */
    public function setViewType( $viewType )
    {
        $this->viewType = $viewType;

        return $this;
    }

    /**
     * @param ExecutionContextInterface $context
     */
    public function validateRecipients(ExecutionContextInterface $context)
    {
        $strRecipients = trim($this->getRecipients());
        if ( $strRecipients != '' ) {
            $arrEmails = explode(self::RECIPIENTS_SEPARATORS, $strRecipients );
            foreach($arrEmails as $strEmail) {
                $valid = filter_var($strEmail, FILTER_VALIDATE_EMAIL);
                if ($valid === false) {
                    $context->addViolationAt('recipients', 'form.recipients_list', array('{{ value }}' => $strEmail), null);
                }
            } // endforeach
        } // if

        // conditional validation:
        // when given checkbox is checked, fields with following validation groups are validated.
        // they are not validated when checkbox is unchecked

        if ( $this->getSummaryPage() ) {
            $context->validate($this, '', 'summary_page_group', true);
        }

        if ( $this->getEmailAction() ) {
            $context->validate($this, '', 'email_recipients_group',true);
        }

        if ( $this->getObjectAction() ) {
            $context->validate($this, '', 'process_class_name', true);
        }
    } // validateRecipients

    /**
     * Add attributes
     *
     * @param \MakingWaves\FormMakerBundle\Entity\FormAttributes $attributes
     * @return FormDefinitions
     */
    public function addAttribute(\MakingWaves\FormMakerBundle\Entity\FormAttributes $attributes)
    {
        $this->attributes[] = $attributes;

        $attributes->setDefinition($this);

        return $this;
    }

    /**
     * Remove attributes
     *
     * @param \MakingWaves\FormMakerBundle\Entity\FormAttributes $attributes
     */
    public function removeAttribute(\MakingWaves\FormMakerBundle\Entity\FormAttributes $attributes)
    {
        $this->attributes->removeElement($attributes);
    }
}