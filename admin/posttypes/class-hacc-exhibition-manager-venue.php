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


class Hacc_Exhibition_Manager_Venue {
    
    /* Declare fields and copnstants */
       
        const POST_TYPE         = "hacc_venue";
        const POST_TYPE_NAME    = 'Venue';

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
                'hierarchical'             => false,
                'has_archive'              => true,
                'query_var'                => true,
                'capability_type'          => 'post',
                'map_meta_cap'             => true,
                'rewrite'                  => array(
                                'slug'              => substr(self::POST_TYPE,5),
                                    'with_front'    => true,
                                    'pages'         => true,
                                    'feeds'         => false
                ),
                'supports'                  => array(
                                    'title',
                                    'editor',
                                    'thumbnail'
                )
            );
            register_post_type(self::POST_TYPE, $args);

        }
        
        /**
         * Create Venue Type Taxomony
         * 
         */
        public function create_venue_type_taxonomy() {
            
            $plural = 'Venue Types';
            $singular = 'Venue Type';
            
            $labels = array(
                'name'                      => $plural,
                'singular_name'             => $singular,
                'search_items'              => 'Search ' . $plural,
                'popular_items'             => 'Popular ' . $plural,
                'all_items'                 => 'All '. $plural,
                'edit_item'                 => 'Edit ' . $singular,
                'update_item'               => 'Update ' . $singular,
                'add_new_item'              => 'Add New ' . $singular,
                'new_item_name'             => 'New ' . $singular . ' Name',
                'separate_items_with_commas'=> 'Separate ' . $plural . ' with commas',
                'add_or_remove_items'       => 'Add or remove '.$plural,
                'Choose_from_most_used'     => 'Choose from most used ' . $singular,
                'not_found'                 => 'No ' . $singular . ' found',
                'menu_name'                 => $plural
            );
            
            $args = array(
                'hierarchical'              => true,
                'labels'                    => $labels,
                'show_ui'                   => true,
                'show_admin_column'         => true,
                'update_count_callback'     => '_update_post_term_count',
                'query_var'                 => true,
                'rewrite'                   => array( 'slug' => 'venue')
            );
            
            register_taxonomy('hacc_venue_category', self::POST_TYPE, $args);
        }
        
        /**
         * get templates
         * @param type $template
         * @return type
         */
        
        public function get_template($template) {
            
//            global $post;
//            
//            if ($post->post_type !== self::POST_TYPE) {
//                return $template;
//            }
//            
//            return hacc_get_post_type_template(substr(self::POST_TYPE,5), $template);
            return $template;
        }
}