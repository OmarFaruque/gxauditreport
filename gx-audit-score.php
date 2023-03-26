<?php
/**
 * @package gx-audit-score
 * @version 1.0.0
 * @author {omar} ronymaha@gmail.com
 */
/*
Plugin Name: GX Audit Score
Plugin URI: #
Description: Audit score report and chart based on pre entired data
Author: Omar
Version: 1.0.0
Text Domain: gx-audit
Author URI: https://www.linkedin.com/in/omar-faruque-bd/
*/


define('GX_TOKEN', 'gx');
define('GX_VERSION', '1.0.0');
define('GX_FILE', __FILE__);
define('GX_PLUGIN_NAME', 'GX Audit Score');

// Helpers.
require_once realpath(plugin_dir_path(GX_FILE)) . DIRECTORY_SEPARATOR . 'includes/helpers.php';


// Load published classes
if (!function_exists('GX_autoloader')) {

    function GX_autoloader($class_name)
    {
        if (0 === strpos($class_name, 'GX')) {
            $classes_dir = realpath(plugin_dir_path(GX_FILE)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = 'class-' . str_replace('_', '-', strtolower($class_name)) . '.php';
            require_once $classes_dir . $class_file;
        }
    }
}
spl_autoload_register('GX_autoloader');


// Backend interface, features & hooks.
if (!function_exists('GX_Backend')) {
    function GX_Backend()
    {   
        return GX_Backend::instance(__FILE__);
    }
}


// Rest API interface, features & hooks.
if (!function_exists('GX_Api')) {
    function GX_Api()
    {   
        return GX_Api::instance(__FILE__);
    }
}

/**
 * Call backend functions
 */
if (is_admin()) {
    GX_Backend();
}

/**
 * Call API
 * 
 */
GX_Api();