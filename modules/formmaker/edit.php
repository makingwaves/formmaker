<?php

$http                   = eZHTTPTool::instance();
$tpl                    = eZTemplate::factory();
$formAttributesArray    = array();
$form_id                = isset($Params['id']) ? $Params['id'] : ''; 
$original_name          = '';

// key is the name of attribute and value is the "reqiured" flag, label and default value
$form_elements = array( 'name'          => array(   'required' => true, 
                                                    'type' => 'text',
                                                    'label' => 'Form name', 
                                                    'value' => '', 
                                                    'css_class' => ''), 
                        'css_class'     => array(   'required' => false,  
                                                    'type' => 'text',
                                                    'label' => 'CSS class', 
                                                    'value' => '', 
                                                    'css_class' => ''),
                        'recipients'    => array(   'required' => true,  
                                                    'type' => 'text',
                                                    'label' => 'E-mail recipients (separated by semicolon)', 
                                                    'value' => '', 
                                                    'css_class' => 'attribute-full-width'),
                        'send_email'    => array(   'required' => false,  
                                                    'type' => 'checkbox',
                                                    'label' => 'Send email to recipients', 
                                                    'value' => 1, 
                                                    'css_class' => ''),    
                        'store_data'    => array(   'required' => false,  
                                                    'type' => 'checkbox',
                                                    'label' => 'Store user data in database', 
                                                    'value' => 1, 
                                                    'css_class' => ''),
                      );

// adding info about validators to each attribute
$formAttributesObjects = formAttributes::getFormAttributes($form_id);
foreach($formAttributesObjects as $formAttribute)
{
    $attr_validators = (array) $formAttribute->getAttributeValidators();
    $formAttribute = (array) $formAttribute;
    $formAttribute['validators'] = $attr_validators;
    $formAttributesArray[] = $formAttribute;
}

// When form is being edited, we need to fill up its definition data from database
if (is_numeric($form_id))
{
    $definition = formDefinitions::getForm($form_id);
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
        if ($form_elements[$key]['type'] == 'checkbox') {
            $form_elements[$key]['value'] = !$http->hasPostVariable($key) ? 0 : 1;
        } 
        else {
            $form_elements[$key]['value'] = $http->postVariable($key);
        }
        
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
            formAttributes::updateFormAttributes($_POST);

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
            $saved_object = formDefinitions::addForm($form_elements);
            $href = 'formmaker/edit/' . $saved_object->attribute('id');
            eZURI::transformURI($href);
            eZHTTPTool::redirect($href);
        }      
    }
}

$validators = formValidators::getValidators(false);
$tpl->setVariable('error_message', $error_message);
$tpl->setVariable('form_elements', $form_elements);
$tpl->setVariable('form_attributes', $formAttributesArray);
$tpl->setVariable('id', $form_id);
$tpl->setVariable('validators', $validators);
$tpl->setVariable('form_name', $original_name);

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
                                   'text'   => ezpI18n::tr( 'extension/formmaker/admin', 'Making Waves eZForms Dashboard' ),
                                   'url'    => false ) );

$Result['left_menu'] = "design:forms/left_menu.tpl";

$contentInfoArray = array();
$contentInfoArray['persistent_variable'] = false;
if ( $tpl->variable( 'persistent_variable' ) !== false )
{
    $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );
}

$Result['content_info'] = $contentInfoArray;