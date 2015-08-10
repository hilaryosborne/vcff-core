<?php

class Event_Email_Item extends VCFF_Event_Item {
    
    public function Render() {
        // Retrieve any validation errors
        $validation_errors = $this->validation_errors;
        // Retrieve the context director
        $action_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // If context data was passed
        $posted_data = $this->data;
        // Populate the type
        $email_fields = $this->_Get_Email_Fields(); 
        // Start gathering content
        ob_start();
        // Include the template file
        include($action_dir.'/Event_Email_Item.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    protected function _Get_Email_Fields() {
        
        $form_instance = $this->form_instance;
        
        $form_fields = $form_instance->fields;
        
        if (!$form_fields || !is_array($form_fields)) { return array(); }
        
        $email_fields = array();
        
        foreach ($form_fields as $machine_code => $field_instance) {
            
            if (!isset($field_instance->is_email) || !$field_instance->is_email) { continue; }
            
            $email_fields[$machine_code] = $field_instance;
        }
        
        return $email_fields;
    }
    
    
    public function Get_From_Name() {
        
        if (!isset($this->value['from_name'])) { return; }
        
        return $this->value['from_name'];
    }
    
    public function Get_From_Address() {
    
        if (!isset($this->value['from_address'])) { return; }
        
        return $this->value['from_address'];
    }
    
    public function Get_Reply_Address() {
    
        if (!isset($this->value['reply_address'])) { return; }
        
        return $this->value['reply_address'];
    }
    
    public function Get_Reply_Name() {
    
        if (!isset($this->value['reply_name'])) { return; }
        
        return $this->value['reply_name'];
    }
    
    public function Get_Send_Emails() {
    
        if (!isset($this->value['to'])) { return; }
        
        return $this->value['to'];
    }
    
    public function Get_CC_Emails() {
        
        if (!isset($this->value['cc'])) { return; }
        
        return $this->value['cc'];
    }
    
    public function Get_BCC_Emails() {
    
        if (!isset($this->value['bcc'])) { return; }
        
        return $this->value['bcc'];
    }
    
    public function Get_Email_Subject() {
    
        if (!isset($this->value['subject'])) { return; }
        
        return $this->value['subject'];
    }
    
    public function Get_Email_Html_Content() {
    
        if (!isset($this->value['message_html'])) { return; }
        
        return $this->value['message_html'];
    }
    
    public function Get_Email_Text_Content() {
        
        if (!isset($this->value['message_text'])) { return; }
        // Return the message text
        return $this->value['message_text'];
    }
    
    public function Check_Validation() {

        $action_instance = $this->action_instance;

        if (!$this->Get_From_Name()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['from_name'] = true;
        }
        
        if (!$this->Get_From_Address()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['from_address'] = true;
        }
        
        if (!$this->Get_Email_Subject()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['subject'] = true;
        }
        
        $send_to = $this->Get_Send_Emails();

        if (!$send_to || !is_array($send_to) || count($send_to) == 0) {
            // Add an alert to notify of field requirements
            $this->validation_errors['to'] = true;
        }

        if (!is_array($this->validation_errors)) { return; }
        
        if (count($this->validation_errors) == 0) { return; }
        
        $action_instance->is_valid = false;
    }
    
    public function Trigger() { 
        
        $form_instance = $this->form_instance;
        
        $mailer = new PHPMailer();
        
        $mailer->From = $this->Get_From_Address();
        $mailer->FromName = $this->Get_From_Name();
        
        if ($this->Get_Reply_Address()) {
            $mailer->addReplyTo($this->Get_Reply_Address(), $this->Get_Reply_Name());
        }

        $mailer->isHTML(true);
        
        
        
        $mailer_is_smtp = vcff_get_setting_value('mailer_is_smtp');
        
        if ($mailer_is_smtp) { 
            
            $mailer->isSMTP(); 

            $mailer->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            $mailer_host = vcff_get_setting_value('mailer_host');

            if ($mailer_host && strlen($mailer_host) > 1) { $mailer->Host = $mailer_host; }

            $mailer_smtp_auth = vcff_get_setting_value('mailer_smtp_auth');

            if ($mailer_smtp_auth) { $mailer->SMTPAuth = true; }

            $mailer_username = vcff_get_setting_value('mailer_username');

            if ($mailer_username) { $mailer->Username = $mailer_username; }

            $mailer_password = vcff_get_setting_value('mailer_password');

            if ($mailer_password) { $mailer->Password = $mailer_password; }

            $mailer_secure = vcff_get_setting_value('mailer_secure');

            if ($mailer_secure) { $mailer->SMTPSecure = $mailer_secure; }

            $mailer_port = vcff_get_setting_value('mailer_port');

            if ($mailer_port) { $mailer->Port = $mailer_port; }
        }

        $send_to = $this->Get_Send_Emails();
        
        if ($send_to && is_array($send_to)) {

            foreach ($send_to as $k => $address_params) {

                $source = $address_params['source'];

                if (!$source) { continue; }

                if ($source == 'entered') {

                    $email_address = vcff_curly_compile($this->form_instance,$address_params['address']);
                } 
                elseif ($source == 'dynamic') {
                
                    $field_value = $this->form_instance->Get_Field($address_params['field'])->Get_RAW_Value();
                    
                    $email_address = $field_value;
                } 

                if (!$email_address) { continue; }

                $mailer->addAddress($email_address);
            }
        }
        
        $cc_to = $this->Get_CC_Emails();
        
        if ($cc_to && is_array($cc_to)) {

            foreach ($cc_to as $k => $address_params) {

                $source = $address_params['source'];

                if (!$source) { continue; }

                if ($source == 'entered') {
                    $email_address = vcff_curly_compile($this->form_instance,$address_params['address']);
                } 
                elseif ($source == 'dynamic') {
                    $field_value = $this->form_instance->Get_Field($address_params['field'])->Get_RAW_Value();
                    
                    $email_address = $field_value;
                } 

                if (!$email_address) { continue; }

                $mailer->addCC($email_address);
            }
        }
        
        $bcc_to = $this->Get_BCC_Emails();
        
        if ($bcc_to && is_array($bcc_to)) {

            foreach ($bcc_to as $k => $address_params) {

                $source = $address_params['source'];

                if (!$source) { continue; }

                if ($source == 'entered') {
                    $email_address = vcff_curly_compile($this->form_instance,$address_params['address']);
                } 
                elseif ($source == 'dynamic') {
                    $field_value = $this->form_instance->Get_Field($address_params['field'])->Get_RAW_Value();
                    
                    $email_address = $field_value;
                } 

                if (!$email_address) { continue; }

                $mailer->addBCC($email_address);
            }
        }
        

        $mailer->Subject = vcff_curly_compile($this->form_instance,$this->Get_Email_Subject());

        $html_content = vcff_curly_compile($this->form_instance,$this->Get_Email_Html_Content());

        $mailer->Body = $html_content;
    
        $text_content = vcff_curly_compile($this->form_instance,$this->Get_Email_Text_Content());
        
        $mailer->AltBody = $text_content;
        
        if (!$mailer->send()) { $this->error = $mailer->ErrorInfo; $form_instance->Add_Alert('danger','There was a problem with PHPMailer: '.$mailer->ErrorInfo); } 
    }
}


add_filter('vcff_settings_group_list',function($group_list, $form_instance){
    
    $group_list[] = array(
        'id' => 'phpmailer_fields',
        'title' => 'PHPMailer Configuration',
        'weight' => 5,
        'hint_html' => '<h4><strong>Instructions</strong></h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur cursus erat at lectus commodo tempor eget vel turpis. Praesent vitae eros semper, aliquet ipsum vel, porttitor tellus.</p>',
        'help_url' => 'http://vcff.theblockquote.com',
    );
    
    return $group_list;
    
},0,2);



add_filter('vcff_settings_field_list',function($field_list, $form_instance){
    
    $field_list[] = array(
        'machine_code' => 'mailer_is_smtp',
        'field_label' => 'Use SMTP',
        'field_group' => 'phpmailer_fields',
        'weight' => 1,
        'field_type' => 'checkbox',
        'checkbox_value' => 'yes',
        'field_dependancy' => false
    );
    
    $field_list[] = array(
        'machine_code' => 'mailer_host',
        'field_label' => 'Host',
        'field_group' => 'phpmailer_fields',
        'weight' => 2,
        'field_type' => 'textfield'
    );
    
    $field_list[] = array(
        'machine_code' => 'mailer_smtp_auth',
        'field_label' => 'Enable SMTP Auth',
        'field_group' => 'phpmailer_fields',
        'checkbox_value' => 'yes',
        'weight' => 3,
        'field_type' => 'checkbox'
    );
    
    $field_list[] = array(
        'machine_code' => 'mailer_username',
        'field_label' => 'SMTP Username',
        'field_group' => 'phpmailer_fields',
        'weight' => 4,
        'field_type' => 'textfield'
    );
    
    $field_list[] = array(
        'machine_code' => 'mailer_password',
        'field_label' => 'SMTP Password',
        'field_group' => 'phpmailer_fields',
        'weight' => 5,
        'field_type' => 'password'
    );
    
    $field_list[] = array(
        'machine_code' => 'mailer_secure',
        'field_label' => 'Encryption',
        'field_group' => 'phpmailer_fields',
        'weight' => 6,
        'field_type' => 'select',
        'values' => array(
            '' => 'Select Encryption Type',
            'tls' => 'TLS',
            'ssl' => 'SSL'
        )
    );
    
    $field_list[] = array(
        'machine_code' => 'mailer_port',
        'field_label' => 'TCP Port',
        'field_group' => 'phpmailer_fields',
        'weight' => 7,
        'field_type' => 'textfield'
    );
    
    return $field_list;
    
},0,2);