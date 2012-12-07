<?php

/**
 * Class interface for forms_attributes_options SQL table
 */
class formAttributesOptions extends eZPersistentObject 
{
    /**
     * Table definition
     * @return array
     */
    public static function definition()
    {
        $def = array( "fields" => array( "id"       => array( "name" => "id",
                                                              "datatype" => "integer",
                                                              "required" => true ),
                                         "attr_id"  => array( "name" => "attr_id",
                                                              "datatype" => "integer",
                                                              "required" => true ),
                                         "label"    => array( "name" => "label",
                                                              "datatype" => "integer",
                                                              "required" => true ) ),
                      "keys" => array('id'),
                      "increment_key" => "id",
                      "class_name" => "formAttributesOptions",
                      "name" => "forms_attributes_options" );
        return $def;
    }     
}