<?php

$http           = eZHTTPTool::instance();
$tpl            = eZTemplate::factory();
$form_id        = isset($Params['id']) ? $Params['id'] : ''; 
$original_name  = '';
$attributes     = array();
$formmaker_ini  = eZINI::instance( 'formmaker.ini' );

// key is the name of attribute and value is the "reqiured" flag, label and default value
$form_elements = array( 'name'          => array(   'required'  => true, 
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'Form name' ), 
                                                    'type'      => 'text',
                                                    'css'       => 'attribute-full-width',
                                                    'value'     => '' ),
                        'first_page'    => array(   'required'  => true, 
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'First page label' ), 
                                                    'type'      => 'text',
                                                    'value'     => '' ),
                        'css_class'     => array(   'required'  => false,
                                                    'label'     => ezpI18n::tr( 'formmaker/admin', 'Form CSS class' ),
                                                    'type'      => 'text',
                                                    'value'     => '' ),
                        'summary_page'  => array(   'required'  => false, 
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'I want a confirmation page with the following label' ), 
                                                    'type'      => 'checkbox',
                                                    'value'     => '' ),
                        'summary_label' => array(   'required'  => false, 
                                                    'type'      => 'text',
                                                    'value'     => '' ),
                        'summary_body'  => array(   'required'  => false, 
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'Confirmation page body text' ), 
                                                    'type'      => 'textarea',
                                                    'css'       => 'attribute-full-width',
                                                    'value'     => "You're about to send following informations. Are they OK?" ),    
                        'receipt_label' => array(   'required'  => true, 
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'Receipt page label' ), 
                                                    'type'      => 'text',
                                                    'css'       => 'attribute-full-width',
                                                    'value'     => '' ),
                        'receipt_intro' => array(   'required'  => false, 
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'Receipt page intro text' ), 
                                                    'type'      => 'textarea',
                                                    'css'       => 'attribute-full-width',
                                                    'value'     => '' ),
                        'receipt_body'  => array(   'required'  => false, 
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'Receipt page body text' ), 
                                                    'type'      => 'textarea',
                                                    'css'       => 'attribute-full-width',
                                                    'value'     => 'Thank you for sending us the informations!' ),
                        'email_action'  => array(   'required'  => false,
                                                    'label'     => ezpI18n::tr( 'formmaker/admin', 'Send data via email' ),
                                                    'type'      => 'checkbox',
                                                    'value'     => true ),
                        'email_title'   => array(   'required'  => false,
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'E-mail title' ),
                                                    'type'      => 'text',
                                                    'css'       => '',
                                                    'value'     => 'New form answer' ),
                        'recipients'    => array(   'required'  => false,
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'E-mail recipients (separated by semicolon)' ),
                                                    'type'      => 'text',
                                                    'css'       => 'attribute-full-width',
                                                    'value'     => '' ),
                        'store_action'  => array(   'required'  => false,
                                                    'label'     => ezpI18n::tr( 'formmaker/admin', 'Store data in database' ),
                                                    'type'      => 'checkbox',
                                                    'css'       => 'attribute-full-width',
                                                    'value'     => false ),
                        'object_action' => array(   'required'  => false,
                                                    'label'     => ezpI18n::tr( 'formmaker/admin', 'Use process class method (advanced)' ),
                                                    'type'      => 'checkbox',
                                                    'value'     => false ),
                        'process_class' => array(   'required'  => false,
                                                    'label'     => ezpI18n::tr('formmaker/admin', 'Process class name' ),
                                                    'type'      => 'text',
                                                    'value'     => '' ),
);

// When form is being edited, we need to fill up its definition data from database
if ( is_numeric( $form_id ) )
{
    $definition     = formDefinitions::getForm( $form_id );
    $attributes     = $definition->getAllAttributes();
    $original_name  = $definition->attribute( 'name' );
    
    foreach ( $form_elements as $key => $data )
    {
        $form_elements[$key]['value'] = $definition->attribute( $key );
    }    
}

$error_message = '';
if( $http->hasPostVariable( 'name' ) ) 
{
    // validating required fields
    foreach ($form_elements as $key => $data)
    {
        if ($form_elements[$key]['type'] == 'checkbox') 
        {
            $form_elements[$key]['value'] = (int)$http->hasPostVariable($key);
        } 
        else 
        {
            $form_elements[$key]['value'] = $http->postVariable($key);
        }
        if ($data['required'] && empty($form_elements[$key]['value']))
        {
            $error_message = ezpI18n::tr('formmaker/admin', 'Please fill all required fields');
        }
    }
        
    if (empty($error_message)) 
    {
        // it's and update
        if (is_numeric($form_id))
        {
            // and there were no errors!
            formDefinitions::updateForm($form_id, $form_elements);
            formAttributes::updateFormAttributes( $_POST, $http->postVariable( 'definition_id' ) );

            // clearing the cache for nodes which uses this form
            formDefinitions::clearFormCache($form_id);   
        }
        // it's a brand new form
        else 
        {      
            // redirecting to edit page (adding attributes)
            $saved_object = formDefinitions::addForm( $form_elements );
            $href = 'formmaker/edit/' . $saved_object->attribute('id');
            eZURI::transformURI( $href );
            eZHTTPTool::redirect( $href );
            eZExecution::cleanExit();
        }        
    }
}

$tpl->setVariable( 'error_message', $error_message );
$tpl->setVariable( 'form_elements', $form_elements );
$tpl->setVariable( 'form_attributes', $attributes );
$tpl->setVariable( 'id', $form_id );
$tpl->setVariable( 'form_name', $original_name );
$tpl->setVariable( 'input_types', formTypes::getAllTypes( $formmaker_ini->variable( 'FormmakerSettings', 'ExcludedFormTypes' ) ) );
$tpl->setVariable( 'separator_id', formTypes::SEPARATOR_ID );
$tpl->setVariable( 'validator_email_id', formValidators::EMAIL_ID);
$tpl->setVariable( 'validator_custom_regex_id', formValidators::CUSTOM_REGEX);

$Result = array();

// if form is saved
$Result['content'] = $tpl->fetch( 'design:formmaker/edit.tpl' );
if( $http->hasPostVariable('definition_id') && empty($error_message) )
{
    if ( $http->hasPostVariable( 'SaveExitButton' ) )
    {
        $href = 'formmaker/list/';
    }
    elseif ( $http->hasPostVariable( 'SaveButton' ) )
    {
        $href = 'formmaker/edit/' . $form_id;      
    }
    
    eZURI::transformURI( $href );
    eZHTTPTool::redirect( $href );
    eZExecution::cleanExit();      
}

$Result['path']    = array( array( 'tag_id' => 0,
                                   'text'   => ezpI18n::tr( 'formmaker/admin', 'Form Maker Dashboard' ),
                                   'url'    => false ) );

$Result['left_menu'] = "design:formmaker/left_menu.tpl";

$contentInfoArray = array();
$contentInfoArray['persistent_variable'] = false;
if ( $tpl->variable( 'persistent_variable' ) !== false )
{
    $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );
}

$Result['content_info'] = $contentInfoArray;