<?php

/**
 * Class interface for formmaker_validators SQL table
 */
class formValidators extends eZPersistentObject 
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
        $def = array( "fields" => array( "id"           => array( "name" => "id",
                                                                  "datatype" => "integer",
                                                                  "required" => true ),
                                         "type"         => array( "name" => "type",
                                                                  "datatype" => "string",
                                                                  "required" => true ),           
                                         "description"  => array( "name" => "description",
                                                                  "datatype" => "string",
                                                                  "required" => true ) ),
                      "keys" => array('id'),
                      "increment_key" => "id",
                      "class_name" => "formValidators",
                      "sort" => array(),
                      "name" => "form_validators" );
        return $def;
    }    
    
    /**
     * Method returns array containing validator data
     * @param int $validator_id
     * @return null|formValidators
     */
    public static function getValidator($validator_id)
    {
        $validator = eZPersistentObject::fetchObjectList(self::definition(), null, array('id' => $validator_id));
        if (isset($validator[0]))
        {
            return $validator[0];
        }
        
        return null;
    }
    
    /**
     * Method returns all validators
     * @return array
     */
    public static function getValidators($all = true)
    {
        $validators = eZPersistentObject::fetchObjectList(self::definition());
        
        // removing NotEmpty validator from array, we'll have a separate checkbox
        if(!$all) {
            foreach($validators as $key => $validator) {
                if($validator->type == 'NotEmpty') {
                    unset($validators[$key]);
                }
            }
        }
        
        return $validators;
        
    }
}