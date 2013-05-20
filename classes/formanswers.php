<?php

/**
 * Class responsible for handling `form_answers` MySQL table
 */
class formAnswers extends eZPersistentObject
{
    private $attributes = array();      // answer attributes

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
            'keys'                  => array( 'id' ),
            'increment_key'         => 'id',
            'class_name'            => 'formAnswers',
            'sort'                  => array( 'answer_date' => 'desc' ),
            'name'                  => 'form_answers',
            'function_attributes'   => array(
                'form_data' => 'getFormData',
                'user'      => 'getUserData'
            )
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

    /**
     * Method returns a set of answers
     * @param int $form_id
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public static function getAnswers( $form_id = null, $offset = null, $limit = null )
    {
        $settings   = array();
        $limit_data = array();
        
        if ( !is_null( $form_id ) )
        {
            $settings['definition_id'] = $form_id ;
        }

        if ( !is_null( $offset ) )
        {
            $limit_data['offset'] = $offset;
        }

        if ( !is_null( $limit ) )
        {
            $limit_data['limit'] = $limit;
        }

        return self::fetchObjectList( self::definition(), null, $settings, null, $limit_data );
    }

    /**
     *
     * @param type $form_id
     * @return type
     */
    public static function getAnswersCount( $form_id = null )
    {
        $settings = array();
        if ( !is_null( $form_id ) )
        {
            $settings['definition_id'] = $form_id ;
        }

        return self::count( self::definition(), $settings );
    }

    /**
     * Metgod returns a list of attributes for current answeer object
     * @return array
     */
    public function getAttributes()
    {
        if ( empty( $this->attributes ) )
        {
            $this->attributes = formAnswersAttributes::getAttributes( $this->attribute( 'id' ) );
        }

        return $this->attributes;
    }

    /**
     * Returns current answer form object
     * @return type
     */
    public function getFormData()
    {
        return formDefinitions::getForm( $this->attribute( 'definition_id' ) );
    }

    /**
     * Method returns the data of answer author user
     * @return eZUser
     */
    public function getUserData()
    {
        return eZUser::fetch( $this->attribute( 'user_id' ) );
    }
}