<?php

/**
 * Class definec custom template fetches
 */
class FormMakerFunctionCollection
{  
    /**
     * Security constant - stores minimum execution time (in seconds) between displaying the form and processing them.
     */
    const MIN_PROCESS_TIME = 2;
    
    /**
     * eZHTTPTool object instance
     * @var eZHTTPTool 
     */
    private $http = null;
    
    /**
     * Method fetches form definition and all attributes for given Form id.
     * It also returns validation error or success template content if there are no errors.
     * @param int $form_id
     * @return array
     */  
    public function fetchFormData($form_id)
    {
        $form_definition    = formDefinitions::getForm($form_id);
        $form_attributes    = $form_definition->getAllAttributes();
        $this->http         = eZHTTPTool::instance();
        $result = $errors = $posted_values = array();

        foreach($form_attributes as $i => $attrib)
        {
            $post_id = $this->generatePostID($attrib);
            if (!$this->http->hasPostVariable($post_id)) {
                continue;
            }

            // validating inputs
            $validation = $attrib->validate($this->http->postVariable($post_id));
            if (!empty($validation))
            {
                $errors[$attrib->attribute('id')] = $validation;
            }

            // generating array of posted values
            $posted_values[$i] = $this->http->postVariable($post_id);
        }    
        
        /**
         * Checking post variables
         */
        if (count($posted_values))
        {
            // Checking required security POST variable
            if ($this->http->postVariable('validation') !== 'False') {
                throw new Exception('Security exception');
            }
            // checking time security and displaying innocent text if failed
            elseif(!eZSession::issetkey('formmaker') || time() - eZSession::get('formmaker') < self::MIN_PROCESS_TIME) {
                $errors[0] = array('Please make sure that all fields are OK.');
            }
            
            // in case when no errors - email is sent or data is stored
            if (empty($errors))
            {
                // Sending email message
                if ($form_definition->attribute('post_action') == 'email')
                {
                    $operation_result = $this->generateEmailContent($form_definition, $form_attributes);
                }
                // TODO: Storing data in database
                else 
                {
                    $operation_result = false;
                }

                // rendering success template
                $tpl = eZTemplate::factory();
                $tpl->setVariable('result', $operation_result);
                $result['success'] = $tpl->fetch( 'design:form_processed.tpl' );  
                ezSession::set('formmaker', time() );
            }
            // there are validation errors, so we need to pass them to the template
            else
            {
                // updating attributes with current values
                foreach ($form_attributes as $i => $attrib)
                {
                    $form_attributes[$i]->setAttribute('default_value', $posted_values[$i]);
                }
            }   
        }
        // If there is no post varaibles, we need to store current timestamp in session
        else
        {
            ezSession::set('formmaker', time() );
        }
        
        $result = array_merge($result, array( 'definition'          => $form_definition,
                                              'attributes'          => $form_attributes,
                                              'validation'          => $errors,
                                              'counted_validators'  => formAttrvalid::countValidatorsForAttributes()));
        return array('result' => $result);
    }
    
    /**
     * Metgod returns attribute post name/id
     * @param type $attrib
     * @return string
     */
    private function generatePostID($attrib)
    {
        return 'field_' . $attrib->attribute('type_id') . '_' . $attrib->attribute('id');
    }
    
    /**
     *  Method generates email message to recipients and sends it
     * @param type $definition
     * @param type $attributes
     * @return type
     */
    private function generateEmailContent($definition, $attributes)
    {
        // creating email content
        $tpl        = eZTemplate::factory();
        $email_data = array();
        foreach ($attributes as $attribute)
        {
            $post_id = $this->generatePostID($attribute);
            if (!$this->http->hasPostVariable($post_id)) {
                continue;
            } 
            
            switch ($attribute->attribute('type_id'))
            {
                case '3': // checkbox
                    $email_data[$attribute->attribute('label')] = ezpI18n::tr( 'extension/formmaker/email', ($this->http->postVariable($post_id) == 'on') ? 'Yes': 'No');
                    break;
                
                case '4': // radio button
                    $option_object = formAttributesOptions::fetchOption( $this->http->postVariable( $post_id ) );
                    $email_data[$attribute->attribute( 'label' )] = $option_object->attribute( 'label' );
                    break;
                
                default:
                    $email_data[$attribute->attribute('label')] = $this->http->postVariable($post_id);
                    break;
            }
            
        }
        $tpl->setVariable('data', $email_data);
        $tpl->setVariable('form_name', $definition->attribute('name'));

        // creating email message
        $mail = new eZMail();
        $mail->setSender('MWeZForm');
        $mail->setReceiver($definition->attribute('recipients'));
        $mail->setSubject($definition->attribute('name') . ' - new answer');
        $mail->setBody($tpl->fetch('design:email/recipient.tpl'));
        $mail->setContentType('text/html');

        $recipients = explode(';', $definition->attribute('recipients'));
        foreach($recipients as $recipient) {
            $mail->addReceiver($recipient);
        }
        
        // sendnig message
        return eZMailTransport::send($mail);        
    }
    
    /**
     * Fetch function checks if attribute of given ID is required or not
     * @param int $attribute_id
     * @return array
     */
    public function isAttributeRequired($attribute_id) 
    {
        return array('result' => formAttrvalid::isAttributeRequired($attribute_id));
    }
    
}