<?php

/**
 * Load Frontend interface, features and hooks
 *
 * @class   GX_Public
 */


if (!defined('ABSPATH')) {
    exit;
}


class GX_Public
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
        
        

        
        // enqueue scripts & styles.
        add_action('wp_enqueue_scripts', array($this, 'publicEnqueueScripts'), 10, 1);
     
        

        //Add shortcode 
        add_shortcode( 'gx_score_board', array($this, 'gx_score_board_callback') );
        
    }
    


    public function gx_score_board_callback(){
        return (
            '<div id="' . $this->token . '_ui_public" class="bg-white border-round-5 pb-5">
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
     * Load admin Javascript.
     *
     * @access  public
     * @return  void
     * @since   1.0.0
     */
    public function publicEnqueueScripts()
    {
        wp_register_style($this->token . '-publiccss', esc_url($this->assets_url) . 'css/public-gx-score.css', array(), $this->version);
        wp_enqueue_style($this->token . '-publiccss');
        wp_enqueue_script($this->token . '-boot', esc_url($this->assets_url) . 'js/boot.js', array('jquery'), $this->version, true);
        // Enqueue custom backend script.
        wp_enqueue_script($this->token . '-public', esc_url($this->assets_url) . 'js/gx-public-score-board.js', array($this->token.'-boot'), $this->version, true);
        // Localize a script.
        wp_localize_script(
            $this->token . '-public',
            $this->token . '_object',
            array(
                'api_nonce' => wp_create_nonce('wp_rest'),
                'root' => rest_url($this->token . '/v1/'),
                'assets_url' => $this->assets_url,
                'base_url' => get_admin_url( '/' ), 
                'user_id' => get_current_user_id()
            )
        );
        
    }

 

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