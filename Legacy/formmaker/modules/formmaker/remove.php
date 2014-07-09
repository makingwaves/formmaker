<?php

$Module = $Params['Module'];

if ( !isset( $Params['id'] ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$http = eZHTTPTool::instance();
$id = $Params['id'];
$form_object = formDefinitions::getForm( $id );

// checking whether there are objects in which current form is assigned as an attribute
if ( count( $form_object->getConnectedObjects() ) )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

// removing the object from database
$form_object->removeContentObject();

// redirect back to the list
$url = '/formmaker/list/';
eZURI::transformURI($url, false, 'full');
eZHTTPTool::redirect( $url );
eZExecution::cleanExit();