var Event_List = function(meta_box) {
    
    var $ = window.jQuery;
    
    var _self = this;
    
    var event_list_el = $(meta_box).find('.event-list');
    
    var event_loading_el = $(meta_box).find('.alert-loading');
    
    _self._Load_List = function() {
        // Serialise the post form
        var post_form = $("#post").serialize();
        // Show the event loading message
        event_loading_el.show();
        // Form load actions
        vcff_do_action('form_event_list_before_load',{'meta_box_el':meta_box,'meta_list_obj':_self});
        // Post the data to the webservice
        $.post(ajaxurl,{
            // Target the events form
            'action':'vcff_events_list',
            'form_data':base64.encode(post_form)
        }, // Handle the response
        function(response){ 
            // Hide the event loading message
            event_loading_el.hide();
            // Create the list element
            var list_el = $(response);
            // Empty the list container
            event_list_el.empty();
            // Populate with the response
            event_list_el.append(list_el);
            // Form load actions
            vcff_do_action('form_event_list_load',{'meta_box_el':meta_box,'meta_list_obj':_self,'meta_list_el':list_el});
            // Prepare the list
            _self._Prepare_List();
        });
    }

    _self._Refresh_List = function() {
        
        _self._Load_List();
    }
    
    _self._Prepare_List = function() {
        // Form load actions
        vcff_do_action('form_event_list_prepare',{'meta_box_el':meta_box,'meta_list_obj':_self});
        // Create a new event action
        $(meta_box).find('.create-event').click(function(e){
            // Prevent the default action
            e.preventDefault();
            // Create a new event form
            new Modify_Event_Form();
        });
        
        $(meta_box).find('.bulk-btn').click(function(e){
            // Prevent the default action
            e.preventDefault();
            // Create a new event form
            new Event_Action_Bulk(meta_box,_self);
        });
        // Loop through each event row
        $(meta_box).find('.event-row').each(function(){
            // Retrieve the row element
            var event_row_el = $(this);
            // Retrieve the action ID
            var action_id = $(event_row_el).attr('data-action-id');
            // What happens when we edit an action
            $(event_row_el).find('.edit-action').click(function(e){
                // Prevent the default action
                e.preventDefault();
                // Create a new event form
                new Modify_Event_Form(action_id);
            });
            // What happens when we edit an action
            $(event_row_el).find('.delete-action').click(function(e){
                // Confirm the removal
                if (!confirm('Are you sure you wish to remove this event?')) { return false; }
                // Prevent the default action
                e.preventDefault();
                // Create a new event form
                new Event_Action_Delete(action_id,event_row_el,meta_box);
            });
            // What happens when we edit an action
            $(event_row_el).find('.move-up').click(function(e){
                // Prevent the default action
                e.preventDefault();
                // Create a new event form
                new Event_Action_Move_Up(action_id,event_row_el,meta_box);
            });
            // What happens when we edit an action
            $(event_row_el).find('.move-down').click(function(e){
                // Prevent the default action
                e.preventDefault();
                // Create a new event form
                new Event_Action_Move_Down(action_id,event_row_el,meta_box);
            });
        });
    }
    // Do the initial load
    _self._Load_List();
    // Form load actions
    vcff_do_action('form_event_list_init',{'meta_box_el':meta_box,'meta_list_obj':_self});
    
    return _self;
};

