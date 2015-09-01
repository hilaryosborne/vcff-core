!function($) {
    // Retrieve the validation parameter element
    var _container = $('.vcff_param_conditions');
    // If there is a validation parameter present
    if ($(_container).length == 0) { return false; }
    // Retrieve the vc param hidden
    var vc_field = $(_container).find('.wpb_vc_param_value');
        
    function _Init() {
        // Retrieve any saved data
        var VCFF_Val_SavedData = $(vc_field).val();
        // If there is currently some saved data
        if (VCFF_Val_SavedData != "") {
            // Loop through each validation line
            var _saved = JSON.parse(base64.decode(VCFF_Val_SavedData));
            
            $.each(_saved.rules,function(i,item){
                
                _Add_Item(item);
            });
        } else { _Add_Item({}); }
        
        $(_container).find('.add-condition').click(function(e){
        
            e.preventDefault();
            
            _Add_Item({});
        });
        
        // Append the change event when updating the value input
        $(_container).find('.conditional-result').change(function(){
            // Ecode and store the current state
            _Store(); 
        });
        // Append the change event when updating the value input
        $(_container).find('.conditional-match').change(function(){
            // Ecode and store the current state
            _Store(); 
        });
    }
    
    function _Add_Item(item_data) { 
        // Retrieve the handlebars template element
        var template_src = $("#conditional_ln_tmpl").html();
        // Compile the handlebars template
        var template_compiled = Handlebars.compile(template_src);
        // Create a new instance of our template
        var item_el = $(template_compiled({}));
        // Perform a related action
        vcff_do_action('vcff_params_validation_init_item',{'item':item_el,'container':_container});
        // Append to the 
        $(_container).find('.conditional-items').append(item_el); 
        // Prepare the item
        _Prepare(item_el,item_data);
    }
    
    function _Prepare(item_el,item_data) {
    
        _Prepare_Elements(item_el,item_data);
        
        _Prepare_Conditions(item_el,item_data);
        
        _Prepare_Values(item_el,item_data);
        // Append the add event
        $(item_el).find('.item-add').click(function(e){
            // Prevent the default action
            e.preventDefault(); 
            // Add a new validation line
            _Add_Item({}); 
            // Perform a related action
            vcff_do_action('vcff_params_validation_add',{'item':item_el,'container':_container});
        });
        // Append the remove event
        $(item_el).find('.item-remove').click(function(e){
            // Prevent the default browser click action
            e.preventDefault();
            // Remove the template from the settings list
            $(item_el).remove(); 
            
            if ($(_container).find('.conditions-item').length == 0) { _Add_Item({}); }
            // Perform a related action
            vcff_do_action('vcff_params_validation_remove',{'item':item_el,'container':_container});
            // Ecode and store the current state
            _Store(); 
        });
        // Append the change event when updating the value input
        $(item_el).find('input').keyup(function(){
            // Ecode and store the current state
            _Store(); 
        });
        // Append the change event on the rule dropdown
        $(item_el).find('select').change(function(){ 
            // Ecode and store the current state
            _Store(); 
        });
    }
    
    function _Prepare_Elements(item_el,item_data) {
        
        var element_field_el = $(item_el).find('.item-element');
        
        $.each(vcff_conditions_els,function(i,item){
            
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
            
            var _el_data = vcff_conditions_els[val];
            
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
            
            var _el = vcff_conditions_els[element_field_val];

            var _rule = false;
            
            $.each(_el.logic_rules,function(i,item) {
                
                if (item.machine_code != val) { return true; }
                
                _rule = item;
            });
 
            if (!_rule || !_rule.value) { return false; } 
            
            if (typeof _rule.value == 'object') {
                
                var _sel_el = $('<select class="item-value form-control">');
                
                $.each(_rule.value,function(i,item){
                
                    $(_sel_el).append('<option value="'+i+'">'+item+'</option>');
                });
                
                $(item_el).find('.col-value').append(_sel_el);
                
                // Append the change event on the rule dropdown
                $(_sel_el).change(function(){ 
                    // Ecode and store the current state
                    _Store(); 
                });

            } else {
                
                var _txt_el = $('<input type="text" class="item-value form-control">');
                
                $(item_el).find('.col-value').append(_txt_el);
                // Append the change event when updating the value input
                $(_txt_el).keyup(function(){
                    // Ecode and store the current state
                    _Store(); 
                });
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
    
    function _Store() {
        // Create the storage object
        var storage_data = {
            'rules':[],
            'result':$(_container).find('.conditional-result').val(),
            'match':$(_container).find('.conditional-match').val(),
        }; 
        // Loop through each validation line
        $(_container).find('.conditions-item').each(function(){
            // If no rule then move on
            if (!$(this).find('.item-rules').val()) { return true; }
            // Push the validation line settings into the array
            storage_data.rules.push({
                'machine_code':$(this).find('.item-element').val(),
                'code':$(this).find('.item-rules').val(),
                'value':$(this).find('.item-value').val()
            });
        });
        // Perform a related action
        storage_data = vcff_apply_filter('vcff_params_validation_store',storage_data,{'container':_container});
        // Update the hidden vc field with the param settings
        $(vc_field).val(base64.encode(JSON.stringify(storage_data)));
    }

    // Initiate 
    _Init();
    
}(window.jQuery);