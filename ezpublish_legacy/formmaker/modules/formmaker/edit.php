<?php

$container = ezpKernel::instance()->getServiceContainer();
$controller = $container->get( 'edit.controller' );

$Result = array(
    'content' => $controller->createAction()->getContent(),
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