<?php

/**
 * Class definec custom template fetches
 */
class FormMakerFunctionCollection
{  
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
    public function fetchFormData( $form_id )
    {
        $form_definition    = formDefinitions::getForm( $form_id );
        $current_page       = $form_definition->getCurrentPage();
        $all_pages          = $form_definition->getPageAttributes();
        $form_page          = $all_pages[$current_page];
        $this->http         = eZHTTPTool::instance();
        $is_page_last       = $form_definition->isCurrentPageLast();
        $result = $errors = $posted_values = array();

        foreach( $form_page['attributes'] as $i => $attrib )
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
            
            // in case when no errors and current page is last one
            if ( empty( $errors ) && $is_page_last )
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
            }
            else
            {
                // updating attributes with current values
                foreach ($form_page['attributes'] as $i => $attrib)
                {
                    $form_page['attributes'][$i]->setAttribute('default_value', $posted_values[$i]);
                }
                
                // there are no errors, so we need to store data in session and redirect to next page
                if ( empty( $errors ) )
                {
                    eZSession::set( 'form_data_page_' . $current_page, $form_page['attributes'] );
                    $current_page++;
                    $form_page['attributes'] = $all_pages[$current_page + 1]['attributes']; // TODO: check if wanted index exist
                }
            }  
        }
        
        $result = array_merge($result, array( 'definition'          => $form_definition,
                                              'attributes'          => $form_page['attributes'],
                                              'validation'          => $errors,
                                              'current_page'        => $current_page,
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