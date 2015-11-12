!function($) {

    if ($('.vcff_param_machine').length == 0) { return false; }
    // Retrieve the filter parameter element
    $('.vcff_param_machine').each(function(){
        
        var machine_el = $(this);
        
        $(machine_el).find('input[type="text"]').keyup(function(){
        
            var val = $(this).val().replace(/[^a-zA-Z0-9-_]+/ig,"");
            
            $(this).val(val);
        });
    });
    
}(window.jQuery);