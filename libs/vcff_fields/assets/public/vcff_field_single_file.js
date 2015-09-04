vcff_add_action('form_setup',function(args){

    var vcff_form = args.form;
    
    var $ = window.jQuery;
    
    $(vcff_form).find('.vcff-simple-upload').each(function(){

        var field_el = $(this);
        // Create a temp form element
        var _tmp_form_el;
        // Create a tmp iframe
        var _tmp_iframe_el;
        // Create a new file upload field
        var _tmp_file_el;
        
        var _Prepare_Form = function() {
            // Create the iframe id
            var _iframe_id = Math.floor((Math.random() * 10000) + 1)+'_file_upload';
            // Create a tmp iframe
            _tmp_iframe_el = $('<iframe />');
            // Set the iframe attributes
            _tmp_iframe_el.attr("id",_iframe_id);
            _tmp_iframe_el.attr("name",_iframe_id);
            // Append the iframe to the body
            $('body').append(_tmp_iframe_el);
            // Retrieve the field machine name
            var _machine_name = $(field_el).attr('data-vcff-field-name');
            // Create a temp form element
            _tmp_form_el = $('<form />');
            // Set the form attributes
            _tmp_form_el.attr("action",vcff_data.ajaxurl+"?action=vcff_field_upload&ajax_action=upload&ajax_code="+_machine_name);
            _tmp_form_el.attr("method","post");
            _tmp_form_el.attr("enctype","multipart/form-data");
            // Append the form to the body
            $('body').append(_tmp_form_el);
            // Create a new file upload field
            _tmp_file_el = $('<input name="file_upload" type="file" />');
            // Append the fileupload field
            $(_tmp_form_el).append(_tmp_file_el);
            // Set the temp form styles
            $(_tmp_form_el).css({'position':'absolute','top':'0px','left':'-10000px'});
            // Set the target of the form to the iframe
            _tmp_form_el.attr("target",_iframe_id);
            // Retrieve the form uuid
            var _form_uuid = $(vcff_form).find('[name="vcff_form_uuid"]').val();
            // Add the form uuid to a hidden field
            _tmp_form_el.append('<input type="hidden" name="vcff_form_uuid" value="'+_form_uuid+'">');
            // Retrieve the form security key
            var _form_key = $(vcff_form).find('[name="vcff_key"]').val();
            // Add the security key to a hidden field
            _tmp_form_el.append('<input type="hidden" name="vcff_key" value="'+_form_key+'">');
            // Add the form field machine code to a hidden field
            _tmp_form_el.append('<input type="hidden" name="machine_name" value="'+_machine_name+'">');
            // Set the iframe styling
            _tmp_iframe_el.css({'position':'absolute','top':'0px','left':'-1000px'});
            // Focus on and trigger a click on the field
            $(_tmp_file_el).focus().trigger('click');
            // When the file field is changed
            $(_tmp_file_el).change(function(){
                // Empty and show the field alerts
                $(field_el).find('.field-alerts').empty().hide();
                // Empty and show the field alerts
                $(field_el).find('.uploading-msg').show();
                // Force the form to submit
                $(_tmp_form_el).submit();
                // When the iframe loads, process results
                $(_tmp_iframe_el).load(function () {
                    // Empty and show the field alerts
                    $(field_el).find('.uploading-msg').hide();
                    // Retrieve the contents of the iframe
                    _Process_Results(this.contentWindow.document.body.innerHTML);
                });
            });
        }   
        
        var _Process_Results = function(data) { 
            // Parse the data into a JSON object
            var json = JSON.parse(base64.decode(data)); 
            // If the request failed
            if (json.result != 'success') {
                // If there are no alerts, return out
                if (typeof json.alerts == "undefined") { return false; }
                // Empty and show the field alerts
                $(field_el).find('.field-alerts').empty().show();
                // Append the json alerts
                $(field_el).find('.field-alerts').html(json.alerts);
                // Return out
                return false;
            }
            // Hide the upload button
            $(field_el).find('.btn-upload').hide();
            // Hide the upload button
            $(field_el).find('.uploaded-file').show();
            // Hide the upload button
            $(field_el).find('.uploaded-file').find('.filename').html(json.data.original);
            // Store the location
            $(field_el).find('.field-original').val(json.data.original);
            // Store the location
            $(field_el).find('.field-filename').val(json.data.filename);
            // Store the location
            $(field_el).find('.field-location').val(json.data.location);
            // Store the location
            $(field_el).find('.field-url').val(json.data.url);
            // Remove the tmp form
            _tmp_form_el.remove();
            // Remove the tmp iframe
            _tmp_iframe_el.remove();
        }
        
        $(field_el).find('.file-cancel').click(function(e){ 
            // Prevent the default action
            e.preventDefault();
            // Hide the upload button
            $(field_el).find('.uploaded-file').hide();
            // Hide the upload button
            $(field_el).find('.btn-upload').show();
            // Retrieve the filename
            var _filename = $(field_el).find('.field-filename').val();
            // Retrieve the form uuid
            var _form_uuid = $(vcff_form).find('[name="vcff_form_uuid"]').val();
            // Retrieve the field machine name
            var _machine_name = $(field_el).attr('data-vcff-field-name');
            // Reset the location
            $(field_el).find('.field-filename').val('');
            // Reset the location
            $(field_el).find('.field-location').val('');
            // Post the data to the webservice
            $.post(vcff_data.ajaxurl,{
                // The wordpress ajax action
                'action':'vcff_field_upload',
                'ajax_action':'remove',
                'ajax_code':_machine_name,
                'vcff_form_uuid':_form_uuid,
                'filename':_filename
            // Process the json data
            },function(result_json){ 
                // If the result completely failed
                if (typeof result_json != "object") { 
                    // Return out
                    return false; 
                }
            },'json');
        });

        $(field_el).find('.btn-upload').click(function(e){ 
            
            e.preventDefault();
            
            _Prepare_Form(); 
        });
        
    });

});
