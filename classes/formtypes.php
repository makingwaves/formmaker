<?php

/**
 * Class interface for form_types SQL table
 */
class formTypes extends eZPersistentObject 
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
                                         "name"         => array( "name" => "name",
                                                                  "datatype" => "string",
                                                                  "required" => true ), 
                                         "validation"   => array( "name" => "validation",
                                                                  "datatype" => "integer",
                                                                  "required" => true ),     
                                         "sep_order"    => array( "name" => "sep_order",
                                                                  "datatype" => "integer",
                                                                  "required" => true ),             
                                         "template"     => array( "name" => "template",
                                                                  "datatype" => "string",
                                                                  "required" => true ) ),
                      "keys" => array('id'),
                      "function_attributes" => array(
                          'validators'  => 'getTypeValidators'
                      ),            
                      "increment_key" => "id",
                      "class_name" => "formTypes",
                      "sort" => array('sep_order' => 'asc'),
                      "name" => "form_types" );
        return $def;
    }    
    
    /**
     * Method returns all available input types
     * @return type
     */
    public static function getAllTypes()
    {
        return eZPersistentObject::fetchObjectList( self::definition() );
    }
    
    /**
     * Method fetches the object by given id
     * @param int $id
     * @return formTypes
     */
    public static function fetchById( $id )
    {
        return eZPersistentObject::fetchObject( self::definition(), null, array( 'id' => $id ) );    
    }
    
    /**
     * Returns validators array for current type, or false in case that type doesn's support validators
     * @return boolean|array
     */
    public function getTypeValidators()
    {
        if ( $this->attribute( 'validation' ) == 0 )
        {
            return false;
        }
        return formValidators::getValidators(false);
    }
    
}