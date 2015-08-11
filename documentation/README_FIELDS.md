Developing Using Fields
=======

VCFF was built with the purpose of allowing developers to extend the platform with custom fields to suit the unique requirements 
encountered with each project. The following documentation outlines how to create and add custom fields to VCFF. 

Creating a basic field
-----------

You can add fields directly to VCFF by placing field classes within the "modded" folder located within /libs/vcff_fields/context. 
This is the best option if you want to develop custom fields without too much supporting logic. If you find yourself needing additional
helper classes or more advanced supporting logic then it is advisable to consider developing a custom plugin of your own.

A VCFF field typically requires at least three files.
1. **Context Class**
This class provides all of the essential data about the field type. Typically it will be named {NS}_{FIELD_NAME}.php
2. **Item Class**
This class is instanced with the creation of each field of that type within a form. Typically it will be named {NS}_{FIELD_NAME}_Item.php
3. **Template File**
This file contains the actual HTML which will be displayed when a field is displayed. Typically it will be named {NS}_{FIELD_NAME}_Item.tpl.php

### 1. Creating the field context class

Create a new conext class within the modded folder

```php
class EX_Example_Field {
    // This will be the wordpress shortcode
    static $field_type = 'ex_example_field';
    // This will be displayed within Visual Composer
    static $field_title = 'Example Field';
    // This identifies the class name of the Item Class
    static $item_class = 'EX_Example_Field_Item';
    // This denotes the class as a context class
    static $is_context = true;
}
```

### 2. Adding Visual Composer & field settings

```php
class EX_Example_Field {
    // This will be the wordpress shortcode
    static $field_type = 'ex_example_field';
    // This will be displayed within Visual Composer
    static $field_title = 'Example Field';
    // This identifies the class name of the Item Class
    static $item_class = 'EX_Example_Field_Item';
    // This denotes the class as a context class
    static $is_context = true;
    // Bare minimum settings for a vcff field
    static function VC_Params() {
        // Return an array of visual composer parameters
        return array(
            'params' => array(
                array (
                    "type" => "vcff_machine",
                    "heading" => __ ( "Machine Code", VCFF_FORM ),
                    "param_name" => "machine_code",
                ), 
                array (
                    "type" => "textfield",
                    "heading" => __ ( "Label (Data Entry)", VCFF_FORM ),
                    "param_name" => "field_label",
                    'value' => __('Enter a field label..'),
                    'admin_label' => true,
                ),
                array (
                    "type" => "textfield",
                    "heading" => __ ( "Label (Data Viewing)", VCFF_FORM ),
                    "param_name" => "view_label",
                ),
            )
        );
    }
    // Bare mimimum field params
    static function Field_Params() {
        // Return any field params
        return array();
    }
}
```

### 2. Registering the field with VCFF

```php
        );
    }
    // Bare mimimum field params
    static function Field_Params() {
        // Return any field params
        return array();
    }
}

vcff_map_field('EX_Example_Field');
```

### 3. Creating the field item class

### 4. Creating the field template

### 5. Adding Custom CSS & JS

Creating fields in custom plugins
-----------

### 1. Creating the field context class

Using advanced field validation
-----------

### WP Hooks, VCFF Hooks and Field Method List

Using advanced field conditions
-----------

### WP Hooks, VCFF Hooks and Field Method List

Adding field AJAX functionality
-----------

### WP Hooks, VCFF Hooks and Field Method List