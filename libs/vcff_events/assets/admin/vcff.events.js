
if (typeof VCFF_Events == "undefined") { var VCFF_Events = []; }

if (typeof VCFF_Triggers == "undefined") { var VCFF_Triggers = []; }

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


var Event_Settings = function(meta_box) {
    
    var $ = window.jQuery;
    
    //
    
    var _events_overview = $(meta_box).find('.events-overview');
    var _events_form = $(meta_box).find('.events-form'); 
    
    $(meta_box).find('.create-event').click(function(e){

        e.preventDefault();

        $('#vcff_meta_model').modal({'backdrop':false});
    });
    
    $(meta_box).find('.force-refresh').click(function(e){

        e.preventDefault();

        Load_List();
    });
    
    var Show_Event_Loading = function() {
        
        $(meta_box).find('.event-loading').show();
    }
    
    var Hide_Event_Loading = function() {
        
        $(meta_box).find('.event-loading').hide();
    }
    
    var Load_List = function(){
        // Serialise the post form
        var post_form = $("#post").serialize();
        // Empty the alerts
        $(meta_box).find('.event-alerts').hide().empty();
        
        $(_events_overview).show();
        $(_events_form).empty().hide();
        // Show the event loading message
        Show_Event_Loading();
        // Post the data to the webservice
        $.post(ajaxurl,{
            // Target the events form
            'action':'vcff_action_list',
            // Encode the data using base64
            'form_data':base64.encode(post_form)
        }, // Handle the response
        function(response){ 
            // Hide the event loading message
            Hide_Event_Loading();
            // If the response was not what we needed
            if (typeof response != "object") { return false; }
            // If there are alerts
            if (typeof response.alerts != "undefined") {
                // If there are alerts then populate
                $(meta_box).find('.event-alerts').show().empty().html(response.alerts);
            }
            // If the response was not what we needed
            if (response.result != 'success') { return false; }
            // Retrieve the convert the js form html
            var list_obj = $(response.data);
            // Append the form
            $(meta_box).find('.event-list').empty().html(list_obj);
            
            List_Actions();
            
        },'json');
    };
    
    Load_List();

    var List_Actions = function() {
        // Retrieve the master input
        var _master_inputs = $(_events_overview).find('#x_1,#x_2');
        // Retrieve the child inputs
        var _child_inputs = $(_events_overview).find('.event-toggle');
        // Retrieve the child items
        var _child_items = $(_events_overview).find('.event-row');
        
        $(_master_inputs).change(function(){ 
            if ($(this).is(':checked')) { 
                $(_master_inputs).prop('checked',true); 
                $(_child_inputs).prop('checked',true); 
            } else {
                $(_master_inputs).prop('checked',false); 
                $(_child_inputs).prop('checked',false); 
            }
        });
        
        $(_events_overview).find('.action-btn').click(function(e){
        
            e.preventDefault();
        
            _Handle_List_Actions();
        });
        
        // Loop through each action id
        $(_child_items).each(function(){
            
            var _item = $(this);
            
            var _action_id = $(this).attr('data-action-id');
            
            $(_item).find('.move-up').click(function(e){
                
                e.preventDefault();
                
                if ($(this).hasClass('move-disabled')) { return false; }
                
                var _prev = $(_item).prev();
                
                if ($(_prev).length > 0) { 
                    
                    $(_prev).before(_item); 
                    
                    _Check_Event_Items();
                }
            });
            
            $(_item).find('.move-down').click(function(e){
                
                e.preventDefault();
                
                if ($(this).hasClass('move-disabled')) { return false; }
                
                var _next = $(_item).next();
                
                if ($(_next).length > 0) { 
                
                    $(_next).after(_item); 
                
                    _Check_Event_Items();
                }
            });
            
            $(_item).find('.edit-action').click(function(e){

                e.preventDefault();
                
                Update_Action_Form(_action_id);
            });
            
        });
        
        var _xhr = {};
        
        var _Check_Event_Items = function() {
            
            if ($(_events_overview).find('.event-row').length <= 1) { 
                
                $(_events_overview).find('.event-row').find('.move-link').hide();
                
                return false; 
            }
            
            $(_events_overview).find('.move-disabled').removeClass('move-disabled');
            
            var _first = $(_events_overview).find('.event-row').first();
            
            $(_first).find('.move-up').addClass('move-disabled');
            
            var _last = $(_events_overview).find('.event-row').last();
            
            $(_last).find('.move-down').addClass('move-disabled');
            
            var _form_id = $('#post_ID').val();
            
            var _event_ids = [];
            
            // Loop through each action id
            $(_events_overview).find('.event-row').each(function(){
                
                var _action_id = $(this).attr('data-action-id');
                
                if (_action_id == '') { return true; }
                
                _event_ids.push(_action_id);
            });
            
            if (typeof _xhr.abort != "undefined") { _xhr.abort(); }
            // Empty the alerts
            $(meta_box).find('.event-alerts').hide().empty();
            // Show the event loading message
            Show_Event_Loading();
            // Post the data to the webservice
            $.post(ajaxurl,{
                // Target the events form
                'action':'vcff_action_list_ordering',
                'form_id':_form_id,
                'action_list':_event_ids
            }, // Handle the response
            function(response){ 
                // Hide the event loading message
                Hide_Event_Loading();
                // Reset the xhr object
                _xhr = {};
                // If the response was not what we needed
                if (typeof response != "object") { return false; }
                // If there are alerts
                if (typeof response.alerts != "undefined") {
                    // If there are alerts then populate
                    $(meta_box).find('.event-alerts').show().empty().html(response.alerts);
                }
                // If the response was not what we needed
                if (response.result != 'success') { return false; }
            });
        }
        
        var _Handle_List_Actions = function() {
            // Retrieve the form id
            var _form_id = $('#post_ID').val();
            // Create the event id list
            var _event_ids = [];
            // Remove any pending action items
            $(_events_overview).find('.pending-action').removeClass('pending-action');
            // Loop through each action id
            $(_events_overview).find('.event-row').each(function(){
                // Retrieve the child inputs
                var _toggle = $(this).find('.event-toggle');
                // If the toggle is not checked
                if (!$(_toggle).is(':checked')) { return true; }
                // Retrieve the action id
                var _action_id = $(this).attr('data-action-id');
                // Push into the event id list
                _event_ids.push(_action_id);
                // Add the pending action class
                $(this).addClass('pending-action');
            });
            // If there are no pending actions, return out
            if ($(_events_overview).find('.pending-action').children().length == 0) { return false; }
            // Retrieve the action code
            var _action_type = $(_events_overview).find('.action-type').val();
            // If we are trying to bulk delete
            if (_action_type == 'delete') {
                // Prompt the user for a confirmation
                if (!confirm('Are you sure you wish to delete these events')) { return false; }
                // If a xhr request is currently being performed
                // Cancel it, stops repeat event results
                if (typeof _xhr.abort != "undefined") { _xhr.abort(); }
                // Show the event loading message
                Show_Event_Loading();
                // Post the data to the webservice
                $.post(ajaxurl,{
                    // Target the events form
                    'action':'vcff_action_list_delete',
                    'form_id':_form_id,
                    'action_list':_event_ids
                }, // Handle the response
                function(response){ 
                    // Hide the event loading message
                    Hide_Event_Loading();
                    // Reset the xhr object
                    _xhr = {};
                    // If the response was not what we needed
                    if (typeof response != "object") { return false; }
                    // If there are alerts
                    if (typeof response.alerts != "undefined") {
                        // If there are alerts then populate
                        $(meta_box).find('.event-alerts').show().empty().html(response.alerts);
                    }
                    // If the response was not what we needed
                    if (response.result != 'success') { return false; }
                    // Remove all pending action items
                    $(_events_overview).find('.pending-action').remove();
                });
            }
            // Form load actions
            vcff_do_action('form_event_form_do_bulk_action',{'form_id':_form_id,'event_ids':_event_ids,'action_type':_action_type});
        }
    }

    // Create a new action form
    var Create_Action_Form = function(){
        // Clear the current form
        Cancel_Action_Form();
        // Form load actions
        vcff_do_action('form_event_form_before_display',{});
        // Empty the alerts
        $(meta_box).find('.event-alerts').hide().empty();
        // Serialise the post form
        var post_form = $("#post").serialize();
        $(_events_overview).hide();
        $(_events_form).show();
        // Show the event loading message
        Show_Event_Loading();
        // Post the data to the webservice
        $.post(ajaxurl,{
            // Target the events form
            'action':'vcff_action_form_new',
            // Encode the data using base64
            'form_data':base64.encode(post_form)
        }, // Handle the response
        function(response){ 
            // Hide the event loading message
            Hide_Event_Loading();
            // If the response was not what we needed
            if (typeof response != "object") { return false; }
            // If there are alerts
            if (typeof response.alerts != "undefined") {
                // If there are alerts then populate
                $(meta_box).find('.event-alerts').show().empty().html(response.alerts);
            }
            // If the response was not what we needed
            if (response.result == 'success') {
                // Retrieve the convert the js form html
                var form_obj = $(response.data.form);
                // Append the form
                $(_events_form).empty().html(form_obj);
                // Form load actions
                vcff_do_action('form_event_form_on_display',{'form':form_obj,'response':response});
                // Prepare the loaded form
                Prepare_Form(form_obj);
                // Form load actions
                vcff_do_action('form_event_form_after_display',{'form':form_obj,'response':response});
            }
        },'json');
    };
    
    // Create a new update action form
    var Update_Action_Form = function(action_id) {
        // Clear the current form
        Cancel_Action_Form();
        // Form load actions
        vcff_do_action('form_event_form_before_display',{'id':action_id});
        
        $(_events_overview).hide();
        $(_events_form).show();
        // Serialise the post form
        var post_form = $("#post").serialize();
        // Empty the alerts
        $(meta_box).find('.event-alerts').hide().empty();
        // Show the event loading message
        Show_Event_Loading();
        // Post the data to the webservice
        $.post(ajaxurl,{
            // Target the events form
            'action':'vcff_action_form_update',
            // The action id
            'action_id':action_id,
            // Encode the data using base64
            'form_data':base64.encode(post_form)
        }, // Handle the response
        function(response){ 
            // Hide the event loading message
            Hide_Event_Loading();
            // If the response was not what we needed
            if (typeof response != "object") { return false; }
            // If there are alerts
            if (typeof response.alerts != "undefined") {
                // If there are alerts then populate
                $(meta_box).find('.event-alerts').show().empty().html(response.alerts);
            }
            // If the response was not what we needed
            if (response.result == 'success') {
                // Retrieve the convert the js form html
                var form_obj = $(response.data.form);
                // Append the form
                $(_events_form).empty().html(form_obj);
                // Form load actions
                vcff_do_action('form_event_form_on_display',{'id':action_id,'form':form_obj,'response':response});
                // Prepare the loaded form
                Prepare_Form(form_obj);
                // Form load actions
                vcff_do_action('form_event_form_after_display',{'id':action_id,'form':form_obj,'response':response});
            }
        },'json');
    } 
    
    // Cancel an existing action form
    var Cancel_Action_Form = function() {
        // Retrieve the form object
        var form_obj = $(_events_form).children().first();
        // Form load actions
        vcff_do_action('form_event_before_form_cancel',{'form':form_obj});
        // Cancel the form
        $(_events_overview).show();
        // remove the form
        $(_events_form).empty().hide();
        // Form load actions
        vcff_do_action('form_event_on_form_cancel',{'form':form_obj});
        // Form load actions
        vcff_do_action('form_event_after_form_cancel',{'form':form_obj});
    }
    
    // Prepare an action form
    var Prepare_Form = function(form_obj) {
    
        // Update an action 
        var _Update_Action = function() {
            // Form load actions
            vcff_do_action('form_event_before_update',{'form':form_obj});
            // Serialise the post form
            var post_form = $("#post").serialize();
            // Empty the alerts
            $(meta_box).find('.event-alerts').hide().empty();
            // Show the event loading message
            Show_Event_Loading();
            // Post the data to the webservice
            $.post(ajaxurl,{
                // Target the events form
                'action':'vcff_action_update',
                // Encode the data using base64
                'form_data':base64.encode(post_form)
            }, // Handle the response
            function(response){ 
                // Hide the event loading message
                Hide_Event_Loading();
                // If the response was not what we needed
                if (typeof response != "object") { return false; }
                // If there are alerts
                if (typeof response.alerts != "undefined") {
                    // If there are alerts then populate
                    $(meta_box).find('.event-alerts').show().empty().html(response.alerts);
                }
                // If we have new form data to show
                if (typeof response.data != "undefined" && typeof response.data.form != "undefined") {
                    // Retrieve the convert the js form html
                    var form_obj = $(response.data.form);
                    // Append the form
                    $(_events_form).empty().html(form_obj);
                    // Prepare the loaded form
                    Prepare_Form(form_obj);
                } 
                // Form load actions
                vcff_do_action('form_event_on_update',{'form':form_obj,'response':response});
                // If the response was not what we needed
                if (response.result == 'success') { 
                    // Clear the action form
                    Cancel_Action_Form();
                    // Reload the submission
                    Load_List();
                }
                // Form load actions
                vcff_do_action('form_event_after_update',{'form':form_obj,'response':response});
            },'json');
        }
        
        // Create an action
        var _Create_Action = function() {
            // Form load actions
            vcff_do_action('form_event_before_create',{'form':form_obj});
            // Serialise the post form
            var post_form = $("#post").serialize();
            $(_events_overview).show();
            $(_events_form).empty().hide();
            // Empty the alerts
            $(meta_box).find('.event-alerts').hide().empty();
            // Show the event loading message
            Show_Event_Loading();
            // Post the data to the webservice
            $.post(ajaxurl,{
                // Target the events form
                'action':'vcff_action_create',
                // Encode the data using base64
                'form_data':base64.encode(post_form)
            }, // Handle the response
            function(response){ 
                // Hide the event loading message
                Hide_Event_Loading();
                // If the response was not what we needed
                if (typeof response != "object") { return false; }
                // If there are alerts
                if (typeof response.alerts != "undefined") {
                    // If there are alerts then populate
                    $(meta_box).find('.event-alerts').show().empty().html(response.alerts);
                }
                // If we have new form data to show
                if (typeof response.data != "undefined" && typeof response.data.form != "undefined") {
                    // Retrieve the convert the js form html
                    var form_obj = $(response.data.form);
                    // Append the form
                    $(_events_form).empty().html(form_obj);
                    // Prepare the loaded form
                    Prepare_Form(form_obj);
                } 
                // Form load actions
                vcff_do_action('form_event_on_create',{'form':form_obj,'response':response});
                // If the response was not what we needed
                if (response.result == 'success') { 
                    // Clear the action form
                    Cancel_Action_Form();
                    // Reload the submission
                    Load_List();
                }
                // Form load actions
                vcff_do_action('form_event_after_create',{'form':form_obj,'response':response});
            },'json');
        }

        var _Event_Field_Update = function() {
        
            var selected_event = $(form_obj).find('.select-event').val();

            $(form_obj).find('.event-item').hide();

            $(form_obj).find('[data-event-code="'+selected_event+'"]').show();
        }

        var _Trigger_Field_Update = function() {
        
            var selected_trigger = $(form_obj).find('.select-trigger').val();

            $(form_obj).find('.trigger-item').hide();

            $(form_obj).find('[data-trigger-code="'+selected_trigger+'"]').show();
        }
        
        _Event_Field_Update();
        
        _Trigger_Field_Update();
        
        $(form_obj).find('.vcff-tag-editor').VCFFTagger();
        
        $(form_obj).find('.select-event').change(function(){ _Event_Field_Update(); });
        
        $(form_obj).find('.select-trigger').change(function(){ _Trigger_Field_Update(); });
        
        $(form_obj).find('.btn-create').click(function(e){ 
            
            e.preventDefault();
            
            _Create_Action(); 
        });
        
        // Append the update event to the update button
        $(form_obj).find('.btn-update').click(function(e){ 
            // Prevent the default action
            e.preventDefault();
            
            _Update_Action();
        });
        
        $(form_obj).find('.btn-cancel').click(function(e){ 
            // Prevent the default action
            e.preventDefault();
            // Cancel the action form
            Cancel_Action_Form();
        });
        // Form load actions
        vcff_do_action('form_event_on_form_load',{'form':form_obj});
    }

    // Delete an action item
    var Delete_Action = function(action_id) {
        // Serialise the post form
        var post_form = $("#post").serialize();
        // Empty the alerts
        $(meta_box).find('.event-alerts').hide().empty();
        // Show the event loading message
        Show_Event_Loading();
        // Post the data to the webservice
        $.post(ajaxurl,{
            // Target the events form
            'action':'vcff_action_delete',
            // The action id
            'action_id':action_id,
            // Encode the data using base64
            'form_data':base64.encode(post_form)
        }, // Handle the response
        function(response){ 
            // Hide the event loading message
            Hide_Event_Loading();
            // If the response was not what we needed
            if (typeof response != "object") { return false; }
            // If there are alerts
            if (typeof response.alerts != "undefined") {
                // If there are alerts then populate
                $(meta_box).find('.event-alerts').show().empty().html(response.alerts);
            }
            // If the response was not what we needed
            if (response.result != 'success') { return false; }

            Load_List();
        },'json');
    }

};


