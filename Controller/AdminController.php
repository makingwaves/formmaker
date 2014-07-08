<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 7/8/14
 * Time: 1:17 PM
 */
namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;

class AdminController extends Controller
{
    public function testAdminAction($someParam = null)
    {
        return $this->render(
            'FormMakerBundle:Admin:test_admin_action.html.twig',
            array('some_param' => $someParam)
        );
    } // testAdminAction
} // class AdminController