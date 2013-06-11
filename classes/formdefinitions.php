<?php

/**
 * Class interface for form_definitions SQL table
 */
class formDefinitions extends eZPersistentObject 
{
    // prefix for keys used in session
    const PAGE_SESSION_PREFIX = 'form_data_page_';
    // session key set when form was just sent
    const SESSION_FORM_SENT_KEY = 'form_just_sent';
    // main node id
    const MAIN_NODE_ID = 2;

    private $isMultipart = false;

    /**
     *  Table definition
     * @return array
     */
    public static function definition()
    {
        $def = array( 'fields' => array( 'id'               => array( 'name' => 'id',
                                                                      'datatype'=> 'integer',
                                                                      'required' => true ),
                                         'name'             => array( 'name' => 'name',
                                                                      'datatype' => 'string',
                                                                      'required' => true ),
                                         'create_date'      => array( 'name' => 'create_date',
                                                                      'datatype' => 'datetime',
                                                                      'required' => true ),
                                         'owner_user_id'    => array( 'name' => 'owner_user_id',
                                                                      'datatype' => 'integer',
                                                                      'required' => true ),
                                         'summary_page'     => array( 'name' => 'summary_page',
                                                                      'datatype' => 'integer',
                                                                      'required' => false ),
                                         'first_page'       => array( 'name' => 'first_page',
                                                                      'datatype' => 'string',
                                                                      'required' => false ),
                                         'summary_label'    => array( 'name' => 'summary_label',
                                                                      'datatype' => 'string',
                                                                      'required' => false ),
                                         'summary_body'     => array( 'name' => 'summary_body',
                                                                      'datatype' => 'string',
                                                                      'required' => false ),
                                         'receipt_label'    => array( 'name' => 'receipt_label',
                                                                      'datatype' => 'string',
                                                                      'required' => false ),
                                         'receipt_intro'    => array( 'name' => 'receipt_intro',
                                                                      'datatype' => 'string',
                                                                      'required' => false ),
                                         'receipt_body'     => array( 'name' => 'receipt_body',
                                                                      'datatype' => 'string',
                                                                      'required' => false ),
                                         'recipients'       => array( 'name' => 'recipients',
                                                                      'datatype' => 'string',
                                                                      'required' => false ),
                                         'email_action'     => array( 'name' => 'email_action',
                                                                      'datatype' => 'integer',
                                                                      'required' => false ),
                                         'store_action'     => array( 'name' => 'store_action',
                                                                      'datatype' => 'integer',
                                                                      'required' => false ),
                                         'object_action'    => array( 'name' => 'object_action',
                                                                      'datatype'=> 'integer',
                                                                      'required' => false ),
                                         'process_class'    => array( 'name' => 'process_class',
                                                                      'datatype' => 'string',
                                                                      'required' => false ),
                                         'email_title'      => array( 'name' => 'email_title',
                                                                      'datatype' => 'string',
                                                                      'required' => true ) ),
                      'keys' => array('id'),
                      'increment_key' => 'id',
                      'class_name' => 'formDefinitions',
                      'sort' => array(),
                      'function_attributes' => array(
                          'user'                => 'getUserData',
                          'datepicker_format'   => 'getDatepickerFormat',
                          'multipart'           => 'isMultipart'
                      ),
                      'name' => 'form_definitions' );
        return $def;
    }    
    
    /**
     * Returns all forms from database
     * @return array
     */
    public static function getAllForms()
    {
        return self::fetchObjectList( self::definition() );
    }
    
