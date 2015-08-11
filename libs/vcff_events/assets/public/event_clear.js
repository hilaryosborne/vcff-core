!function($) {
    
    vcff_add_action('after_form_check_conditions',function(args){
        new Event_Clear(args);
    },10);
    
    vcff_add_action('after_form_submission',function(args){
        new Event_Clear(args);
    },10);
    
    var Event_Clear = function(args) {
    
        var _form_el = args.form;
        
        var _json = args.json;
        
        var _data = _json.data;
        
        if (typeof _data.events == "undefined") { return false; }
        
        if (typeof _data.events.clear == "undefined") { return false; }
        
        $(_form_el).get(0).reset();
    }
    
}(window.jQuery);