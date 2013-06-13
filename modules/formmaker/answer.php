<?php
/**
 * Script displays one particular form answer
 * @param array $Params
 */

if ( !isset( $Params['id'] ) )
{
    return $Params['Module']->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$answer_id  = $Params['id'];
$answer     = formAnswers::getAnswer( $answer_id );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'answer', $answer );

$Result = array(
    'content'   => $tpl->fetch( 'design:formmaker/answer.tpl' ),
    'path'      => array( array(
        'tag_id' => 0,
        'text'   => ezpI18n::tr( 'formmaker/admin', 'Form Maker Dashboard' ),
        'url'    => false
    ) ),
    'left_menu' => 'design:formmaker/left_menu.tpl'
);