!function($) {
	
    var RefreshSettingsContainer = function(){ 
        // Serialise the post form
        var post_form = $("#VCFF_SETTINGS").serialize();
        // Post the data to the webservice
        $.post(ajaxurl,{
            'action':'settings_refresh',
            'form_data':post_form
        },function(response){
            // If no data was returned
            if (response.result != 'success') { return false; }
            // If no data was returned
            if (typeof response.data == 'undefined') { return false; }
            
            _Render(response);
            
        },'json');
    };

    var SubmitSettingsContainer = function(){
    
        $("#VCFF_SETTINGS").find('.form-alerts').empty();
        // Serialise the post form
        var post_form = $("#VCFF_SETTINGS").serialize();
        // Post the data to the webservice
        $.post(ajaxurl,{
            'action':'settings_submit',
            'form_data':post_form
        },function(response){ 
            // If the response was not what we needed
            if (typeof response != "object") { return false; }
            // If there are alerts
            if (typeof response.alerts != "undefined") {
                // If there are alerts then populate
                $("#VCFF_SETTINGS").find('.form-alerts').show().empty().html(response.alerts);
            }
            // If no data was returned
            if (typeof response.data != 'undefined') { 
                
                $("#VCFF_SETTINGS").find('.vcff-tab-nav').empty();
                $("#VCFF_SETTINGS").find('.vcff-settings-page').remove();
                
                _Render(response); 
            }

            
            
        },'json');
    };
    
    var _Render = function(json_data){
        // Retrieve the settings pages
        var settings_pages = json_data.data.pages;

        var vcff_settings_container = $('.vcff-settings-container');

        var vcff_settings_container_tabs = $(vcff_settings_container).find('.vcff-tabs');

        if (typeof settings_pages == 'undefined') { return false; }

        var present_pages = [];

        var load_js_scripts = [];

        var last_page_object = false;

        var last_tab_object = false;
        
        if (typeof json_data.alerts != "undefined") {
        
           $("#VCFF_SETTINGS").find('.settings-alerts').html(json_data.alerts);
        }
        
        // Loop through new pages
        $.each(settings_pages,function(i,page){

            present_pages.push(page.id);

            if ($(vcff_settings_container_tabs).find('[data-vcff-settings-page="'+page.id+'"]').length > 0) {

                var vcff_page_obj = $(vcff_settings_container_tabs).find('[data-vcff-settings-page="'+page.id+'"]').first(); 

                var vcff_page_tab = $(vcff_settings_container_tabs).find('[data-vcff-settings-tab="'+page.id+'"]').first(); 

                last_page_object = vcff_page_obj;

                last_tab_object = vcff_page_tab;

            } else {

                var vcff_page_tab = $('<li><a href="#vcff_page_'+page.id+'">'+page.title+'</a></li>');

                $(vcff_page_tab).attr('data-vcff-settings-tab', page.id);

                var vcff_page_obj = $(page.html);

                $(vcff_page_obj).attr('data-vcff-settings-page', page.id);

                if (typeof last_page_object != "object") {

                    $(vcff_settings_container_tabs).prepend(vcff_page_obj);

                    $(vcff_settings_container_tabs).find('ul.vcff-tab-nav').prepend(vcff_page_tab);

                } else {

                    $(last_page_object).after(vcff_page_obj);

                    $(last_tab_object).after(vcff_page_tab);
                }

                last_page_object = vcff_page_obj;

                last_tab_object = vcff_page_tab;
            }

            var present_groups = [];

            if (typeof page.groups != 'undefined') {

                var last_group_object = false;

                $.each(page.groups,function(i,group){

                    present_groups.push(group.id);

                    if ($(vcff_page_obj).find('[data-vcff-settings-group="'+group.id+'"]').length > 0) {

                        var group_obj = $(vcff_page_obj).find('[data-vcff-settings-group="'+group.id+'"]');

                    } else {

                        var group_obj = $(group.html);

                        if (typeof last_group_object != "object") { 

                            $(vcff_page_obj).find('.settings-page-groups').append(group_obj);    
                        } 
                        else { $(last_group_object).after(group_obj); }

                        $(group_obj).attr('data-vcff-settings-group', group.id);
                    }

                    var present_fields = [];

                    if (typeof group.fields != 'undefined') { 

                        var last_field_object = false;

                        $.each(group.fields,function(i,field){

                            present_fields.push(field.machine_code);

                            if ($(group_obj).find('[data-vcff-settings-field="'+field.machine_code+'"]').length > 0) {

                                var field_obj = $(group_obj).find('[data-vcff-settings-field="'+field.machine_code+'"]');

                            } else {

                                var field_obj = $(field.html);

                                if (typeof last_field_object != "object") { 

                                    $(group_obj).find('.settings-group-fields').append(field_obj);
                                } 
                                else { $(last_field_object).after(field_obj); }

                                $(field_obj).attr('data-vcff-settings-field', field.machine_code);

                                if (typeof field.js == "object") {

                                    $.each(field.js,function(i,js_src){

                                        if (load_js_scripts.indexOf(js_src) == -1) {

                                            load_js_scripts.push(js_src);
                                        }
                                    });
                                }
                            }

                            last_field_object = field_obj;
                        });
                    }
                    // Loop through present groups
                    var settings_found_fields = $(group_obj).find('[data-vcff-settings-field]');
                    // Loop through each of the found groups
                    $(settings_found_fields).each(function(){ 
                        // Retrieve the page it
                        var settings_machine_code = $(this).attr('data-vcff-settings-field');
                        // If the page is not present, remove it
                        if (present_fields.indexOf(settings_machine_code) == -1) { $(this).remove(); }
                    });

                    last_group_object = group_obj;
                });
            }
            // Loop through present groups
            var settings_found_groups = $(vcff_page_obj).find('.vcff-settings-group');
            // Loop through each of the found groups
            $(settings_found_groups).each(function(){
                // Retrieve the page it
                var settings_group_id = $(this).attr('data-vcff-settings-group');
                // If the page is not present, remove it
                if (present_groups.indexOf(settings_group_id) == -1) { $(this).remove(); }
            });
        });
        // Loop through present pages
        var settings_found_pages = $(vcff_settings_container).find('[data-vcff-settings-page]');
        // Loop through each of the found pages
        $(settings_found_pages).each(function(){
            // Retrieve the page it
            var settings_page_id = $(this).attr('data-vcff-settings-page');
            // If the page is not present, remove it
            if (present_pages.indexOf(settings_page_id) == -1) { 

                $(vcff_settings_container_tabs).find('[data-vcff-settings-tab="'+settings_page_id+'"]').remove();

                $(this).remove(); 
            }
        });

        $.each(load_js_scripts,function(i,js_src){

            $.getScript(js_src);
        });

        if (!$(vcff_settings_container_tabs).hasClass('ui-tabs')) {

            $(".vcff-tabs").tabs();

        } else {

            $(".vcff-tabs").tabs("refresh");
        }

        $('.vcff-settings-field').unbind( "change" );

        $('.vcff-settings-field').change(function(){
            // Update all the settingsboxes
            RefreshSettingsContainer();
        });
    };
    
    $(document).ready(function(){
    
        if ($('#VCFF_SETTINGS').length > 0) {
        
            RefreshSettingsContainer();
        }
        
        $('.btn-submit').click(function(){
            // Update all the settingsboxes
            SubmitSettingsContainer();
        });
        
    });
    
}(window.jQuery);