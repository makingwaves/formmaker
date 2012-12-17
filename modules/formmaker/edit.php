<?php

$http           = eZHTTPTool::instance();
$tpl            = eZTemplate::factory();
$form_id        = isset($Params['id']) ? $Params['id'] : ''; 
$original_name  = '';
$attributes     = array();

// key is the name of attribute and value is the "reqiured" flag, label and default value
$form_elements = array( 'name'          => array( 'required' => true, 'label' => ezpI18n::tr('extension/formmaker/admin', 'Form name' ), 'value' => '' ), 
                        'recipients'    => array( 'required' => true, 'label' => ezpI18n::tr('extension/formmaker/admin', 'E-mail recipients (separated by semicolon)' ), 'value' => '' ),
                        'email_sender'  => array( 'required' => true, 'label' => ezpI18n::tr('extension/formmaker/admin', 'Email sender' ), 'value' => '' ) 
                      );

// When form is being edited, we need to fill up its definition data from database
if (is_numeric($form_id))
{
    $definition = formDefinitions::getForm($form_id);
    $attributes = $definition->getAllAttributes();
    $original_name = $definition->attribute('name');
    foreach ($form_elements as $key => $data) {
        $form_elements[$key]['value'] = $definition->attribute($key);
    }    
}

$error_message = '';
if( $http->hasPostVariable('name') ) 
{
    // validating required fields
    foreach ($form_elements as $key => $data)
    {
        $form_elements[$key]['value'] = $http->postVariable($key);
        if ($data['required'] && empty($form_elements[$key]['value']))
        {
            $error_message = ezpI18n::tr('extension/formmaker/admin', 'Please fill all required fields');
        }
    }
        
    // it's and update
    if (is_numeric($form_id))
    {
        if (empty($error_message)) 
        {
            // and there were no errors!
            formDefinitions::updateForm($form_id, $form_elements);
            formAttributes::updateFormAttributes( $_POST, $http->postVariable( 'definition_id' ) );

            // clearing the cache for nodes which uses this form
            formDefinitions::clearFormCache($form_id);
        }       
    }
    // it's a brand new form
    else 
    {      
        // and there were some errors!
        if (empty($error_message)) 
        {
            // redirecting to edit page (adding attributes)
            $saved_object = formDefinitions::addForm($_POST);
            $href = 'formmaker/edit/' . $saved_object->attribute('id');
            eZURI::transformURI($href);
            eZHTTPTool::redirect($href);
        }
    }
}

$tpl->setVariable( 'error_message', $error_message );
$tpl->setVariable( 'form_elements', $form_elements );
$tpl->setVariable( 'form_attributes', $attributes );
$tpl->setVariable( 'id', $form_id );
$tpl->setVariable( 'form_name', $original_name );
$tpl->setVariable( 'input_types', formTypes::getAllTypes() );
$tpl->setVariable( 'separator_id', formTypes::SEPARATOR_ID );
$tpl->setVariable( 'validator_email_id', formValidators::EMAIL_ID);

$Result = array();

// if form is saved
if( $http->hasPostVariable('definition_id') && empty($error_message) )
{
    $tpl->setVariable( 'form_definition', $http->postVariable('definition_id') );
    $Result['content'] = $tpl->fetch( 'design:forms/saved.tpl' );
}
else
{
    $Result['content'] = $tpl->fetch( 'design:forms/edit.tpl' );
}

$Result['path']    = array( array( 'tag_id' => 0,
                                   'text'   => ezpI18n::tr( 'extension/formmaker/admin', 'Form Maker Dashboard' ),
                                   'url'    => false ) );

$Result['left_menu'] = "design:forms/left_menu.tpl";

$contentInfoArray = array();
$contentInfoArray['persistent_variable'] = false;
if ( $tpl->variable( 'persistent_variable' ) !== false )
{
    $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );
}

$Result['content_info'] = $contentInfoArray;