    /**
     * Returns form with given ID
     * @param int $id
     * @return formDefinitions
     */
    public static function getForm($id)
    {
        return self::fetchObject( self::definition(), null, array( 'id' => $id ) );
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
     * Method removes a from
     */
    public function removeContentObject() 
    {         
        eZPersistentObject::removeObject( formDefinitions::definition(), array( 'id' => $this->attribute( 'id' ) ) );
    }

    /**
     * Use to create a new object, set the values and store in a db record
     * @param array $form_elements - defined in edit.php
     * @return null|\oneTimeLogin
     */
    public static function addForm( $form_elements )
    {
        $data = array( 
            'id'            => null, 
            'create_date'   => null,
            'owner_user_id' => eZUser::currentUser()->id()
        );
        
        foreach ($form_elements as $id => $element)
        {
            $data[$id] = $element['value'];
        }   
        
        $object = new self( $data );
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
                array('data_type_string' => 'form', 'data_text' => $form_id),
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
    
    /**
     * Method return all attributes of current form
     * @param boolean $only_enabled
     * @return array
     */
    public function getAllAttributes( $only_enabled = false )
    {
        $settings = array(
            'definition_id' => $this->attribute( 'id' )
        );
        if ( $only_enabled )
        {
            $settings['enabled'] = 1;
        }
        return eZPersistentObject::fetchObjectList( formAttributes::definition(), null, $settings );
    }
    
    /**
     * Method returns page attributes sorted by pages or for given page
     * @param boolean $page
     * @return array
     */
    public function getPageAttributes( $page = false )
    {
        $attributes_by_pages    = array();
        $current_page           = 0;
        $page_enabled           = true;

        foreach ( $this->getAllAttributes( true ) as $attribute )
        {
            if ( $attribute->attribute( 'type_id' ) == formTypes::SEPARATOR_ID )
            {
                $current_page ++;
                $page_enabled = (bool)$attribute->attribute( 'enabled' );
            }
            
            if ( $page_enabled )
            {
                if ( !isset( $attributes_by_pages[$current_page] ) )
                {
                    $attributes_by_pages[$current_page] = array(
                        'page_info'     => array(), 
                        'attributes'    => array()
                    );
                }  
                
                if ( $attribute->attribute( 'type_id' ) == formTypes::SEPARATOR_ID )
                {
                    $attributes_by_pages[$current_page]['page_info'] = $attribute;
                }
                else 
                {
                    if( $attribute->attribute( 'type_id' ) == formTypes::FILE_ID )
                    {
                        $this->isMultipart = true;
                    }
                    $attributes_by_pages[$current_page]['attributes'][] = $attribute;
                }
            }
        }
        if ( $page )
        {
            return $attributes_by_pages[$page];
        }
        return $attributes_by_pages;
    }
    
    /**
     * Method removed unused attributes based on arraf of correct ones
     * @param array $correct_attributes
     */
    public function removeUnusedAttributes( $correct_attributes )
    {
        foreach ( $this->getAllAttributes() as $attribute )
        {
            if ( !in_array($attribute->attribute( 'id' ), $correct_attributes ) )
            {
                $attribute->removeRecord();
            }
        }
    }
    
    /**
     * Method returns a list of objects connected with the current form
     * @return array
     */
    public function getConnectedObjects() 
    {
        $params = array(
            'ExtendedAttributeFilter'   => array(
                'id'        => 'FormRelatedObjectsFilter',
                'params'    => array( 'form_id' => $this->attribute( 'id' ) )
            )
        );
        
        $fetch_result = eZContentObjectTreeNode::subTreeByNodeID( $params, self::MAIN_NODE_ID );
        $data = array();
        foreach ( $fetch_result as $node )
        {
            $data[$node->attribute( 'node_id' )] = $node->attribute( 'name' );
        }
        
        return $data;
    }
    
    /**
     * Method returns the data of form author user
     * @return eZUser
     */
    public function getUserData()
    {
        return eZUser::fetch( $this->attribute( 'owner_user_id' ) );
    }
    
    /**
     * Function returns Datepicker format which will be used on front
     * @return string
     */
    public function getDatepickerFormat()
    {
        $formmaker_ini = eZINI::instance( 'formmaker.ini' );
        if ( $formmaker_ini->variable( 'FormmakerSettings', 'DynamicDateFormat' ) == 'enabled' )
        {
            $locale = eZLocale::instance();
            return $formmaker_ini->hasGroup( 'ShortDateFormat_' . $locale->ShortDateFormat ) ? 
                   $formmaker_ini->variable( 'ShortDateFormat_' . $locale->ShortDateFormat, 'DatepickerFormat' ) :
                   $formmaker_ini->variable( 'FormmakerSettings', 'DefaultDatepickerFormat' );            
        }
        
        return $formmaker_ini->variable( 'FormmakerSettings', 'DefaultDatepickerFormat' );
    }

    /**
    * Method returns true if there's file attribute in use
    * actual check located in getPageAttributes
    * @return bool
    */
    public function isMultipart()
    {
        return $this->isMultipart;
    }

}
