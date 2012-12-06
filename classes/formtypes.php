<?php

/**
 * Class interface for formmaker_types SQL table
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
        $def = array( "fields" => array( "id"      => array( "name" => "id",
                                                             "datatype" => "integer",
                                                             "required" => true ),         
                                         "name"     => array( "name" => "name",
                                                              "datatype" => "string",
                                                              "required" => true ), 
                                         "template" => array( "name" => "template",
                                                              "datatype" => "string",
                                                              "required" => true ) ),
                      "keys" => array('id'),
                      "increment_key" => "id",
                      "class_name" => "formTypes",
                      "sort" => array(),
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
    
}