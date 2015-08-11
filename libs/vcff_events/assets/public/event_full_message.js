!function($) {
    
    vcff_add_action('after_form_check_conditions',function(args){
        new Event_Full_Message(args);
    },10);
    
    vcff_add_action('after_form_submission',function(args){
        new Event_Full_Message(args);
    },10);
    
    var Event_Full_Message = function(args) {
    
        var _form_el = args.form;
        
        var _json = args.json;
        
        var _data = _json.data;
        
        if (typeof _data.events == "undefined") { return false; }
        
        if (typeof _data.events.full_message == "undefined") { return false; }
        
        $(_form_el).hide();
        
        $.each(_data.events.full_message,function(i,_html){
        
            $(_form_el).after(_html);
        });
    }
    
}(window.jQuery);