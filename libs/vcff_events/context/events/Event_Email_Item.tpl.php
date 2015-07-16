<div data-event-code="<?php echo $this->type; ?>" class="event-item">
    
    <div class="action-field-group">
        <div class="action-group-header">
            <h4><strong><?php echo __('Who is sending this email?', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
        </div>
        <div class="action-group-contents">
            <div class="row">
                <div class="col-sm-4">
                    <p>Instructions</p>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <input name="event_action[events][send_email][from_name]" placeholder="From Name" type="text" value="<?php echo $this->Get_From_Name(); ?>" class="form-control">
                        <?php if ($this->Is_Update() && isset($validation_errors['from_name'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-text"><?php echo __('Please enter a from name', VCFF_NS); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <input name="event_action[events][send_email][from_address]" placeholder="From Email Address" type="text" value="<?php echo $this->Get_From_Address(); ?>" class="form-control">
                        <?php if ($this->Is_Update() && isset($validation_errors['from_address'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-text"><?php echo __('Please enter a from address', VCFF_NS); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <input name="event_action[events][send_email][reply_address]" placeholder="Reply Address" type="text" value="<?php echo $this->Get_Reply_Address(); ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <input name="event_action[events][send_email][reply_name]" placeholder="Reply Name" type="text" value="<?php echo $this->Get_Reply_Name(); ?>" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="action-field-group">
        <div class="action-group-header">
            <h4><strong><?php echo __('Who is receiving this email?', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
        </div>
        <div class="action-group-contents"> 
            
            <div class="row">
                <div class="col-sm-4">
                    <p>Instructions</p>
                </div>
                <div class="col-sm-8">
                    <div class="form-group to-address-list">
                        <label class="control-label">Send Email To</label>
                        <div class="to-address-list-items">
                            <?php $send_emails = $this->Get_Send_Emails(); ?>
                            <?php if ($send_emails && is_array($send_emails)): ?>
                            <?php $i=0; foreach($send_emails as $k => $email_data): ?>
                            <div class="to-item form-inline">
                                <select name="event_action[events][send_email][to][to_<?php echo $i; ?>][source]" class="item-source form-control">
                                    <option <?php if (isset($email_data['source']) && $email_data['source'] == 'entered'): ?>selected="selected"<?php endif; ?> value="entered">Enter an address</option>
                                    <option <?php if (isset($email_data['source']) && $email_data['source'] == 'dynamic'): ?>selected="selected"<?php endif; ?> value="dynamic">From email field</option>
                                </select>
                                <select name="event_action[events][send_email][to][to_<?php echo $i; ?>][field]" class="item-field form-control" style="display:none;">
                                    <option>Select email field</option>
                                    <?php foreach ($email_fields as $machine_code => $field_instance): ?>
                                    <option <?php if (isset($email_data['field']) && $email_data['field'] == $machine_code): ?>selected="selected"<?php endif; ?> value="<?php echo $machine_code; ?>"><?php echo $field_instance->attributes['field_label']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input name="event_action[events][send_email][to][to_<?php echo $i; ?>][address]" class="item-address form-control" style="display:none;" type="text" value="<?php echo $email_data['address']; ?>">
                                <a href="#" class="dashicons dashicons-plus-alt item-add"></a><a href="#" class="dashicons dashicons-dismiss item-remove"></a>
                            </div>
                            <?php $i++; ?>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php if ($this->Is_Update() && isset($validation_errors['to'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-text"><?php echo __('Please enter at least one email address', VCFF_NS); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <script id="event_email_to" type="text/x-handlebars-template"> 
                    <div class="to-item form-inline">
                        <select name="event_action[events][send_email][to][to_{{i}}][source]" class="item-source form-control">
                            <option value="entered">Enter an address</option>
                            <option value="dynamic">From email field</option>
                        </select>
                        <select name="event_action[events][send_email][to][to_{{i}}][field]" class="item-field form-control" style="display:none;">
                            <option>Select email field</option>
                            <?php foreach ($email_fields as $machine_code => $field_instance): ?>
                            <option value="<?php echo $machine_code; ?>"><?php echo $field_instance->attributes['field_label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input name="event_action[events][send_email][to][to_{{i}}][address]" class="item-address form-control" style="display:none;" type="text" value="">
                        <a href="#" class="dashicons dashicons-plus-alt item-add"></a><a href="#" class="dashicons dashicons-dismiss item-remove"></a>
                    </div>
                    </script>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-4">
                    <p>Instructions</p>
                </div>
                <div class="col-sm-8">
                    <div class="form-group cc-address-list">
                        <label class="control-label">CC Email To</label>
                        <div class="cc-address-list-items">
                            <?php $cc_emails = $this->Get_CC_Emails(); ?>
                            <?php if ($cc_emails && is_array($cc_emails)): ?>
                            <?php $i=0; foreach($cc_emails as $k => $email_data): ?>
                            <div class="cc-item form-inline">
                                <select name="event_action[events][send_email][cc][cc_<?php echo $i; ?>][source]" class="item-source form-control">
                                    <option <?php if (isset($email_data['source']) && $email_data['source'] == 'entered'): ?>selected="selected"<?php endif; ?> value="entered">Enter an address</option>
                                    <option <?php if (isset($email_data['source']) && $email_data['source'] == 'dynamic'): ?>selected="selected"<?php endif; ?> value="dynamic">From email field</option>
                                </select>
                                <select name="event_action[events][send_email][cc][cc_<?php echo $i; ?>][field]" class="item-field form-control" style="display:none;">
                                    <option>Select email field</option>
                                    <?php foreach ($email_fields as $machine_code => $field_instance): ?>
                                    <option <?php if (isset($email_data['field']) && $email_data['field'] == $machine_code): ?>selected="selected"<?php endif; ?> value="<?php echo $machine_code; ?>"><?php echo $field_instance->attributes['field_label']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input name="event_action[events][send_email][cc][cc_<?php echo $i; ?>][address]" class="item-address form-control" style="display:none;" type="text" value="<?php echo $email_data['address']; ?>">
                                <a href="#" class="dashicons dashicons-plus-alt item-add"></a><a href="#" class="dashicons dashicons-dismiss item-remove"></a>
                            </div>
                            <?php $i++; ?>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php if ($this->Is_Update() && isset($validation_errors['cc'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-text"><?php echo __('Please enter at least one email address', VCFF_NS); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <script id="event_email_cc" type="text/x-handlebars-template"> 
                    <div class="cc-item form-inline">
                        <select name="event_action[events][send_email][cc][cc_{{i}}][source]" class="item-source form-control">
                            <option value="entered">Enter an address</option>
                            <option value="dynamic">From email field</option>
                        </select>
                        <select name="event_action[events][send_email][cc][cc_{{i}}][field]" class="item-field form-control" style="display:none;">
                            <option>Select email field</option>
                            <?php foreach ($email_fields as $machine_code => $field_instance): ?>
                            <option value="<?php echo $machine_code; ?>"><?php echo $field_instance->attributes['field_label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input name="event_action[events][send_email][cc][cc_{{i}}][address]" class="item-address form-control" style="display:none;" type="text" value="">
                        <a href="#" class="dashicons dashicons-plus-alt item-add"></a><a href="#" class="dashicons dashicons-dismiss item-remove"></a>
                    </div>
                    </script>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <p>Instructions</p>
                </div>
                <div class="col-sm-8">
                    <div class="form-group bcc-address-list">
                        <label class="control-label">BCC Email To</label>
                        <div class="bcc-address-list-items">
                            <?php $bcc_emails = $this->Get_BCC_Emails(); ?>
                            <?php if ($bcc_emails && is_array($bcc_emails)): ?>
                            <?php $i=0; foreach($bcc_emails as $k => $email_data): ?>
                            <div class="bcc-item form-inline">
                                <select name="event_action[events][send_email][bcc][bcc_<?php echo $i; ?>][source]" class="item-source form-control">
                                    <option <?php if (isset($email_data['source']) && $email_data['source'] == 'entered'): ?>selected="selected"<?php endif; ?> value="entered">Enter an address</option>
                                    <option <?php if (isset($email_data['source']) && $email_data['source'] == 'dynamic'): ?>selected="selected"<?php endif; ?> value="dynamic">From email field</option>
                                </select>
                                <select name="event_action[events][send_email][bcc][bcc_<?php echo $i; ?>][field]" class="item-field form-control" style="display:none;">
                                    <option>Select email field</option>
                                    <?php foreach ($email_fields as $machine_code => $field_instance): ?>
                                    <option <?php if (isset($email_data['field']) && $email_data['field'] == $machine_code): ?>selected="selected"<?php endif; ?> value="<?php echo $machine_code; ?>"><?php echo $field_instance->attributes['field_label']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input name="event_action[events][send_email][bcc][bcc_<?php echo $i; ?>][address]" class="item-address form-control" style="display:none;" type="text" value="<?php echo $email_data['address']; ?>">
                                <a href="#" class="dashicons dashicons-plus-alt item-add"></a><a href="#" class="dashicons dashicons-dismiss item-remove"></a>
                            </div>
                            <?php $i++; ?>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <script id="event_email_bcc" type="text/x-handlebars-template"> 
                    <div class="bcc-item form-inline">
                        <select name="event_action[events][send_email][bcc][bcc_{{i}}][source]" class="item-source form-control">
                            <option value="entered">Enter an address</option>
                            <option value="dynamic">From email field</option>
                        </select>
                        <select name="event_action[events][send_email][bcc][bcc_{{i}}][field]" class="item-field form-control" style="display:none;">
                            <option>Select email field</option>
                            <?php foreach ($email_fields as $machine_code => $field_instance): ?>
                            <option value="<?php echo $machine_code; ?>"><?php echo $field_instance->attributes['field_label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input name="event_action[events][send_email][bcc][bcc_{{i}}][address]" class="item-address form-control" style="display:none;" type="text" value="">
                        <a href="#" class="dashicons dashicons-plus-alt item-add"></a><a href="#" class="dashicons dashicons-dismiss item-remove"></a>
                    </div>
                    </script>
                </div>
            </div>
        </div>
    </div>
    
    <div class="action-field-group">
        <div class="action-group-header">
            <h4><strong><?php echo __('The Email Subject', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
        </div>
        <div class="action-group-contents">
            <div class="row">
                <div class="col-sm-4">
                    <p>Instructions</p>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <?php echo vcff_curly_editor_textfield($this->form_instance,'event_action[events][send_email][subject]',$this->Get_Email_Subject()); ?>
                        <?php if ($this->Is_Update() && isset($validation_errors['subject'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-text"><?php echo __('Please enter an email subject', VCFF_NS); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="action-field-group">
        <div class="action-group-header">
            <h4><strong><?php echo __('HTML Message Contents', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
        </div>
        <div class="action-group-contents">
            <div class="row">
                <div class="col-sm-4">
                    <p>Instructions</p>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <?php echo vcff_curly_editor_textarea($this->form_instance,'event_action[events][send_email][message_html]',$this->Get_Email_Html_Content()); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="action-field-group">
        <div class="action-group-header">
            <h4><strong><?php echo __('HTML Text Contents', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
        </div>
        <div class="action-group-contents">
            <div class="row">
                <div class="col-sm-4">
                    <p>Instructions</p>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <?php echo vcff_curly_editor_textarea($this->form_instance,'event_action[events][send_email][message_text]',$this->Get_Email_Text_Content()); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>