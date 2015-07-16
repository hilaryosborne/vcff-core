if (typeof VCFF_Field_Hooks == 'undefined') {
    var VCFF_Field_Hooks = [];
}


!function($) {

    var VCFF = function(vcff_form){
        
        var _self = this;
    
        var _request = {};
    
        var _is_submitted = false;
        
        vcff_do_action('form_setup',{'form':vcff_form,'form_obj':_self});
        
        _self.Setup_Events = function() {
            
            if ($(vcff_form).find('.form-alerts-panel').length > 0) {
            
                $(vcff_form).find('.form-alerts').remove();
            }
            
            if ($(vcff_form).hasClass('do-ajax-submit')) {
            
                $(vcff_form).submit(function(e){
                    e.preventDefault();

                    if (!_is_submitted) {
                        _self.Pre_Send();
                        _self.Submit_Form_AJAX();
                    }
                });
            
            } else {

                $(vcff_form).submit(function(e){
                    e.preventDefault();
                    if (!_is_submitted) {
                        _self.Pre_Send();
                        _self.Submit_Form_Standard();
                    }
                });

            }
        
            $(vcff_form).find('.check-change').change(function(){ 
                
                clearTimeout(_buffer);
            
                _self.Check_Conditions(); 
            });
            
            var _buffer = {};
            
            $(vcff_form).find('.key-change').keyup(function(){ 
            
                clearTimeout(_buffer);
                
                _buffer = setTimeout(function(){ _self.Check_Conditions(); },450);
            });

            $(vcff_form).find('.click-refresh').click(function(){ 
                $(vcff_form).find('.form-alerts').empty();
                $(vcff_form).find('.form-alerts-panel').empty();
                $(vcff_form).find('.container-alerts').empty();
                $(vcff_form).find('.field-alerts').empty();
                _self.Check_Conditions(); 
            });
        };

        _self.Pre_Send = function() {
            $(vcff_form).find('.form-alerts').empty();
            $(vcff_form).find('.form-alerts-panel').empty();
            $(vcff_form).find('.container-alerts').empty();
            $(vcff_form).find('.field-alerts').empty();
        };
    
        _self.Check_Conditions = function() {
            // If the request can be cancelled
            if (typeof _request.abort != 'undefined') { _request.abort(); }
            // Serialise the post form
            var serialised_fields = $(vcff_form).serialize();
            // Pre form submission actions
            vcff_do_action('before_form_check_conditions',{'form':vcff_form});
            // Apply any required filters
            serialised_fields = vcff_apply_filter('form_fields_serialize',serialised_fields,{});
            serialised_fields = vcff_apply_filter('form_fields_conditions_serialize',serialised_fields,{});
            // Post the data to the webservice
            _request = $.post(vcff_data.ajaxurl,{
                'action':'form_check_conditions',
                'form_data':base64.encode(serialised_fields)
            },function(result_json){ 
                // If the result completely failed
                if (typeof result_json != "object") { 
                    // Pre form submission actions
                    vcff_do_action('form_check_conditions_error',{'form':vcff_form});
                    // Return out
                    return false; 
                }
                // Apply required filters
                result_json = vcff_apply_filter('form_check_conditions_result',result_json,{});
                // Pre form submission actions
                vcff_do_action('do_form_check_conditions',{'form':vcff_form});
                // Fire the apply to form function
                _self.Apply_Updates(result_json.form);
                // If the submission was not successfull
                if (result_json.result != 'success') {
                    // Pre form submission actions
                    vcff_do_action('form_check_conditions_failed',{'form':vcff_form,'json':result_json});
                }
                // Pre form submission actions
                vcff_do_action('after_form_check_conditions',{'form':vcff_form});
                // Reset the request object
                _request = {};
            },'json');
        };
    
        _self.Submit_Form_Standard = function() { 
        
            _is_submitted = true;
            // Pre form submission actions
            vcff_do_action('before_form_submission',{'form':vcff_form});
            // Pre form standard submission actions
            vcff_do_action('before_form_standard_submission',{'form':vcff_form});
            // Serialise the post form
            var serialised_fields = $(vcff_form).serialize();
            // Apply any required filters
            serialised_fields = vcff_apply_filter('form_fields_serialize',serialised_fields,{});
            serialised_fields = vcff_apply_filter('form_fields_submission_serialize',serialised_fields,{});
            serialised_fields = vcff_apply_filter('form_fields_standard_submission_serialize',serialised_fields,{});
            // If the request can be cancelled
            if (typeof _request.abort != 'undefined') { _request.abort(); }
            // Post the data to the webservice
            _request = $.post(vcff_data.ajaxurl,{
                'action':'form_check_validation',
                'form_data':base64.encode(serialised_fields)
            },function(result_json){ 
                // If the result completely failed
                if (typeof result_json != "object") { 
                    // Pre form submission actions
                    vcff_do_action('form_submission_error',{'form':vcff_form,'json':result_json});
                    // Pre form standard submission actions
                    vcff_do_action('form_standard_submission_error',{'form':vcff_form,'json':result_json});
                    // Return out
                    return false; 
                }
                // Apply required filters
                result_json = vcff_apply_filter('form_submission_result',result_json,{});
                result_json = vcff_apply_filter('form_standard_submission_result',result_json,{});
                // Set the is submitted to false
                _is_submitted = false;
                // Fire the apply to form function
                _self.Apply_Updates(result_json.form);
                // Pre form submission actions
                vcff_do_action('do_form_submission',{'form':vcff_form,'json':result_json});
                // Pre form standard submission actions
                vcff_do_action('do_form_standard_submission',{'form':vcff_form,'json':result_json});
                // If the submission was not successfull
                if (result_json.form.form.result != 'passed') {
                    // Pre form submission actions
                    vcff_do_action('form_submission_failed',{'form':vcff_form,'json':result_json});
                    // Pre form standard submission actions
                    vcff_do_action('form_ajax_submission_failed',{'form':vcff_form,'json':result_json});
                }
                // If the form was successfull
				if (result_json.form.form.result == 'passed') { $(vcff_form).unbind('submit').submit(); }
                // Pre form submission actions
                vcff_do_action('after_form_submission',{'form':vcff_form,'json':result_json});
                // Pre form standard submission actions
                vcff_do_action('after_form_standard_submission',{'form':vcff_form,'json':result_json});
                // Reset the request object
                _request = {};
            },'json');
        };
   
		_self.Submit_Form_AJAX = function() {
            _is_submitted = true;
            // Pre form submission actions
            vcff_do_action('before_form_submission',{'form':vcff_form});
            // Pre form standard submission actions
            vcff_do_action('before_form_ajax_submission',{'form':vcff_form});
            // Serialise the post form
            var serialised_fields = $(vcff_form).serialize();
            // Apply any required filters
            serialised_fields = vcff_apply_filter('form_fields_serialize',serialised_fields,{});
            serialised_fields = vcff_apply_filter('form_fields_submission_serialize',serialised_fields,{});
            serialised_fields = vcff_apply_filter('form_fields_ajax_submission_serialize',serialised_fields,{});
            // If the request can be cancelled
            if (typeof _request.abort != 'undefined') { _request.abort(); }
            // Post the data to the webservice
            _request = $.post(vcff_data.ajaxurl,{
                'action':'form_ajax_submit',
                'form_data':base64.encode(serialised_fields)
            },function(result_json){ 
                // If the result completely failed
                if (typeof result_json != "object") { 
                    // Pre form submission actions
                    vcff_do_action('form_submission_error',{'form':vcff_form,'json':result_json});
                    // Pre form standard submission actions
                    vcff_do_action('form_ajax_submission_error',{'form':vcff_form,'json':result_json});
                    // Return out
                    return false; 
                }
                // Apply required filters
                result_json = vcff_apply_filter('form_submission_result',result_json,{});
                result_json = vcff_apply_filter('form_ajax_submission_result',result_json,{});
                
                _is_submitted = false;
                // Fire the apply to form function
                _self.Apply_Updates(result_json.form);
                // Pre form submission actions
                vcff_do_action('do_form_submission',{'form':vcff_form,'json':result_json});
                // Pre form standard submission actions
                vcff_do_action('do_form_ajax_submission',{'form':vcff_form,'json':result_json});
                // If the form was successfull
                // If the submission was not successfull
                if (result_json.form.form.result != 'passed') {
                    // Pre form submission actions
                    vcff_do_action('form_submission_failed',{'form':vcff_form,'json':result_json});
                    // Pre form standard submission actions
                    vcff_do_action('form_ajax_submission_failed',{'form':vcff_form,'json':result_json});
                } 
                else { $(vcff_form).get(0).reset(); }
                // Pre form submission actions
                vcff_do_action('after_form_submission',{'form':vcff_form,'json':result_json});
                // Pre form standard submission actions
                vcff_do_action('after_form_ajax_submission',{'form':vcff_form,'json':result_json});
                // Update the form with a new key
                if (typeof result_json.form_key != "undefined") { $(vcff_form).find('[name="vcff_key"]').val(result_json.form_key); }
                // Reset the request object
                _request = {};
            },'json');
        };
        
        _self.Apply_Updates = function(data) {

			if (typeof data.containers != "undefined" && data.containers != null) {
				// Retrieve the conditional data
                var containers = data.containers; 
				// Loop through each of the returned fields
				$.each(containers,function(container_name,container){
					// Retrieve the field dom element
					var container_obj = $(vcff_form).find('[data-vcff-container="'+container_name+'"]');
					// If there is no field of this name
					if ($(container_obj).length === 0) { return true; }
                    
                    var container_type = container.type;

					// If there are conditions for this container
					if (typeof container.conditions != "undefined") {
						// Retrieve the conditions
						var conditions = container.conditions;
                        // If the field is set to visible
                        if (conditions.visibility == 'visible') {
                            // Show the field
                            $(container_obj).show();    
                        } // If the field is hidden, hide it
                        else if (conditions.visibility == 'hidden') { $(container_obj).hide(); }
					}
                    
                    if (typeof container.alerts != 'undefined') {

                        $(container_obj).find('.container-alerts').show().html(container.alerts);
                    }
                    // Pre form standard submission actions
                    vcff_do_action('container_do_refresh',{'form':vcff_form,'data':data,'container':container,'container_el':container_obj});
                    
                    if (typeof container.data != "undefined") {
                    
                        $.each(VCFF_Field_Hooks,function(i,hook){
                        
                            if (hook.type != container_type) { return true; }
                            // Fire the callback
                            hook.callback(container_obj,container.data); 
                        });
                    }
				});
			} 

			if (typeof data.fields != "undefined" && data.fields != null) { 
				// Retrieve the conditional data
                var fields = data.fields; 
				// Loop through each of the returned fields
				$.each(fields,function(machine_code,field){ 
					// Retrieve the field dom element
					var field_obj = $(vcff_form).find('[data-vcff-field-name="'+machine_code+'"]');  
					// If there is no field of this name
					if ($(field_obj).length === 0) { console.log(machine_code); return true; }
					// If there are conditions for this container
					if (typeof field.conditions != "undefined") {
						// Retrieve the conditions
						var conditions = field.conditions;
                        // If the field is set to visible
                        if (conditions.visibility == 'visible') {
                            // Show the field
                            $(field_obj).show();    
                        } // If the field is hidden, hide it
                        else if (conditions.visibility == 'hidden') { $(field_obj).hide(); }
					}
					// If there are conditions for this container
					if (typeof field.validation != "undefined") {
						// Retrieve the conditions
						var validation = field.validation;
                        // If the field is set to visible
                        if (validation.result != 'passed') { }
					}
                    
                    if (typeof field.alerts != 'undefined') {

                        $(field_obj).find('.field-alerts').show().html(field.alerts);
                    }
                    
                    var field_type = field.type;
                    // Pre form standard submission actions
                    vcff_do_action('field_do_refresh',{'form':vcff_form,'data':data,'field':field,'field_el':field_obj});
                    
                    if (typeof field.data != "undefined") {
                    
                        $.each(VCFF_Field_Hooks,function(i,hook){
                        
                            if (hook.type != field_type) { return true; }
                            // Fire the callback
                            hook.callback(field_obj,field.data); 
                        });
                    }
                    
				});
			}
			
			if (typeof data.form != "undefined" && data.form != null) {

                var form = data.form;

				if (typeof form.alerts != 'undefined') {
					
                    $(vcff_form).find('.form-alerts').show().html(form.alerts);
                    
                    $(vcff_form).find('.form-alerts-panel').show().html(form.alerts);
				}
                
                if (typeof form.ajax != 'undefined') {

                    if (typeof form.ajax.html != 'undefined') {
                        
                        $(vcff_form).hide().after(form.ajax.html);
                    }
                }
                
                if (typeof form.redirects != 'undefined') {
					
                    var redirect_url = form.redirects[0];
                    
                    var redirect_method = form.redirects[1];
                    
                    var redirect_params = form.redirects[2];
                    
                    if (redirect_method == 'get') {
                    
                        window.location = redirect_url;
                    } 
                    else if (redirect_method == 'post') {
                        
                        var form_obj = $('<form>');

                        $(form_obj).attr('action',redirect_url).attr('method','post');

                        $.each(redirect_params,function(machine_code,field_data){
                        
                            var field_obj = $('<input>');
                            
                            $(field_obj).attr('type','hidden').attr('name',machine_code).attr('value',field_data);
                            
                            $(form_obj).append(field_obj);
                        });

                        $('body').append(form_obj);

                        $(form_obj).submit();
                    }
				}
				
			}
            
        };
    
        _self.Setup_Events();
    };

    $(document).ready(function(){
        
        $('.vcff-form').each(function(){
        
            var vcff = new VCFF($(this));
            
            vcff.Check_Conditions();
        });
        
    });

}(window.jQuery);
