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


                // Frontend data load 
                register_rest_route(
                    $this->token . '/v1',
                    '/frontend_config/',
                    array(
                        'methods' => 'POST',
                        'callback' => array($this, 'gx_get_single_user_data'),
                        'permission_callback' => array($this, 'getPermission'),
                    )
                );

                //User meta details
                register_rest_route(
                    $this->token . '/v1',
                    '/get_user_details/',
                    array(
                        'methods' => 'POST',
                        'callback' => array($this, 'gx_get_single_user_metadata'),
                        'permission_callback' => array($this, 'getPermission'),
                    )
                );


                // Update Settings 
                register_rest_route(
                    $this->token . '/v1',
                    '/update_settings/',
                    array(
                        'methods' => 'POST',
                        'callback' => array($this, 'gx_update_settings'),
                        'permission_callback' => array($this, 'getPermission'),
                    )
                );


                // Get Settings data
                register_rest_route(
                    $this->token . '/v1',
                    '/update_settings/',
                    array(
                        'methods' => 'GET',
                        'callback' => array($this, 'gx_get_settings'),
                        'permission_callback' => function(){return true;},
                    )
                );

            }
        );


        // add_action( 'wp_head', function(){

        //     $table = $this->wpdb->prefix . 'gx_audit';
        //     $qry = $this->wpdb->prepare("SELECT `items` FROM {$table} WHERE `user_id`=%d ORDER BY `id` ASC LIMIT 1", 1);
        //     $data = $this->wpdb->get_row($qry, OBJECT);

        //     echo 'data array <pre>';
        //     print_r($data);
        //     echo '</pre>';
        // } );

    }


    /**
     * Get settings data 
     * Query from options table 
     * Request process from settings tab 
     */
    function gx_get_settings(){
        $msg = get_option( 'gx_display_message');
        return new WP_REST_Response(array('gx_display_message' => $msg), 200);
    }

    /**
     * Update settings from Settings tab 
     * Store data to options table
     */
    public function gx_update_settings($data){
        $gx_msg = $data['gx_display_message'];
        update_option( 'gx_display_message', $gx_msg, true );
        return new WP_REST_Response(array('msg' => 'success'), 200);        
    }



    /**
     * Get single user meta data for backend
     */
    public function gx_get_single_user_metadata($data){
        $user_id = $data['id'];
        $v = new stdClass();
        $v->name = get_user_meta( $user_id, 'name', true );
        $v->location = get_user_meta( $user_id, 'location', true );
        $v->gx_id = get_user_meta( $user_id, 'gx_id', true );
        $v->type = get_user_meta( $user_id, 'type', true );
        $v->staff = get_user_meta( $user_id, 'staff', true );
        $v->touch_points = get_user_meta( $user_id, 'touch_points', true );
        $v->sector_number = get_user_meta( $user_id, 'sector_number', true );
        $v->logourl = get_user_meta( $user_id, 'logourl', true );
        $v->logoid = get_user_meta( $user_id, 'logoid', true );

        $table = $this->wpdb->prefix . 'gx_audit';
        $qry = $this->wpdb->prepare("SELECT `items`, `socials` FROM {$table} WHERE `user_id`=%d ORDER BY `id` ASC LIMIT 1", $user_id);
        $dataqry = $this->wpdb->get_row($qry, OBJECT);
        $items = '';
        if($dataqry) $items = json_decode($dataqry->items);
        if($dataqry) $socials = json_decode($dataqry->socials);
        return new WP_REST_Response(array('users' => $v, 'items' => $items, 'socials' => $socials), 200);
    }

    /**
     * Get Single user data
     */
    public function gx_get_single_user_data($data){
        $table = $this->wpdb->prefix . 'gx_audit';
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $qry = $this->wpdb->prepare("SELECT * FROM {$table} WHERE `user_id`=%d AND `date` != %s", $data['id'], '0000-00-00 00:00:00');

        $availableDates = $this->wpdb->get_results($qry, OBJECT);
        $availableDates = array_map(function($v){
            return array(
                'value' => $v->date, 
                'label' => date('jS F, Y', strtotime($v->date))
            );
        }, $availableDates);

        $msg = '';
        if(!is_null($start_date)){
            

            $msg='inside';
            $start_date     = date('Y-m-d', strtotime($start_date));
            $end_date       = date('Y-m-d', strtotime($end_date));

            $qry .= $this->wpdb->prepare(" AND DATE(`date`) = %s OR DATE(`date`)=%s", $start_date, $end_date);    
        }
        $qry .= $this->wpdb->prepare(" ORDER BY `date` ASC LIMIT 2");

        $results = $this->wpdb->get_results($qry);
        $socialArray = array();
        $results = array_map(function($v) use ($socialArray){
            $v->name = get_user_meta( $v->user_id, 'name', true );
            $v->location = get_user_meta( $v->user_id, 'location', true );
            $v->gx_id = get_user_meta( $v->user_id, 'gx_id', true );
            $v->type = get_user_meta( $v->user_id, 'type', true );
            $v->staff = get_user_meta( $v->user_id, 'staff', true );
            $v->touch_points = get_user_meta( $v->user_id, 'touch_points', true );
            $v->sector_number = get_user_meta( $v->user_id, 'sector_number', true );
            $v->logourl = get_user_meta( $v->user_id, 'logourl', true );
            $v->logoid = get_user_meta( $v->user_id, 'logoid', true );
            $v->socials = json_decode($v->socials);
            return $v;
        }, $results);

        // Social process 
        foreach($results as $k => $sr){
            foreach($sr->socials as $s){
                if(!isset($socialArray[$s->label])) $socialArray[$s->label] = array(0 => 0, 1 => 0);
                $socialArray[$s->label][$k] = $s->value;
            }
        }
       
        return new WP_REST_Response(array('available_dates' => $availableDates, 'results' => $results, 'gx_display_message' => get_option( 'gx_display_message', false ), 'socials' => $socialArray), 200);
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
        date_default_timezone_set("Asia/Dhaka");
        $data = $data['data'];
        $data['items'] = json_encode($data['items']);
        $data['socials'] = json_encode($data['socials']);
        $data['date'] = date('Y-m-d', strtotime($data['date']));

        // Update user meta
        if(isset($data['user_id']) && !empty($data['user_id'])){
            $user_id = $data['user_id'];
            update_user_meta( $user_id, 'name', $data['name'] );
            update_user_meta( $user_id, 'location', $data['location'] );
            update_user_meta( $user_id, 'gx_id', $data['gx_id'] );
            update_user_meta( $user_id, 'type', $data['type'] );
            update_user_meta( $user_id, 'staff', $data['staff'] );
            update_user_meta( $user_id, 'touch_points', $data['touch_points'] );
            update_user_meta( $user_id, 'sector_number', $data['sector_number'] );
            update_user_meta( $user_id, 'logourl', $data['logourl'] );
            update_user_meta( $user_id, 'logoid', $data['logoid'] );
        }

        unset($data['location']);
        unset($data['gx_id']);
        unset($data['type']);
        unset($data['staff']);
        unset($data['touch_points']);
        unset($data['sector_number']);
        unset($data['logourl']);
        unset($data['logoid']);
        unset($data['name']);
        

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

        $data = array_map(function($v){
            $v->name = get_user_meta( $v->user_id, 'name', true );
            $v->location = get_user_meta( $v->user_id, 'location', true );
            $v->gx_id = get_user_meta( $v->user_id, 'gx_id', true );
            $v->type = get_user_meta( $v->user_id, 'type', true );
            $v->staff = get_user_meta( $v->user_id, 'staff', true );
            $v->touch_points = get_user_meta( $v->user_id, 'touch_points', true );
            $v->sector_number = get_user_meta( $v->user_id, 'sector_number', true );
            $v->logourl = get_user_meta( $v->user_id, 'logourl', true );
            $v->logoid = get_user_meta( $v->user_id, 'logoid', true );
            return $v;
        }, $data);
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