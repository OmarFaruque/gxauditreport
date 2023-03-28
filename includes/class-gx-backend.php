<?php

/**
 * Load Backend interface, features and hooks
 *
 * @class   GX_Backend
 */


if (!defined('ABSPATH')) {
    exit;
}


class GX_Backend
{
    
    /**
     * Class instance for singleton  class
     *
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $instance = null;


    /**
     * The token.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $token;

    /**
     * The main plugin file.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $file;

    /**
     * The main plugin directory.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $dir;

    /**
     * The plugin assets directory.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_dir;

    /**
     * Suffix for Javascripts.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $script_suffix;

    /**
     * The plugin assets URL.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_url;

    /**
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $version;
    
    /**
     * The plugin hook suffix.
     *
     * @var     array
     * @access  public
     * @since   1.0.0
     */
    public $hook_suffix = array();

    /**
     * WP DB
     */
    private $wpdb;


    
    /**
     * Constructor function.
     *
     * @access  public
     * @param string $file plugin start file path.
     * @since   1.0.0
     */
    public function __construct($file = '')
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->version = GX_VERSION;
        $this->token = GX_TOKEN;
        $this->file = $file;
        $this->dir = dirname($this->file);
        $this->assets_dir = trailingslashit($this->dir) . 'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));
        $this->script_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        $plugin = plugin_basename($this->file);
        
        // Add settings page to $hook_suffix 
        array_push($this->hook_suffix, 'gx-settings');

        // add action links to link to link list display on the plugins page.
        add_filter("plugin_action_links_$plugin", array($this, 'pluginActionLinks'));

        // reg activation hook.
        register_activation_hook($this->file, array($this, 'install'));
        
        // reg deactivation hook.
        register_deactivation_hook($this->file, array($this, 'deactivation'));


        
        // enqueue scripts & styles.
        add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'), 10, 1);
        add_action('admin_enqueue_scripts', array($this, 'adminEnqueueStyles'), 10, 1);
        

        // Admin Menu 
        add_action( 'admin_menu', array($this, 'gx_admin_menu_page_hook') );
        
    }
    
    /**
     * @access  private
     * @desc    Add saperate admin menu for acotrs shipping table
     * 
    */

    public function gx_admin_menu_page_hook(){
        $this->hook_suffix[] = add_menu_page( 
            __('GX Audit', 'gx-audit'), 
            __('GX Audit', 'gx-audit'), 
            'manage_options',
            'gx-audit', 
            array($this, 'gx_admin_page_callback'), 
            'dashicons-media-spreadsheet', 
            99
        );
    }


    /**
     * @access  public
     * @return  page content
     * 
     */
    public function gx_admin_page_callback(){
        echo (
            '<div id="' . $this->token . '_ui_root" class="bg-white border-round-5 pb-5">
                <div class="' . $this->token . '_loader"><p>' . __('Loading User Interface...', 'gx-audit') . '</p></div>
            </div>'
        );
    }
 

    /**
     * Ensures only one instance of Class is loaded or can be loaded.
     *
     * @param string $file plugin start file path.
     * @return Main Class instance
     * @since 1.0.0
     * @static
     */
    public static function instance($file = '')
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($file);
        }
        return self::$instance;
    }


    /**
     * Show action links on the plugin screen.
     *
     * @param mixed $links Plugin Action links.
     *
     * @return array
     */
    public function pluginActionLinks($links)
    {
        $action_links = array(
            'settings' => '<a href="' . admin_url('admin.php?page=' . $this->token) . '">'
                . __('Configure', 'gx-audit') . '</a>'
        );

        return array_merge($action_links, $links);
    }




    /**
     * Installation. Runs on activation.
     *
     * @access  public
     * @return  void
     * @since   1.0.0
     */
    public function install()
    {
        global $wpdb;
        include_once ABSPATH . '/wp-admin/includes/upgrade.php';
        $table_charset = '';
        $prefix = $wpdb->prefix;
        $gx_table = $prefix . 'gx_audit';
        $charset_collate = $wpdb->get_charset_collate();

        $create_gx_table_sql = "CREATE TABLE {$gx_table} (
            id int(11) NOT NULL auto_increment,
            name varchar(250) NOT NULL,
            user_id int(11) NOT NULL,
            location text NOT NULL,
            gx_id varchar(200) NOT NULL,
            type varchar(200) NOT NULL,
            staff int(11) NOT NULL,
            touch_points int(11) NOT NULL,
            sector_number int(11) NOT NULL,
            logourl text NOT NULL,
            logoid int(11) NOT NULL,
            items text NOT NULL,
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            inserted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)) $charset_collate;";

            maybe_create_table($gx_table, $create_gx_table_sql);
    }


    /**
     * Load admin CSS.
     *
     * @access  public
     * @return  void
     * @since   1.0.0
     */
    public function adminEnqueueStyles($screen)
    {
        
        if (!isset($this->hook_suffix) || empty($this->hook_suffix)) {    
            return;
        }
        if (in_array($screen, $this->hook_suffix, true)) {
            wp_register_style($this->token . '-admin', esc_url($this->assets_url) . 'css/element.css', array(), $this->version);
            wp_enqueue_style($this->token . '-admin');
            wp_enqueue_style($this->token . '-admin-wrapper');
        }
    }

    /**
     * Load admin Javascript.
     *
     * @access  public
     * @return  void
     * @since   1.0.0
     */
    public function adminEnqueueScripts()
    {
        if (!isset($this->hook_suffix) || empty($this->hook_suffix)) {   
            return;
        }

        $screen = get_current_screen();

        if (in_array($screen->id, $this->hook_suffix, true)) {

            if ( ! did_action( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            }

            wp_enqueue_script($this->token . '-boot', esc_url($this->assets_url) . 'js/boot.js', array('jquery'), $this->version, true);
            // Enqueue custom backend script.
            wp_enqueue_script($this->token . '-backend', esc_url($this->assets_url) . 'js/gx-audit.js', array($this->token.'-boot'), $this->version, true);
            wp_add_inline_script( $this->token . '-boot', 'window.lodash = _.noConflict();', 'after' );
            // Localize a script.
            wp_localize_script(
                $this->token . '-backend',
                $this->token . '_object',
                array(
                    'api_nonce' => wp_create_nonce('wp_rest'),
                    'root' => rest_url($this->token . '/v1/'),
                    'assets_url' => $this->assets_url,
                    'base_url' => get_admin_url( '/' ), 
                    'ajaxurl' => admin_url('admin-ajax.php')
                )
            );
        }
    }

 
    // wp_localize_script('plugin_name_boot', 'PluginNameAdmin', $PluginNameAdminVars);

    
    /**
     * Deactivation hook
     */
    public function deactivation(){}

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->version);
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->version);
    }
}