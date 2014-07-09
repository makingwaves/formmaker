<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 7/8/14
 * Time: 2:48 PM
 */

$Module = $Params['Module'];
$Result = array();




$container = ezpKernel::instance()->getServiceContainer();
/** @var \MakingWaves\FormMakerBundle\Controller\AdminController $controller */
$controller = $container->get( 'test.admin.controller' );
$Result['content'] = $controller->testAdminAction( 'blabla' )->getContent();