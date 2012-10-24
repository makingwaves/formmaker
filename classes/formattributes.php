<?php

/**
 * Class interface for formmaker_attributes SQL table
 */
class formAttributes extends eZPersistentObject 
{
    /**
     * Constructor
     * @param type $row
     */
    public function __construct( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /**
     *  Table definition
     * @return array
     */
    public static function definition()
    {
        $def = array( "fields" => array( "id"               => array( "name" => "id",
                                                                      "datatype" => "integer",
                                                                      "required" => true ),
                                         "attr_order"       => array( "name" => "attr_order",
                                                                      "datatype" => "integer",
                                                                      "required" => true ),
                                         "definition_id"    => array( "name" => "definition_id",
                                                                      "datatype" => "integer",
                                                                      "required" => true ),
                                         "type"             => array( "name" => "type",
                                                                      "datatype" => "string",
                                                                      "required" => true ),
                                         "default_value"    => array( "name" => "default_value",
                                                                      "datatype" => "string",
                                                                      "required" => false ),
                                         "label"            => array( "name" => "label",
                                                                      "datatype" => "string",
                                                                      "required" => true ),
                                         "css_class"        => array( "name" => "css_class",
                                                                      "datatype" => "string",
                                                                      "required" => false ) ),
                      "keys" => array('id'),
                      "function_attributes" => array( 'object' => 'getContentObject' ),
                      "increment_key" => "id",
                      "class_name" => "formAttributes",
                      "sort" => array('attr_order' => 'asc'),
                      "name" => "form_attributes" );
        return $def;
    }    
    
    /**
     * Method returns content object
     * @return type
     */
    public function getContentObject() 
    { 
        return eZPersistentObject::fetchObject( eZContentObject::definition(),
                                null, // all fields
                                array( 'id' => $this->attribute( 'id' ) ), // conditions
                                true // return as object
                                );        
    }
    
    /**
     * Method returns array containing all attributes of given MWForm ID
     * @param int $form_id
     * @return array
     */
    public static function getFormAttributes($form_id)
    {   
        if (!is_numeric($form_id)) {
            return array();
        }
        
        return eZPersistentObject::fetchObjectList(self::definition(), null, array('definition_id' => $form_id));
    }
    
    /**
     * Returns attribute with given ID or null in case of incorrect ID
     * @param int $id
     * @return null|formAttributes
     */
    public static function getAttribute($id)
    {
        $attribute = eZPersistentObject::fetchObjectList(self::definition(), null, array('id' => $id));
        if (isset($attribute[0]))
        {
            return $attribute[0];
        }
        return null;
    }
        
    
    /**
     * Meturns array of validators assigned to current attribute object
     * @return array
     */
    public function getAttributeValidators()
    {
        return formAttrvalid::getValidatorsByAttribute($this->attribute('id'));
    }
    
    /**
     * Method validates current row and returns an array of error messages. In case when everything id OK, array is empty.
     * @param string $value - here comes value from the form
     * @return array
     */
    public function validate($value)
    {
        $attribute_validators = $this->getAttributeValidators();
        $result = array();
        
        foreach ($attribute_validators as $attr_valid)
        {
            // we don't want to validate empty values except "Required"  validator
            if ($attr_valid->attribute('validator_id') != 5 && empty($value)) {
                continue;
            }
            
            $validator_row = formValidators::getValidator($attr_valid->attribute('validator_id'));
            $class_name = 'Validate_' . $validator_row->attribute('type');
            $validator_object = new $class_name;

            // if value is not valid
            if(!$validator_object->isValid($value))
            {
                $messages = $validator_object->getMessages();

                // validator ID as a key and message as a value
                $result[$validator_row->attribute('id')] = $messages;
            }
        }        
        
        return $result;
    }
    
    
    /*
     * adds new attributes or updates existing ones
     * @param array $data
     * @return null
     */
    static function updateFormAttributes( $data ) 
    {
        
        $ids = $objects = array();
        
        // get IDs of all existing attributes
        $attributes = self::getFormAttributes( $data['definition_id'] );

        foreach($attributes as $id) {
            $ids[] = $id->id;
        }
        
        // determine removed attributes
        $removed = array_diff($ids, $data['ids']);
        
        
        // delete removed attirbutes
        foreach( $removed as $id ) {
            
            $cond = array( 'id' => $id );
            eZPersistentObject::removeObject( formAttributes::definition(), $cond );
            
            // remove also validators info
            $cond = array( 'attribute_id' => $id );
            eZPersistentObject::removeObject( formAttrvalid::definition(), $cond );
            
        }
        
        // build array with attributes to add
        foreach( $data['ids'] as $key => $item ) {
            
            $validators = array();
            $validators[] = $data['validators'][$key];
            
            if( $data['mandatoriesValue'][$key] )
            {
                $validators[] = 5; // mandatory value
            }
            
            if( $data['types'][$key] == 'checkbox' )
            {
                $data['placeholders'][$key] = $data['placeholdersValue'][$key];
            }
            
            $objects[] = array(
                'id' => $data['ids'][$key],
                'attr_order' => $key,
                'definition_id' => $data['definition_id'],
                'type' => $data['types'][$key],
                'default_value' => $data['placeholders'][$key],
                'label' => $data['labels'][$key],
                'css_class' => $data['css_classes'][$key],
                'validators' => $validators
            );
            
        }
        
        // add  new attributes or update existing
        foreach( $objects as $key => $item ) {
            
            // check if alredy in DB
            $cond = array( 'id' => $item['id'] );
            $simpleObj = eZPersistentObject::fetchObject( formAttributes::definition(), null, $cond );
            
            $cond = array( 'attribute_id' => $item['id'] );
            $activeValidatorsList = eZPersistentObject::fetchObjectList( formAttrvalid::definition(), null, $cond );
            
            foreach( $activeValidatorsList as $activeValidator ) {
                eZPersistentObject::removeObject( formAttrvalid::definition(), $cond );
            }
            
            // update existing
            if( $simpleObj )
            {
                
               // $cond = array( 'id' => $id );
                //eZPersistentObject::removeObject( formAttributes::definition(), $cond );
                
                // update validators
                foreach( $item['validators'] as $validatorId ) {
                    
                    if(!is_numeric($validatorId)){
                        continue;
                    }

                    $validator = new formAttrvalid( array( 'attribute_id' => $item['id'],
                                                            'validator_id' => $validatorId,
                                                            ));
                    $validator->store();
                }

                $simpleObj->setAttribute( 'attr_order', $key );
                $simpleObj->setAttribute( 'definition_id', $item['definition_id'] );
                $simpleObj->setAttribute( 'type', $item['type'] );
                $simpleObj->setAttribute( 'default_value', $item['default_value'] );
                $simpleObj->setAttribute( 'label', $item['label'] );
                $simpleObj->setAttribute( 'css_class', $item['css_class'] );
                $simpleObj->store();
                
            }
            // add new
            else
            {
                
                $simpleObj = new formAttributes( array( 'id' => '', 
                                                   'attr_order' => $key,
                                                   'definition_id' => $item['definition_id'],
                                                   'type' => $item['type'],
                                                   'default_value' => $item['default_value'],
                                                   'label' => $item['label'],
                                                   'css_class' => $item['css_class']
                                                   ));
                
                // first store the object, we need id for validators' table
                $simpleObj->store();
                
                // add validators
                foreach( $item['validators'] as $validatorId ) {
                    
                    if(!is_numeric($validatorId)){
                        continue;
                    }                    
                    
                    $validator = new formAttrvalid( array( 'attribute_id' => $simpleObj->attribute('id'),
                                                            'validator_id' => $validatorId,
                                                            ));
                    $validator->store();
                }
            }

        }
    }
}