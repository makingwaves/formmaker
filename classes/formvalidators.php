<?php

/**
 * Class interface for formmaker_validators SQL table
 */
class formValidators extends eZPersistentObject 
{
    // ids from databse
    const DIGITS_ID     = 1;
    const EMAIL_ID      = 2;
    const FLOAT_ID      = 3;
    const NOT_EMPTY_ID  = 5;
    const HOSTNAME_ID   = 6;
    const IP_ID         = 7;
    const DATE_ID       = 8;
    const DATE_YEAR_ID  = 9;
    const CUSTOM_REGEX  = 10;
    
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
                                         "regex"        => array( "name" => "regex",
                                                                  "datatype" => "string",
                                                                  "required" => true ),              
                                         "description"  => array( "name" => "description",
                                                                  "datatype" => "string",
                                                                  "required" => true ) ),
                      "keys" => array('id'),
                      "increment_key" => "id",
                      "class_name" => "formValidators",
                      "sort" => array(),
                      "sort" => array('description' => 'asc'),
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