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
            'keys'                  => array( 'id' ),
            'increment_key'         => 'id',
            'class_name'            => 'formAnswers',
            'sort'                  => array( 'answer_date' => 'desc' ),
            'name'                  => 'form_answers',
            'function_attributes'   => array(
                'form_data'     => 'getFormData',
                'user'          => 'getUserData',
                'attributes'    => 'getAttributes',
                'summary'       => 'getSummary'
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
     * Fetch and return answer object
     * @param int $answer_id
     * @return formAnswers
     */
    public static function getAnswer( $answer_id )
    {
        return self::fetchObject( self::definition(), null, array( 'id' => $answer_id ) );
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
        return formAnswersAttributes::getAttributes( $this->attribute( 'id' ) );
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
     * Generate a quick summary of the answer
     * @return string
     */
    public function getSummary()
    {
        $accepted_attributes    = array( formTypes::TEXTLINE_ID, formTypes::SELECT_ID, formTypes::RADIO_ID, formTypes::CHECKBOX_ID );
        $output                 = '';
        $separator              = ' / ';

        foreach ( $this->getAttributes() as $attribute )
        {
            $structure = $attribute->getStructure();
            if ( !in_array( $structure->attribute( 'type_id' ), $accepted_attributes ) )
            {
                continue;
            }
            
            if ( !empty( $output ) )
            {
                $output .= $separator;
            }

            $output .= $structure->attribute( 'label' ). ': ' . $attribute->attribute( 'answer' );
        }

        return $output;
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