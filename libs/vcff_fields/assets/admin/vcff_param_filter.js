!function($) {
    // Retrieve the filter parameter element
    var filter_container = $('.vcff_param_filters');
    // If there is a filter parameter present
    if ($(filter_container).length == 0) { return false; }
    // Retrieve the vc param hidden
    var vc_field = $(filter_container).find('.wpb_vc_param_value');

    function _Init() {
        // Retrieve any saved data
        var stored_data = $(vc_field).val();
        // If there is currently some saved data
        if (stored_data != "") {
            // Loop through each validation line
            $(filter_container).find('.filter-item').each(function(){
                // Retrieve the filter item
                var filter_item = $(this);
                // Prepare the item
                _Prepare(filter_item);  
            });
        } // Otherwise add an empty validation line 
        else { _Add_Filter_Item(); }
    }

    function _Add_Filter_Item() {
        // Retrieve the handlebars template element
        var template_src = $("#filter_ln_tmpl").html();
        // Compile the handlebars template
        var template_compiled = Handlebars.compile(template_src);
        // Create a new instance of our template
        var filter_item = $(template_compiled({}));
        // Perform a related action
        vcff_do_action('vcff_params_filters_init_item',{'item':filter_item,'container':filter_container});
        // Append to the 
        $(filter_container).find('.filter-settings').append(filter_item);
        // Prepare the item
        _Prepare(filter_item);
    }
    
    function _Prepare(item_el) {
        // Do prepare action
        vcff_do_action('vcff_params_filters_before_prepare',{'item':item_el,'container':filter_container});
        // Append the change event when updating the value input
        $(item_el).find('select').change(function(){
            // Perform a related action
            vcff_do_action('vcff_params_filters_update_rule',{'item':item_el,'container':filter_container});
            // Encode and store the current state
            _Store(); 
        });
        // Append the add event
        $(item_el).find('.ln-add').click(function(e){
            // Prevent the default event
            e.preventDefault(); 
            // Perform a related action
            vcff_do_action('vcff_params_filters_add',{'item':item_el,'container':filter_container});
            // Add a new filter item
            _Add_Filter_Item(); 
        });
        // Append the remove event
        $(item_el).find('.ln-remove').click(function(e){
            // Prevent the default browser click action
            e.preventDefault();
            // Perform a related action
            vcff_do_action('vcff_params_filters_remove',{'item':item_el,'container':filter_container});
            // Remove the template from the settings list
            $(item_el).remove(); 
            // If there is only onevalidation line
            if ($(filter_container).find('.filter-item').length == 1) {
                // Hide all remove links
                $(filter_container).find('.ln-remove').hide();
            } // Otherwise if there are multiple 
            else { $(filter_container).find('.ln-remove').show(); }
            // Ecode and store the current state
            _Store(); 
        });
        // If there is only onevalidation line
        if ($(filter_container).find('.filter-item').length == 1) {
            // Hide all remove links
            $(filter_container).find('.ln-remove').hide();
        } // Otherwise if there are multiple 
        else { $(filter_container).find('.ln-remove').show(); }
        // Do prepare action
        vcff_do_action('vcff_params_filters_after_prepare',{'item':item_el,'container':filter_container});
    }

    function _Store() {
        // Create the storage object
        var storage_data = [];
        // Loop through each validation line
        $(filter_container).find('.filter-item').each(function(){
            // Push the validation line settings into the array
            storage_data.push({
                'rule':$(this).find('.ln-rule').val()
            });
        });
        // Perform a related action
        storage_data = vcff_apply_filter('vcff_params_filters_store',storage_data,{'container':filter_container});
        // Update the hidden vc field with the param settings
        $(vc_field).val(base64.encode(JSON.stringify(storage_data)));
    }

    _Init();

}(window.jQuery);