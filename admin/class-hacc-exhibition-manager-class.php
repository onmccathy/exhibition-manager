<?php
/**
 * The exhibition custom post type functionality of the plugin.
 *
 * @link       http://huttartsites.co.nz
 * @since      1.0.0
 *
 * @package    Hacc_Exhibition_Manager
 * @subpackage Hacc_Exhibition_Manager/admin
 */


class Hacc_Exhibition_Manager_Class {
    
    /* Declare fields and copnstants */
       
        const POST_TYPE         = 'hacc_class';
        const POST_TYPE_NAME    = 'Class';
        const SAVENONCE         = 'hacc_class_save_nonce';
        const METABOX_TITLE     = 'Class Details';
        const PARENT_POST_TYPE  = 'hacc_programme';
        const VALIDATION_ERRORS = 'hacc_class_errors';
        
        const START_DATE        = 'hacc_StartDate';
        const START_TIME        = 'hacc_StartTime';
        const END_DATE          = 'hacc_EndDate';
        const END_TIME          = 'hacc_EndTime';
        
        const PUBLIC_PRICE      = 'hacc_public_price';
        const MEMBER_PRICE      = 'hacc_member_price';
        const TUTOR             = 'hacc_tutor'; // field
        const TUTOR_POST_TYPE   = 'hacc_tutor';
        const VENUE_POST_TYPE   = 'hacc_venue';
        const VENUE             = 'hacc_venue'; // field

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

