<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function testAdminAction(Request $request, $someParam = null)
    {
        return $this->render(
            'FormMakerBundle:Default:test.html.twig',
            array('some_param' => $someParam)
        );
    } // testAdminAction
} // class DefaultController
