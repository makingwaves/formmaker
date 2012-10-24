<?php

/**
 * Class interface for mwezforms_definitions SQL table
 */
class mwEzFormsDefinitions extends eZPersistentObject 
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
                                         "name"             => array( "name" => "name",
                                                                      "datatype" => "string",
                                                                      "required" => true ),
                                         "create_date"      => array( "name" => "create_date",
                                                                      "datatype" => "datetime",
                                                                      "required" => true ),
                                         "owner_user_id"    => array( "name" => "owner_user_id",
                                                                      "datatype" => "integer",
                                                                      "required" => true ),
                                         "post_action"      => array( "name" => "post_action",
                                                                      "datatype" => "string",
                                                                      "required" => true ),
                                         "recipients"       => array( "name" => "recipients",
                                                                      "datatype" => "string",
                                                                      "required" => false ),
                                         "css_class"        => array( "name" => "css_class",
                                                                      "datatype" => "string",
                                                                      "required" => false ) ),
                      "keys" => array('id'),
                      "function_attributes" => array( 'object' => 'getContentObject' ),
                      "increment_key" => "id",
                      "class_name" => "mwEzFormsDefinitions",
                      "sort" => array(),
                      "name" => "mwezforms_definitions" );
        return $def;
    }    
    
    /**
     * Returns all forms from database
     * @return array
     */
    public static function getAllForms()
    {
        return eZPersistentObject::fetchObjectList(self::definition());
    }
    
    /**
     * Returns form with given ID or empty array in case of incorrect ID
     * @param int $id
     * @return array
     */
    public static function getForm($id)
    {
        $form = eZPersistentObject::fetchObjectList(self::definition(), null, array('id' => $id));
        if (isset($form[0]))
        {
            return $form[0];
        }
        return array();
    }

    /**
     * Updates form with given ID with passed $data
     * @param type $id
     * @param type $data - array with structure of array $form_elements defined in edit.php
     */
    public static function updateForm($id, $data)
    {
        $form = eZPersistentObject::fetchObject(self::definition(), null, array('id' => $id));
        foreach ($data as $identifier => $attribute)
        {
            $form->setAttribute($identifier, $attribute['value']);
        }
        
        return $form->store();
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
    
    public static function removeContentObject($id) 
    {         
        return eZPersistentObject::removeObject( mwEzFormsDefinitions::definition(), array( 'id' => $id ) );
    }

    /**
     * Use to create a new object, set the values and store in a db record
     * @param array $data
     * @return null|\oneTimeLogin
     */
    public static function addForm( $data )
    {
        
        $object = new mwEzFormsDefinitions( array( 'id' => null, 
                                           'name' => $data['name'],
                                           'create_date' => null,
                                           'owner_user_id' => 14,
                                           'post_action' => 'email',
                                           'recipients' => $data['recipients'],
                                           'css_class' => $data['css_class'] ) );
        $object->store();
        return $object;
    }    
    
    /**
     * Method clears the cache for objects which are using given $form_id
     * @param int $form_id
     */
    public static function clearFormCache($form_id)
    {
        // fetch objects which are using this form
        $objects = eZContentObjectAttribute::fetchObjectList(
                eZContentObjectAttribute::definition(), 
                array('contentobject_id'), 
                array('data_type_string' => 'mwform', 'data_text' => $form_id),
                null,
                null,
                true,
                array('contentobject_id')
        );
        
        // remove cache from these objects
        foreach ($objects as $object)
        {
            $object_id = $object->attribute('contentobject_id');
            eZContentCacheManager::clearContentCache( $object_id );
        }
        
        return true;
    }    
}
