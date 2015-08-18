
var $ = window.jQuery;

$(document).ready(function(){ 

    $('.form-loading-alert').each(function(){
        
        var alert_obj = $(this);
        
        $(alert_obj).hide();
        
        if ($(this).hasClass('for-error')) {
            
            vcff_add_action('form_submission_failed',function(args){
                Display_Alert(args.form);
            });
            
            vcff_add_action('before_form_submission',function(args){
                Hide_Alert(args.form);
            });
            
        }
        
        if ($(this).hasClass('for-conditions')) {

            vcff_add_action('before_form_check_conditions',function(args){
                Display_Alert(args.form);
            });

            vcff_add_action('do_form_check_conditions',function(args){
                Hide_Alert(args.form);
            });
        }

        if ($(this).hasClass('for-submission')) {

            vcff_add_action('before_form_submission',function(args){
                Display_Alert(args.form);
            });

            vcff_add_action('do_form_submission',function(args){
                Hide_Alert(args.form);
            });
        }

        var Display_Alert = function() { 
            $(alert_obj).show();
        };

        var Hide_Alert = function() {
            $(alert_obj).hide();
        };

    });

});