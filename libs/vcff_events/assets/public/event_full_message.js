!function($) {
    
    vcff_add_action('event_do_refresh',function(args){
        new Event_Full_Message(args);
    },10);

    var Event_Full_Message = function(args) {
    
        var _form_el = args.form;
        
        var _event = args.event;
        
        var _event_data = args.event_data;
        
        if (_event != "full_message") { return false; }
        
        $(_form_el).hide();
        
        $.each(_event_data,function(i,_html){
        
            $(_form_el).after(_html);
        });
    }
    
}(window.jQuery);