<?php

class FormMaker_isImage
{ 
 
	private $Operators;

	function __construct()
	{
	    $this->Operators = array('is_image');
	}
	 
	function operatorList()
	{
	    return $this->Operators;
	}
	 
	function namedParameterPerOperator()
	{
	    return true;
	}
	 
	function namedParameterList()
	{
	    return array(
	            'is_image' => array(
	                    'display_string' => array(
	                            'type' => 'string',
	                            'required' => true,
	                            'default' => ''
	                    )
	            )
	    );
	}
	 
	function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace,$currentNamespace, &$operatorValue, $namedParameters )
	{
	    if ($operatorName == 'is_image')
	    {
	        $operatorValue = $this->is_image($namedParameters['display_string']);
	    }
	}
	
	function is_image( $args )
	{
		$imgData = getimagesize($args);

		if( is_numeric($imgData[0]) ) {
			return true;
		}
		else {
			return false;
		}

	    return ( getimagesize($args) );
	}
	 
}