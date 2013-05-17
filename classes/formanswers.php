<?php

/**
 * Class responsible for handling `form_answers` MySQL table
 */
class formAnswers extends eZPersistentObject
{
    /**
     * MySQL table definition
     * @return array
     */
    public static function definition()
    {
        return array(
            'fields' => array(
                'id'                => array( 'name'     => 'id',
                                              'datatype' => 'integer',
                                              'required' => true ),
                 'definition_id'    => array( 'name'     => 'definition_id',
                                              'datatype' => 'integer',
                                              'required' => true ),
                 'answer_date'      => array( 'name'     => 'answer_date',
                                              'datatype' => 'string',
                                              'required' => true ),
                 'user_id'          => array( 'name'     => 'user_id',
                                              'datatype' => 'integer',
                                              'required' => true )
                ),
            'keys'          => array( 'id' ),
            'increment_key' => 'id',
            'class_name'    => 'formAnswers',
            'sort'          => array( 'answer_date' => 'asc' ),
            'name'          => 'form_answers'
        );
    }

    /**
     * Method adds a new instance of answer into database and returns the object
     * @param int $definition_id
     * @return \self
     */
    public static function addNewAnswer( $definition_id )
    {
        $object = new self( array(
            'definition_id' => $definition_id,
            'user_id'       => eZUser::currentUser()->id()
        ) );

        $object->store();
        return $object;
    }
}