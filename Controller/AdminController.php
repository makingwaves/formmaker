<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 7/8/14
 * Time: 1:17 PM
 */
namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function testAdminAction(Request $request, $someParam = null)
    {
        return $this->render(
            'FormMakerBundle:Admin:test.html.twig',
            array('some_param' => $someParam)
        );
    } // testAdminAction
} // class AdminController