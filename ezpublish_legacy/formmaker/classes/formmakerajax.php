<?php


class formmakerAjax extends ezjscServerFunctions
{
    /**
     * Runs Edit controller and new attrib action from new stack.
     *
     */
    public static function newattrib()
    {
        $container = ezpKernel::instance()->getServiceContainer();
        $controller = $container->get('edit.controller');
        $request = $container->get('request.provider');

        return $controller->newAttribAction($request::createFromGlobals())->getContent();
    } // newAttrib
} // class formmakerAjax