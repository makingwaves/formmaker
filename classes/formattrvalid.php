<?php

/**
 * Class interface for form_validators SQL table
 */
class formAttrvalid extends eZPersistentObject 
{
    /**
     * ID of the "required" validator
     */
    const REQUIRED_ID = 5;
    
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
        $def = array( "fields" => array( "attribute_id" => array( "name" => "attribute_id",
                                                                  "datatype" => "integer",
                                                                  "required" => true ),
                                         "validator_id" => array( "name" => "validator_id",
                                                                  "datatype" => "integer",
                                                                  "required" => true ) ),
                      "keys" => array('attribute_id', 'validator_id'),
                      "class_name" => "formAttrvalid",
                      "sort" => array(),
                      "name" => "form_attr_valid" );
        return $def;
    }    
    
    /**
     * Method creates, sotres in db and returns and new object of db record
     * @param int $attribute_id
     * @param int $validator_id
     * @return \self
     */
    public static function addRecord( $attribute_id, $validator_id )
    {
        $object = new self ( array(
            'attribute_id'  => $attribute_id,
            'validator_id'  => $validator_id
        ) );
        $object->store();
        return $object;
    }
    
    /**
     * Method removes the record for given attributes
     * @param int $attribute_id
     * @param int $validator_id
     */
    public static function removeRecord( $attribute_id, $validator_id )
    {
        eZPersistentObject::removeObject( self::definition(), array(
            'attribute_id'  => $attribute_id,
            'validator_id'  => $validator_id
        ) );
    }
    
    /**
     * Method returns all attribute validators
     * @param int $attribute_id
     * @return array
     */
    public static function getValidatorsByAttribute($attribute_id)
    {
        return eZPersistentObject::fetchObjectList(self::definition(), null, array('attribute_id' => $attribute_id));
    }
    
    /**
     * Method checks if attribute of given ID is required or not
     * @param int $attribute_id
     * @return boolean
     */
    public static function isAttributeRequired($attribute_id)
    {
        $validators = self::getValidatorsByAttribute($attribute_id);
        $is_required = false;
        
        foreach ($validators as $validator)
        {
            if ($validator->attribute('validator_id') == self::REQUIRED_ID)
            {
                $is_required = true;
                break;
            }
        }
        
        return $is_required;
    }
    
    /**
     * Method returns array with counted validators per 
     * @return int
     */
    public static function countValidatorsForAttributes()
    {
        $all_rows   = eZPersistentObject::fetchObjectList(self::definition());
        $counted    = array();
        
        foreach ($all_rows as $row) 
        {
            $attr_id = $row->attribute('attribute_id');
            if (!isset($counted[$attr_id])) {
                $counted[$attr_id] = 0;
            }
            
            $counted[$attr_id] ++;
        }
        
        return $counted;
    }
}