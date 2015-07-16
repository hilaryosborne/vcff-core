!function($) {
 
	var RefreshMetaStatus = function() {
		// Serialise the post form
        var post_form = $("#post").serialize();
        // Post the data to the webservice
        $.post(ajaxurl,{
            'action':'form_meta_status_refresh',
            'form_data':base64.encode(post_form)
        },function(response){
			
			response_json = JSON.parse(response);

			if (typeof response_json.result == "undefined") { return false; }
	
			if (response_json.result != "success") { return false; }
			
			if (response_json.data === null) { 
				$('.vcff-meta-status-container').empty();
			} else {
				var decoded_html = atob(response_json.data.html); 
			
				$('.vcff-meta-status-container').empty().append(decoded_html);
			}
		});
	};
	
    var RefreshMetaContainer = function(){
        // Form load actions
        vcff_do_action('meta_refresh_before',{});
        // Serialise the post form
        var post_form = $("#post").serialize();
        // Apply any required filters
        post_form = vcff_apply_filter('meta_refresh_serialize',post_form,{});
        // Post the data to the webservice
        $.post(ajaxurl,{
            'action':'form_meta_fields_refresh',
            'form_data':base64.encode(post_form)
        },function(json_data){
            // Apply required filters
            json_data = vcff_apply_filter('meta_refresh_result',json_data,{});
            // Retrieve the meta pages
            var meta_pages = json_data.data.pages;
            
            var vcff_meta_container = $('.vcff-meta-container');
            
            var vcff_meta_container_tabs = $(vcff_meta_container).find('.vcff-tabs');
            
            if (typeof meta_pages == 'undefined') { return false; }
            
            var present_pages = [];
            
            var load_js_scripts = [];
            
            var last_page_object = false;
            
            var last_tab_object = false;
            
            // Loop through new pages
            $.each(meta_pages,function(i,page){

                present_pages.push(page.id);
                // Apply any required filters
                page = vcff_apply_filter('meta_refresh_page_data',page,{});
                // Form load actions
                vcff_do_action('meta_refresh_before_page',{'page_data':page});   
                
                if ($(vcff_meta_container_tabs).find('[data-vcff-meta-page="'+page.id+'"]').length > 0) {
                
                    var vcff_page_obj = $(vcff_meta_container_tabs).find('[data-vcff-meta-page="'+page.id+'"]').first(); 
                    
                    var vcff_page_tab = $(vcff_meta_container_tabs).find('[data-vcff-meta-tab="'+page.id+'"]').first(); 

                    last_page_object = vcff_page_obj;
                
                    last_tab_object = vcff_page_tab;

                } else {
                
                    var vcff_page_tab = $('<li><a href="#vcff_page_'+page.id+'">'+page.title+'</a></li>');
                    
                    $(vcff_page_tab).attr('data-vcff-meta-tab', page.id);
                    
                    var vcff_page_obj = $(page.html);
                    
                    $(vcff_page_obj).attr('data-vcff-meta-page', page.id);
                    
                    if (typeof last_page_object != "object") {
                    
                        $(vcff_meta_container_tabs).prepend(vcff_page_obj);
                        
                        $(vcff_meta_container_tabs).find('ul.vcff-tab-nav').prepend(vcff_page_tab);
                    
                    } else {

                        $(last_page_object).after(vcff_page_obj);
                        
                        $(last_tab_object).after(vcff_page_tab);
                    }
                    
                    last_page_object = vcff_page_obj;
                
                    last_tab_object = vcff_page_tab;
                    // Form load actions
                    vcff_do_action('meta_refresh_create_page',{'page':vcff_page_obj,'page_data':page,'tab':vcff_page_tab});
                }
                // Form load actions
                vcff_do_action('meta_refresh_do_page',{'page':vcff_page_obj,'page_data':page,'tab':vcff_page_tab});
                // Form load actions
                vcff_do_action('meta_refresh_after_page',{'page':vcff_page_obj,'page_data':page,'tab':vcff_page_tab});

                var present_groups = [];

                if (typeof page.groups != 'undefined') {
                    
                    var last_group_object = false;
                    
                    $.each(page.groups,function(i,group){

                        present_groups.push(group.id);
                        // Apply any required filters
                        group = vcff_apply_filter('meta_refresh_group_data',group,{});
                        // Form load actions
                        vcff_do_action('meta_refresh_before_group',{'group_data':group});
                        
                        if ($(vcff_page_obj).find('[data-vcff-meta-group="'+group.id+'"]').length > 0) {

                            var group_obj = $(vcff_page_obj).find('[data-vcff-meta-group="'+group.id+'"]');
                            
                        } else {

                            var group_obj = $(group.html);

                            if (typeof last_group_object != "object") { 
                            
                                $(vcff_page_obj).find('.meta-page-groups').append(group_obj);    
                            } 
                            else { $(last_group_object).after(group_obj); }

                            $(group_obj).attr('data-vcff-meta-group', group.id);
                            // Form load actions
                            vcff_do_action('meta_refresh_create_group',{'group':group_obj,'group_data':group});
                        }
                        // Form load actions
                        vcff_do_action('meta_refresh_do_group',{'group':group_obj,'group_data':group});
                        // Form load actions
                        vcff_do_action('meta_refresh_after_group',{'group':group_obj,'group_data':group});

                        var present_fields = [];

                        if (typeof group.fields != 'undefined') { 

                            var last_field_object = false;

                            $.each(group.fields,function(i,field){

                                present_fields.push(field.machine_code);
                                // Apply any required filters
                                field = vcff_apply_filter('meta_refresh_field_data',field,{});
                                // Form load actions
                                vcff_do_action('meta_refresh_before_field',{'field_data':field});

                                if ($(group_obj).find('[data-vcff-meta-field="'+field.machine_code+'"]').length > 0) {

                                    var field_obj = $(group_obj).find('[data-vcff-meta-field="'+field.machine_code+'"]');

                                } else {

                                    var field_obj = $(field.html);

                                    if (typeof last_field_object != "object") { 
                                        
                                        $(group_obj).find('.meta-group-fields').append(field_obj);
                                    } 
                                    else { $(last_field_object).after(field_obj); }

                                    $(field_obj).attr('data-vcff-meta-field', field.machine_code);
                                    // Form load actions
                                    vcff_do_action('meta_refresh_create_field',{'field':field_obj,'field_data':field});
                                }
                                // Form load actions
                                vcff_do_action('meta_refresh_do_field',{'field':field_obj,'field_data':field});
                                // Form load actions
                                vcff_do_action('meta_refresh_after_field',{'field':field_obj,'field_data':field});

                                last_field_object = field_obj;
                            });
                        }
                        // Loop through present groups
                        var meta_found_fields = $(group_obj).find('[data-vcff-meta-field]');
                        // Loop through each of the found groups
                        $(meta_found_fields).each(function(){ 
                            // Retrieve the page it
                            var meta_field_name = $(this).attr('data-vcff-meta-field');
                            // If the page is not present, remove it
                            if (present_fields.indexOf(meta_field_name) == -1) { 
                                // Form load actions
                                vcff_do_action('meta_refresh_remove_field',{'field':$(this)});
                                // Remove the field
                                $(this).remove(); 
                            }
                        });
                        
                        last_group_object = group_obj;
                    });
                }
                // Loop through present groups
                var meta_found_groups = $(vcff_page_obj).find('.vcff-meta-group');
                // Loop through each of the found groups
                $(meta_found_groups).each(function(){
                    // Retrieve the page it
                    var meta_group_id = $(this).attr('data-vcff-meta-group');
                    // If the page is not present, remove it
                    if (present_groups.indexOf(meta_group_id) == -1) { 
                        // Form load actions
                        vcff_do_action('meta_refresh_remove_group',{'group':$(this)});
                        // Remove the group
                        $(this).remove();
                    }
                });
            });
            // Loop through present pages
            var meta_found_pages = $(vcff_meta_container).find('[data-vcff-meta-page]');
            // Loop through each of the found pages
            $(meta_found_pages).each(function(){
                // Retrieve the page it
                var meta_page_id = $(this).attr('data-vcff-meta-page');
                // If the page is not present, remove it
                if (present_pages.indexOf(meta_page_id) == -1) { 
                    // Form load actions
                    vcff_do_action('meta_refresh_remove_page',{'page':$(this)});
                    // Remove the tab
                    $(vcff_meta_container_tabs).find('[data-vcff-meta-tab="'+meta_page_id+'"]').remove();
                    // Remove the group
                    $(this).remove(); 
                }
            });

            if (!$(vcff_meta_container_tabs).hasClass('ui-tabs')) {
                
                $(".vcff-tabs").tabs();
            
            } else {
            
                $(".vcff-tabs").tabs("refresh");
            }

            $('.vcff-meta-field').unbind("change");

            $('.vcff-meta-field').change(function(){
                // Update all the metaboxes
                RefreshMetaContainer();
				
				RefreshMetaStatus();
            });
            // Form load actions
            vcff_do_action('meta_refresh_after',{});
            
        },'json');

    };
    
    $(document).ready(function(){
    
        if ($('#VCFF_FORM_SETTINGS').length > 0) {
        
            RefreshMetaContainer();
       
            RefreshMetaStatus();
        }

    });
    
}(window.jQuery);