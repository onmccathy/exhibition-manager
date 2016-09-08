<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://huttartsites.co.nz
 * @since      1.0.0
 *
 * @package    Hacc_Exhibition_Manager
 * @subpackage Hacc_Exhibition_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hacc_Exhibition_Manager
 * @subpackage Hacc_Exhibition_Manager/admin
 * @author     Owen McCarthy <onmccarthy@gmail.com>
 */
class Hacc_Exhibition_Manager_Admin {
    
        /**
         * Constants
         * CSS Version
         */
         const CSS_JS_VERSION = '1.0.0';
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
                
                $this->load_dependencies();

	}
        
        /**
	 * Load the required post-type dependecies.
	 *
	 * Include the following files:
	 *
	 * - class-hacc-exhibition-manager-artist. Defines the Artist Post type.
	 * - class-hacc-exhibition-manager-venue. Defines the Venue Post type.
	 * - class-hacc-exhibition-manager-exhibition. Defines the Artist Post type.
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
                /**
		 * The Artist Custom post type
		 */
		require_once plugin_dir_path( __FILE__) . 'posttypes/class-hacc-exhibition-manager-artist.php';
        
                /**
		 * The Venue Custom post type
		 */
		require_once plugin_dir_path(__FILE__) . 'posttypes/class-hacc-exhibition-manager-venue.php';
                
                /**
		 * The Exhibition Custom post type
		 */
		require_once plugin_dir_path( __FILE__ ) . 'posttypes/class-hacc-exhibition-manager-exhibition.php';
                
                 /**
		 * The Tutor Custom post type
		 */
		require_once plugin_dir_path( __FILE__ ) . 'posttypes/class-hacc-exhibition-manager-tutor.php';
                
                 /**
		 * The Workshop Custom post type
		 */
		require_once plugin_dir_path( __FILE__ ) . 'posttypes/class-hacc-exhibition-manager-workshop.php';
                
                          
                /**
		 * The Programme post type
		 */
		require_once plugin_dir_path( __FILE__ ) . 'posttypes/class-hacc-exhibition-manager-programme.php';
                
                /**
		 * The Group post type
		 */
		require_once plugin_dir_path( __FILE__ ) . 'posttypes/class-hacc-exhibition-manager-group.php';
                
                /**
		 * The Group post type
		 */
		require_once plugin_dir_path( __FILE__ ) . 'posttypes/class-hacc-exhibition-manager-news.php';
                
                 /**
		 * The Fancy Widget
		 */
		require_once plugin_dir_path( __FILE__ ) . 'widgets/class-hacc-flash-container-widget.php';
                
                /**
		 * The Fancy Widget with widget area
		 */
		require_once plugin_dir_path( __FILE__ ) . 'widgets/class-hacc-flash-insert-widget.php';
                
                /**
		 * The displays templated output
		 */
		require_once plugin_dir_path( __FILE__ ) . 'widgets/class-hacc-multi-container-widget.php';
                
                /**
		 * The workshop widget
		 */
		require_once plugin_dir_path( __FILE__ ) . 'widgets/hacc_workshop-widget.php';
                
                /**
		 * The Group Widget
		 */
		require_once plugin_dir_path( __FILE__ ) . 'widgets/hacc_group-widget.php';
        }
        
        

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hacc_Exhibition_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hacc_Exhibition_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
                global $pagenow, $typenow;
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hacc-exhibition-manager-admin.css', array(), $this->version, 'all' );
               
                if ($typenow == 'hacc_exhibition' || $typenow =='hacc_workshop' || $typenow =='hacc_programme' || $typenow == 'hacc_news' || $typenow == 'hacc_group' ) {
                    // only load if we are editing an exhibition or adding a new one
                    if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
                        wp_enqueue_style('hacc-jquery-ui',plugin_dir_url( __FILE__ ) .'css/jquery-ui.css',array(),self::CSS_JS_VERSION);
                        wp_enqueue_style('hacc-date-time-css',plugin_dir_url( __FILE__ ) .'css/flatpickr.min.css',array(),self::CSS_JS_VERSION);
                        wp_enqueue_script('hacc-date-picker',plugin_dir_url( __FILE__ ) .'js/script-admin-exhibition.js', array('jquery','hacc-time-picker'), self::CSS_JS_VERSION,true);
                        wp_enqueue_script('hacc-time-picker',plugin_dir_url( __FILE__ ) .'js/flatpickr.min.js', array('jquery'), self::CSS_JS_VERSION,true);
                    }
                }
        
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hacc_Exhibition_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hacc_Exhibition_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hacc-exhibition-manager-admin.js', array( 'jquery' ), $this->version, false );

	}
        
        public function register_widgets (){

            register_widget('Hacc_Flashy_Container');
            register_widget('Hacc_Flashy_Insert');
            register_widget('Hacc_Multi_Container');
            register_widget('Hacc_Workshop_Widget');
            register_widget('Hacc_Group_Widget');
            
            
        }
        
        /**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Exhibition Manager', TEXTDOMAIN ),
			__( 'Exhibition Manager', TEXTDOMAIN ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
        }
        
        /**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {
                
                // Add a General section
                add_settings_section($this->option_name . '_general',__( 'General', TEXTDOMAIN ),array( $this, $this->option_name . '_general_cb' ),$this->plugin_name);
                add_settings_section($this->option_name . '_exhibition',__( 'Exhibition', TEXTDOMAIN ),array( $this, $this->option_name . '_exhibition_cb' ),$this->plugin_name);
                
                add_settings_field($this->option_name . '_position',__( 'Text position', TEXTDOMAIN ),array( $this, $this->option_name . '_position_cb' ),$this->plugin_name,$this->option_name . '_general',array( 'label_for' => $this->option_name . '_position' ));
                add_settings_field($this->option_name . '_day',__( 'Post is outdated after', TEXTDOMAIN ),array( $this, $this->option_name . '_day_cb' ),$this->plugin_name,$this->option_name . '_general',array( 'label_for' => $this->option_name . '_day' ));
                
                add_settings_field($this->option_name . '_gallery_title',__( 'Gallery Title', TEXTDOMAIN ),array( $this, $this->option_name . '_gallery_title_cb' ),$this->plugin_name,$this->option_name . '_exhibition',array( 'label_for' => $this->option_name . '_gallery_title' ));
                
                register_setting( $this->plugin_name, $this->option_name . '_gallery_title');
                register_setting( $this->plugin_name, $this->option_name . '_position', array( $this, $this->option_name . '_sanitize_position' ) );
                register_setting( $this->plugin_name, $this->option_name . '_day' );
                

                
        }
        
        
        /**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/hacc-exhibition-manager-admin-display.php';
	}
        
        /**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function hacc_general_cb() {
		echo '<p>' . __( 'Please change the settings accordingly.', TEXTDOMAIN ) . '</p>';
	}
        
        /**
	 * Render the radio input field for position option
	 *
	 * @since  1.0.0
	 */
	public function hacc_position_cb() {
            $position = get_option( $this->option_name. '_position')
		?>
			<fieldset>
				<label>
					<input type="radio" name="<?php echo $this->option_name . '_position' ?>" id="<?php echo $this->option_name . '_position' ?>" value="before" <?php checked( $position, 'before' ); ?>>
					<?php _e( 'Before the content', 'outdated-notice' ); ?>
				</label>
				<br>
				<label>
					<input type="radio" name="<?php echo $this->option_name . '_position' ?>" value="after" <?php checked( $position, 'after' ); ?>>
					<?php _e( 'After the content', 'outdated-notice' ); ?>
				</label>
			</fieldset>
		<?php
	}
        
        /**
	 * Render the treshold day input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function hacc_day_cb() {
                $day = get_option( $this->option_name . '_day' );
		echo '<input type="text" name="' . $this->option_name . '_day' . '" id="' . $this->option_name . '_day' . '" value="' . $day . '"> ' . __( 'days', TEXTDOMAIN );
	}
        
        /**
	 * Render the treshold day input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function hacc_name_cb() {
                $name = get_option( $this->option_name . '_name' );
		echo '<input type="text" name="' . $this->option_name . '_name' . '" id="' . $this->option_name . '_name' . '" value="' . $name . '"> ' . __( 'name', TEXTDOMAIN );
	}
        
        /**
	 * Sanitize the text position value before being saved to database
	 *
	 * @param  string $position $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function hacc_sanitize_position( $position ) {
		if ( in_array( $position, array( 'before', 'after' ), true ) ) {
	        return $position;
	    }
	}
        
        /**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function hacc_exhibition_cb() {
		echo '<p>' . __( 'Please change the settings accordingly.', TEXTDOMAIN ) . '</p>';
	}
        
        /**
	 * Render the gallery title input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function hacc_gallery_title_cb() {
                $gallery_title = get_option( $this->option_name . '_gallery_title' );
		echo '<input type="text" name="' . $this->option_name . '_gallery_title' . '" id="' . $this->option_name . 'gallery_title' . '" value="' . $gallery_title . '"> ' . __( ' Set gallery title to be used in exhibition template', TEXTDOMAIN );
	}
        
        
        
}
