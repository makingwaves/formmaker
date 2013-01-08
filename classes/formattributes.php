<?php

/**
 * Class interface for form_attributes SQL table
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
                                         "enabled"          => array( "name" => "enabled",
                                                                      "datatype" => "int",
                                                                      "required" => true ),       
                                         "email_receiver"   => array( "name" => "email_receiver",
                                                                      "datatype" => "int",
                                                                      "required" => true ),             
                                         "default_value"    => array( "name" => "default_value",
                                                                      "datatype" => "string",
                                                                      "required" => false ),
                                         "label"            => array( "name" => "label",
                                                                      "datatype" => "string",
                                                                      "required" => true ),
                                         "description"      => array( "name" => "description",
                                                                      "datatype" => "string",
                                                                      "required" => false ) ),
                      "keys" => array('id'),
                      "function_attributes" => array(
                          'validators'      => 'getAttributeValidators',
                          'validator_ids'   => 'getValidatorAllIds',
                          'type_data'       => 'getTypeData',
                          'is_mandatory'    => 'isMandatoryHtml',
                          'options'         => 'getOptions'
                      ),
                      "increment_key" => "id",
                      "class_name" => "formAttributes",
                      "sort" => array('attr_order' => 'asc'),
                      "name" => "form_attributes" );
        return $def;
    }    
    
    /**
     * Mehtod returns empty inctance on attribute
     * @return \self
     */
    public static function createEmpty()
    {
        return new self( array(
            'enabled' => 1
        ) );
    }
    
    /**
     * Method creates, stores in db and returns new object of attribute
     * @param int $order
     * @param int $definition_id
     * @param int $type_id
     * @param string $label
     * @param int $enabled
     * @param string $description
     * @param string $def_value    
     * @param int $email_receiver 
     * @return \self
     */
    public static function addNewAttribute( $order, $definition_id, $type_id, $label, $enabled, $description, $def_value, $email_receiver )
    {
        $object = new self( array(
            'definition_id'     => $definition_id,
            'type_id'           => $type_id
        ) );
        $object->setData( $order, $label, $enabled, $description, $def_value, $email_receiver );
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
     * Returns attribute with given ID or false in case of incorrect ID
     * @param int $id
     * @return false|formAttributes
     */
    public static function getAttribute( $id )
    {
        return eZPersistentObject::fetchObject( self::definition(), null, array( 'id' => $id ) );
    }
        
    
    /**
     * Meturns array of validators assigned to current attribute object
     * @return array
     */
    public function getAttributeValidators()
    {
        return formAttrvalid::getValidatorsByAttribute( $this->attribute( 'id' ) );
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
            if ($validator_row->attribute( 'type' ) == 'Regex')
            {
                $validator_object = new $class_name( $validator_row->attribute( 'regex' ) );
            }
            else 
            {
                $validator_object = new $class_name;
            }

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
            $item['default'] = isset( $item['default'] ) ? $item['default'] : '';
            $item['email_receiver'] = isset( $item['email_receiver'] ) ? $item['email_receiver'] : 0;
            $item['description'] = isset( $item['description'] ) ? $item['description'] : '';
            
            // if ID is an integer, we're UPDATING the attribute, because it does EXIST in database
            if ( ctype_digit( (string)$id ) )
            {
                $processed_ids[] = $id;
                $attribute = self::getAttribute( $id );
                $attribute->setData( $order, $item['label'], $item['enabled'], $item['description'], $item['default'], $item['email_receiver'] );
                $attribute->store();
                
                $correct_validators = array();
                if ( isset( $item['validation'] ) && ctype_digit( (string)$item['validation'] ) &&  $item['validation'] > 0 )
                {
                    $correct_validators[] = $item['validation'];
                }
                
                if ( isset( $item['mandatory'] ) && $item['mandatory'] == 'on' )
                {
                    $correct_validators[] = formValidators::NOT_EMPTY_ID;
                }      
                
                $attribute->updateValidators( $correct_validators );
                $default_value_to_set = false;
                if ( isset( $item['options'] ) && is_array( $item['options'] ) )
                {
                    $default_value = (string)$attribute->attribute( 'default_value' );
                    $default_input = false;
                    if ( $attribute->attribute( 'type_id' ) == formTypes::RADIO_ID && !empty( $default_value ) && !ctype_alpha( $default_value ) )
                    {
                        $default_input = $default_value;
                    }
                    $default_value_to_set = $attribute->updateOptions( $item['options'], $default_value );
                }
            }
            // ID is an unique hash, which means that it's NEW one and we need to add it to database
            else 
            {
                $attribute = self::addNewAttribute( $order, $definition_id, $item['type'], $item['label'], $item['enabled'], $item['description'], $item['default'], $item['email_receiver'] );
                $processed_ids[] = $attribute->attribute( 'id' );
                // adding 'required' validator
                if ( $item['mandatory'] == 'on' )
                {
                    formAttrvalid::addRecord( $attribute->attribute( 'id' ), formValidators::NOT_EMPTY_ID );
                }
                // adding other validator
                if ( isset( $item['validation'] ) && ctype_digit( (string)$item['validation'] ) &&  $item['validation'] > 0 )
                {
                    formAttrvalid::addRecord( $attribute->attribute( 'id' ), $item['validation'] );
                }
                
                // adding attribute options
                $default_value_to_set = false;
                if ( isset( $item['options'] ) && is_array( $item['options'] ) )
                {
                    $option_order = 0;
                    foreach ( $item['options'] as $key => $label )
                    {
                        $option_order++;
                        $option_object = $attribute->addOption( $label, $option_order );
                        
                        $default_value = (string)$attribute->attribute( 'default_value' );
                        if ( $key == $default_value && $attribute->attribute( 'type_id' ) == formTypes::RADIO_ID ) 
                        {
                            $default_value_to_set = $option_object->attribute( 'id' );
                        }
                    }
                }
            }
            
            // setting default value for radio button
            if ( $default_value_to_set )
            {
                $attribute->setAttribute( 'default_value', $default_value_to_set );
                $attribute->store();
            }            
        }
        
        $form = formDefinitions::getForm( $data['definition_id'] );
        $form->removeUnusedAttributes( $processed_ids );
    }
    
    /**
     * Method sets the changable data in current attribute object
     * @param int $order
     * @param string $label
     * @param int $enabled
     * @param string description
     * @param string $default
     * @param int $email_receiver
     */
    private function setData( $order, $label, $enabled, $description, $default, $email_receiver )
    {
        $this->setAttribute( 'attr_order', $order );
        $this->setAttribute( 'label', $label );
        $this->setAttribute( 'enabled', $enabled );
        $this->setAttribute( 'description', $description );
        $this->setAttribute( 'default_value', $default );
        $this->setAttribute( 'email_receiver', $email_receiver );
    }
    
    /**
     * Method removed old validators and add new ones (for current attribute)
     * @param array $correct_validators
     */
    private function updateValidators( $correct_validators )
    {
        $existing_correct = array();
        foreach ( $this->getAttributeValidators() as $validator )
        {
            // removing an old attribute
            if ( !in_array( $validator->attribute( 'validator_id' ), $correct_validators ) )
            {
                formAttrvalid::removeRecord( $this->attribute( 'id' ), $validator->attribute( 'validator_id' ) );
            }
            else
            {
                // making an array of correct validators that already exists in database
                $existing_correct[] = $validator->attribute( 'validator_id' );
            }
        }
        
        // adding new entries to database
        foreach ( array_diff( $correct_validators, $existing_correct ) as $validator_id )
        {
            formAttrvalid::addRecord( $this->attribute('id'), $validator_id );
        }
    }
    
    /**
     * Meethod removed an attribute with given id
     * @param int $id
     */
    public function removeRecord()
    {
        eZPersistentObject::removeObject( self::definition(), array(
            'id'    => $this->attribute( 'id' )
        ) );        
    }
    
    /**
     * Method adds new attribute option
     * @param string $label
     * @param int $order
     * @return formAttributesOptions
     */
    private function addOption( $label, $order )
    {
        return formAttributesOptions::addOption( $label, $order, $this->attribute( 'id' ) );
    }
    
    /**
     * Method return current attribute options
     * @return array
     */
    public function getOptions( $pairs = false )
    {
        return formAttributesOptions::getAttributeOptions( $this->attribute( 'id' ), $pairs );
    }
    
    /**
     * Method updated the attribute options basing on array with correct entried
     * @param array $correct_options
     */
    private function updateOptions( $correct_options, $default_hash = false )
    {      
        // generating helper array
        $correct_ids = array();
        foreach ( $correct_options as $id => $label )
        {
            $correct_ids[] = $id;
        }
        
        // removing outdated options
        foreach ( $this->getOptions() as $option )
        {
            if ( !in_array( $option->attribute( 'id' ), $correct_ids ) ) 
            {
                $option->removeOption();
            }
        }
        
        $order = 0;
        $default_value = false;
        foreach ( $correct_options as $opt_id => $label )
        {
            $opt_id = (string)$opt_id;
            $order++;
            // adding new option
            if ( !ctype_digit( $opt_id ) )
            {
                $option_object = $this->addOption($label, $order);
                if ( $default_hash && $default_hash == $opt_id )
                {
                    $default_value = $option_object->attribute( 'id' );
                }
            }
            // editing existing one
            else
            {
                $option_object = formAttributesOptions::fetchOption( $opt_id );
                $option_object->setData( $this->attribute( 'id' ), $label, $order);
                $option_object->store();
            }
        }
        
        return $default_value;
    }
}