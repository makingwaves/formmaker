<?php

$container = ezpKernel::instance()->getServiceContainer();
$controller = $container->get( 'edit.controller' );
$request = $container->get( 'request.provider' );

$formId = isset($Params['id']) ? $Params['id'] : 0;

$Result = array(
    'content_info' => array(
        'persistent_variable' => array(
            'left_menu' => false
        )
    ),
    'path' => array(
        array(
            'text' => $container->get('translator')->trans('create.form', array(), 'formmaker')
        )
    )
);

if ( $formId == 0 ) {
    $Result['content'] = $controller->createAction($request::createFromGlobals())->getContent();
} else {
    $Result['content'] = $controller->editAction($request::createFromGlobals(), $formId)->getContent();
} // endif