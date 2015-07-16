
var Event_List_Obj = {};

vcff_add_action('meta_refresh_create_field',function(args){

    if (typeof args.field_data == "undefined") { return false; }
    
    if (typeof args.field_data.machine_code == "undefined") { return false; }
    
    if (args.field_data.machine_code != "events_wizard") { return false; }
    
    var $ = window.jQuery;
    
    Event_List_Obj = Event_List($('#VCFF_Meta_Submission_Events'));
});