<?php

$Module = array( 'name' => 'formmaker' );

$ViewList = array();
$FunctionList = array();

$FunctionList['edit'] = array(); // edit is for reading/listing/creating/editing
$FunctionList['remove'] = array(); // remove is only for removing form
$FunctionList['special'] = array(); // function to allow editing some special fields

/**
 * list
 */
$ViewList['list'] = array(
    'functions' => array( 'edit' ),
    'ui_context' => 'read',
    'script' => 'list.php',
    'params' => array( 'ObjectID' ),
    'default_navigation_part' => 'formmakernavigationpart'
);

/**
 * remove
 */
$ViewList['remove'] = array(
    'functions' => array( 'remove' ),
    'script' => 'remove.php',
    'default_navigation_part' => 'formmakernavigationpart',
    'params' => array( 'id' )
);

/**
 * editing forms
 */
$ViewList['edit'] = array(
    'functions' => array('edit'),
    'script' => 'edit.php',
    'default_navigation_part' => 'formmakernavigationpart',
    'params' => array( 'id' )
);

/**
 * editing forms special rules
 */
$ViewList['special'] = array(
    'functions' => array('special')
);

/**
 * Viewing the answers
 */
$ViewList['answers'] = array(
    'functions'                 => array( 'edit' ),
    'script'                    => 'answers.php',
    'default_navigation_part'   => 'formmakernavigationpart'
);

/**
 * Display one particular answer
 */
$ViewList['answer'] = array(
    'functions'                 => array( 'edit' ),
    'script'                    => 'answer.php',
    'default_navigation_part'   => 'formmakernavigationpart',
    'params'                    => array( 'id' )
);

$ViewList['test'] = array(
    'function'      => array(),
    'script'        => 'test.php',
    'default_navigation_part'   => 'formmakernavigationpart'
);