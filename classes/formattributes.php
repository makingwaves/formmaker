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
                                         "type_id"          => array( "name" => "type_id",
                                                                      "datatype" => "int",
                                                                      "required" => true ),
                                         "default_value"    => array( "name" => "default_value",
                                                                      "datatype" => "string",
                                                                      "required" => false ),
                                         "label"            => array( "name" => "label",
                                                                      "datatype" => "string",
                                                                      "required" => true ) ),
                      "keys" => array('id'),
                      "function_attributes" => array(
                          'validators'      => 'getAttributeValidators',
                          'validator_ids'   => 'getValidatorAllIds',
                          'type_data'       => 'getTypeData',
                          'is_mandatory'    => 'isMandatoryHtml'
                      ),
                      "increment_key" => "id",
                      "class_name" => "formAttributes",
                      "sort" => array('attr_order' => 'asc'),
                      "name" => "form_attributes" );
        return $def;
    }    
    
    public static function createEmpty()
    {
        return new self();
    }
    
    /**
     * Method creates, stores in db and returns new object of attribute
     * @param int $order
     * @param int $definition_id
     * @param int $type_id
     * @param string $def_value
     * @param string $label
     * @return \self
     */
    public static function addNewAttribute( $order, $definition_id, $type_id, $def_value, $label )
    {
        $object = new self( array(
            'attr_order'    => $order,
            'definition_id' => $definition_id,
            'type_id'       => $type_id,
            'default_value' => $def_value,
            'label'         => $label
        ) );
        $object->store();
        return $object;        
    }
    
    /**
     * Method returns type data for current attribute
     * @return formTypes
     */
    public function getTypeData()
    {
        return formTypes::fetchById( $this->attribute( 'type_id' ) );
    }
    
    /**
     * Method checks whether attribue is mandatory
     * @return boolean
     */
    public function isMandatoryHtml()
    {
        return (formAttrvalid::isAttributeRequired( $this->attribute('id') )) ? 'on' : 0;
    }
    
    /**
     * Method returns array containing all attributes of given MWForm ID
     * @param int $form_id
     * @return array
     */
    public static function getFormAttributes($form_id)
    {   
        if ( !ctype_digit( $form_id ) ) {
            return array();
        }
        
        return eZPersistentObject::fetchObjectList( self::definition(), null, array( 'definition_id' => $form_id ) );
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
     * Method returns an array of attribue validator's ids.
     * @return array
     */
    public function getValidatorAllIds()
    {
        $result = array();
        foreach ( $this->getAttributeValidators() as $validator )
        {
            $result[] = $validator->attribute( 'validator_id' );
        }
        return $result;
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
    static function updateFormAttributes( $data, $definition_id ) 
    {
        $order = 0;
        $processed_ids = array(); // an array of processed ids, will be completed inside to loop
        
        foreach ( $data as $key => $item )
        {
            // we need only form elements on this level
            if ( !preg_match( '/^formelement_/', $key ) )
            {
                continue;
            }
            
            $id = explode( '_', $key );
            $id = $id[1];
            $order ++;
            
            // if ID is an integer, we're UPDATING the attribute, because it does EXIST in database
            if ( ctype_digit( $id ) )
            {
                
            }
            // ID is an unique hash, which means that it's NEW one and we need to add it to database
            else 
            {
                $attribute = self::addNewAttribute( $order, $definition_id, $item['type'], $item['default'], $item['label'] );
                // adding 'required' validator
                if ( $item['mandatory'] == 'on' )
                {
                    formAttrvalid::addRecord( $attribute->attribute( 'id' ), formAttrvalid::REQUIRED_ID );
                }
                // adding other validator
                if ( isset( $item['validation'] ) && ctype_digit( $item['validation'] ) )
                {
                    formAttrvalid::addRecord( $attribute->attribute( 'id' ), $item['validation'] );
                }
            }
        }
    }
}