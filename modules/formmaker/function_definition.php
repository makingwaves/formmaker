<?php

$FunctionList = array();

$FunctionList['data'] = array( 
    'name'              => 'data',
    'operation_types'   => array('read'),
    'call_method'       => array( 
        'class'     => 'FormMakerFunctionCollection',
        'method'    => 'fetchFormData'
    ),
    'parameter_type'    => 'standard',
    'parameters'        => array(
        array(
            'name'      => 'form_id', 
            'type'      => 'integer', 
            'required'  => true
        ),
    ) 
);

$FunctionList['is_attrib_required'] = array( 
    'name'              => 'is_attrib_required',
    'operation_types'   => array('read'),
    'call_method'       => array( 
        'class'     => 'FormMakerFunctionCollection',
        'method'    => 'isAttributeRequired'
    ),
    'parameter_type'    => 'standard',
    'parameters'        => array(
        array(
            'name'      => 'attribute_id', 
            'type'      => 'integer', 
            'required'  => true
        ),
    ) 
);