<?php

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

$FunctionList['answers'] = array(
    'name'              => 'answers',
    'operation_types'   => array( 'read' ),
    'call_method'       => array(
        'class'     => 'AnswersFunctionCollection',
        'method'    => 'getAnswers'
    ),
    'parameter_type'    => 'standard',
    'parameters'        => array(
        array(
            'name'      => 'form_id',
            'type'      => 'integer',
            'required'  => false
        ),
        array(
            'name'      => 'offset',
            'type'      => 'integer',
            'required'  => false
        ),
        array(
            'name'      => 'limit',
            'type'      => 'integer',
            'required'  => false
        )
    )
);

$FunctionList['answers_count'] = array(
    'name'              => 'answers_count',
    'operation_types'   => array( 'read' ),
    'call_method'       => array(
        'class'     => 'AnswersFunctionCollection',
        'method'    => 'getAnswersCount'
    ),
    'parameter_type'    => 'standard',
    'parameters'        => array(
        array(
            'name'      => 'form_id',
            'type'      => 'integer',
            'required'  => false
        )
    )
);

$FunctionList['answers_forms'] = array(
    'name'              => 'answers_forms',
    'operation_types'   => array( 'read' ),
    'call_method'       => array(
        'class'     => 'AnswersFunctionCollection',
        'method'    => 'getFormsList'
    ),
    'parameter_type'    => 'standard',
    'parameters'        => array(
        array(
            'name'      => 'only_collectors',
            'type'      => 'boolean',
            'required'  => false
        )
    )
);