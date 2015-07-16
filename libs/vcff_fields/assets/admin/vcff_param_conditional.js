!function($) {
    // Retrieve the filter parameter element
    var conditions_container = $('#vc_properties-panel .vcff_param_conditions');
    
    if ($(conditions_container).length == 0) { return false; }
    // Retrieve the handlebars template element
    var tmpl_Src = $("#conditional_ln_tmpl").html();
    // Compile the handlebars template
    var tmpl_Cmp = Handlebars.compile(tmpl_Src);
    // Retrieve the various field containers
    var Cont_Settings = $(conditions_container).find('.conditional-settings');
    var Cont_Lines = $(conditions_container).find('.conditional-lines');
    // Retrieve the vc param hidden
    var Input_ActionVisibility = $(conditions_container).find('.conditional-display');
    var Input_ActionTarget = $(conditions_container).find('.conditional-distribution');
    var Input_VcParam = $(conditions_container).find('.wpb_vc_param_value');
        
    function VCFF_Init() {
        // Append the change event on the rule dropdown
        $(Input_ActionVisibility).change(function(){ console.log('Updating'); VCFF_EncodeAndStore(); });
        // Append the change event on the rule dropdown
        $(Input_ActionTarget).change(function(){ console.log('Updating'); VCFF_EncodeAndStore(); });
        // Retrieve any saved data
        var stored_data = $(Input_VcParam).val(); 
        // If there is currently some saved data
        if (stored_data != "") { 
            // Loop through each validation line
            $(Cont_Lines).find('.conditional-ln').each(function(){
                // Retrieve the conditional line
                var condition_item = $(this);
                // Prepare the item
                _Prepare(condition_item);
            });
        } // Otherwise add an empty line 
        else { VCFF_AddLine(); }
        
    }
    
    function VCFF_AddLine() { 
        // Create a new instance of our template
        var condition_item = $(tmpl_Cmp({}));
        // Append to the 
        $(Cont_Lines).append(condition_item); 
        // Perform a related action
        vcff_do_action('vcff_params_validation_init_item',{'item':condition_item,'container':conditions_container});
        // Prepare the item
        _Prepare(condition_item);
    }
    
    function _Prepare(item_el) {
        // Append the change event on the rule dropdown
        $(item_el).find('select.ln-fieldname').change(function(){ 
            // Empty the 
            $(item_el).find('select.ln-fieldcheck').empty();
            
            var machine_code = $(this).val();
            
            if (machine_code && typeof vcff_conditions_fields[machine_code] == "object") {
                
                var field_conditions = vcff_conditions_fields[machine_code]['field_conditions']; 
            
                $.each(field_conditions,function(i,item){
                    $(item_el).find('select.ln-fieldcheck').append('<option value="'+i+'">'+item+'</option>');
                });
                
                $(item_el).find('.ln-value').show(); 
                $(item_el).find('.ln-fieldcheck').show();
            } 
            else {
                $(item_el).find('.ln-value').hide();
                $(item_el).find('.ln-fieldcheck').hide();
            }
            // Perform a related action
            vcff_do_action('vcff_params_condition_update_field',{'item':item_el,'container':conditions_container});
            // Ecode and store the current state
            VCFF_EncodeAndStore(); 
        });
        // Append the change event on the rule dropdown
        $(item_el).find('select.ln-fieldcheck').change(function(){ 
            // Perform a related action
            vcff_do_action('vcff_params_condition_update_check',{'item':item_el,'container':conditions_container});
            // Ecode and store the current state
            VCFF_EncodeAndStore(); 
        });
        // Append the change event when updating the value input
        $(item_el).find('input').keyup(function(){
            // Perform a related action
            vcff_do_action('vcff_params_condition_update_value',{'item':item_el,'container':conditions_container});
            // Ecode and store the current state
            VCFF_EncodeAndStore(); 
        });
        // Append the add event
        $(item_el).find('.ln-add').click(function(e){ 
            // Add a new line
            VCFF_AddLine();
            // Perform a related action
            vcff_do_action('vcff_params_condition_add',{'item':item_el,'container':conditions_container});
            // Prevent the default browser click action
            e.preventDefault();
        });
        // Append the remove event
        $(item_el).find('.ln-remove').click(function(e){
            // Perform a related action
            vcff_do_action('vcff_params_condition_remove',{'item':item_el,'container':conditions_container});
            // Remove the template from the settings list
            $(item_el).remove(); 
            // Prevent the default browser click action
            e.preventDefault();
            // If there is only onevalidation line
            if ($(Cont_Lines).find('.conditional-ln').length == 1) {
                // Hide all remove links
                $(Cont_Lines).find('.ln-remove').hide();
            } // Otherwise if there are multiple 
            else { $(Cont_Lines).find('.ln-remove').show(); }
            // Ecode and store the current state
            VCFF_EncodeAndStore(); 
        });
        
        var fieldname_val = $(item_el).find('select.ln-fieldname').val();
        
        if (!fieldname_val || fieldname_val == '') {
            $(item_el).find('.ln-value').hide(); 
            $(item_el).find('.ln-fieldcheck').hide();
        }
        // If there is only onevalidation line
        if ($(Cont_Lines).find('.conditional-ln').length == 1) {
            // Hide all remove links
            $(Cont_Lines).find('.ln-remove').hide();
        } // Otherwise if there are multiple 
        else { $(Cont_Lines).find('.ln-remove').show(); }
    }
    
    function VCFF_EncodeAndStore() {
        // Create the storage object
        var VCFF_Store = {
            'visibility':$(Input_ActionVisibility).val(),
            'target':$(Input_ActionTarget).val(),
            'conditions':[]
        };
        // Loop through each validation line
        $(Cont_Lines).find('.conditional-ln').each(function(){
            // Push the validation line settings into the array
            VCFF_Store.conditions.push({
                'check_field':$(this).find('.ln-fieldname').val(),
                'check_condition':$(this).find('.ln-fieldcheck').val(),
                'check_value':$(this).find('.ln-value').val()
            });
        });
        // Update the hidden vc field with the param settings
        $(Input_VcParam).val(base64.encode(JSON.stringify(VCFF_Store)));
    }

    // Initiate 
    VCFF_Init();
    
}(window.jQuery);