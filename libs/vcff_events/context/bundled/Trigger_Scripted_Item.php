<?php

class Trigger_Scripted_Item extends VCFF_Trigger_Item {
    
    public function Render() {
        // Retrieve the context director
        $trigger_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // If context data was passed
        $posted_data = $this->data;
        // Start gathering content
        ob_start();
        // Include the template file
        include($trigger_dir.'/'.get_class($this).'.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function Check() {
        // Returns false everytime
        return true;
    }
}