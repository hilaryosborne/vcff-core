!function($) {
    // Retrieve the validation parameter element
    var validation_container = $('.vcff_param_validation');
    // If there is a validation parameter present
    if ($(validation_container).length == 0) { return false; }
    // Retrieve the vc param hidden
    var vc_field = $(validation_container).find('.wpb_vc_param_value');

    function _Init() {
        // Retrieve any saved data
        var VCFF_Val_SavedData = $(vc_field).val();
        // If there is currently some saved data
        if (VCFF_Val_SavedData != "") {
            // Loop through each validation line
            $(validation_container).find('.validation-item').each(function(){ 
                // Retrieve the validation item
                var validation_item = $(this);
                // Perform a related action
                vcff_do_action('vcff_params_validation_init_item',{'item':validation_item,'container':validation_container});
                // Prepare the item
                _Prepare(validation_item);
            });
        } // Otherwise add an empty validation line 
        else { _Add_Validation_Item(); }
        
        $(validation_container).find('.add-validation').click(function(e){
        
            e.preventDefault();
            
            _Add_Validation_Item();
        });
    }

    function _Add_Validation_Item() {
        // Retrieve the handlebars template element
        var template_src = $("#validation_ln_tmpl").html();
        // Compile the handlebars template
        var template_compiled = Handlebars.compile(template_src);
        // Create a new instance of our template
        var validation_item = $(template_compiled({}));
        // Perform a related action
        vcff_do_action('vcff_params_validation_init_item',{'item':validation_item,'container':validation_container});
        // Append to the 
        $(validation_container).find('.validation-settings').append(validation_item); 
        // Prepare the item
        _Prepare(validation_item);
    }
    
    function _Prepare(item_el) {
        // Do prepare action
        vcff_do_action('vcff_params_validation_before_prepare',{'item':item_el,'container':validation_container});
        // Check rule value function
        function __Check_Rule(rule_el) {
            // Retrieve the rule value
            var rule_val = $(rule_el).val();
            // If the rule value is not empty
            if (rule_val != "") {
                // Retrieve the selected options
                var select_option = $(rule_el).find('option:selected');
                // Retrieve the requires value
                var selected_requires_val = $(select_option).attr('data-val-hasvalue');
                // If this param requires a value
                if (selected_requires_val == 'yes') {
                    // Show the value field
                    $(item_el).find('.item-value').show(); 
                } // Otherwise hide the value and empty the field 
                else { $(item_el).find('.item-value').val('').hide(); }
            } 
            else { $(item_el).find('.item-value').val('').hide(); }
        }
        // Append the change event on the rule dropdown
        $(item_el).find('.item-rule').change(function(){ 
            // Retrieve the rule value
            __Check_Rule($(this));
            // Ecode and store the current state
            _Store(); 
        });
        // Force the check rule
        __Check_Rule($(item_el).find('.item-rule'));
        // Append the change event when updating the value input
        $(item_el).find('input').keyup(function(){
            // Perform a related action
            vcff_do_action('vcff_params_validation_update_value',{'item':item_el,'container':validation_container});
            // Ecode and store the current state
            _Store(); 
        });
        // Append the add event
        $(item_el).find('.item-add').click(function(e){
            // Add a new validation line
            _Add_Validation_Item(); 
            // Perform a related action
            vcff_do_action('vcff_params_validation_add',{'item':item_el,'container':validation_container});
            // Prevent the default action
            e.preventDefault(); 
        });
        // Append the remove event
        $(item_el).find('.item-remove').click(function(e){
            // Prevent the default browser click action
            e.preventDefault();
            // Remove the template from the settings list
            $(item_el).remove(); 
            // Perform a related action
            vcff_do_action('vcff_params_validation_remove',{'item':item_el,'container':validation_container});
            // Ecode and store the current state
            _Store(); 
            
            if ($(validation_container).find('.validation-item').length == 0) { _Add_Validation_Item(); }
        });
        // Do prepare action
        vcff_do_action('vcff_params_validation_after_prepare',{'item':item_el,'container':validation_container});
    }

    function _Store() {
        // Create the storage object
        var storage_data = []; 
        // Loop through each validation line
        $(validation_container).find('.validation-item').each(function(){
            // Push the validation line settings into the array
            storage_data.push({
                'rule':$(this).find('.item-rule').val(),
                'value':$(this).find('.item-value').val()
            });
        });
        // Perform a related action
        storage_data = vcff_apply_filter('vcff_params_validation_store',storage_data,{'container':validation_container}); console.log(storage_data);
        // Update the hidden vc field with the param settings
        $(vc_field).val(base64.encode(JSON.stringify(storage_data)));
    }

    _Init();

}(window.jQuery);

