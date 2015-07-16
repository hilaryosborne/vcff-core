!function($) {
    // Retrieve the url_var parameter element
    var vars_container = $('.vcff_param_url_vars');
    // If there is a url_var parameter present
    if ($(vars_container).length == 0) { return false; }
    // Retrieve the vc param hidden
    var vc_field = $(vars_container).find('.wpb_vc_param_value');

    function _Init() {
        // Retrieve any saved data
        var stored_data = $(vc_field).val();
        // If there is currently some saved data
        if (stored_data != "") {
            // Loop through each validation line
            $(vars_container).find('.url-item').each(function(){
                // Retrieve the url_var item
                var url_item = $(this);
                // Perform a related action
                vcff_do_action('vcff_params_url_vars_init_item',{'item':url_item,'container':vars_container});
                // Append the change event when updating the value input
                _Prepare(url_item)
            });
        } // Otherwise add an empty validation line 
        else { _Add_Filter_Item(); }
    }

    function _Add_Filter_Item() {
        // Retrieve the handlebars template element
        var template_src = $("#url_var_ln_tmpl").html();
        // Compile the handlebars template
        var template_compiled = Handlebars.compile(template_src);
        // Create a new instance of our template
        var url_item = $(template_compiled({}));
        // Perform a related action
        vcff_do_action('vcff_params_url_vars_init_item',{'item':url_item,'container':vars_container});
        // Append to the 
        $(vars_container).find('.url-settings').append(url_item);
        
        _Prepare(url_item);
    }
    
    function _Prepare(item_el) {
        // Do prepare action
        vcff_do_action('vcff_params_url_vars_before_prepare',{'item':item_el,'container':vars_container});
        // Append the change event when updating the value input
        $(item_el).find('select.fld-rule').change(function(){
            // Perform a related action
            vcff_do_action('vcff_params_url_vars_update_rule',{'item':item_el,'container':vars_container});
            // Encode and store the current state
            _Store(); 
        });
        // Append the change event when updating the value input
        $(item_el).find('input.fld-value').keyup(function(){
            // Perform a related action
            vcff_do_action('vcff_params_url_vars_update_value',{'item':item_el,'container':vars_container});
            // Encode and store the current state
            _Store(); 
        });
        // Append the add event
        $(item_el).find('.ln-add').click(function(e){
            // Prevent the default event
            e.preventDefault(); 
            // Perform a related action
            vcff_do_action('vcff_params_url_vars_add',{'item':item_el,'container':vars_container});
            // Add a new url_var item
            _Add_Filter_Item(); 
        });
        // Append the remove event
        $(item_el).find('.ln-remove').click(function(e){
            // Prevent the default browser click action
            e.preventDefault();
            // Perform a related action
            vcff_do_action('vcff_params_url_vars_remove',{'item':item_el,'container':vars_container});
            // Remove the template from the settings list
            $(item_el).remove(); 
            // If there is only onevalidation line
            if ($(vars_container).find('.url-item').length == 1) {
                // Hide all remove links
                $(vars_container).find('.ln-remove').hide();
            } // Otherwise if there are multiple 
            else { $(vars_container).find('.ln-remove').show(); }
            // Ecode and store the current state
            _Store(); 
        });
        // If there is only onevalidation line
        if ($(vars_container).find('.url-item').length == 1) {
            // Hide all remove links
            $(vars_container).find('.ln-remove').hide();
        } // Otherwise if there are multiple 
        else { $(vars_container).find('.ln-remove').show(); }
        // Do prepare action
        vcff_do_action('vcff_params_url_vars_after_prepare',{'item':item_el,'container':vars_container});
    }
    
    function _Store() {
        // Create the storage object
        var storage_data = [];
        // Loop through each validation line
        $(vars_container).find('.url-item').each(function(){
            // Push the validation line settings into the array
            storage_data.push({
                'rule':$(this).find('.fld-rule').val(),
                'value':$(this).find('.fld-value').val(),
            });
        });
        // Perform a related action
        storage_data = vcff_apply_filter('vcff_params_url_vars_store',storage_data,{'container':vars_container}); console.log(storage_data);
        // Update the hidden vc field with the param settings
        $(vc_field).val(base64.encode(JSON.stringify(storage_data)));
    }

    _Init();

}(window.jQuery);