<?php

$container = ezpKernel::instance()->getServiceContainer();
$controller = $container->get( 'answers.controller' );
$offset = isset( $Params['UserParameters']['offset'] ) ? $Params['UserParameters']['offset'] : 0;
$formId = isset( $Params['UserParameters']['form_id'] ) ? $Params['UserParameters']['form_id'] : null;

$Result = array(
    'content' => $controller->displayAction( $offset, $formId )->getContent(),
    'content_info' => array(
        'persistent_variable' => array(
            'left_menu' => false
        ),
    ),
    'path' => array( array(
        'text' => $container->get('translator')->trans('answers', array(), 'formmaker')
    ))
);