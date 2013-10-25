<?php

/**
 * Class defines custom template fetches
 */
class FormMakerFunctionCollection
{
    private $http;                      // eZHTTPTool object instance
    private $definition;                // form definition object
    private $ini;                       // formmaker.ini instance

    // a list actions responsible for processing form results
    public static $action_types = array(
        'email_action', 'store_action', 'object_action'
    );


    /**
     * Method fetches form definition and all attributes for given Form id.
     * It also returns validation error or success template content if there are no errors.
     * @param int $form_id
     * @param string $view - "default" for default
     * @return array
     */
    public function fetchFormData( $form_id, $view )
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
                'success'               => $tpl->fetch( 'design:formmaker/view/' . $view . '/form_processed.tpl' ),
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
            $post_id = $this->generatePostID( $attrib );
            if ( !$this->http->hasPostVariable( $post_id ) )
            {
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
        if ( count( $posted_values ) || $this->http->hasPostVariable( 'summary_page' ) || !empty( $_FILES ) )
        {
            // Checking required security POST variable
            if ( $this->http->postVariable('validation') !== 'False' )
            {
                throw new Exception('Security exception');
            }

            if ( count( $posted_values ) || !empty( $_FILES ) )
            {
                $attachmentsDir = $this->ini->variable('Mail', 'AttachmentsDir');

                // updating attributes with current values
                foreach ($form_page['attributes'] as $i => $attrib)
                {
                    $allowed_file_types = $attrib->attribute( 'allowed_file_types' );

                    // processing File attributes
                    if( !empty( $allowed_file_types ) && !file_exists( $attachmentsDir . $attrib->attribute( 'default_value' ) ) && empty( $posted_values[$i] ) )
                    {
                        $post_id = $this->generatePostID( $attrib );
                        // we can't continue with file processing when it wasn't uploaded (name is empty)
                        if ( !isset( $_FILES[$post_id] ) || empty( $_FILES[$post_id]['name'] ) )
                        {
                            continue;
                        }

                        $file = eZHTTPFile::fetch( $post_id );
                        $parts = explode( '.', $file->attribute( 'original_filename' ) );
                        $ext = end( $parts );

                        if( $file && in_array( $ext, explode( ',', $allowed_file_types) ) )
                        {
                            $file->store( $attachmentsDir , $ext );
                            $thumb = $this->thumbName($file->attribute('filename'));

                            $img = eZImageManager::instance();
                            $img->readINISettings();
                            $img->convert( $file->attribute( 'filename' ), $attachmentsDir , 'thumb' );

                            $form_page['attributes'][$i]->setAttribute('default_value', $file->attribute('filename'));
                            $form_page['files'][$i]['file'] = $file->attribute('filename');
                            $form_page['files'][$i]['thumb'] = $thumb;
                        }
                    }
                    // processing all other attributes (except File)
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
                $data_to_send = $this->getDataToSend( $view );

                if ( $this->definition->attribute( 'summary_page' ) && !$this->http->hasPostVariable( 'summary_page' ) )
                {
                    // rendering summary page
                    $tpl->setVariable( 'all_pages', $data_to_send['data'] );
                    $tpl->setVariable( 'body_text', $this->definition->attribute( 'summary_body' ) );
                    $tpl->setVariable( 'form_id', $this->definition->attribute( 'id' ) );
                    $result['summary_page'] = $tpl->fetch( 'design:formmaker/view/' . $view . '/summary_page.tpl' );
                }
                // processing the data only if array contains the data
                elseif ( !empty( $data_to_send['data'] ) )
                {
                    $operation_result = true;
                    foreach ( self::$action_types as $action_type )
                    {
                        if ( $this->definition->attribute( $action_type ) && $operation_result )
                        {
                            $operation_result = $this->processFormData( $action_type, $data_to_send );
                        }
                    }

                    // rendering success template
                    $tpl->setVariable( 'result', $operation_result );
                    $tpl->setVariable( 'form_definition', $this->definition );
                    $result['success'] = $tpl->fetch( 'design:formmaker/view/' . $view . '/form_processed.tpl' );
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
     * Method returns attribute post name/id based on indentifier or generated
     * @param type $attrib
     * @return string
     */
    private function generatePostID( $attrib )
    {
        return $post_id = 'field_' . $attrib->attribute('type_id') . '_' . $attrib->attribute('id');
    }

    /**
     * Method generates email message to recipients and sends it
     * @param array $data_to_send
     * @return type
     */
    private function processEmail( $data_to_send )
    {
        // creating email content
        $recipients  = explode( ';', $this->definition->attribute( 'recipients' ) );

        // sendnig message to default recipient(s) (for form definition)
        $status = $this->sendEmail(
                $this->definition->attribute( 'email_title' ),
                'formmaker/email/recipient.tpl',
                $data_to_send['data'],
                $recipients,
                $data_to_send['attachments']
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
                        $this->definition->attribute( 'email_title' ),
                        'formmaker/email/user.tpl',
                        $data_to_send['data'],
                        array( $email_address ),
                        $data_to_send['attachments']
                );
            }
        }

        return $status;
    }

    /**
     * Method processes the given data, depending of given type.
     * @param string $type
     * @param array $data
     * @return boolean
     */
    private function processFormData( $type, $data )
    {
        switch ( $type )
        {
            case 'email_action':
                $status = $this->processEmail( $data );
                $this->removeSessionData();
                break;

            case 'store_action':
                $status = $this->storeAnswer( $data );
                break;

            case 'object_action':
                $process_class = $this->definition->attribute( 'process_class' );
                $processing_object = new $process_class();
                $status = $processing_object->processSubmit( $data );
                $this->removeSessionData();
                break;

            default:
                $status = false;
                break;
        }

        return $status;
    }

    /**
     * Method sends an email message basing on fiven attributes
     * @param string $subject
     * @param string $template
     * @param array $email_data
     * @param string $email_address
     * @param array $recipients
     * @return boolean
     */
    private function sendEmail( $subject, $template, $email_data, $recipients, $attachments )
    {
        $validator = new FormMaker_Validate_EmailAddress();
        foreach ( $recipients as $i => $recipient )
        {
            if ( !$validator->isValid( $recipient ) )
            {
                unset( $recipients[$i] );
            }
        }

        // there are no correct recipient addresses, so nothing to do here
        if ( empty( $recipients ) )
        {
            return false;
        }

        $sender = eZINI::instance( 'site.ini' )->variable( 'MailSettings', 'EmailSender' );
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
                }

                if(!$mail->Send())
                {
                    // Message was not sent
                    // debug accessible with $mail->ErrorInfo;
                    $result = false;
                }
                else
                {
                    // Message has been sent
                    $result = true;
                }

                // sent or not, need to delete attachments
                foreach( $attachments as $attachment )
                {
                    if( file_exists( $attachment ) )
                    {
                        unlink($attachment);
                        unlink($this->thumbName($attachment));
                    }
                }
                break;
        }

        return $result;
    }

    /**
     * Storing user input in a database
     * @param array $data
     * @return bool
     */
    private function storeAnswer( $data )
    {
        $answer = formAnswers::addNewAnswer( $this->definition->attribute( 'id' ) );
        return formAnswersAttributes::addAttributes( $data, $answer->attribute( 'id' ) );
    }

    /**
     * Fetch function checks if attribute of given ID is required or not
     * @param int $attribute_id
     * @return array
     */
    public function isAttributeRequired( $attribute_id )
    {
        return array( 'result' => formAttrvalid::isAttributeRequired( $attribute_id ) );
    }

    /**
     * Method removes form session data
     */
    private function removeSessionData()
    {
        foreach ( $_SESSION as $key => $value )
        {
            if ( !preg_match( '/^' . formDefinitions::PAGE_SESSION_PREFIX . '/', $key ) )
            {
                continue;
            }
            // clean up the session
            unset( $_SESSION[$key] );
        }
    }

    /**
     * Method returns data ready for sending
     * @param string $view
     * @return type
     */
    private function getDataToSend( $view )
    {
        $email_data     = array();
        $receivers      = array();
        $attachments    = array();
        $i              = 0;
        $filled_only    = $this->ini->variable( 'ViewSettings_' . $view, 'SendOnlyFilledData' );

        foreach ( $_SESSION as $key => $page )
        {
            if ( !preg_match( '/^' . formDefinitions::PAGE_SESSION_PREFIX . '/', $key ) )
            {
                continue;
            }
            $email_data[$i]['page_label'] = ( $page['page_info'] instanceof formAttributes ) ? $page['page_info']->attribute( 'label' ) : $this->definition->attribute( 'first_page' );

            foreach ( $page['attributes'] as $j => $attribute )
            {
                $default_value  = $attribute->attribute( 'default_value' );
                $type_id        = $attribute->attribute( 'type_id' );

                switch ( $type_id )
                {
                    case formTypes::CHECKBOX_ID:
                        if ( $filled_only != 'true' || !empty( $default_value ) )
                        {
                            $email_data[$i]['attributes'][$j]['id'] = $attribute->attribute( 'id' );
                            $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                            $email_data[$i]['attributes'][$j]['identifier'] = $attribute->attribute( 'identifier' );
                            $email_data[$i]['attributes'][$j]['value'] = ezpI18n::tr( 'formmaker/email', ( $default_value == 'on') ? 'Yes': 'No');
                            $email_data[$i]['attributes'][$j]['is_file'] = false;
                            $email_data[$i]['attributes'][$j]['is_image'] = false;
                            $email_data[$i]['attributes'][$j]['type_id'] = $type_id;
                        }
                        break;

                    case formTypes::RADIO_ID:
                        if ( $filled_only != 'true' || !empty( $default_value ) )
                        {
                            $email_data[$i]['attributes'][$j]['id'] = $attribute->attribute( 'id' );
                            $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                            $email_data[$i]['attributes'][$j]['identifier'] = $attribute->attribute( 'identifier' );
                            $option_object = formAttributesOptions::fetchOption( $default_value );
                            $email_data[$i]['attributes'][$j]['value'] = (!is_null($option_object)) ? $option_object->attribute( 'label' ) : ezpI18n::tr( 'formmaker/email', 'Not checked' );
                            $email_data[$i]['attributes'][$j]['is_file'] = false;
                            $email_data[$i]['attributes'][$j]['is_image'] = false;
                            $email_data[$i]['attributes'][$j]['type_id'] = $type_id;
                        }
                        break;

                    case formTypes::SELECT_ID:
                        if ( $filled_only != 'true' || !empty( $default_value ) )
                        {
                            $email_data[$i]['attributes'][$j]['id'] = $attribute->attribute( 'id' );
                            $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                            $option_object = formAttributesOptions::fetchOption( $default_value );
                            $email_data[$i]['attributes'][$j]['value'] = (!is_null($option_object)) ? $option_object->attribute( 'label' ) : ezpI18n::tr( 'formmaker/email', 'Not selected' );
                            $email_data[$i]['attributes'][$j]['identifier'] = $attribute->attribute( 'identifier' );
                            $email_data[$i]['attributes'][$j]['is_file'] = false;
                            $email_data[$i]['attributes'][$j]['is_image'] = false;
                            $email_data[$i]['attributes'][$j]['type_id'] = $type_id;
                        }
                        break;

                    case formTypes::TEXTLINE_ID:
                        if ( $filled_only != 'true' || !empty( $default_value ) )
                        {
                            if ( $attribute->attribute( 'email_receiver' ) == 1 )
                            {
                                $receivers[] = $default_value;
                            }
                            $email_data[$i]['attributes'][$j]['id'] = $attribute->attribute( 'id' );
                            $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                            $email_data[$i]['attributes'][$j]['identifier'] = $attribute->attribute( 'identifier' );
                            $email_data[$i]['attributes'][$j]['value'] = $attribute->attribute( 'default_value' );
                            $email_data[$i]['attributes'][$j]['is_file'] = false;
                            $email_data[$i]['attributes'][$j]['is_image'] = false;
                            $email_data[$i]['attributes'][$j]['type_id'] = $type_id;
                        }
                        break;

                    case formTypes::FILE_ID:
                        $email_data[$i]['attributes'][$j]['id'] = $attribute->attribute( 'id' );
                        $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                        $email_data[$i]['attributes'][$j]['identifier'] = $attribute->attribute( 'identifier' );
                        $email_data[$i]['attributes'][$j]['value'] = $attribute->attribute( 'default_value' );
                        $email_data[$i]['attributes'][$j]['is_file'] = file_exists($attribute->attribute( 'default_value' ));
                        $email_data[$i]['attributes'][$j]['is_image'] = $this->isImage($attribute->attribute( 'default_value' ));
                        $attachments[] = $attribute->attribute( 'default_value' );
                        $email_data[$i]['attributes'][$j]['type_id'] = $type_id;
                        break;

                    default:
                        if ( $filled_only != 'true' || !empty( $default_value ) )
                        {
                            $email_data[$i]['attributes'][$j]['id'] = $attribute->attribute( 'id' );
                            $email_data[$i]['attributes'][$j]['label'] = $attribute->attribute( 'label' );
                            $email_data[$i]['attributes'][$j]['identifier'] = $attribute->attribute( 'identifier' );
                            $email_data[$i]['attributes'][$j]['value'] = $attribute->attribute( 'default_value' );
                            $email_data[$i]['attributes'][$j]['is_file'] = false;
                            $email_data[$i]['attributes'][$j]['is_image'] = false;
                            $email_data[$i]['attributes'][$j]['type_id'] = $type_id;
                        }
                        break;
                }
            }
            $i++;
        }

        return array(
            'data'          => $email_data,
            'receivers'     => $receivers,
            'attachments'   => $attachments,
        );
    }

    /**
     * Method executes the internal script. It depends on FormsAffected setting and runs in two caseses only: when FormsAffected array is empty (runs for all forms) or
     * FormsAffected array is not empty and currently processed form matches its value
     */
    private function executeExternalScript()
    {
        if ( $this->ini->hasVariable( 'ExternalScript', 'Path' ) )
        {
            $forms_affected = array();
            if ( $this->ini->hasVariable( 'ExternalScript', 'FormsAffected' ) )
            {
                $forms_affected = $this->ini->variable( 'ExternalScript', 'FormsAffected' );
            }

            if ( empty( $forms_affected ) || in_array( $this->definition->attribute( 'id' ), $forms_affected ) )
            {
                require $this->ini->variable( 'ExternalScript', 'Path' );
            }
        }
    }

    /**
     * Method overrides $form_page variable by injecting the data from external source. It depends on FormsAffected setting and runs in two caseses only:
     * when FormsAffected array is empty (runs for all forms) or FormsAffected array is not empty and currently processed form matches its value
     * @param array $form_page
     */
    private function injectExternalData( &$form_page )
    {
        if ( $this->ini->hasVariable( 'ExternalDataInject', 'Path' ) )
        {
            $forms_affected = array();
            if ( $this->ini->hasVariable( 'ExternalDataInject', 'FormsAffected' ) )
            {
                $forms_affected = $this->ini->variable( 'ExternalDataInject', 'FormsAffected' );
            }

            if ( empty( $forms_affected ) || in_array( $this->definition->attribute( 'id' ), $forms_affected ) )
            {
                require $this->ini->variable( 'ExternalDataInject', 'Path' );
            }
        }
    }

    /**
    * generates and returns file name for a thumbnail
    **/
    private function thumbName( $file )
    {
        $data = explode('.', $file);
        $start = reset($data);
        $extension = end($data);
        $thumb = $start . '_thumb.' . $extension;
        return $thumb;
    }

    /**
     * checks if uploaded file is an actual image based on getimagesize()
     * @param string $filePath
     * @return bool
     */
    public static function isImage( $filePath )
    {
        try
        {
            if ( !file_exists( $filePath ) )
            {
                throw new Exception( 'Given path is not correct file path' );
            }
            getimagesize( $filePath );
        }
        catch (Exception $e)
        {
            return false;
        }

        return true;
    }

    /**
     * Returns the IDs from post_id string
     * @param string $post_id
     * @return array
     * @throws Exception
     */
    private function explodePostId( $post_id )
    {
        $parts = explode( '_', $post_id );
        if ( !isset( $parts[2] ) )
        {
            throw new Exception( 'Incorrect post ID string given' );
        }

        return array(
            'type_id'       => $parts[1],
            'attribute_id'  => $parts[2]
        );
    }

    /**
     * Returns the attribute id from array returned by explodePostId
     * @param string $post_id
     * @return int
     */
    private function getAttributeIdFromPostId( $post_id )
    {
        $data = $this->explodePostId( $post_id );
        return (int)$data['attribute_id'];
    }
}