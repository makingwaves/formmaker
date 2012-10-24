<?php

$Module = array( 'name' => 'mwezforms' );

$ViewList = array();
$FunctionList = array();

// we need to have only two policy functions 
$FunctionList['edit'] = array(); // edit is for reading/listing/creating/editing
$FunctionList['remove'] = array(); // remove is only for removing form

/*
 * list
 */
$ViewList['list'] = array(
    'functions' => array( 'edit' ),
    'ui_context' => 'read',
    'script' => 'list.php',
    'params' => array( 'ObjectID' ),
    'default_navigation_part' => 'mwezformsnavigationpart'
);

/*
 * remove
 */
$ViewList['remove'] = array(
    'functions' => array( 'remove' ),
    'script' => 'remove.php',
    'default_navigation_part' => 'mwezformsnavigationpart',
    'params' => array( 'id' )
);

/**
 * editing forms
 */
$ViewList['edit'] = array(
    'functions' => array('edit'),
    'script' => 'edit.php',
    'default_navigation_part' => 'mwezformsnavigationpart',
    'params' => array( 'id' )
);