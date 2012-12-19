<?php

/**
 * Class interface for form_attributes_options SQL table
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
                                         "opt_order"=> array( "name" => "opt_order",
                                                              "datatype" => "integer",
                                                              "required" => true ),            
                                         "label"    => array( "name" => "label",
                                                              "datatype" => "string",
                                                              "required" => true ) ),
                      "keys" => array('id'),
                      "increment_key" => "id",
                      "class_name" => "formAttributesOptions",
                      "sort" => array('opt_order' => 'asc'),
                      "name" => "form_attributes_options" );
        return $def;
    }
    
    /**
     * Method adds new entry to database
     * @param string $label
     * @param int $order
     * @param int $attribute_id
     * @return \self
     */
    public static function addOption( $label, $order, $attribute_id )
    {
        $obj = new self( array (
            'attr_id'   => $attribute_id,
            'opt_order' => $order,
            'label'     => $label
        ) );
        $obj->store();
        return $obj;
    }
    
    /**
     * Method returns options for given attribute
     * @param int $attribute_id
     * @param $pairs - returns array containing pairs $key => $value
     * @return array
     */
    public static function getAttributeOptions( $attribute_id, $pairs = false )
    {
        $data = eZPersistentObject::fetchObjectList( self::definition(), null, array(
            'attr_id' => $attribute_id
        ) );
        
        if ($pairs)
        {
            $result = array();
            foreach( $data as $opt )
            {
                $result[$opt->attribute('id')] = $opt->attribute('label');
            }   
            $data = $result;
        }
        
        return $data;
    }
    
    /**
     * Method removed an atttribute option
     * @param type $option_id
     */
    public function removeOption()
    {
        eZPersistentObject::removeObject( self::definition(), array(
            'id'    => $this->attribute( 'id' )
        ) );          
    }
    
    /**
     * Returns attribute option with given ID or false in case of incorrect ID
     * @param int $id
     * @return false|formAttributesOptions
     */
    public static function fetchOption( $id )
    {
        return eZPersistentObject::fetchObject( self::definition(), null, array( 'id' => $id ) );
    }    
    
    /**
     * Method sets data for current object
     * @param int $attr_id
     * @param string $label
     * @param int $opt_order
     */
    public function setData( $attr_id, $label, $opt_order )
    {
        $this->setAttribute( 'attr_id', $attr_id );
        $this->setAttribute( 'label', $label );
        $this->setAttribute( 'opt_order', $opt_order);
    }
}