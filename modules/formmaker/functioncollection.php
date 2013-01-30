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
    // formmaker.ini instance
    private $ini;
    
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
        $this->ini          = eZINI::instance( 'formmaker.ini' );
        $current_page       = $this->http->hasPostVariable( 'current_page' ) ? $this->http->postVariable( 'current_page' ) : 0;
        $all_pages          = $this->definition->getPageAttributes();
        $form_page          = $all_pages[$current_page];
        $is_page_last       = count( $all_pages ) == ( $current_page + 1) ? true : false;
        $result             = array();
        $errors             = array();
        $posted_values      = array();

        // checking whether user has access to form
        $this->executeExternalScript();
        
        // removing sent flag from session
        if ( eZSession::issetkey( formDefinitions::SESSION_FORM_SENT_KEY ) && empty( $_POST ) )
        {
            eZSession::unsetkey( formDefinitions::SESSION_FORM_SENT_KEY );
        }        
        
        // rendering receipt template. This part handles F5 hitting problem on receipt page
        if ( eZSession::issetkey( formDefinitions::SESSION_FORM_SENT_KEY ) )
        {
            $tpl = eZTemplate::factory();
            $tpl->setVariable( 'result', false );
            $tpl->setVariable( 'form_definition', $this->definition );
            $result = array(
                'success'               => $tpl->fetch( 'design:form_processed.tpl' ),
                'validation'            => $errors,
                'definition'            => $this->definition,
                'attributes'            => $form_page['attributes'],
                'counted_validators'    => formAttrvalid::countValidatorsForAttributes(),
                'current_page'          => $current_page,
                'all_pages'             => $all_pages,
                'date_validator'        => formValidators::DATE_ID,
                'date_year_validator'   => formValidators::DATE_YEAR_ID,
            ); 
            return array('result' => $result);
        }

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
        
        // overriding $form_page (in case when external script is set up)
        $this->injectExternalData( $form_page );

        // Checking post variables
        if ( count( $posted_values ) || $this->http->hasPostVariable( 'summary_page' ) )
        {
            // Checking required security POST variable
            if ( $this->http->postVariable('validation') !== 'False' ) {
                throw new Exception('Security exception');
            }

            if ( count( $posted_values ) )
            {

                $attachmentsDir = $this->ini->variable('Mail', 'AttachmentsDir');

                // updating attributes with current values
                foreach ($form_page['attributes'] as $i => $attrib)
                {

                    if( !empty($attrib->allowed_file_types) // if there are some files in form
                        && !file_exists($attachmentsDir . $attrib->attribute('default_value') )
                        && empty( $posted_values[$i] )
                       )
                    {

                        $file = eZHTTPFile::fetch( $this->generatePostID($attrib) );
                        
                        if($file)
                        {

                            $file->store('formmaker', 'jpg');

                            $thumb = $this->_thumbName($file->attribute('filename'));

                            $img = eZImageManager::instance();
                            $img->readINISettings();

                            $img->convert( $file->attribute('filename'), $this->ini->variable('Mail', 'AttachmentsDir') , 'thumb');

                            $email_data[$i]['attributes'][$j]['label'] = $attrib->attribute( 'label' );
                            $email_data[$i]['attributes'][$j]['value'] = $file->attribute('filename');

                            $attachments[] = $file->attribute('filename');

                            $form_page['attributes'][$i]->setAttribute('default_value', $file->attribute('filename'));
                            $form_page['attributes'][$i]->setAttribute('thumb', '111');

                            $form_page['files'][$i]['file'] = $file->attribute('filename');
                            $form_page['files'][$i]['thumb'] = $thumb;

                        }

                    }
                    else
                    {
                        $form_page['attributes'][$i]->setAttribute('default_value', $posted_values[$i]);
                    }

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
                $data_to_send = $this->getDataToSend();
                
                if ( $this->definition->attribute( 'summary_page' ) && !$this->http->hasPostVariable( 'summary_page' ) )
                {
                    // rendering summary page
                    $tpl->setVariable( 'all_pages', $data_to_send['email_data'] );
                    $tpl->setVariable( 'body_text', $this->definition->attribute( 'summary_body' ) );
                    $result['summary_page'] = $tpl->fetch( 'design:summary_page.tpl' );   
                }
                // processing the data only if array contains the data
                elseif ( !empty( $data_to_send['email_data'] ) )
                {
                    // Sending email message
                    if ($this->definition->attribute('post_action') == 'email')
                    {
                        $operation_result = $this->processEmail( $data_to_send );
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
                    eZSession::set( formDefinitions::SESSION_FORM_SENT_KEY, true );
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
			$form_page['files'] = eZSession::get( formDefinitions::PAGE_SESSION_PREFIX . $current_page );
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
                                              'date_year_validator' => formValidators::DATE_YEAR_ID,
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
     * @param array $data_to_send
     * @return type
     */
    private function processEmail( $data_to_send )
    {
        // creating email content
        $sender     = $this->definition->attribute( 'email_sender' );
        $recipients = explode( ';', $this->definition->attribute( 'recipients' ) );
        $attachments = $data_to_send['attachments'];
        
        // sendnig message to default recipient(s) (for form definition)
        $status = $this->sendEmail(
                $sender,
                $this->definition->attribute( 'name' ) . ' - ' . ezpI18n::tr( 'formmaker/email', 'New answer' ),
                'email/recipient.tpl',
                $data_to_send['email_data'],
                $recipients,
                $attachments
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
                        array( $email_address ),
                        $data_to_send['attachments']
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
    private function sendEmail( $sender, $subject, $template, $email_data, $recipients, $attachments )
    {

        switch ( $this->ini->variable( 'Mail', 'MailClass' ) )
        {

            case 'eZMail':
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
                $result = eZMailTransport::send( $mail );                          
                break;
            
            case 'PHPMailer':
                $tpl = eZTemplate::factory();
                $tpl->setVariable( 'data', $email_data );
                $body = $tpl->fetch( 'design:' . $template );

                $mail = new PHPMailer();

                // $mail->IsSMTP();  // telling the class to use SMTP
                // $mail->Host     = "smtp.example.com"; // SMTP server

                $mail->From     = $sender;
                $mail->FromName = $sender;
                foreach( $recipients as $recipient )
                {
                    $mail->AddAddress( $recipient );
                }

                $mail->Subject  = $subject;
                $mail->Body     = $body;
                $mail->IsHTML(true);
                $mail->CharSet = 'utf-8';

                foreach( $attachments as $attachment )
                {
                    $mail->AddAttachment($attachment);
                    unlink($attachment);
                    unlink($this->_thumbName($attachment));
               }

                if(!$mail->Send())
                {
                    echo 'Message was not sent.';
                    echo 'Mailer error: ' . $mail->ErrorInfo;
                    $result = false;

                }
                else
                {
                    // echo 'Message has been sent.';
                    $result = true;
                }

                break;

            default:
                # code...
                break;
        }

        return $result;		

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
        $email_data     = array();
        $receivers      = array();
        $i              = 0;
        $filled_only    = $this->ini->variable( 'FormmakerSettings', 'SendOnlyFilledData' );

        foreach ( $_SESSION as $key => $page )
        {
            if ( !preg_match( '/^' . formDefinitions::PAGE_SESSION_PREFIX . '/', $key ) ) {
                continue;
            }

            $email_data[$i]['page_label'] = ( $page['page_info'] instanceof formAttributes ) ? $page['page_info']->attribute( 'label' ) : $this->definition->attribute( 'first_page' );
            

            foreach ( $page['attributes'] as $j => $attribute )
            {
                $default_value = $attribute->attribute( 'default_value' );

                switch ($attribute->attribute('type_id'))
                {
                    case formTypes::CHECKBOX_ID: // checkbox
                        $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                        $email_data[$i]['attributes'][$j]['value'] = ezpI18n::tr( 'formmaker/email', ( $default_value == 'on') ? 'Yes': 'No');
                        break;

                    case formTypes::RADIO_ID: // radio button
                        if ( $filled_only != 'true' || !empty( $default_value ) )
                        {
                            $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                            $option_object = formAttributesOptions::fetchOption( $default_value );
                            $email_data[$i]['attributes'][$j]['value'] = (!is_null($option_object)) ? $option_object->attribute( 'label' ) : ezpI18n::tr( 'formmaker/email', 'Not checked' );
                        }
                        break;

                    case formTypes::TEXTLINE_ID:
                        if ( $attribute->attribute( 'email_receiver' ) == 1 )
                        {
                            $receivers[] = $default_value;
                        }
                        else /* fixed: without else condition text attributes were skipped from summary... */
                        {
                            $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                            $email_data[$i]['attributes'][$j]['value'] = $attribute->attribute( 'default_value' );
                        }
                        break;

                    case formTypes::FILE:
                        $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                        $email_data[$i]['attributes'][$j]['value'] = $attribute->attribute( 'default_value' );
                        $attachments[] = $attribute->attribute( 'default_value' );
                        break;

                    default:
                        if ( $filled_only != 'true' || !empty( $default_value ) )
                        {
                            $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                            $email_data[$i]['attributes'][$j]['value'] = $attribute->attribute( 'default_value' );
                        }
                        break;
                }    
                
            }
            $i++;
        }  

        return array(
            'email_data'    => $email_data,
            'receivers'     => $receivers,
            'attachments'   => $attachments,
        );        
    }
    
    /**
     * Method executes the internal script
     */
    private function executeExternalScript()
    {
        if ( $this->ini->hasVariable( 'FormmakerSettings', 'ExternalScript' ) )
        {
            require $this->ini->variable( 'FormmakerSettings', 'ExternalScript' );
        }        
    }
    
    /**
     * Method overrides $form_page variable. 
     * @param array $form_page
     */
    private function injectExternalData( &$form_page )
    {
        if ( $this->ini->hasVariable( 'FormmakerSettings', 'ExternalData' ) )
        {
            require $this->ini->variable( 'FormmakerSettings', 'ExternalData' );
        }
    }

    /**
    * generates and returns file name for a thumbnail
    **/
    private function _thumbName( $file )
    {
        $data = explode('.', $file);
        $start = reset($data);
        $extension = end($data);
        $thumb = $start . '_thumb.' . $extension;
        return $thumb;
    }

}