<?php
/**
 * The news custom post type functionality of the plugin.
 *
 * @link       http://huttartsites.co.nz
 * @since      1.0.0
 *
 * @package    Hacc_Exhibition_Manager
 * @subpackage Hacc_Exhibition_Manager/admin
 */


class Hacc_Exhibition_Manager_News {
    
    /* Declare fields and copnstants */
       
        const POST_TYPE         = 'hacc_news';
        const POST_TYPE_NAME    = 'News Item';
        const SAVENONCE         = 'hacc_exhibition_save_nonce';
        const METABOX_TITLE     = 'Publish on Front Page';
        const PARENT_POST_TYPE  = '';
        
        const START_DATE        = 'hacc_StartDate';
        const END_DATE          = 'hacc_EndDate';        

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
	 * @param      string    $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
                
                add_shortcode('hacc-list-news', array($this, 'hacc_list_news'));
	}
        
        public function create_post_type() {
            
            $singular = self::POST_TYPE_NAME;
            $plural = self::POST_TYPE_NAME . 's';

            $labels = array(
                'name'                      => $plural,
                 'singular_name'            => $singular,
                 'add_name'                 => 'Add',
                 'add_new_item'             => 'Add ' . $singular,
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
                'menu_position'             => '6',
                'menu_icon'                 => 'dashicons-businessman',
                'can_export'                => true,
                'delete_with_user'          => false,
                'hierarchical'              => false,
                'has_archive'               => true,
                'query_var'                 => true,
                'taxonomies'                => array('Category'),
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
                                    'thumbnail'
                )
            );
            register_post_type(self::POST_TYPE, $args);           
            
        }
        
        /**
         * Gets the single for this post type
         * @global type $post
         * @param type $template
         * @return type
         */
        public function get_template($template) {
            
            global $post;
            
            if (!isset($post)) {
                return $template;
            }
 
            if ($post->post_type !== self::POST_TYPE) {
                return $template;
            }
            return hacc_get_post_type_template($post->post_type, $template);
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
                'side',
                'high'    
                );
        }
        /**
         * Displays Exhibition Metabox.
         * @param type $post
         */
        function meta_callback($post) {

            // get post metadata
            wp_nonce_field(basename(__FILE__),  self::SAVENONCE);

            require_once plugin_dir_path( __FILE__ ) . 'metaboxes/hacc-news-metabox.php';
 
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
            
            // Get time zone option set in admin panel so we can determine local dates and times
            // TODO Not used but needs testing when we have moved the add to stagging and a server 
            // thst is located somewhere in the world. App may get the local from the server
            // 
            $timeZoneString = get_option('timezone_string');
            $timeZone = new DateTimeZone($timeZoneString);
            
            // link exhibition to venue/gallery. 
            
//            if (isset($_POST[self::PARENT_POST_TYPE])) {
//                                
//                $args = array(
//                    'ID'            => $post_id,
//                    'post_parent'   => sanitize_text_field($_POST[self::PARENT_POST_TYPE]),
//                );
//                // unhook , post and rehook function so it doesn't loop infinitely
//                // see Wordpress codex wp_update_post 
//                remove_action('save_post', array($this,'save_meta_data'),20);
//                wp_update_post( $args );
//                add_action('save_post', array($this,'save_meta_data'),20);
//                
//               
//            }
            
            if (isset($_POST[self::START_DATE])) {
                $date = new DateTime(sanitize_text_field($_POST[self::START_DATE]));
                update_post_meta($post_id, self::START_DATE, $date->format('Y-m-d'));
            }
            
            if (isset($_POST[self::END_DATE])) {
                
                $date = new DateTime(sanitize_text_field($_POST[self::END_DATE]));
                update_post_meta($post_id, self::END_DATE, $date->format('Y-m-d'));
            }
        }
            
        /**
        * Exhibitions list shortcode
        * 
        * Shortcode [hacc-list-exhibitions]
        * 
        * Parameters:
        * 
        * 'title'                           => Section Title,
        * 'no-exhibitions-message'          => Message displayed if there are no exhibitions to list.
        * 'count'                           => Maximum number of exhibitions read,
        * 'period'                          => 'future' -  Lists Exhibitions with a start date greater than today's date.
        *                                      'past'   -  Lists Exhibitions where the end date is less than today's date.
        *                                      'present -  Lists Exhibitions where the start date is less or equal than today 
        *                                              and the end date is greater than or equal to today
        * 'show-opening-soon-if-no-current' => 'yes'        If there are no exhibitions currently showing display 'no-exhibitions-message' message.
        *                                         
        * 'show-title'                      => 'yes'    -  Shows the list title
        * 'show featured image'             => 'yes'    -  Shows featured image.
        * 'show start date                  => 'yes'    -  show start date title and startdate value.
        * 'show end date'                   => 'yes'    -  show end date title and end date value.
        * 'date-format                      =>  'd-m-Y' -  The date format used to display start date and end date.
        * 'start-date-title'                => 'Opens'  -  The start date title.
        * 'end-date-title'                  => 'Closes' -  The end date title.
        * 'include-action-button            => 'yes'    -  Include an action button that links to the exhibition record.
        * 'action-button-text               => 'Find Out More'  - The button text.
        * 
        */
        
        function hacc_list_exhibitions ($atts) {
            
            global $post;
                       
            $atts = shortcode_atts( array(
                'title'                             => 'Up Coming',
                'no-news-message'                   => 'There is no news to report.',
                'count'                             =>  20,
                'period'                            => 'future',
                'show-opening-soon-if-no-current'   => 'yes',
                'opening-soon-count'                => 2,
                'opening-soon-title'                => 'Opening Soon',
                'show-title'                        => 'yes',
                'show-featured-image'               => 'yes',
                'show-start-date'                   => 'yes',
                'show-end-date'                     => 'yes',
                'date-format'                       => 'Y-m-d',
                'start-date-title'                  => 'Opens',
                'end-date-title'                    => 'Closes',
                'include-action-button'             => 'yes',
                'action-button-text'                => 'Find Out More',
                'action-button-class'               => 'hacc-button',
                
            ),$atts);
            
            $paged = get_query_var( 'paged') ? get_query_var( 'paged') : 1;
            
            
            $nowDate = new DateTime('now');
            $now =  $nowDate->format('Y-m-d');
            
            $post_count = (int)$atts['count'];
            $opening_soon_count = (int)$atts['opening-soon-count'];
                
            $future_args = array(
                
                'post_type'         =>self::POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'     => $post_count,
                'meta_key'          => self::START_DATE,
                'orderby'           => 'meta_value',
                'order'             => 'ASC',                
                'meta_query'        => array(
                    array (
                        'key'       =>self::START_DATE,
                        'value'     => $now,
                        'compare'   => '>',
                    ),
                ),
                
            );
            
            $opening_soon_args = array(
                
                'post_type'         =>self::POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'     => $opening_soon_count,
                'meta_key'          => self::START_DATE,
                'orderby'           => 'meta_value',
                'order'             => 'ASC',                
                'meta_query'        => array(
                    array (
                        'key'       =>self::START_DATE,
                        'value'     => $now,
                        'compare'   => '>',
                    ),
                ),
                
            );
            
            $past_args = array(
                
                'post_type'         => self::POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'     => $post_count,
                'meta_key'          => self::END_DATE,
                'orderby'           => 'meta_value',
                'order'             => 'DESC',
                'meta_query'        => array(
                    array (
                            'key'       =>self::END_DATE,
                            'value'     => $now,
                            'compare'   => '<=',
                            
                        ),
                ),
            );
            
            $present_args = array(
                
                'post_type'         =>self::POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'     => $post_count,
                'meta_key'          => self::START_DATE,
                'orderby'           => 'meta_value',                
                'meta_query'        => array(
                    'relation'      => 'AND',
                    array (
                            'key'       =>self::START_DATE,
                            'compare'   => '<=',
                            'value'     => $now,
                    ),
                    array (
                            'key'       =>self::END_DATE,
                            'compare'   => '>=',
                            'value'     => $now,
                    ),
                ),
            );
            
            $args = $future_args;
            if ($atts['period'] == 'past') {
                $args = $past_args;
            }
            if ($atts['period'] == 'present') {
                $args = $present_args;
            }
            
            $events = new WP_Query($args);
            
            $no_current = '';
            if (!$events->have_posts() && 
                    $atts['period'] == 'present' &&
                    $atts['show-opening-soon-if-no-current'] == 'yes') {
                wp_reset_postdata();
                $no_current = 'No Exhibitions are currently showing';
                $args = $opening_soon_args;
                $atts['title'] = $atts['opening-soon-title'];
                $events = new WP_Query($args);
            }

            $html = '<div class="hacc-container">';
            // if required - show title
            if($atts['show-title'] == 'yes') {
                $html  .= '<div class="list-title"><h2>';
                    $html .= $atts['title'] . '</h2></div>';
            }
            // 
            $formatOddEven = '-odd';
            if ($events->have_posts()) {
                $html .= '<div class="hacc-exhibitions">';
                if ($events->have_posts()) {
                    while ($events->have_posts()) {
                        $events->the_post();
                        $stored_metadata = get_metadata('post',$post->ID);
                        $start_date = '';
                        $end_date = '';

                        if (!empty($stored_metadata[self::START_DATE])) {

                            $start_date = new DateTime($stored_metadata[self::START_DATE][0]);
                        }
                        if (!empty($stored_metadata[self::END_DATE])) {
                            $end_date = new DateTime($stored_metadata[self::END_DATE][0]);
                        }
                        // set css class name suffix alternativly to -odd and -even
                        $html .= '<div class= "hacc-exhibition' . $formatOddEven . '">';
                        if ($formatOddEven == '-odd') {
                            $formatOddEven = '-even';
                        } else {
                            $formatOddEven = '-odd';
                        }
                        $html .= '<h3>' . esc_html(get_the_title()) . '</h3>';
                        $html .= '<div class="hacc-entry-dates">';
                        if ($atts['show-start-date'] == 'yes') {
                            $html .='<span class="hacc-entry-label hacc-exhibition-date">'. $atts['start-date-title'] . ' </span>';
                            $html .= '<span class="hacc-entry-value  hacc-exhibition-date">' . $start_date->format($atts['date-format']) .' </span>';
                        }
                        if ($atts['show-end-date'] == 'yes') {
                            $html .= '<span class="hacc-entry-label  hacc-exhibition-date">'. $atts['end-date-title'] . ' </span>';
                            $html .= '<span class="hacc-entry-value  hacc-exhibition-date">' . $end_date->format($atts['date-format']) . ' </span>';
                        }
                        $html .= '</div>';
                        // show featured image
                        if ($atts['show-featured-image'] == 'yes') {
                            // Display the thumbnail and post content
                            if ( has_post_thumbnail()) {
                                $html .= get_the_post_thumbnail();
                            }
                        }
                        if($atts['include-action-button'] == 'yes') {
                            $html .= '<p><a href="';
                            $html .= esc_html(get_the_permalink());
                            $html .= '"><button class="hacc-item-button" type="button">';
                            $html .= esc_html($atts['action-button-text']) . '</button></a>';
                            
                        }
                        $html .= '</div>';
                    }
                }
                $html .= '</div>';
            } else {
                $html .= '<div class="hacc-message">'.$atts['no-exhibitions-message'];
            }
            $html .= '</div>'; // 
            wp_reset_postdata();
            return $html;
       
            
        }
}
