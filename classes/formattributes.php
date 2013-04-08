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
                                         "identifier"       => array( "name" => "identifier",
                                                                      "datatype" => "string",
                                                                      "required" => false ),
                                         "enabled"          => array( "name" => "enabled",
                                                                      "datatype" => "int",
                                                                      "required" => true ),       
                                         "email_receiver"   => array( "name" => "email_receiver",
                                                                      "datatype" => "int",
                                                                      "required" => true ),             
                                         "default_value"    => array( "name" => "default_value",
                                                                      "datatype" => "string",
                                                                      "required" => false ),
                                         "css"              => array( "name" => "css",
                                                                      "datatype" => "string",
                                                                      "required" => false ),            
                                         "label"            => array( "name" => "label",
                                                                      "datatype" => "string",
                                                                      "required" => true ),
                                         "description"      => array( "name" => "description",
                                                                      "datatype" => "string",
                                                                      "required" => false ),
                                         "allowed_file_types"      => array( "name" => "allowed_file_types",
                                                                      "datatype" => "string",
                                                                      "required" => false )
                                         ),
                      "keys" => array('id'),
                      "function_attributes" => array(
                          'validators'      => 'getAttributeValidators',
                          'validator_ids'   => 'getValidatorAllIds',
                          'type_data'       => 'getTypeData',
                          'is_mandatory'    => 'isMandatoryHtml',
                          'options'         => 'getOptions',
                          'custom_regex'    => 'getCustomRegex',
                          'is_file'         => 'is_file',
                          'is_image'        => 'is_image',
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
     * @param string $identifier
     * @param int $enabled
     * @param string $description
     * @param string $def_value    
     * @param int $email_receiver 
     * @param string $css
     * @param string $allowed_file_types
     * @return \self
     */
    public static function addNewAttribute( $order, $definition_id, $type_id, $label, $identifier, $enabled, $description, $def_value, $email_receiver, $css, $allowed_file_types )
    {
        $object = new self( array(
            'definition_id'     => $definition_id,
            'type_id'           => $type_id
        ) );
        $object->setData( $order, $label, $identifier, $enabled, $description, $def_value, $email_receiver, $css, $allowed_file_types );
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
        $attribute = eZPersistentObject::fetchObject( self::definition(), null, array( 'id' => $id ) );
        return $attribute;
    }
        
    
    /**
     * Returns array of validators assigned to current attribute object
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
            $class_name = 'FormMaker_Validate_' . $validator_row->attribute('type');
            if ($validator_row->attribute( 'type' ) == 'Regex')
            {
                switch ( $validator_row->attribute( 'id' ) ) 
                {
                    case formValidators::DATE_ID:
                        $formmaker_ini = eZINI::instance( 'formmaker.ini' );
                        // integration with locale date format
                        if ( $formmaker_ini->variable( 'FormmakerSettings', 'DynamicDateFormat' ) == 'enabled' )
                        {
                            $locale = eZLocale::instance();
                            $regex = $formmaker_ini->hasGroup( 'ShortDateFormat_' . $locale->ShortDateFormat ) ? 
                                     $formmaker_ini->variable( 'ShortDateFormat_' . $locale->ShortDateFormat, 'Regex' ) :
                                     $validator_row->attribute( 'regex' );
                        }
                        else
                        {
                            $regex = $validator_row->attribute( 'regex' );
                        }
                        break;

                    case formValidators::CUSTOM_REGEX:
                        $regex = $attr_valid->attribute( 'regex' );
                        break;

                    default:
                        $regex = $validator_row->attribute( 'regex' );
                        break;
                }

                $validator_object = new $class_name( $regex );
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
            $item['identifier'] = isset( $item['identifier'] ) ? $item['identifier'] : '';
            $item['email_receiver'] = isset( $item['email_receiver'] ) ? $item['email_receiver'] : 0;
            $item['description'] = isset( $item['description'] ) ? $item['description'] : '';
            $item['css'] = isset( $item['css'] ) ? $item['css'] : '';
            $item['mandatory'] = isset( $item['mandatory'] ) ? $item['mandatory'] : 0;
            $item['allowed_file_types'] = isset( $item['allowed_file_types'] ) ? $item['allowed_file_types'] : '';
            
            // if ID is an integer, we're UPDATING the attribute, because it does EXIST in database
            if ( ctype_digit( (string)$id ) )
            {
                $processed_ids[] = $id;
                $attribute = self::getAttribute( $id );
                $attribute->setData( $order,
                                     $item['label'],
                                     $item['identifier'],
                                     $item['enabled'],
                                     $item['description'],
                                     $item['default'],
                                     $item['email_receiver'],
                                     $item['css'],
                                     $item['allowed_file_types'] );
                $attribute->store();

                $correct_validators = array();
                if ( isset( $item['validation'] ) && ctype_digit( (string)$item['validation'] ) &&  $item['validation'] > 0 )
                {
                    $correct_validators[$item['validation']] = array(
                        'id'    => $item['validation'],
                        'regex' => ( !empty( $item['custom_regex'] ) &&  $item['validation'] == formValidators::CUSTOM_REGEX) ? $item['custom_regex'] : ''
                    );
                }
                
                if ( isset( $item['mandatory'] ) && $item['mandatory'] == 'on' )
                {
                    $correct_validators[formValidators::NOT_EMPTY_ID] = array(
                        'id'    => formValidators::NOT_EMPTY_ID
                    );
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
                $attribute = self::addNewAttribute( $order,
                                                    $definition_id,
                                                    $item['type'],
                                                    $item['label'],
                                                    $item['identifier'],
                                                    $item['enabled'],
                                                    $item['description'],
                                                    $item['default'],
                                                    $item['email_receiver'],
                                                    $item['css'],
                                                    $item['allowed_file_types'] );
                $processed_ids[] = $attribute->attribute( 'id' );
                // adding 'required' validator
                if ( $item['mandatory'] == 'on' )
                {
                    formAttrvalid::addRecord( $attribute->attribute( 'id' ), formValidators::NOT_EMPTY_ID );
                }
                // adding other validator
                if ( isset( $item['validation'] ) && ctype_digit( (string)$item['validation'] ) &&  $item['validation'] > 0 )
                {
                    formAttrvalid::addRecord( $attribute->attribute( 'id' ), $item['validation'], ( isset( $item['custom_regex'] ) ) ? $item['custom_regex'] : '' );
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
                        if ( $key == $default_value && in_array( $attribute->attribute( 'type_id'), array( formTypes::RADIO_ID, formTypes::SELECT_ID ) ) ) 
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
     * @param string $identifier
     * @param int $enabled
     * @param string description
     * @param string $default
     * @param int $email_receiver
     * @param string $css
     */
    private function setData( $order, $label, $identifier, $enabled, $description, $default, $email_receiver, $css, $allowed_file_types )
    {
        $this->setAttribute( 'attr_order', $order );
        $this->setAttribute( 'label', $label );
        $this->setAttribute( 'identifier', $identifier );
        $this->setAttribute( 'enabled', $enabled );
        $this->setAttribute( 'description', $description );
        $this->setAttribute( 'default_value', $default );
        $this->setAttribute( 'email_receiver', $email_receiver );
        $this->setAttribute( 'css', $css );
        $this->setAttribute( 'allowed_file_types', $allowed_file_types );
    }
    
    /**
     * Method removed old validators and add new ones (for current attribute)
     * @param array $correct_validators
     */
    private function updateValidators( $correct_validators )
    {              
        // removing all validators
        foreach ( $this->getAttributeValidators() as $old_validator )
        {
            formAttrvalid::removeRecord( $this->attribute( 'id' ), $old_validator->attribute( 'validator_id' ) );
        }
        
        // adding new validators
        foreach ( $correct_validators as $validator_id => $new_validator )
        {
            formAttrvalid::addRecord(
                $this->attribute('id'), 
                $validator_id, 
                ( isset( $correct_validators[$validator_id]['regex'] ) ) ? $correct_validators[$validator_id]['regex'] : ''                     
            );
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
    
    /**
     * Method returns custom regex in case when attribute uses it
     * @return string
     */
    public function getCustomRegex()
    {
        foreach( $this->getAttributeValidators() as $validator )
        {
            if ($validator->attribute('validator_id') == formValidators::CUSTOM_REGEX) 
            {
                return $validator->attribute( 'regex' );
            }
        }
        return '';
    }

    /**
    * Method returns true if attribute holds a file
    * @return bool
    */
    public function is_file()
    {
        return file_exists($this->attribute('default_value'));
    }

    /**
    * Method returns true if file is an actual image
    * @return bool
    */
    public function is_image()
    {
        return FormMakerFunctionCollection::isImage($this->attribute('default_value'));
    }

}