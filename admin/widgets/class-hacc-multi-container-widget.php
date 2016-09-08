<?php

/**
 * Core class used to implement a Text widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class Hacc_Multi_Container extends WP_Widget {

	/**
	 * Sets up a new Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hacc-multi-container',
			'description' => __( 'Multiple Widget Container' ),
		);
		
		parent::__construct( 'Hacc_Multi_Container', __( 'Multiple Widget Container' ), $widget_ops );
	}

	/**
	 * Outputs the content for the current Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Text widget instance.
	 */
	public function widget( $cont_args, $instance ) {
               
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
 
		$template = $instance['template'];
                $post_parent = $instance['post_parent'];
                echo $cont_args['before_widget'];
                echo '<div class="hacc-multiple-widget-container">';
		if ( ! empty( $title ) ) {
			echo $cont_args['before_title'] . $title . $cont_args['after_title'];
		} 
                // todo check file exists before requiring it.
                require_once plugin_dir_path(__FILE__). 'templates/' . $template;
		echo '</div>';

	}

	/**
	 * Handles updating settings for the current Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['button_title'] = sanitize_text_field( $new_instance['button_title'] );
                $instance['button_link'] = sanitize_text_field( $new_instance['button_link'] );
                $instance['template'] = sanitize_text_field( $new_instance['template'] );
                $instance['post_type'] = sanitize_text_field( $new_instance['post_type'] );
                $instance['post_parent'] = sanitize_text_field( $new_instance['post_parent'] );
                
		return $instance;
	}

	/**
	 * Outputs the Text widget settings form.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
                $defaults = array(
                    'title' => '',
                    'button_title'  => 'Find out More',
                    'button_link'  => '',
                    'template'  => '',
                    'post_type'  => '',
                    'post_parent'  => '',
                );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = sanitize_text_field( $instance['title'] );
                $button_title = sanitize_text_field( $instance['button_title'] );
                $button_link = sanitize_text_field( $instance['button_link'] );
                $template = sanitize_text_field( $instance['template'] );
                $post_type = sanitize_text_field( $instance['post_type'] );
                $post_parent = sanitize_text_field( $instance['post_parent'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
                <p><label for="<?php echo $this->get_field_id('button_title'); ?>"><?php _e('Button Title:'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_title')); ?>" name="<?php echo $this->get_field_name('button_title'); ?>" type="text" value="<?php echo esc_attr($button_title); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id('button_link'); ?>"><?php _e('Button Link:'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_link')); ?>" name="<?php echo $this->get_field_name('button_link'); ?>" type="text" value="<?php echo esc_url($button_link); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id('template'); ?>"><?php _e('Template: '); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('template')); ?>" name="<?php echo $this->get_field_name('template'); ?>" type="text" value="<?php echo esc_attr($template); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post Type: '); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('post_type')); ?>" name="<?php echo $this->get_field_name('post_type'); ?>" type="text" value="<?php echo esc_attr($post_type); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id('post_parent'); ?>"><?php _e('Post Title: '); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('post_parent')); ?>" name="<?php echo $this->get_field_name('post_parent'); ?>" type="text" value="<?php echo esc_attr($post_parent); ?>" /></p>
                    
                <?php
	}
}



