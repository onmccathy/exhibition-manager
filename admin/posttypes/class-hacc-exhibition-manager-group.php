<?php
/**
 * The Venue custom post type functionality of the plugin.
 *
 * @link       http://huttartsites.co.nz
 * @since      1.0.0
 *
 * @package    Hacc_Exhibition_Manager
 * @subpackage Hacc_Exhibition_Manager/admin
 */


class Hacc_Exhibition_Manager_Group {
    
    /* Declare fields and copnstants */
        const POST_TYPE         = 'hacc_group';
        const SAVENONCE             = 'hacc_group_save_nonce';
         const POST_TYPE_NAME       = 'Group';
        const METABOX_TITLE         = 'Group Details';
        const PARENT_POST_TYPE      = 'hacc_venue';
        const START_TIME            = 'hacc_StartTime';
        const END_TIME              = 'hacc_EndTime'; 
        const DAY_OF_WEEK           = 'hacc_DayOfWeek';
        const FREQUENCY             = 'hacc_Frequency';
        const CONVENOR              = 'hacc_Convenor';
        const CONVENOR_PHONE_NUMBER = 'hacc_phone_number';
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
        
        /**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'hacc';
        
        

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
        
        public function create_post_type() {
            
            $singular = self::POST_TYPE_NAME;
            $plural = self::POST_TYPE_NAME . 's';

            $labels = array(
                'name'                      => $plural,
                 'singular_name'            => $singular,
                 'add_name'                 => 'Add New',
                 'add_new_item'             => 'Add New ' . $singular,
                 'edit'                     => 'Edit',
                 'edit_item'                => 'Edit ' . $singular,
                 'new_item'                 => 'New ' . $singular,
                 'view'                     => 'View ' . $singular,
                 'view_item'                => 'View ' . $singular,
                 'search_term'              => 'Search ' . $plural,
                 'parent'                   => 'Parent ' . $singular,
                 'not_found'                => 'No ' . $plural . ' found',
                 'not_found_in_trash'       => 'No ' . $plural . ' in Trash'
            );

            $args = array(
                'labels'                    => $labels,
                'public'                    => true,
                'publicly_queryable'        => true,
                'exclude_from_search'       => false,
                'show_in_nav_menus'         =>true,
                'show_ui'                   => true,
                'show_in_menu'              => true,
                'show_in_admin_bar'         => true,
                'menu_position'             => '7',
                'menu_icon'                 => 'dashicons-businessman',
                'can_export'                => true,
                'delete_with_user'          => false,
                'hierarchical'              => false,
                'has_archive'               => true,
                'query_var'                 => true,
                'taxonomies'                => array('category', 'post_tag'),
                'capability_type'           => 'post',
                'map_meta_cap'              => true,
                'rewrite'                   => array(
                                'slug'              => substr(self::POST_TYPE,5),
                                    'with_front'    => true,
                                    'pages'         => true,
                                    'feeds'         => false
                ),
                'supports'                  => array(
                                    'title',
                                    'editor',
                                    'thumbnail',
                                    'excerpt',    
                )
            );
            register_post_type(self::POST_TYPE, $args);

        }
        
        
        
        /**
         * get templates
         * @param type $template
         * @return type
         */
        
        public function get_template($template) {
            
            global $post;
            
            if(!isset($post)) {
                return $template;
            }
            
            if ($post->post_type !== self::POST_TYPE) {
                return $template;
            }
            
            return hacc_get_post_type_template(substr(self::POST_TYPE,5), $template);

        }
        
        /**
         * create release meta box 
         */
        function create_metabox() {
 
            add_meta_box(
                self::POST_TYPE . '_metabox',
                __(self::METABOX_TITLE),
                array($this, 'meta_callback'),
                self::POST_TYPE,
                'normal',
                'core'
            );
        }
        
        /**
         * Displays Exhibition Metabox.
         * @param type $post
         */
        function meta_callback($post) {

            // get post metadata
            wp_nonce_field(basename(__FILE__),  self::SAVENONCE);

            require_once plugin_dir_path( __FILE__ ) . 'metaboxes/hacc-group-metabox.php';
 
            wp_reset_postdata(); 
            
        }
        
                /*
         * Update post parent product
         */
        
        function save_meta_data($post_id) {
            
                       
            $is_autosave = wp_is_post_autosave($post_id);
            $is_revision = wp_is_post_revision($post_id);
            $is_valid_nonce = false;
            if (isset($_POST[self::SAVENONCE])) {
                if(wp_verify_nonce($_POST[self::SAVENONCE],  basename(__FILE__))) {
                    $is_valid_nonce = true;
                }
            }
                         
            if (!$is_valid_nonce || $is_autosave || $is_revision) {
                return;
            }
            
            // link exhibition to venue/gallery. 
            
            if (isset($_POST[self::PARENT_POST_TYPE])) {
                                
                $args = array(
                    'ID'            => $post_id,
                    'post_parent'   => sanitize_text_field($_POST[self::PARENT_POST_TYPE]),
                );
                // unhook , post and rehook function so it doesn't loop infinitely
                // see Wordpress codex wp_update_post 
                remove_action('save_post_hacc_group', array($this,'save_meta_data'),20);
                wp_update_post( $args );
                add_action('save_post_hacc_group', array($this,'save_meta_data'),20);
                
               
            }
            
            if (isset($_POST[self::START_TIME])) {
                $time = sanitize_text_field($_POST[self::START_TIME]);
                update_post_meta($post_id, self::START_TIME, $time);
            }
            
            if (isset($_POST[self::END_TIME])) {
                $time = sanitize_text_field($_POST[self::END_TIME]);
                update_post_meta($post_id, self::END_TIME, $time);
            }
            
            if (isset($_POST[self::DAY_OF_WEEK])) {
                $dayofWeek = sanitize_text_field($_POST[self::DAY_OF_WEEK]);
                update_post_meta($post_id, self::DAY_OF_WEEK, $dayofWeek);
            }
            
            if (isset($_POST[self::FREQUENCY])) {
                $frequency = sanitize_text_field($_POST[self::FREQUENCY]);
                update_post_meta($post_id, self::FREQUENCY, $frequency );
            }
            
            if (isset($_POST[self::CONVENOR])) {
                $convenor = sanitize_text_field($_POST[self::CONVENOR]);
                update_post_meta($post_id, self::CONVENOR, $convenor );
            }
            
            if (isset($_POST[self::CONVENOR_PHONE_NUMBER])) {
                $phone = sanitize_text_field($_POST[self::CONVENOR_PHONE_NUMBER]);
                update_post_meta($post_id, self::CONVENOR_PHONE_NUMBER, $phone );
            }
        }
}