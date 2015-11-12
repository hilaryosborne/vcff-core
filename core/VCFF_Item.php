<?php

class VCFF_Item {
    
    public $_actions;
    
    public $_filters;
    
    public function Add_Action($action,$function,$priority = 10) {
        // Add to the actions
        $this->_actions[$action][] = array(
            'function' => $function,
            'priority' => $priority
        );
        // Return for chaining
        return $this;
    }

    public function Do_Action($action,$args=array()) {
        // Retrieve the list of actions
        $actions = $this->_actions;
        // If there are no actions, return out
        if (!is_array($actions) || !isset($actions[$action])) { return; }
        // Retrieve the list of actions
        $selected_actions = $actions[$action];
        // Sort the actions
        usort($selected_actions,function($a,$b){
            // If the priorities are the same
            if ($a['priority'] == $b['priority']) { return 0; }
            // Otherwise return the difference
            return ($a['priority'] < $b['priority']) ? -1 : 1;
        });
        // Loop through each action
        foreach ($selected_actions as $k => $_action) {
            // Call the action function
            call_user_func($_action['function'], $args);
        }
        // Return for chaining
        return $this;
    }
    
    public function Add_Filter($filter,$function,$priority = 10) {
        // Add to the actions
        $this->_filters[$filter][] = array(
            'function' => $function,
            'priority' => $priority
        );
        // Return for chaining
        return $this;
    }

    public function Apply_Filters($filter,$value,$args) {
        // Retrieve the list of actions
        $filters = $this->_filters;
        // If there are no actions, return out
        if (!is_array($filters) || !isset($filters[$filter])) { return $value; }
        // Retrieve the list of actions
        $selected_filters = $filters[$filter];
        // Sort the actions
        usort($selected_filters,function($a,$b){
            // If the priorities are the same
            if ($a['priority'] == $b['priority']) { return 0; }
            // Otherwise return the difference
            return ($a['priority'] < $b['priority']) ? -1 : 1;
        });
        // Loop through each action
        foreach ($selected_filters as $k => $_filter) {
            // Call the action function
            $value = call_user_func($_filter['function'], $value, $args);
        }
        // Return the value
        return $value;
    }

    // Add a form alert
    public function Add_Alert($message,$type) {
        // Ensure the type is allowable
        if (!in_array($type,array('success','info','warning','danger'))) { return $this; }
        // If there are no current alerts matching the type, populate with empty array
        if (!isset($this->alerts[$type])) { $this->alerts[$type] = array(); }
        // Add the alert message
        $this->alerts[$type][] = $message;
        // Return for chaining
        return $this;
    }
    
    public function Get_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // Otherwise return the alerts
        return $this->alerts;
    }
    
    public function Get_Alerts_HTML() {
        // Retrieve the current alerts
        $alerts = $this->alerts;
        // If there are no alerts, return out
        if (!$alerts || !is_array($alerts) || count($alerts) == 0) { return; }
        // Start the html var
        $html = '';
        // Populate with any danger alerts
        $html .= $this->Get_Danger_Alerts_HTML();
        // Populate with any success alerts
        $html .= $this->Get_Success_Alerts_HTML();
        // Populate with any warning alerts
        $html .= $this->Get_Warning_Alerts_HTML();
        // Populate with any info alerts
        $html .= $this->Get_Info_Alerts_HTML();
        // Otherwise return the alerts
        return $html;
    }
    
    public function Get_Success_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['success'])) { return; }
        // Otherwise return the alerts
        return $this->alerts['success'];
    }
    
    public function Get_Success_Alerts_HTML() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['success'])) { return; }
        // Start the html content
        $html = '<div class="alert alert-success" role="alert">';
        // Otherwise return the alerts
        foreach ($this->alerts['success'] as $k => $alert) {
            // Append the alert html content
            $html .= '<div class="alert-text">'.$alert.'</div>';
        }
        // End the html content
        $html .= '</div>';
        // Return the alert html
        return $html;
    }
    
    public function Get_Info_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['info'])) { return; }
        // Otherwise return the alerts
        return $this->alerts['info'];
    }
    
    public function Get_Info_Alerts_HTML() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['info'])) { return; }
        // Start the html content
        $html = '<div class="alert alert-info" role="alert">';
        // Otherwise return the alerts
        foreach ($this->alerts['info'] as $k => $alert) {
            // Append the alert html content
            $html .= '<div class="alert-text">'.$alert.'</div>';
        }
        // End the html content
        $html .= '</div>';
        // Return the alert html
        return $html;
    }
    
    public function Get_Warning_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['warning'])) { return; }
        // Otherwise return the alerts
        return $this->alerts['warning'];
    }
    
    public function Get_Warning_Alerts_HTML() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['warning'])) { return; }
        // Start the html content
        $html = '<div class="alert alert-warning" role="alert">';
        // Otherwise return the alerts
        foreach ($this->alerts['warning'] as $k => $alert) {
            // Append the alert html content
            $html .= '<div class="alert-text">'.$alert.'</div>';
        }
        // End the html content
        $html .= '</div>';
        // Return the alert html
        return $html;
    }
    
    public function Get_Danger_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['danger'])) { return; }
        // Otherwise return the alerts
        return $this->alerts['danger'];
    }
    
    public function Get_Danger_Alerts_HTML() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['danger'])) { return; }
        // Start the html content
        $html = '<div class="alert alert-danger" role="alert">';
        // Otherwise return the alerts
        foreach ($this->alerts['danger'] as $k => $alert) {
            // Append the alert html content
            $html .= '<div class="alert-text">'.$alert.'</div>';
        }
        // End the html content
        $html .= '</div>';
        // Return the alert html
        return $html;
    }
}