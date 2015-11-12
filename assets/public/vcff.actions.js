var vcff_actions = [];

var vcff_add_action = function(hook,callback,priority) {
    // The existing flag
    var is_existing = false;
    // Determine the array length
    var _len = vcff_actions.length;
    // Ensure the array is long enough
    if (_len > 0) {
        // Loop through each array item
        for (var i = 0; i < _len; i++) {
            // Retrieve the action item
            var action = vcff_actions[i];
            // If the hooks are different, continue on
            if (hook != action.hook) { continue; }
            // If the functions do not match, continue on
            if (action.callback.toString() != callback.toString()) { continue; }
            // Set the flag to true
            is_existing = true;
        }
    }
    // If there is an existing action with the same callback
    if (is_existing) { return false; }
    // Create the action with the required values
    var action_item = {'hook':hook,'callback':callback};
    // If a priority has been passed
    if (typeof priority != "undefined") {
        // Add the priority value
        action_item.priority = priority;
    } // Otherwise assign with a default priority
    else { action_item.priority = 10; }
    // Push into the vcff actions
    vcff_actions.push(action_item); 
    // Sort the actions array
    vcff_actions.sort(function(a, b) { return a.priority-b.priority });
};

var vcff_do_action = function(hook,args) { 
    // Determine the array length
    var _len = vcff_actions.length;
    // Ensure the array is long enough
    if (_len == 0) { return false; }
    // Loop through each array item
    for (var i = 0; i < _len; i++) {
        // Retrieve the action item
        var action = vcff_actions[i];
        // If the hooks are different, continue on
        if (hook != action.hook) { continue; }
        // Retrieve the callback function
        var callback = action.callback;
        // Pass the args to the action
        callback(args);
    } 
};

var vcff_filters = [];

var vcff_add_filter = function(hook,callback,priority) {
    // The existing flag
    var is_existing = false;
    // Determine the array length
    var _len = vcff_filters.length;
    // Ensure the array is long enough
    if (_len > 0) {
        // Loop through each array item
        for (var i = 0; i < _len; i++) {
            // Retrieve the action item
            var filter = vcff_filters[i];
            // If the hooks are different, continue on
            if (hook != filter.hook) { continue; }
            // If the functions do not match, continue on
            if (filter.callback.toString() != callback.toString()) { continue; }
            // Set the flag to true
            is_existing = true;
        }
    }
    // If there is an existing action with the same callback
    if (is_existing) { return false; }
    // Create the action with the required values
    var filter_item = {'hook':hook,'callback':callback};
    // If a priority has been passed
    if (typeof priority != "undefined") {
        // Add the priority value
        filter_item.priority = priority;
    } // Otherwise assign with a default priority
    else { filter_item.priority = 10; }
    // Push into the vcff actions
    vcff_filters.push(filter_item);
    // Sort the actions array
    vcff_filters.sort(function(a, b) { return a.priority-b.priority });
};

var vcff_apply_filter = function(hook,value,args) {
    // Determine the array length
    var _len = vcff_filters.length;
    // Ensure the array is long enough
    if (_len == 0) { return value; }
    // Loop through each array item
    for (var i = 0; i < _len; i++) {
        // Retrieve the action item
        var filter = vcff_filters[i];
        // If the hooks are different, continue on
        if (hook != filter.hook) { continue; }
        // Retrieve the callback function
        var callback = action.callback;
        // Pass the args to the action
        value = callback(value,args);
    }
    // Return the filtered value
    return value;
};