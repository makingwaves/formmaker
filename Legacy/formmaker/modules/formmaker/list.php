<?php

$container = ezpKernel::instance()->getServiceContainer();
$controller = $container->get( 'list.controller' );

$Result = array(
    'content' => $controller->displayAction()->getContent(),
    'left_menu' => false,
    'path' => array( array(
        'text' => $container->get('translator')->trans('FormMaker Dashboard')
    ))
);