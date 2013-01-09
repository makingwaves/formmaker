<?php

/**
 * Class definec custom template fetches
 */
class FormMakerFunctionCollection
{  
    // eZHTTPTool object instance
    private $http;
    // form definition object
    private $definition;
    
    /**
     * Method fetches form definition and all attributes for given Form id.
     * It also returns validation error or success template content if there are no errors.
     * @param int $form_id
     * @return array
     */  
    public function fetchFormData( $form_id )
    {
        $this->http         = eZHTTPTool::instance();
        $this->definition   = formDefinitions::getForm( $form_id );
        $current_page       = $this->http->hasPostVariable( 'current_page' ) ? $this->http->postVariable( 'current_page' ) : 0;
        $all_pages          = $this->definition->getPageAttributes();
        $form_page          = $all_pages[$current_page];
        $is_page_last       = count( $all_pages ) == ( $current_page + 1) ? true : false;
        $result             = array();
        $errors             = array();
        $posted_values      = array();

        // removing data from session when there is no POST request
        if ( !$this->http->hasPostVariable( 'validation' ) )
        {
            $this->removeSessionData();
        }

        foreach( $form_page['attributes'] as $i => $attrib )
        {
            $post_id = $this->generatePostID($attrib);
            if (!$this->http->hasPostVariable($post_id)) {
                continue;
            }

            // validation only when button "Send" or "Next" clicked
            if ( !$this->http->hasPostVariable( 'form-back' ) )
            {
                // validating inputs
                $validation = $attrib->validate($this->http->postVariable($post_id));
                if (!empty($validation))
                {
                    $errors[$attrib->attribute('id')] = $validation;
                }
            }

            // generating array of posted values
            $posted_values[$i] = $this->http->postVariable($post_id);
        }    
        
        // Checking post variables
        if ( count( $posted_values ) || $this->http->hasPostVariable( 'summary_page' ) )
        {
            // Checking required security POST variable
            if ( $this->http->postVariable('validation') !== 'False' ) {
                throw new Exception('Security exception');
            }

            if ( count( $posted_values ) )
            {
                // updating attributes with current values
                foreach ($form_page['attributes'] as $i => $attrib)
                {
                    $form_page['attributes'][$i]->setAttribute('default_value', $posted_values[$i]);
                }
            }

            // there are no errors, so we need to store data in session
            if ( empty( $errors ) && !$this->http->hasPostVariable( 'summary_page' ) )
            {
                eZSession::set( formDefinitions::PAGE_SESSION_PREFIX . $current_page, $form_page );
                if ( isset( $all_pages[$current_page+1] ) && !$this->http->hasPostVariable( 'form-back' ) )
                {
                    $current_page++;
                    $form_page['attributes'] = $all_pages[$current_page]['attributes'];
                }
            }

            // in case when no errors and current page is last one
            if ( empty( $errors ) && $is_page_last && $this->http->hasPostVariable( 'form-send' ) )
            {
                $tpl = eZTemplate::factory();
                if ( $this->definition->attribute( 'summary_page' ) && !$this->http->hasPostVariable( 'summary_page' ) )
                {
                    // rendering summary page
                    $data_to_send = $this->getDataToSend();
                    $tpl->setVariable( 'all_pages', $data_to_send['email_data'] );
                    $result['summary_page'] = $tpl->fetch( 'design:summary_page.tpl' );   
                }
                else
                {
                    // Sending email message
                    if ($this->definition->attribute('post_action') == 'email')
                    {
                        $operation_result = $this->processEmail();
                        $this->removeSessionData();
                    }
                    // TODO: Storing data in database
                    else 
                    {
                        $operation_result = false;
                    }

                    // rendering success template
                    $tpl->setVariable( 'result', $operation_result );
                    $tpl->setVariable( 'form_definition', $this->definition );
                    $result['success'] = $tpl->fetch( 'design:form_processed.tpl' );                      
                }
            } 
        }
        
        if ( $this->http->hasPostVariable( 'form-back' ) )
        {
            if ( !$this->http->hasPostVariable( 'summary_page' ) )
            {
                $current_page -= 1;
            }
            $form_page['attributes'] = eZSession::get( formDefinitions::PAGE_SESSION_PREFIX . $current_page );
            $form_page['attributes'] = $form_page['attributes']['attributes'];
        }
        elseif ( $this->http->hasPostVariable( 'form-next' ) && eZSession::issetkey( formDefinitions::PAGE_SESSION_PREFIX . $current_page ) )
        {
            $form_page['attributes'] = eZSession::get( formDefinitions::PAGE_SESSION_PREFIX . $current_page );
            $form_page['attributes'] = $form_page['attributes']['attributes'];            
        }
        
        $result = array_merge($result, array( 'definition'          => $this->definition,
                                              'attributes'          => $form_page['attributes'],
                                              'validation'          => $errors,
                                              'current_page'        => $current_page,
                                              'all_pages'           => $all_pages,
                                              'date_validator'      => formValidators::DATE_ID,
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
     * Method generates email message to recipients and sends it
     * @return type
     */
    private function processEmail()
    {
        // creating email content
        $data_to_send   = $this->getDataToSend();
        $sender         = $this->definition->attribute( 'email_sender' );
        $recipients     = explode( ';', $this->definition->attribute( 'recipients' ) );
        
        // sendnig message to default recipient(s) (for form definition)
        $status = $this->sendEmail(
                $sender,
                $this->definition->attribute( 'name' ) . ' - ' . ezpI18n::tr( 'extension/formmaker/email', 'New answer' ),
                'email/recipient.tpl',
                $data_to_send['email_data'],
                $recipients
        );     
        
        // sending email to additional receivers
        if ( $status && !empty( $data_to_send['receivers'] ) )
        {
            foreach ( $data_to_send['receivers'] as $email_address )
            {
                if ( empty( $email_address ) )
                {
                    continue;
                }

                $status = $this->sendEmail(
                        $sender,
                        $this->definition->attribute( 'name' ),
                        'email/user.tpl',
                        $data_to_send['email_data'],
                        array( $email_address )
                );
            }
        }
        
        return $status;
    }
    
    /**
     * Method sends an email message basing on fiven attributes
     * @param string $sender
     * @param string $sender
     * @param string $template
     * @param array $email_data
     * @param string $email_address
     * @param array $recipients
     * @return boolean
     */
    private function sendEmail( $sender, $subject, $template, $email_data, $recipients )
    {
        $tpl    = eZTemplate::factory();
        $mail   = new eZMail();
        
        $tpl->setVariable( 'data', $email_data );
        $mail->setSender( $sender ); 
        $mail->setSubject( $subject );
        $mail->setContentType('text/html');
        $mail->setBody( $tpl->fetch( 'design:' . $template ) );
        foreach( $recipients as $recipient ) 
        {
            $mail->addReceiver( $recipient );
        } 
        
        return eZMailTransport::send( $mail );          
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
    
    /**
     * Method removes form session data
     */
    private function removeSessionData()
    {
        foreach ( $_SESSION as $key => $value )
        {
            if ( !preg_match( '/^' . formDefinitions::PAGE_SESSION_PREFIX . '/', $key ) ) {
                continue;
            }
            // clean up the session
            unset( $_SESSION[$key] );
        }         
    }
    
    /**
     * Method returns data ready for sending
     * @return type
     */
    private function getDataToSend()
    {
        $email_data = array();
        $receivers  = array();
        $i          = 0;
        
        foreach ( $_SESSION as $key => $page )
        {
            if ( !preg_match( '/^' . formDefinitions::PAGE_SESSION_PREFIX . '/', $key ) ) {
                continue;
            }

            $email_data[$i]['page_label'] = ( $page['page_info'] instanceof formAttributes ) ? $page['page_info']->attribute( 'label' ) : $this->definition->attribute( 'first_page' );
            
            foreach ( $page['attributes'] as $attribute )
            {
                switch ($attribute->attribute('type_id'))
                {
                    case formTypes::CHECKBOX_ID: // checkbox
                        $email_data[$i]['attributes'][$attribute->attribute('label')] = ezpI18n::tr( 'extension/formmaker/email', ( $attribute->attribute( 'default_value' ) == 'on') ? 'Yes': 'No');
                        break;

                    case formTypes::RADIO_ID: // radio button
                        $option_object = formAttributesOptions::fetchOption( $attribute->attribute( 'default_value' ) );
                        $email_data[$i]['attributes'][$attribute->attribute( 'label' )] = (!is_null($option_object)) ? $option_object->attribute( 'label' ) : ezpI18n::tr( 'extension/formmaker/email', 'Not checked' );
                        break;

                    case formTypes::TEXTLINE_ID:
                        if ( $attribute->attribute( 'email_receiver' ) == 1 )
                        {
                            $receivers[] = $attribute->attribute( 'default_value' );
                        }
                    
                    default:
                        $email_data[$i]['attributes'][$attribute->attribute('label')] = $attribute->attribute( 'default_value' );
                        break;
                }                
            }
            $i++;
        }  
        
        return array(
            'email_data'    => $email_data,
            'receivers'     => $receivers
        );        
    }
}