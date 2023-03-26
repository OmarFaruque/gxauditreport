<?php

if (!defined('ABSPATH')) {
    exit;
}
if(!class_exists('GX_Api')){
class GX_Api
{


    /**
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $instance = null;

    /**
     * The version number.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $version;
    /**
     * The token.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $token;

    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->token = GX_TOKEN;

        add_action(
            'rest_api_init',
            function () {
                
                // Get Config from DB
                register_rest_route(
                    $this->token . '/v1',
                    '/config/',
                    array(
                        'methods' => 'GET',
                        'callback' => array($this, 'getConfig'),
                        'permission_callback' => array($this, 'getPermission'),
                    )
                );



                // Save new entry to DB
                register_rest_route(
                    $this->token . '/v1',
                    '/new_entry/',
                    array(
                        'methods' => 'POST',
                        'callback' => array($this, 'set_new_entry_to_db'),
                        'permission_callback' => array($this, 'getPermission'),
                    )
                );


                // Remove entire item from db 
                register_rest_route(
                    $this->token . '/v1',
                    '/remove_by_id/',
                    array(
                        'methods' => 'POST',
                        'callback' => array($this, 'gx_remove_entry_from_db'),
                        'permission_callback' => array($this, 'getPermission'),
                    )
                );

            }
        );

        // add_action( 'wp_head', array($this, 'testF') );
    }



    /**
     * Remove user item from database 
     * 
     * @param $data as array
     */
    public function gx_remove_entry_from_db($data){
        $id = (int)$data['id'];
        $remove = $this->wpdb->delete(
            $this->wpdb->prefix . 'gx_audit', 
            array('id' => $id), 
            array('%d')
        );

        $msg = 'failed';
        if($remove) $msg = 'success';

        return new WP_REST_Response(array('msg' => $msg), 200);
    }






    /**
     * @access  public
     * @param   post array 
     * @return  success message
     */
    public function set_new_entry_to_db($data){
        $data = $data['data'];
        $data['items'] = serialize($data['items']);
        if(isset($data['id']) && !empty($data['id'])){
            $id = (int)$data['id'];
            unset($data['id']);
            unset($data['inserted_at']);
            unset($data['updated_at']);
            $this->wpdb->update(
                $this->wpdb->prefix . 'gx_audit',
                $data, 
                array('id' => $id)
            );
        }else{
            $this->wpdb->insert(
                $this->wpdb->prefix . 'gx_audit', 
                $data
            );
        }

        $return = array(
            'msg' => 'success', 
            'data' => $data
        );
        return new WP_REST_Response($return, 200);
    }


    /**
     * Get all score from Database 
     */
    public function cc_get_all_score(){
        $table = $this->wpdb->prefix . 'gx_audit';
        $qry = "SELECT * FROM {$table}";
        $data = $this->wpdb->get_results($qry, OBJECT);
        foreach($data as $k => $s) $data[$k]->items = unserialize($s->items);
        return $data;
    }



    /**
     * @access  public
     * @return  config from DB
     */
    public function getConfig()
    {
        $config = array();
        $wp_users = get_users( array() );
        $wp_users = array_map(function($v){
            return(
                array('id' => $v->ID, 'name' => $v->data->display_name)
            );
        }, $wp_users);
        $config['wp_users'] = $wp_users;
        $config['gx_lists'] = $this->cc_get_all_score();
        
        return new WP_REST_Response($config, 200);
    }



    /**
     *
     * Ensures only one instance of APIFW is loaded or can be loaded.
     *
     * @param string $file Plugin root path.
     * @return Main APIFW instance
     * @see WordPress_Plugin_Template()
     * @since 1.0.0
     * @static
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Permission Callback
     **/
    public function getPermission()
    {
        if (current_user_can('administrator') || current_user_can('read')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }
}
}