                add_shortcode('hacc-list-classes', array($this, 'hacc_list_classes'));
	}
        
        public function create_post_type() {
            
            $singular = self::POST_TYPE_NAME;
            $plural = self::POST_TYPE_NAME. 's';

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
                'taxonomies'                => array('category',),
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
         * Gets the single for this post type
         * @global type $post
         * @param type $template
         * @return type
         */
        public function get_template($template) {
            
            global $post;

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
                'normal',
                'core'
            );
        }
        /**
         * Displays Class Metabox.
         * @param type $post
         */
        function meta_callback($post) {

            // get post metadata
            wp_nonce_field(basename(__FILE__),  self::SAVENONCE);

            require_once plugin_dir_path( __FILE__ ) . 'partials/hacc-class-metabox.php';
 
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
            
            if ($this->isDataValid($post_id) ) { 
                om_log("Valid");
                $this->save_class( $post_id) ;
            }
            
            
        }
        
        /**
         * Validate metabox data
         * @param $post_id The Id of the post being validated.
         * @return boolean. True there are no errors, False there are errors.
         */
        
        function isDataValid($post_id) {
            $validated = true;
            if (isset($_POST[self::PUBLIC_PRICE])) {
                $public_price = sanitize_text_field($_POST[self::PUBLIC_PRICE]);
                if ( !is_numeric($public_price)) {
                    add_settings_error('hacc-class-public-price-message','hacc-class-validate-message',
                        esc_attr('Public Price is not numeric'), 'error'
                        );
                    $vaidated = false;
                }
            }
            if (isset($_POST[self::MEMBER_PRICE])) {
                $member_price = sanitize_text_field($_POST[self::MEMBER_PRICE]);
                if ( !is_numeric($member_price)) {
                    add_settings_error('hacc-class-member-price-message','hacc-class-validate-message',
                        esc_attr('Member Price is not numeric'), 'error'
                        );
                    $vaidated = false;
                }
            }
            if (!$validated) {
                set_transient( self::VALIDATION_ERRORS , get_settings_errors(), 30);
                add_action( 'admin_notices', array(this, 'class_admin_notices'));
            }
            return $validated ;
        }
        
        /**
         * save the class post type data.
         * @param type $post_id
         */
        
        function save_class( $post_id) {
            
            if (isset($_POST[self::PARENT_POST_TYPE])) {
                                
                $args = array(
                    'ID'            => $post_id,
                    'post_parent'   => sanitize_text_field($_POST[self::PARENT_POST_TYPE]),
                );
                // unhook , post and rehook function so it doesn't loop infinitely
                // see Wordpress codex wp_update_post 
                remove_action('save_post_hacc_class', array($this,'save_meta_data'),20);
                wp_update_post( $args );
                add_action('save_post_hacc_class', array($this,'save_meta_data'),20);
                
               
            }
            
            if (isset($_POST[self::TUTOR])) {
                $tutor = sanitize_text_field($_POST[self::TUTOR]);
                update_post_meta($post_id, self::TUTOR, $tutor);
            }
            
            if (isset($_POST[self::VENUE])) {
                $venue = sanitize_text_field($_POST[self::VENUE]);
                update_post_meta($post_id, self::VENUE, $venue);
            }
            
            if (isset($_POST[self::START_DATE])) {
                $time = strtotime(sanitize_text_field($_POST[self::START_DATE]));
                update_post_meta($post_id, self::START_DATE, $time);
            }
            
            if (isset($_POST[self::END_DATE])) {
                $time = strtotime(sanitize_text_field($_POST[self::END_DATE]));
                update_post_meta($post_id, self::END_DATE, $time);
            }
            
            if (isset($_POST[self::START_TIME])) {
                $time = strtotime(sanitize_text_field($_POST[self::START_TIME]));
                update_post_meta($post_id, self::START_TIME, $time);
            }
            
            if (isset($_POST[self::END_TIME])) {
                $time = strtotime(sanitize_text_field($_POST[self::END_TIME]));
                update_post_meta($post_id, self::END_TIME, $time);
            }
            
            if (isset($_POST[self::PUBLIC_PRICE])) {
                $public_price = floatval(sanitize_text_field($_POST[self::PUBLIC_PRICE]));
                update_post_meta($post_id, self::PUBLIC_PRICE, $public_price);
            }
            
            if (isset($_POST[self::MEMBER_PRICE])) {
                $member_price = floatval(sanitize_text_field($_POST[self::MEMBER_PRICE]));
                update_post_meta($post_id, self::MEMBER_PRICE, $member_price);
            }
            
            
        }
        
        function class_admin_notices() {
            // if there were no errors then exit 
            if( !($errors = get_transient(self::VALIDATION_ERRORS))) {
                return;
            }
            
           // Build list of errors that exist in VALIDATION_ERRORS
            
            $message = '<div id="hacc_message" class="error below-h2">,<p><ul>';
            foreach ($errors as $error) {
                $message .= '<li>'. $error['message'] . '</li>';
            }
            
            $message .= '</ul></p></div><!--#error -->';
            
            // Write message to screen
            
            echo $message;
            
            // Clear the transient and unhook admin notices so we don't see duplicate messages.
            
            delete_transient(self::VALIDATION_ERRORS);
            remove_action( 'admin_notices', array($this,class_admin_notices));
        }     
        
        /**
        * Classes list shortcode
        * 
        * Shortcode [hacc-list-exhibitions]
        * 
        * Parameters:
        * 
        * 'title'                       => Section Title,
        * 'no-exhibitions-message'      => Message displayed if there are no exhibitions to list.
        * 'count'                       => Maximum number of exhibitions read,
        * 'period'                      => 'future' -  Lists Exhibitions with a start date greater than today's date.
        *                                  'past'   -  Lists Exhibitions where the end date is less than today's date.
        *                                  'present -  Lists Exhibitions where the start date is less or equal than today 
        *                                              and the end date is greater than or equal to today
        * 'show-upcomming-if-no-current'=> 'no'        If there are no exhibitions currently showing display 'no-exhibitions-message' message.
        *                                  'yes'       Show up and coming.         
        * 'pagination'                  => 'false'     
        * 
        */
        
        function hacc_list_classes ($atts) {
       
            global $post;
            
            $atts = shortcode_atts( array(
                'title'                             => 'Up Coming',
                'no-exhibitions-message'            => 'There are no up and coming exhibitions scheduled',
                'count'                             =>  20,
                'period'                            => 'future',
                'show-upcomming-if-no-current'      => 'no',
                'show-title'                        => 'yes',
                'show-featured-image'               => 'yes',
                'show-start-date'                   => 'yes',
                'show-end-date'                     => 'yes',
                'date-format'                       => 'd.m.Y',
                'start-date-title'                  => 'Opens',
                'end-date-title'                    => 'Closes',
                'include-action-button'             => 'yes',
                'action-button-text'                => 'Find Out More',
                'action-button-class'               => 'hacc-button',
                
            ),$atts);
            
            $paged = get_query_var( 'paged') ? get_query_var( 'paged') : 1;
            
            $now = strtotime(date('Y-m-d h:i:s'));
            
            $post_count = (int)$atts['count'];
                
            $future_args = array(
                
                'post_type'         =>self::POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'     => $post_count,
                'meta_key'          => self::START_DATE,
                'orderby'           => 'meta_value_num',
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
                'orderby'           => 'meta_value_num',
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
                'orderby'           => 'meta_value_num',                
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
            
            
            if (!$events->have_posts() && 
                    $atts['period'] == 'present' &&
                    $atts['show-upcomming-if-no-current'] == 'yes') {
                wp_reset_postdata();
                $args = $future_args;
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

                            $start_date = date($atts['date-format'], $stored_metadata[self::START_DATE][0]);
                        }
                        if (!empty($stored_metadata[self::END_DATE])) {
                            $end_date = date($atts['date-format'], $stored_metadata[self::END_DATE][0]);
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
                            $html .= '<span class="hacc-entry-value  hacc-exhibition-date">' . $start_date .' </span>';
                        }
                        if ($atts['show-end-date'] == 'yes') {
                            $html .= '<span class="hacc-entry-label  hacc-exhibition-date">'. $atts['end-date-title'] . ' </span>';
                            $html .= '<span class="hacc-entry-value  hacc-exhibition-date">' . $end_date . ' </span>';
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
                            $html .= '<p><a class="hacc-exhibition-link" href="';
                            $html .= esc_html(get_the_permalink());
                            $html .= '">';
                            $html .= esc_html($atts['action-button-text']) . '</a></p>';
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