var Event_Action_Bulk = function(_meta_el,_meta_obj) {
    // Retrieve jsquery
    var $ = window.jQuery;
    // Cache self
    var _self = this;
    // The event list element
    var event_list_el = $(_meta_el).find('.event-list');
    // The event loading element
    var event_loading_el = $(_meta_el).find('.alert-loading');
    // Create the event id list
    var _event_ids = [];
    // Loop through each action id
    $(_meta_el).find('.event-row').each(function(){
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
    // Serialise the post form
    var post_form = $("#post").serialize();
    // Show the event loading message
    event_loading_el.show();
    // Post the data to the webservice
    $.post(ajaxurl,{
        // Target the events form
        'action':'vcff_events_ajax_list',
        // The action id 
        'ajax_code':$('#post_id').val(),
        // The bulk action
        'ajax_action':'bulk',
        // The action id 
        'bulk_action':$('.bulk-type').val(),
        // The action id 
        'event_list':_event_ids,
        // Encode the data using base64
        'form_data':base64.encode(post_form)
    }, // Handle the response
           function(response){ 
        // Hide the event loading message
        event_loading_el.hide();
        // If the response was not what we needed
        if (typeof response != "object") { return false; }
        // If there are alerts
        if (typeof response.alerts != "undefined") {
            // Retrieve the alerts el
            var alerts_el = $(response.alerts);
            // If there are alerts then populate
            $(_meta_el).find('.event-alerts').show().empty().append(alerts_el);
        }
        // If the response was not what we needed
        if (response.result == 'success') { 
            // Refresh the event list
            Event_List_Obj._Refresh_List();
        }
    },'json');
}

var Event_Action_Delete = function(_id,_item_el,_meta_el) {
    // Retrieve jsquery
    var $ = window.jQuery;
    // Cache self
    var _self = this;
    // The event list element
    var event_list_el = $(_meta_el).find('.event-list');
    // The event loading element
    var event_loading_el = $(_meta_el).find('.alert-loading');
    // Serialise the post form
    var post_form = $("#post").serialize();
    // Show the event loading message
    event_loading_el.show();
    // Form load actions
    vcff_do_action('form_event_list_action_before_delete',{'form':_id,'item_el':_item_el,'meta_el':_meta_el});
    // Post the data to the webservice
    $.post(ajaxurl,{
        // Target the events form
        'action':'vcff_events_ajax_list',
        // The action id 
        'ajax_code':$('#post_id').val(),
        // The bulk action
        'ajax_action':'delete',
        // The action id 
        'action_id':_id,
        // Encode the data using base64
        'form_data':base64.encode(post_form)
    }, // Handle the response
    function(response){ 
        // Hide the event loading message
        event_loading_el.hide();
        // If the response was not what we needed
        if (typeof response != "object") { return false; }
        // If there are alerts
        if (typeof response.alerts != "undefined") {
            // Retrieve the alerts el
            var alerts_el = $(response.alerts);
            // If there are alerts then populate
            $(_meta_el).find('.event-alerts').show().empty().append(alerts_el);
        }
        // If the response was not what we needed
        if (response.result == 'success') { 
            // Remove the item element
            $(_item_el).fadeOut(550,function(){ 
                // Form load actions
                vcff_do_action('form_event_list_action_delete',{'form':_id,'item_el':_item_el,'meta_el':_meta_el});
                // Remove the item
                $(this).remove(); 
                // If no more event rows exist
                if ($(_meta_el).find('.event-row').length != 0) { return true; }
                // Empty the contents
                $(event_list_el).empty();
            });
        }
    },'json');
}

var _move_xhr = {};

var Event_Action_Move_Up = function(_id,_item_el,_meta_el) {
    // Retrieve jsquery
    var $ = window.jQuery;
    // Cache self
    var _self = this;
    // The event list element
    var event_list_el = $(_meta_el).find('.event-list');
    // If the move link has a disabled
    if ($(_item_el).find('.move-up').hasClass('move-disabled')) { return false; }
    // Retrieve the next el
    var _prev = $(_item_el).prev();
    // If the next el doesn't exist
    if ($(_prev).length > 0) { 
        // Move the item
        $(_prev).before(_item_el); 
        // The event list
        var _event_ids = []; 
        // Loop through each action id
        $(_meta_el).find('.event-row').each(function(){
            // Retrieve the action id
            var _action_id = $(this).attr('data-action-id');
            // If no action id, continue on
            if (_action_id != '') { _event_ids.push(_action_id); }
        });
        // Cancel any pending xhr request
        if (typeof _move_xhr.abort != "undefined") { _move_xhr.abort(); }
        // Serialise the post form
        var post_form = $("#post").serialize();
        // Form load actions
        vcff_do_action('form_event_list_action_before_move',{'form':_id,'item_el':_item_el,'meta_el':_meta_el,'event_list':_event_ids});
        // Post the data to the webservice
        $.post(ajaxurl,{
            // Target the events form
            'action':'vcff_events_ajax_list',
            // The action id 
            'ajax_code':$('#post_id').val(),
            // The bulk action
            'ajax_action':'move',
            // The action id 
            'event_list':_event_ids,
            // Encode the data using base64
            'form_data':base64.encode(post_form)
        }, // Handle the response
        function(response){ 
            // Reset the xhr object
            _move_xhr = {};
            // Form load actions
            vcff_do_action('form_event_list_action_move',{'form':_id,'item_el':_item_el,'meta_el':_meta_el,'event_list':_event_ids});
            // If the response was not what we needed
            if (typeof response != "object") { return false; }
        });
    }
}

var Event_Action_Move_Down = function(_id,_item_el,_meta_el) {
    // Retrieve jsquery
    var $ = window.jQuery;
    // Cache self
    var _self = this;
    // The event list element
    var event_list_el = $(_meta_el).find('.event-list');
    // If the move link has a disabled
    if ($(_item_el).find('.move-down').hasClass('move-disabled')) { return false; }
    // Retrieve the next el
    var _next = $(_item_el).next();
    // If the next el doesn't exist
    if ($(_next).length > 0) { 
        // Move the item
        $(_next).after(_item_el); 
        // The event list
        var _event_ids = []; 
        // Loop through each action id
        $(_meta_el).find('.event-row').each(function(){
            // Retrieve the action id
            var _action_id = $(this).attr('data-action-id');
            // If no action id, continue on
            if (_action_id != '') { _event_ids.push(_action_id); }
        });
        // Cancel any pending xhr request
        if (typeof _move_xhr.abort != "undefined") { _move_xhr.abort(); }
        // Serialise the post form
        var post_form = $("#post").serialize();
        // Form load actions
        vcff_do_action('form_event_list_action_before_move',{'form':_id,'item_el':_item_el,'meta_el':_meta_el,'event_list':_event_ids});
        // Post the data to the webservice
        $.post(ajaxurl,{
            // Target the events form
            'action':'vcff_events_ajax_list',
            // The action id 
            'ajax_code':$('#post_id').val(),
            // The bulk action
            'ajax_action':'move',
            // The action id 
            'event_list':_event_ids,
            // Encode the data using base64
            'form_data':base64.encode(post_form)
        }, // Handle the response
        function(response){ 
            // Reset the xhr object
            _move_xhr = {};
            // Form load actions
            vcff_do_action('form_event_list_action_move',{'form':_id,'item_el':_item_el,'meta_el':_meta_el,'event_list':_event_ids});
            // If the response was not what we needed
            if (typeof response != "object") { return false; }
        });
    }
}