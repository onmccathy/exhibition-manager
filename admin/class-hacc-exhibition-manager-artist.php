<?php
/**
 * The Artist custom post type functionality of the plugin.
 *
 * @link       http://huttartsites.co.nz
 * @since      1.0.0
 *
 * @package    Hacc_Exhibition_Manager
 * @subpackage Hacc_Exhibition_Manager/admin
 */


class Hacc_Exhibition_Manager_Artist {
    
    /* Declare fields and copnstants */
       
        const POST_TYPE         = "hacc_artist";
        const POST_TYPE_NAME    = 'Artist';

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
                 'public'                   => true,
                 'publicly_queryable'       => true,
                 'exclude_from_search'      => false,
                 'show_in_nav_menus'        =>true,
                 'show_ui'                  => true,
                 'show_in_menu'             => true,
                 'show_in_admin_bar'        => true,
                 'menu_position'            => '6',
                 'menu_icon'                => 'dashicons-businessman',
                 'can_export'               => true,
                 'delete_with_user'         => false,
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
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

