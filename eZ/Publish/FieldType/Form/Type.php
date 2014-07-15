<?php

namespace MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form;

use Doctrine\ORM\EntityManager;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\Core\FieldType\Value as CoreValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;

/**
 * Class Type - implemented according to tutorial https://doc.ez.no/display/EZP/eZ+Publish+5+Field+Type+Tutorial
 * @package MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form
 */
class Type extends FieldType
{
    private $typeIdentifier = 'form';

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $doctrine;

    /**
     * Constructor
     * @param \Doctrine\ORM\EntityManager $doctrine
     */
    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    /**
     * Getter for type identifier
     * @return string
     */
    public function getFieldTypeIdentifier()
    {
        return $this->typeIdentifier;
    }

    /**
     * @param mixed $inputValue
     * @return Value|mixed
     */
    protected function createValueFromInput( $inputValue )
    {
        $valueInstance = $this->createNewValue(array(
            'formId' => $inputValue
        ));
        
        return $valueInstance;
    }

    /**
     * Validate the input
     * @param \eZ\Publish\Core\FieldType\Value $value
     * @throws \eZ\Publish\Core\Base\Exceptions\InvalidArgumentType
     */
    protected function checkValueStructure(\eZ\Publish\Core\FieldType\Value $value)
    {
        if(!is_numeric($value->formId)) {
            
            throw new \eZ\Publish\Core\Base\Exceptions\InvalidArgumentType(
                '$value->formId',
                'integer',
                $value->formId
            );
        }
    }

    /**
     * @return \eZ\Publish\SPI\FieldType\Value|Value
     */
    public function getEmptyValue()
    {
        return $this->createNewValue();
    }

    public function validateValidatorConfiguration( $validatorConfiguration )
    {
        return array();
    }

    public function validate( FieldDefinition $fieldDefinition, SPIValue $fieldValue )
    {
        return array();
    }

    public function getName(SPIValue $value)
    {
        return 'some name';
    }

    protected function getSortInfo(CoreValue $value)
    {
        return $this->getName($value);
    }

    public function fromHash($hash)
    {
        if ($hash === null) {

            return $this->getEmptyValue();
        }

        return $this->createNewValue( $hash );
    }

    public function toHash(SPIValue $value)
    {
        if ( $this->isEmptyValue($value)) {

            return null;
        }

        return array(
            'formId' => $value->formId
        );
    }

    /**
     * @param SPIValue $value
     * @return \eZ\Publish\SPI\Persistence\Content\FieldValue
     */
    public function toPersistenceValue( SPIValue $value )
    {
        return new FieldValue(
            array(
                "data" => $this->toHash($value),
                "externalData" => null,
                "sortKey" => $this->getSortInfo($value),
            )
        );
    }

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\FieldValue $fieldValue
     * @return \MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form\Value
     */
    public function fromPersistenceValue(FieldValue $fieldValue)
    {
        if ($fieldValue->data === null){

            return $this->getEmptyValue();
        }

        return $this->createNewValue($fieldValue->data);
    }

    /**
     * Fetches the form data from definitions table and creates Value object.
     * @param array $data
     * @return Value
     */
    private function createNewValue(array $data = array())
    {
        $formDefinitions = $this->doctrine->getRepository('FormMakerBundle:FormDefinitions');
        $data['formDefinition'] = !isset($data['formId']) ? null : $formDefinitions->find($data['formId']);

        return new Value($data);
    }
}
