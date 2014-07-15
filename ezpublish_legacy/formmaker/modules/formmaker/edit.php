<?php

$container = ezpKernel::instance()->getServiceContainer();
$controller = $container->get( 'edit.controller' );
$request = $container->get( 'request.getter' );

$formId        = isset($Params['id']) ? $Params['id'] : '0';

$Result = array(
    'content' => $controller->editAction($request::createFromGlobals(), $formId)->getContent(),
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