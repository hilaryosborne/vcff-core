(function ($, undefined) {

    $.fn.getCursorPosition = function () {
        var el = $(this).get(0);
        var pos = 0;
        if ('selectionStart' in el) {
            pos = el.selectionStart;
        } else if ('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
    
    $.fn.VCFFTagger = function() {
        var _self = this;
        
        $(_self).each(function(){
            var tag_selected = $(this).find('.tag-list');
            var tag_insert = $(this).find('.tag-insert');
            var tag_field = $(this).find('.tag-editor-field');
            $(tag_insert).click(function(){
                var element = $(tag_field).get(0);
                var selected_tag = $(tag_selected).val();
                var content = $(tag_field).val();

                var position = 0;
                if ('selectionStart' in element) {
                    position = element.selectionStart;
                } else if ('selection' in document) {
                    element.focus();
                    var selected_range = document.selection.createRange();
                    var selected_length = document.selection.createRange().text.length;
                    selected_range.moveStart('character', -element.value.length);
                    position = selected_range.text.length - selected_length;
                }

                $(tag_field).val(content.substr(0, position)+"{"+selected_tag+"}"+content.substr(position));
            });
        });
    }
    
})(window.jQuery);


var Modify_Event_Form = function(_id) {
    
    var $ = window.jQuery;
    
    var meta_model_el = $('#vcff_meta_model');
    
    if (typeof _id == "undefined") { _id = 0; }
    
    var _Get_Form = function() {
        // Serialise the post form
        var post_form = $("#post").serialize();
        // Form load actions
        vcff_do_action('form_event_form_before_display',{});
        // Bring up the model
        $('#vcff_meta_model').modal({});
        // Populate with the response
        $(meta_model_el).find('.modal-header').empty();
        $(meta_model_el).find('.modal-header').html('<h4 class="modal-title">Modify Event</h4>');
        // Append the body contents
        $(meta_model_el).find('.modal-body').empty();
        // Create the alert element
        var alert_el = $('<div class="alert alert-loading alert-warning" role="alert"></div>');
        // Populate the alert element
        alert_el.append('<h4 class="text-center">Loading Event Form</h4>');
        alert_el.append('<p class="text-center">Please stand by, we are loading the event modify form.</p>');
        // Append the alert element
        $(meta_model_el).find('.modal-body').append(alert_el);
        // Post the data to the webservice
        $.post(ajaxurl,{
            // Target the events form
            'action':'vcff_events_form',
            // The action id 
            'action_id':_id,
            // Encode the data using base64
            'form_data':base64.encode(post_form)
        }, // Handle the response
        function(response){ 
            // Build the form element
            var form_el = $(response);
            // Append the footer actions
            $(meta_model_el).find('.modal-footer').empty();
            $(meta_model_el).find('.modal-footer').append('<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>');
            $(meta_model_el).find('.modal-footer').append('<button type="button" data-loading-text="Saving Event..." class="btn btn-save btn-primary">Save Event</button>');
            // Append the body contents
            $(meta_model_el).find('.modal-body').empty().append(form_el);
            // Form load actions
            vcff_do_action('form_event_on_form_load',{'form':form_el});
            // Form load actions
            vcff_do_action('form_event_form_on_display',{'form':form_el});
            // Prepare the modal form
            _Prepare_Modal(meta_model_el);
            // Prepare the form
            _Prepare_Form(form_el);
            // Form load actions
            vcff_do_action('form_event_form_after_display',{'form':form_el});
        });
    }

    var _Prepare_Modal = function(modal_el) {
        // Retrieve the button save
        var btn_save_el = $(modal_el).find('.btn-save');
        // What happens when we save
        $(btn_save_el).click(function(e){
            // Prevent the default
            e.preventDefault();
            // Set the loading state to loading
            btn_save_el.button('loading');
            // Serialise the post form
            var post_form = $("#post").serialize();
            // Hide and empty any current alerts
            $(modal_el).find('.event-alerts').hide().empty();
            // Post the data to the webservice
            $.post(ajaxurl,{
                // Target the events form
                'action':'vcff_events_ajax_form',
                // The action id 
                'ajax_action':'save',
                // The action id 
                'ajax_code':$('#post_id').val(),
                // Encode the data using base64
                'form_data':base64.encode(post_form)
            }, // Handle the response
            function(response){ 
                // Reset the save button
                btn_save_el.button('reset');
                // If the response was not what we needed
                if (typeof response != "object") { return false; }
                // If we have new form data to show
                if (typeof response.data != "undefined" && typeof response.data.form != "undefined") {
                    // Build the form element
                    var form_el = $(response.data.form);
                    // Append the body contents
                    $(modal_el).find('.modal-body').empty().append(form_el);
                    // Form load actions
                    vcff_do_action('form_event_on_form_load',{'form':form_el});
                    // Form load actions
                    vcff_do_action('form_event_form_on_display',{'form':form_el});
                    // Prepare the form
                    _Prepare_Form(form_el);
                    // Form load actions
                    vcff_do_action('form_event_form_after_display',{'form':form_el});
                }
                // If there are alerts
                if (typeof response.alerts != "undefined") {
                    // Retrieve the alerts el
                    var alerts_el = $(response.alerts);
                    // If there are alerts then populate
                    $(modal_el).find('.event-alerts').show().empty().append(alerts_el);
                }
                // If the response was not what we needed
                if (response.result == 'success') { 
                    // Form load actions
                    vcff_do_action('form_event_after_save',{'form':form_el});
                    // Hide the model
                    $(modal_el).modal('hide');
                    
                    Event_List_Obj._Refresh_List();
                }
            },'json');
        });
    }
    
    var _Prepare_Form = function(form_el) {
        // Form load actions
        vcff_do_action('form_event_on_save',{'form':form_el});
        // A snippet to allow auto generation of machine names
        $(form_el).find('.event-label').change(function(){
            // If there is a machine name, leave it alone
            if ($(form_el).find('.machine-name').val()) { return true; }
            // Calculate the machine name value
            var val = $(this).val().replace(/ /g,"_").replace(/[^a-zA-Z0-9-_]+/ig,"").toLowerCase();
            // Set the val
            $(form_el).find('.machine-name').val(val);
        });
        // Control what characters are used as machine names
        $(form_el).find('.machine-name').keyup(function(){
            // Calculate the machine name value
            var val = $(this).val().replace(/[^a-zA-Z0-9-_]+/ig,"").toLowerCase();
            // Set the val
            $(this).val(val);
        });
        // What happens if the event type is updated
        var _Event_Type_Update = function() {
            // Retrieve the selected event
            var selected_event = $(form_el).find('.select-event').val();
            // Each each of the events
            $(form_el).find('.event-item').hide();
            // Show the selected events
            $(form_el).find('[data-event-code="'+selected_event+'"]').show();
        }
        // Append the select event to the el
        $(form_el).find('.select-event').change(function(){ _Event_Type_Update(); });
        // Perform the field update
        _Event_Type_Update();
        // What happens if the trigger type is updated
        var _Trigger_Type_Update = function() {
            // Retrieve the selected trigger
            var selected_trigger = $(form_el).find('.select-trigger').val();
            // Each each of the events
            $(form_el).find('.trigger-item').hide();
            // Show the selected events
            $(form_el).find('[data-trigger-code="'+selected_trigger+'"]').show();
        }
        // Append the select trigger
        $(form_el).find('.select-trigger').change(function(){ _Trigger_Type_Update(); });
        // Perform the initial field update
        _Trigger_Type_Update();
        // Apply the vcff tagger
        $(form_el).find('.vcff-tag-editor').VCFFTagger();
    }
 
    _Get_Form();

}

/**
 * 
 * 
 */
 
vcff_add_action('form_event_on_form_load',function(args){ 
    
    var form_obj = args.form;
    // Retrieve jQuery from the global space
    var $ = window.jQuery;
    // An inc to help us give rules an id
    var cc_i = 0; var bcc_i = 0; var to_i = 0;

    var Add_TO_Item = function() {
        // Retrieve the handlebars template element
        var tmpl_src = $("#event_email_to").html();
        // Compile the handlebars template
        var tmpl_cmp = Handlebars.compile(tmpl_src);
        // Create a new instance of our template
        var new_item = $(tmpl_cmp({
            'i':to_i
        }));
        // Append to the 
        $(form_obj).find('.to-address-list-items').append(new_item);
        // Prepare the rule's events
        Prepare_TO_Item(new_item);
        // Inc up the i var
        to_i++;
    };

    var Prepare_TO_Item = function(to_item) {
        // Toggle the source
        var Toggle_Source = function() {
            // Retrieve the selected source value
            var source_val = $(to_item).find('.item-source').val();
            // If the source is to be entered
            if (source_val == 'entered') {
                // Hide the item field input
                $(to_item).find('.item-field').hide();
                // Show the item address field
                $(to_item).find('.item-address').show();
            } // Otherwise if the source is to be selected 
            else {
                // Show the item field input
                $(to_item).find('.item-field').show();
                // Hide the item address field
                $(to_item).find('.item-address').hide();
            } 
        };
        // Check existing items
        var Check_Existing = function() { 
            // If there is only one address item
            if ($(form_obj).find('.to-item').length === 1) {
                // Hide all remove links
                $(form_obj).find('.to-item').find('.item-remove').hide(); 
            } // Otherwise if there are multiple 
            else { $(form_obj).find('.to-item').find('.item-remove').show();  }
        }
        // Event handling of item source selection
        $(to_item).find('.item-source').change(function(e){ Toggle_Source(); });
        // Toggle the source based on current value
        Toggle_Source();
        // Add the new item event
        $(to_item).find('.item-add').click(function(e){ 
            // Prevent the default browser click event
            e.preventDefault();
            // Trigger the add callback
            Add_TO_Item();
        });
        // Add the remove item event
        $(to_item).find('.item-remove').click(function(e){
            // Prevent the default browser click event
            e.preventDefault();
            // Remove the field item object
            $(to_item).remove(); 
            // Check the existing items
            Check_Existing();
        });
        // Check existing items
        Check_Existing();
    };
    // Loop through any existing rules
    $(form_obj).find('.to-item').each(function(){
        // Prepare the rule's events
        Prepare_TO_Item($(this));
        // Inc up the i var
        to_i++;
    });
    // If there are no conditional rules
    if ($(form_obj).find('.to-item').length == 0) {
        // Add a new rule item
        Add_TO_Item();
    }
    
    var Add_CC_Item = function() {
        // Retrieve the handlebars template element
        var tmpl_src = $("#event_email_cc").html();
        // Compile the handlebars template
        var tmpl_cmp = Handlebars.compile(tmpl_src);
        // Create a new instance of our template
        var new_item = $(tmpl_cmp({
            'i':cc_i
        }));
        // Append to the 
        $(form_obj).find('.cc-address-list-items').append(new_item);
        // Prepare the rule's events
        Prepare_CC_Item(new_item);
        // Inc up the i var
        cc_i++;
    };

    var Prepare_CC_Item = function(cc_item) {
        // Toggle the source
        var Toggle_Source = function() {
            // Retrieve the selected source value
            var source_val = $(cc_item).find('.item-source').val();
            // If the source is to be entered
            if (source_val == 'entered') {
                // Hide the item field input
                $(cc_item).find('.item-field').hide();
                // Show the item address field
                $(cc_item).find('.item-address').show();
            } // Otherwise if the source is to be selected 
            else {
                // Show the item field input
                $(cc_item).find('.item-field').show();
                // Hide the item address field
                $(cc_item).find('.item-address').hide();
            } 
        };
        // Check existing items
        var Check_Existing = function() {
            // If there is only one address item
            if ($(form_obj).find('.cc-item').length == 1) {
                // Hide all remove links
                $(form_obj).find('.cc-item').find('.item-remove').hide();
            } // Otherwise if there are multiple 
            else { $(form_obj).find('.cc-item').find('.item-remove').show(); }
        }
        // Event handling of item source selection
        $(cc_item).find('.item-source').change(function(e){ Toggle_Source(); });
        // Toggle the source based on current value
        Toggle_Source();
        // Add the new item event
        $(cc_item).find('.item-add').click(function(e){ 
            // Prevent the default browser click event
            e.preventDefault();
            // Trigger the add callback
            Add_CC_Item();
        });
        // Add the remove item event
        $(cc_item).find('.item-remove').click(function(e){
            // Prevent the default browser click event
            e.preventDefault();
            // Remove the field item object
            $(cc_item).remove(); 
            // Check the existing items
            Check_Existing();
        });
        // Check existing items
        Check_Existing();
    };
    
    // Loop through any existing rules
    $(form_obj).find('.cc-item').each(function(){
        // Prepare the rule's events
        Prepare_CC_Item($(this));
        // Inc up the i var
        cc_i++;
    });
    // If there are no conditional rules
    if ($(form_obj).find('.cc-item').length == 0) {
        // Add a new rule item
        Add_CC_Item();
    }
    
    var Add_BCC_Item = function() {
        // Retrieve the handlebars template element
        var tmpl_src = $("#event_email_bcc").html();
        // Compile the handlebars template
        var tmpl_cmp = Handlebars.compile(tmpl_src);
        // Create a new instance of our template
        var new_item = $(tmpl_cmp({
            'i':to_i
        }));
        // Append to the 
        $(form_obj).find('.bcc-address-list-items').append(new_item);
        // Prepare the rule's events
        Prepare_BCC_Item(new_item);
        // Inc up the i var
        bcc_i++;
    };
    
    
    var Prepare_BCC_Item = function(bcc_item) {
        // Toggle the source
        var Toggle_Source = function() {
            // Retrieve the selected source value
            var source_val = $(bcc_item).find('.item-source').val();
            // If the source is to be entered
            if (source_val == 'entered') {
                // Hide the item field input
                $(bcc_item).find('.item-field').hide();
                // Show the item address field
                $(bcc_item).find('.item-address').show();
            } // Otherwise if the source is to be selected 
            else {
                // Show the item field input
                $(bcc_item).find('.item-field').show();
                // Hide the item address field
                $(bcc_item).find('.item-address').hide();
            } 
        };
        // Check existing items
        var Check_Existing = function() {
            // If there is only one address item
            if ($(form_obj).find('.bcc-item').length == 1) {
                // Hide all remove links
                $(form_obj).find('.bcc-item').find('.item-remove').hide();
            } // Otherwise if there are multiple 
            else { $(form_obj).find('.bcc-item').find('.item-remove').show(); }
        }
        // Event handling of item source selection
        $(bcc_item).find('.item-source').change(function(e){ Toggle_Source(); });
        // Toggle the source based on current value
        Toggle_Source();
        // Add the new item event
        $(bcc_item).find('.item-add').click(function(e){ 
            // Prevent the default browser click event
            e.preventDefault();
            // Trigger the add callback
            Add_BCC_Item();
        });
        // Add the remove item event
        $(bcc_item).find('.item-remove').click(function(e){
            // Prevent the default browser click event
            e.preventDefault();
            // Remove the field item object
            $(bcc_item).remove(); 
            // Check the existing items
            Check_Existing();
        });
        // Check existing items
        Check_Existing();
    };
    // Loop through any existing rules
    $(form_obj).find('.bcc-item').each(function(){
        // Prepare the rule's events
        Prepare_BCC_Item($(this));
        // Inc up the i var
        bcc_i++;
    });
    // If there are no conditional rules
    if ($(form_obj).find('.bcc-item').length == 0) {
        // Add a new rule item
        Add_BCC_Item();
    }
});


vcff_add_action('form_event_on_form_load',function(args){ 
    
    var form_obj = args.form;
    // Retrieve jQuery from the global space
    var $ = window.jQuery;
    
    var _i = 0;
    
    // Retrieve the validation parameter element
    var _container = $('.trigger-conditional');
    // If there is a validation parameter present
    if ($(_container).length == 0) { return false; }
    
    function _Init() {
    
        if (typeof vcff_trigger_conditions_val == "object") {
            
            if (typeof vcff_trigger_conditions_val.rules == "object") {
                
                $.each(vcff_trigger_conditions_val.rules,function(i,item){ _Add_Item(item); });
            }
        }
        
        if ($(_container).find('.conditions-item').length == 0) {
            // Retrieve any saved data
            _Add_Item({});
        }
        
        // If there is only onevalidation line
        if ($(_container).find('.conditions-item').length == 1) {
            // Hide all remove links
            $(_container).find('.item-remove').hide();
        } // Otherwise if there are multiple 
        else { $(_container).find('.item-remove').show(); }
    }
    
    function _Add_Item(item_data) { 
        // Retrieve the handlebars template element
        var template_src = $("#trigger_item_conditional_rule").html();
        // Compile the handlebars template
        var template_compiled = Handlebars.compile(template_src);
        // Create a new instance of our template
        var item_el = $(template_compiled({'i':_i}));
        // Perform a related action
        vcff_do_action('vcff_params_validation_init_item',{'item':item_el,'container':_container});
        // Append to the 
        $(_container).find('.conditional-items').append(item_el); 
        // Prepare the item
        _Prepare(item_el,item_data);
        // Inc up the i var
        _i++;
    }
    
    function _Prepare(item_el,item_data) {
    
        _Prepare_Elements(item_el,item_data);
        
        _Prepare_Conditions(item_el,item_data);
        
        _Prepare_Values(item_el,item_data);
        // Append the add event
        $(item_el).find('.item-add').click(function(e){
            // Add a new validation line
            _Add_Item({}); 
            // Perform a related action
            vcff_do_action('vcff_params_validation_add',{'item':item_el,'container':_container});
            // Prevent the default action
            e.preventDefault(); 
        });
        // Append the remove event
        $(item_el).find('.item-remove').click(function(e){
            // Prevent the default browser click action
            e.preventDefault();
            // Remove the template from the settings list
            $(item_el).remove(); 
            // If there is only onevalidation line
            if ($(_container).find('.conditions-item').length == 1) {
                // Hide all remove links
                $(_container).find('.item-remove').hide();
            } // Otherwise if there are multiple 
            else { $(_container).find('.item-remove').show(); }
            // Perform a related action
            vcff_do_action('vcff_params_validation_remove',{'item':item_el,'container':_container});
        });
    }
    
    function _Prepare_Elements(item_el,item_data) {
        
        var element_field_el = $(item_el).find('.item-element');
        
        $.each(vcff_trigger_conditions_fields,function(i,item){
            
            $(element_field_el).append('<option value="'+i+'">'+i+'</option>');
        });
        
        console.log(item_data);
        
        if (typeof item_data.machine_code != 'undefined') {
            
            $(element_field_el).val(item_data.machine_code);
        }
    }
    
    function _Prepare_Conditions(item_el,item_data) {
        
        var element_field_el = $(item_el).find('.item-element');
        
        var rules_field_el = $(item_el).find('.item-rules');

        function __Do() {
            
            var val = $(element_field_el).val();
            
            $(rules_field_el).empty();
            
            if (!val || val == "") { $(rules_field_el).hide().val(""); return false; } 
            
            var _el_data = vcff_trigger_conditions_fields[val];
            
            $(rules_field_el).show().append('<option value="">Select A Rule</option>');

            $.each(_el_data.logic_rules,function(i,item){

                $(rules_field_el).append('<option value="'+item.machine_code+'">'+item.title+'</option>');
            });
        };
        
        $(element_field_el).change(function(){
            __Do();
        });
        
        if (typeof item_data.code != 'undefined') {
            __Do();
            $(rules_field_el).val(item_data.code);
        }
    }
    
    function _Prepare_Values(item_el,item_data) {
        
        var element_field_el = $(item_el).find('.item-element');
        
        var rules_field_el = $(item_el).find('.item-rules');
        
        $(element_field_el).change(function(){
        
            if ($(this).val()) { return false; }
            
            $(item_el).find('.col-value').empty();  
        });
        
        function __Do() {
            
            var val = $(rules_field_el).val();
            
            var element_field_val = $(element_field_el).val();
            
            $(item_el).find('.col-value').empty();  
            
            if (!val || val == "") { return false; } 
            
            var _el = vcff_trigger_conditions_fields[element_field_val];

            var _rule = false;
            
            $.each(_el.logic_rules,function(i,item) {
                
                if (item.machine_code != val) { return true; }
                
                _rule = item;
            });
 
            if (!_rule || !_rule.value) { return false; } 
            
            var __i = $(item_el).attr('data-item-i');
            
            var _name = 'event_action[triggers][conditional][rules][rule_'+__i+'][value]';
            
            if (typeof _rule.value == 'object') {
                
                var _sel_el = $('<select name="'+_name+'" class="item-value form-control">');
                
                $.each(_rule.value,function(i,item){
                
                    $(_sel_el).append('<option value="'+i+'">'+item+'</option>');
                });
                
                $(item_el).find('.col-value').append(_sel_el);

            } else {
                
                var _txt_el = $('<input name="'+_name+'" type="text" class="item-value form-control">');
                
                $(item_el).find('.col-value').append(_txt_el);
            }
        };
        
        $(rules_field_el).change(function(){

            __Do();
        });
        
        if (typeof item_data.value != 'undefined') {
            __Do();
            $(item_el).find('.item-value').val(item_data.value);
        }
        
    }
    
    // Initiate 
    _Init();
    
});