<?php

/**
 * Script processes the answer view of FormMaker module
 * @param array $Params
 */

$view_parameters['offset']  = isset( $Params['UserParameters']['offset'] ) ? $Params['UserParameters']['offset'] : 0;
$view_parameters['form_id'] = isset( $Params['UserParameters']['form_id'] ) ? $Params['UserParameters']['form_id'] : null;

$tpl = eZTemplate::factory();
$tpl->setVariable( 'view_parameters', $view_parameters );

$Result = array(
    'content'   => $tpl->fetch( 'design:formmaker/answers.tpl' ),
    'path'      => array( array(
        'tag_id' => 0,
        'text'   => ezpI18n::tr( 'formmaker/admin', 'Form Maker Dashboard' ),
        'url'    => false
    ) ),
    'left_menu' => 'design:formmaker/left_menu.tpl'
);