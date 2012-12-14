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
        $this->http         = eZHTTPTool::instance();
        $form_definition    = formDefinitions::getForm( $form_id );
        $current_page       = $this->http->hasPostVariable( 'current_page' ) ? $this->http->postVariable( 'current_page' ) : 0;
        $all_pages          = $form_definition->getPageAttributes();
        $form_page          = $all_pages[$current_page];
        $is_page_last       = count( $all_pages ) == ( $current_page + 1) ? true : false;
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

            // updating attributes with current values
            foreach ($form_page['attributes'] as $i => $attrib)
            {
                $form_page['attributes'][$i]->setAttribute('default_value', $posted_values[$i]);
            }

            // there are no errors, so we need to store data in session and redirect to next page
            if ( empty( $errors ) )
            {
                eZSession::set( formDefinitions::PAGE_SESSION_PREFIX . $current_page, $form_page );
                $current_page++;
                if ( isset( $all_pages[$current_page] ) )
                {
                    $form_page['attributes'] = $all_pages[$current_page]['attributes'];
                }
            }            
            
            // in case when no errors and current page is last one
            if ( empty( $errors ) && $is_page_last )
            {
                // Sending email message
                if ($form_definition->attribute('post_action') == 'email')
                {
                    $data_to_send = array();
                    foreach ( $_SESSION as $key => $value )
                    {
                        if ( !preg_match( '/^' . formDefinitions::PAGE_SESSION_PREFIX . '/', $key ) ) {
                            continue;
                        }
                        
                        $data_to_send[] = $value;
                        // clean up the session
                        unset( $_SESSION[$key] );
                    } 
                    $operation_result = $this->generateEmailContent($form_definition, $data_to_send);
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
        }
        
        $result = array_merge($result, array( 'definition'          => $form_definition,
                                              'attributes'          => $form_page['attributes'],
                                              'validation'          => $errors,
                                              'current_page'        => $current_page,
                                              'pages_count'         => count( $all_pages ),
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
     * @param formDefinitions $definition
     * @param array $form_pages
     * @return type
     */
    private function generateEmailContent($definition, $form_pages)
    {
        // creating email content
        $tpl        = eZTemplate::factory();
        $email_data = array();
        foreach ($form_pages as $i => $page)
        {
            $email_data[$i]['page_label'] = ( $page['page_info'] instanceof formAttributes ) ? $page['page_info']->attribute( 'label' ) : $definition->attribute( 'name' );
            
            foreach ( $page['attributes'] as $attribute )
            {
                switch ($attribute->attribute('type_id'))
                {
                    case formTypes::CHECKBOX_ID: // checkbox
                        $email_data[$i]['attributes'][$attribute->attribute('label')] = ezpI18n::tr( 'extension/formmaker/email', ( $attribute->attribute( 'default_value' ) == 'on') ? 'Yes': 'No');
                        break;

                    case formTypes::RADIO_ID: // radio button
                        $option_object = formAttributesOptions::fetchOption( $attribute->attribute( 'default_value' ) );
                        $email_data[$i]['attributes'][$attribute->attribute( 'label' )] = $option_object->attribute( 'label' );
                        break;

                    default:
                        $email_data[$i]['attributes'][$attribute->attribute('label')] = $attribute->attribute( 'default_value' );
                        break;
                }                
            }
        }
        
        $tpl->setVariable('data', $email_data);

        // creating email message
        $mail = new eZMail();
        $mail->setSender( 'MWeZForm' ); // TODO: set default email sender
        $mail->setSubject( $definition->attribute( 'name' ) . ' - ' . ezpI18n::tr( 'extension/formmaker/email', 'New answer' ) );
        $mail->setBody( $tpl->fetch( 'design:email/recipient.tpl' ) );
        $mail->setContentType('text/html');

        $recipients = explode( ';', $definition->attribute( 'recipients' ) );
        foreach( $recipients as $recipient ) 
        {
            $mail->addReceiver( $recipient );
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