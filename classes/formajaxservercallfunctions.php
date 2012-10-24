<?php

/**
 * Class to implement ezjscore methods
 * @author Piotr Szczygieł <piotr.szczygieł@makingwaves.pl>
 */
class formAjaxServerCallFunctions extends ezjscServerFunctions
{
    /**
     * Method validates single form element. Returns true in case of validation was OK, false in case of errors during exexution and string with html
     * in case when validation is not ok.
     * @param type $args
     * @return boolean|string
     */
    public static function validate ($args)
    {
        $http = eZHTTPTool::instance();
        
        if ($http->hasPostVariable('id') && $http->hasPostVariable('value'))
        {
            $id = $http->postVariable('id');
            $attribute = formAttributes::getAttribute($id);
            
            $validation = $attribute->validate($http->postVariable('value'));
            $errors = array();
            if (!empty($validation))
            {
                $errors[$id] = $validation;
                $tpl = eZTemplate::factory();
                $tpl->setVariable('attribute_id', $id);
                $tpl->setVariable('errors', $errors);
                
                // there are errors - returning string with hmtl code do display
                return $tpl->fetch('design:mwform_error.tpl');                
            }
            
            // validated OK
            return true;
        }
        
        // incorrect validation
        return false;
    }
}