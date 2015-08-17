!function($) {
    
    vcff_add_action('event_do_refresh',function(args){
        new Event_Clear(args);
    },10);
    
    var Event_Clear = function(args) {
    
        var _form_el = args.form;
        
        var _event = args.event;
        
        var _event_data = args.event_data;
        
        if (typeof _event != "clear") { return false; }
        
        $(_form_el).get(0).reset();
    }
    
}(window.jQuery);