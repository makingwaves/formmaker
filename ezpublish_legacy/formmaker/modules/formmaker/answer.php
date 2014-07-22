<?php

if ( !isset( $Params['id'] ) ) {
    return $Params['Module']->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$answer_id = $Params['id'];

$container = ezpKernel::instance()->getServiceContainer();
$controller = $container->get( 'answers.controller' );

$Result = array(
    'content' => $controller->detailsAction( $answer_id )->getContent(),
    'content_info' => array(
        'persistent_variable' => array(
            'left_menu' => false
        ),
    ),
    'path' => array( array(
        'text' => $container->get('translator')->trans('answers', array(), 'formmaker')
    ))
);