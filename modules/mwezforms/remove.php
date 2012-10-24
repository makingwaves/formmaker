<?php
$http = eZHTTPTool::instance();

$viewParameters = array();
if ( isset( $Params['id'] ) )
{
    $id = $Params['id'];
}


// remove row
mwEzFormsDefinitions::removeContentObject($id);


$server = $_SERVER['SERVER_NAME'];
$uriString = $_SERVER['PHP_SELF'];
$uriString = explode('/', $uriString);
unset($uriString[sizeof($uriString)-1]);
unset($uriString[sizeof($uriString)-1]);
$uri = implode('/', $uriString);
$uri = 'http://' . $server . $uri . '/list';
header('location: ' . $uri);