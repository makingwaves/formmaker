<?php

namespace eZPublishLegacy\FormMaker\DataTypes;

/**
 * Class formType
 * @package eZPublishLegacy\FormMaker\DataTypes
 */
class FormType extends \eZDataType
{
    const DATA_TYPE_STRING = 'form';
    const DATA_TYPE_NAME = 'Form';

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $doctrine;

    /**
     * @var \Symfony\Component\Translation\Translator
     */
    private $translator;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eZDataType(self::DATA_TYPE_STRING, self::DATA_TYPE_NAME, array(
            'serialize_supported' => true,
            'object_serialize_map' => array('data_text' => self::DATA_TYPE_STRING)
        ));

        $container = \ezpKernel::instance()->getServiceContainer();
        $this->doctrine = $container->get('doctrine');
        $this->translator = $container->get('translator');
    }

    /**
     * Returns the post identifier
     * @param string $base
     * @param $contentObjectAttribute
     * @return string
     */
    private function getPostIdentifier($base, \eZContentObjectAttribute $contentObjectAttribute)
    {
        $identifier = $base . '_' . self::DATA_TYPE_STRING . '_' . $contentObjectAttribute->attribute('id');
        return $identifier;
    }

    /**
     * @param \eZContentObjectAttribute $contentObjectAttribute
     * @return mixed
     */
    private function getFormId(\eZContentObjectAttribute $contentObjectAttribute)
    {
        $data = explode('|', $contentObjectAttribute->attribute('data_text'));
        $formId = $data[0];

        return $formId;
    }

    /**
     * Returns the content to object template
     * @param \eZContentObjectAttribute $contentObjectAttribute
     * @return array
     */
    public function objectAttributeContent($contentObjectAttribute)
    {
        $formId = $this->getFormId($contentObjectAttribute);
        $formData = $this->doctrine->getRepository('FormMakerBundle:FormDefinitions')->find($formId);
        $formsList = $this->doctrine->getRepository('FormMakerBundle:FormDefinitions')->findAll();
        $formsSet = array();

        foreach($formsList as $formObject) {

            $formsSet[] = array(
                'id' => $formObject->getId(),
                'name' => $formObject->getName()
           );
        }

        return array(
            'forms_list' => $formsSet,
            'form_id' => $formId,
            'form_name' => empty($formData) ? '' : $formData->getName()
       );
    }

    /**
     * Attribute validator method
     * @param \eZHTTPTool $http
     * @param string $base
     * @param \eZContentObjectAttribute $contentObjectAttribute
     * @return int
     */
    public function validateObjectAttributeHTTPInput($http, $base, $contentObjectAttribute)
    {
        $postIdentifier = $this->getPostIdentifier($base, $contentObjectAttribute);

        if ($http->hasPostVariable($postIdentifier))
        {
            $formId = $http->postVariable($postIdentifier);

            // ID needs to be numeric
            if (empty($formId) && $contentObjectAttribute->validateIsRequired())
            {
                $contentObjectAttribute->setValidationError($this->translator->trans('form.is.required', array(), 'formmaker'));
                return \eZInputValidator::STATE_INVALID;
            }
            elseif (is_numeric($formId) && !$this->doctrine->getRepository('FormMakerBundle:FormDefinitions')->find($formId))
            {
                $contentObjectAttribute->setValidationError($this->translator->trans('form.id.doesnt.exist', array(), 'formmaker'));
                return \eZInputValidator::STATE_INVALID;
            }
        }

        return \eZInputValidator::STATE_ACCEPTED;
    }

    /**
     * Method stores object attribute data
     * @param \eZHTTPTool $http $http
     * @param string $base
     * @param \eZContentObjectAttribute $contentObjectAttribute
     * @return boolean
     */
    public function fetchObjectAttributeHTTPInput($http, $base, $contentObjectAttribute)
    {
        $postId = $this->getPostIdentifier($base, $contentObjectAttribute);

        if ($http->hasPostVariable($postId)) {

            $contentObjectAttribute->setAttribute('data_text', $http->postVariable($postId));
            return true;
        }

        return false;
    }


    /**
     *
     *
     * ONLY GOD KNOWS WHAT METHODS BELOW DOES
     *
     *
     */


    /**
     * Returns the meta data used for storing search indexes.
     * @param \eZContentObjectAttribute $contentObjectAttribute
     * @return string
     */
    public function metaData($contentObjectAttribute)
    {
        return $contentObjectAttribute->attribute('data_text');
    }

    /**
     * return string representation of an contentobjectattribute data for simplified export
     * @param \eZContentObjectAttribute $contentObjectAttribute
     * @return string
     */
    public function toString($contentObjectAttribute)
    {
        return $contentObjectAttribute->attribute('data_text');
    }

    /**
     * @param \eZContentObjectAttribute $contentObjectAttribute
     * @param $string
     */
    public function fromString($contentObjectAttribute, $string)
    {
        return $contentObjectAttribute->setAttribute('data_text', $string);
    }

    /**
     * Returns the content of the string for use as a title
     * @param \eZContentObjectAttribute $contentObjectAttribute
     * @param string $name
     * @return string
     */
    public function title($contentObjectAttribute, $name = null)
    {
        return $contentObjectAttribute->attribute('data_text');
    }

    public function isIndexable()
    {
        return true;
    }

    public function isInformationCollector()
    {
        return false;
    }

    public function sortKeyType()
    {
        return 'string';
    }

    /**
     * Simple string insertion is supported.
     * @return boolean
     */
    public function isSimpleStringInsertionSupported()
    {
        return true;
    }

    public function hasObjectAttributeContent($contentObjectAttribute)
    {
        return trim($contentObjectAttribute->attribute('data_text')) != '';
    }

    public function insertSimpleString($object, $objectVersion, $objectLanguage, $objectAttribute, $string, &$result) { }
    public function storeClassAttribute($attribute, $version){ }
    public function storeDefinedClassAttribute($attribute){ }
    public function fixupClassAttributeHTTPInput($http, $base, $classAttribute) { }
    public function storeObjectAttribute($attribute) { }
    public function initializeObjectAttribute($contentObjectAttribute, $currentVersion, $originalContentObjectAttribute) { }
    public function batchInitializeObjectAttributeData($classAttribute) { }
    public function supportsBatchInitializeObjectAttribute() { }
    public function diff($old, $new, $options = false) { }
    public function unserializeContentClassAttribute($classAttribute, $attributeNode, $attributeParametersNode) { }
    public function serializeContentClassAttribute($classAttribute, $attributeNode, $attributeParametersNode) { }
    public function sortKey($contentObjectAttribute) { }
}

\eZDataType::register(FormType::DATA_TYPE_STRING, 'eZPublishLegacy\FormMaker\DataTypes\FormType');