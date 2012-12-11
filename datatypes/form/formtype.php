<?php

/**
 * Form datatype class
 * @author Piotr Szczygieł <piotr.szczygiel@makingwaves.pl>
 */
class formType extends eZDataType
{
    const DATA_TYPE_STRING = 'form';

    /**
     * Initializes with a string id and a description.
     */
    function formType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, 'Form',
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_text' => 'form' ) ) );
    }

    /**
     * Attribute validator method
     * @param type $http
     * @param type $base
     * @param type $contentObjectAttribute
     * @return type
     */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ($http->hasPostVariable($base . '_form_' . $contentObjectAttribute->attribute('id')))
        {
            $form_id = $http->postVariable($base . '_form_' . $contentObjectAttribute->attribute('id'));
            // ID needs to be numeric
            if (empty($form_id) && $contentObjectAttribute->validateIsRequired())
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Form required.' ) );
                return eZInputValidator::STATE_INVALID;                
            }
            elseif (is_numeric($form_id) && !count(formDefinitions::getForm($form_id)))
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', "Given Form ID doesn't exist" ) );
                return eZInputValidator::STATE_INVALID;                 
            }
        }
        
        return eZInputValidator::STATE_ACCEPTED;
    }

    /**
     * Method stores object attribute data
     * @param type $http
     * @param type $base
     * @param type $contentObjectAttribute
     * @return boolean
     */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ($http->hasPostVariable($base . '_form_' . $contentObjectAttribute->attribute('id')))
        {
            $form_id = $http->postVariable($base . '_form_' . $contentObjectAttribute->attribute('id'));
            $contentObjectAttribute->setAttribute( 'data_text', $form_id );
            return true;
        }
        
        return false;
    }  
    
    /**
     * Returns the content to object template
     * @param type $contentObjectAttribute
     * @return array
     */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $data = explode('|', $contentObjectAttribute->attribute('data_text'));
        $form_id = $data[0];
        $form_data = formDefinitions::getForm($form_id);
        $form_name = '';
        if (!empty($form_data))
        {
            $form_name = $form_data->attribute('name');
        }
        
        return array( 'forms_list'  => formDefinitions::getAllForms(),
                      'form_id'   => $form_id,
                      'form_name' => $form_name);
    }

    /**
     * Returns the meta data used for storing search indeces.
     * @param type $contentObjectAttribute
     * @return type
     */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text', $data );
    }
    
    /**
     * return string representation of an contentobjectattribute data for simplified export
     * @param type $contentObjectAttribute
     * @return type
     */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text', $data );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_text', $data );
    }

    /**
     * Returns the content of the string for use as a title
     * @param type $contentObjectAttribute
     * @param type $name
     * @return type
     */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( 'data_text', $data );
    }

    function isIndexable()
    {
        return true;
    }

    function isInformationCollector()
    {
        return false;        
    }

    function sortKeyType()
    {
        return 'string';
    }

    /**
     * Simple string insertion is supported.
     * @return boolean
     */
    function isSimpleStringInsertionSupported()
    {
        return true;
    }
    
    function insertSimpleString( $object, $objectVersion, $objectLanguage,
                                 $objectAttribute, $string,
                                 &$result ) { }
                                 
    function storeClassAttribute( $attribute, $version ){ }
    
    function storeDefinedClassAttribute( $attribute ){ }
    
    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute ) { }    
    
    function storeObjectAttribute( $attribute ) { }
    
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute ) { }
    
    function batchInitializeObjectAttributeData( $classAttribute ) { }
    
    function supportsBatchInitializeObjectAttribute() { }
    
    function diff( $old, $new, $options = false ) { }
    
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode ) { }
    
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode ) { }
    
    function sortKey( $contentObjectAttribute ) { }
    
    function hasObjectAttributeContent( $contentObjectAttribute ) 
    {
        return trim( $contentObjectAttribute->attribute( 'data_text' ) ) != '';
    }
}

eZDataType::register( formType::DATA_TYPE_STRING, 'formtype' );
