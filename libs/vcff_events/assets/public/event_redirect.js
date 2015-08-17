!function($) {
    
    vcff_add_action('event_do_refresh',function(args){
        new Event_Redirect(args);
    },10);
    
    var Event_Redirect = function(args) { 
        
        var _form_el = args.form;
        
        var _event = args.event;
        
        var _event_data = args.event_data;
        
        if (typeof _event != "redirect") { return false; }
        
        var _redirect = _event_data;

        if (_redirect.method.toLowerCase() == 'get') {

            window.location = _redirect.url+'?'+_redirect.params;
        } 
        else if (_redirect.method.toLowerCase() == 'post') {
            // Create a new temp form
            var _tmp_form_el = $('<form>');
            // Populate the form's attributes
            $(_tmp_form_el).attr('action',_redirect.url).attr('method','post');
            // Split the params by the & sign
            var _params_split = _redirect.params.split("&");
            // Loop through each param
            $.each(_params_split,function(i,item){
                // Split the params by the & sign
                var _item_split = item.split("=");
                // Create a new tmp input object
                var _tmp_field_obj = $('<input>');
                // Setup the input as a hidden
                $(_tmp_field_obj).attr('type','hidden').attr('name',_item_split[0]).attr('value',_item_split[1]);
                // Append to the form
                $(_tmp_form_el).append(_tmp_field_obj);
            });
            // Append to the body of the page
            $('body').append(_tmp_form_el);
            // Submit the form
            $(_tmp_form_el).submit();
        }
        
        
    }
    
}(window.jQuery);