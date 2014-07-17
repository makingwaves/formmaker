<?php

$container = ezpKernel::instance()->getServiceContainer();
$controller = $container->get( 'remove.controller' );
$formId = isset($Params['id']) ? $Params['id'] : 0;

$Result = array(
    'content' => $controller->removeAction( $formId )->getContent(),
    'content_info' => array(
        'persistent_variable' => array(
            'left_menu' => false
        )
    ),
    'path' => array(
        array(
            'text' => $container->get('translator')->trans('remove.form', array(), 'formmaker')
        )
    )
);