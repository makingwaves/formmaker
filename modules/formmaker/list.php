<?php

$http = eZHTTPTool::instance();

$viewParameters = array();
if ( isset( $Params['Offset'] ) )
    $viewParameters['offset'] = (int) $Params['Offset'];

$tpl = eZTemplate::factory();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'persistent_variable', false );
$tpl->setVariable( 'forms', formDefinitions::getAllForms() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:forms/list.tpl' );

$Result['path']    = array( array( 'tag_id' => 0,
                                   'text'   => ezpI18n::tr( 'extension/formmaker/admin', 'Form Maker Dashboard' ),
                                   'url'    => false ) );

$Result['left_menu'] = "design:forms/left_menu.tpl";

$contentInfoArray = array();
$contentInfoArray['persistent_variable'] = false;
if ( $tpl->variable( 'persistent_variable' ) !== false )
    $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );

$Result['content_info'] = $contentInfoArray;