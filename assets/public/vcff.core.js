!function($) {
    
    var VCFF = function(vcff_form){
        
        var _self = this;
    
        var _request = {};
    
        var _is_submitted = false;
        
        var _buffer = {};
        
        vcff_do_action('form_setup',{'form':vcff_form,'form_obj':_self});
        
        var _first_check = true;
        
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
            } 
            else {

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
            
                _buffer = setTimeout(function(){ _self.Pre_Send(); _self.Check_Conditions(); },450);
            });
            
            $(vcff_form).find('.key-change').keyup(function(){ 
            
                clearTimeout(_buffer);
                
                _buffer = setTimeout(function(){ _self.Pre_Send(); _self.Check_Conditions(); },450);
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
            clearTimeout(_buffer);
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
                _self.Apply_Updates(result_json);
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
                _self.Apply_Updates(result_json);
                // Pre form submission actions
                vcff_do_action('do_form_submission',{'form':vcff_form,'json':result_json});
                // Pre form standard submission actions
                vcff_do_action('do_form_standard_submission',{'form':vcff_form,'json':result_json});
                // If the submission was not successfull
                if (result_json.data.form.result != 'passed') {
                    // Pre form submission actions
                    vcff_do_action('form_submission_failed',{'form':vcff_form,'json':result_json});
                    // Pre form standard submission actions
                    vcff_do_action('form_ajax_submission_failed',{'form':vcff_form,'json':result_json});
                }
                // If the form was successfull
				if (result_json.data.form.result == 'passed') { $(vcff_form).unbind('submit').submit(); }
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
                if (typeof result_json != "object") {  console.log('BAH');
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
                _self.Apply_Updates(result_json);
                // Pre form submission actions
                vcff_do_action('do_form_submission',{'form':vcff_form,'json':result_json});
                // Pre form standard submission actions
                vcff_do_action('do_form_ajax_submission',{'form':vcff_form,'json':result_json});
                // If the form was successfull
                // If the submission was not successfull
                if (result_json.data.form.result != 'passed') {
                    // Pre form submission actions
                    vcff_do_action('form_submission_failed',{'form':vcff_form,'json':result_json});
                    // Pre form standard submission actions
                    vcff_do_action('form_ajax_submission_failed',{'form':vcff_form,'json':result_json});
                } 
                else { 
                    // Pre form submission actions
                    vcff_do_action('form_submission_passed',{'form':vcff_form,'json':result_json});
                    // Pre form standard submission actions
                    vcff_do_action('form_ajax_submission_passed',{'form':vcff_form,'json':result_json});
                }
                // Pre form submission actions
                vcff_do_action('after_form_submission',{'form':vcff_form,'json':result_json});
                // Pre form standard submission actions
                vcff_do_action('after_form_ajax_submission',{'form':vcff_form,'json':result_json});
                // Reset the request object
                _request = {};
            },'json');
        };
        
        _self.Apply_Updates = function(json) {
        
			if (typeof json.data.containers != "undefined" && json.data.containers != null) {
				// Retrieve the conditional data
                var containers = json.data.containers; 
				// Loop through each of the returned fields
				$.each(containers,function(container_name,container){
					// Retrieve the field dom element
					var container_obj = $(vcff_form).find('[data-vcff-container="'+container_name+'"]');
					// If there is no field of this name
					if ($(container_obj).length === 0) { return true; }
					// If there are conditions for this container
					if (!$(container_obj).hasClass('ignore-visibility') && typeof container.visibility != "undefined") {
                        // If the field is set to visible
                        if (container.visibility == 'visible') {
                            // Show the field
                            $(container_obj).show();    
                        } // If the field is hidden, hide it
                        else if (container.visibility == 'hidden') { $(container_obj).hide(); }
					}
                    // If the container has alerts
                    if (typeof container.alerts != 'undefined' && container.alerts) {
                        // Append any container alerts
                        $(container_obj).find('.container-alerts').show().html(container.alerts);
                    }
                    // Pre form standard submission actions
                    vcff_do_action('container_do_refresh',{'form':vcff_form,'data':json.data,'container':container,'container_el':container_obj});
				});
			} 

			if (typeof json.data.fields != "undefined" && json.data.fields != null) { 
				// Retrieve the conditional data
                var fields = json.data.fields; 
				// Loop through each of the returned fields
				$.each(fields,function(machine_code,field){ 
					// Retrieve the field dom element
					var field_obj = $(vcff_form).find('[data-vcff-field-name="'+machine_code+'"]');  
					// If there is no field of this name
					if ($(field_obj).length === 0) { return true; }
					// If there are conditions for this container
					if (!$(field_obj).hasClass('ignore-visibility') && typeof field.visibility != "undefined") {
                        // If the field is set to visible
                        if (field.visibility == 'visible') {
                            // Show the field
                            $(field_obj).show();    
                        } // If the field is hidden, hide it
                        else if (field.visibility == 'hidden') { $(field_obj).hide(); }
					}
					// If the field has alerts
                    if (typeof field.alerts != 'undefined' && field.alerts) {
                        // Append any field alerts
                        $(field_obj).find('.field-alerts').show().html(field.alerts);
                    }
                    // Pre form standard submission actions
                    vcff_do_action('field_do_refresh',{'form':vcff_form,'data':json.data,'field':field,'field_el':field_obj});
				});
			}
            
            if (typeof json.data.supports != "undefined" && json.data.supports != null) { 
				// Retrieve the conditional data
                var supports = json.data.supports; 
				// Loop through each of the returned support
				$.each(supports,function(machine_code,support){ 
					// Retrieve the support dom element
					var support_obj = $(vcff_form).find('[data-vcff-support-name="'+machine_code+'"]');  
					// If there is no support of this name
					if ($(support_obj).length === 0) { return true; } 
					// If there are conditions for this container
					if (!$(support_obj).hasClass('ignore-visibility') && typeof support.visibility != "undefined") {
                        // If the support is set to visible
                        if (support.visibility == 'visible') {
                            // Show the support
                            $(support_obj).show();    
                        } // If the support is hidden, hide it
                        else if (support.visibility == 'hidden') { $(support_obj).hide(); }
					}
					// If the support has alerts
                    if (typeof support.alerts != 'undefined' && support.alerts) {
                        // Append any support alerts
                        $(support_obj).find('.support-alerts').show().html(support.alerts);
                    } 
                    // Pre form standard submission actions
                    vcff_do_action('support_do_refresh',{'form':vcff_form,'data':json.data,'support':support,'support_el':support_obj});
				});
			}
            
            if (typeof json.data.events != "undefined" && json.data.events != null) { 
				// Retrieve the conditional data
                var events = json.data.events; 
				// Loop through each of the returned fields
				$.each(events,function(type,event_data){ 
                    // Pre form standard submission actions
                    vcff_do_action('event_do_refresh',{'form':vcff_form,'data':json.data,'event':type,'event_data':event_data});
				});
			}
			
			if (typeof json.data.form != "undefined" && json.data.form != null) {
                
                var form = json.data.form;
                // If the form has alerts
				if (typeof form.alerts != 'undefined' && form.alerts) {
					// Append to the standard form alerts panel
                    $(vcff_form).find('.form-alerts').show().html(form.alerts);
                    // Append to any custom alerts panel
                    $(vcff_form).find('.form-alerts-panel').show().html(form.alerts);
				}
                
                // Update the form with a new key
                if (typeof form.origin_key != "undefined") { 
                    
                    $(vcff_form).find('[name="vcff_origin_key"]').val(form.origin_key); 
                }
                // Pre form standard submission actions
                vcff_do_action('form_update',{'form':vcff_form,'data':json.data});
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

var CaptchaCallback = function() {
    var $ = window.jQuery;
    
    $('.recaptcha-field').each(function(){
        var site_key = $(this).attr('data-sitekey');
        grecaptcha.render($(this).get(0), {'sitekey' : site_key});
    });
}