var vcff_prepare_form = function(action_id,form_el){

    var $ = window.jQuery;
    // Form load actions
    vcff_do_action('form_event_form_on_display',{'id':action_id,'form':form_el});
    
    var _Event_Field_Update = function() {

        var selected_event = $(form_el).find('.select-event').val();

        $(form_el).find('.event-item').hide();

        $(form_el).find('[data-event-code="'+selected_event+'"]').show();
    }

    var _Trigger_Field_Update = function() {

        var selected_trigger = $(form_el).find('.select-trigger').val();

        $(form_el).find('.trigger-item').hide();

        $(form_el).find('[data-trigger-code="'+selected_trigger+'"]').show();
    }

    _Event_Field_Update();

    _Trigger_Field_Update();

    $(form_el).find('.vcff-tag-editor').VCFFTagger();

    $(form_el).find('.select-event').change(function(){ _Event_Field_Update(); });

    $(form_el).find('.select-trigger').change(function(){ _Trigger_Field_Update(); });
    // Form load actions
    vcff_do_action('form_event_form_after_display',{'id':action_id,'form':form_el});
    // Form load actions
    vcff_do_action('form_event_on_form_load',{'form':form_el});
};

// Email Event Type
vcff_add_action('form_event_on_form_load',function(args){ 
    
    var form_obj = args.form;
    // Retrieve jQuery from the global space
    var $ = window.jQuery;
    // An inc to help us give rules an id
    var bcc_i = 0; var to_i = 0;

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
            if ($(form_obj).find('.to-item').length == 1) {
                // Hide all remove links
                $(form_obj).find('.to-item').find('.item-remove').hide();
            } // Otherwise if there are multiple 
            else { $(form_obj).find('.to-item').find('.item-remove').show(); }
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

// Conditional Trigger Type
vcff_add_action('form_event_on_form_load',function(args){ 
    
    var form_obj = args.form;
    
    // Retrieve jQuery from the global space
    var $ = window.jQuery;
    // An inc to help us give rules an id
    var rule_i = 0;
    // Dynamically add a new rule item
    var Add_Rule = function() {
        // Retrieve the handlebars template element
        var tmpl_src = $("#trigger_item_conditional_rule").html();
        // Compile the handlebars template
        var tmpl_cmp = Handlebars.compile(tmpl_src);
        // Create a new instance of our template
        var new_item = $(tmpl_cmp({
            'i':rule_i
        }));
        // Append to the 
        $(form_obj).find('.conditional-rules').append(new_item);
        // Prepare the rule's events
        Prepare_Rule(new_item);
        // Inc up the i var
        rule_i++;
        // If there is only onevalidation line
        if ($(form_obj).find('.conditional-rule').length == 1) {
            // Hide all remove links
            $(form_obj).find('.item-remove').hide();
        } // Otherwise if there are multiple 
        else { $(form_obj).find('.item-remove').show(); }
    }
    // Prepare the rule item
    var Prepare_Rule = function(rule_obj) {
        // Append the add event
        $(rule_obj).find('.item-add').click(function(e){ 
            // Add a new line
            Add_Rule();
            // Prevent the default browser click event
            e.preventDefault();
        });
        // Append the remove event
        $(rule_obj).find('.item-remove').click(function(e){
            // Remove the template from the settings list
            $(rule_obj).remove(); 
            // Prevent the default browser click event
            e.preventDefault();
            // If there is only onevalidation line
            if ($(form_obj).find('.conditional-rule').length == 1) {
                // Hide all remove links
                $(form_obj).find('.item-remove').hide();
            } // Otherwise if there are multiple 
            else { $(form_obj).find('.item-remove').show(); }
        });
    }
    // Loop through any existing rules
    $(form_obj).find('.conditional-rule').each(function(){
        // Prepare the rule's events
        Prepare_Rule($(this));
        // Inc up the i var
        rule_i++;
    });
    // If there are no conditional rules
    if ($(form_obj).find('.conditional-rule').length == 0) {
        // Add a new rule item
        Add_Rule();
    }
    // If there is only one conditional rule
    if ($(form_obj).find('.conditional-rule').length == 1) {
        // Hide all remove links
        $(form_obj).find('.item-remove').hide();
    } // Otherwise if there are multiple 
    else { $(form_obj).find('.item-remove').show(); }
});