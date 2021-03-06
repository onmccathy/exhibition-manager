<?php

/**
 * Core class used to implement a Text widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class Hacc_Flashy_Insert extends WP_Widget {

	/**
	 * Sets up a new Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'Hacc_Flashy_Insert',
			'description' => __( 'Flashy Insert' ),
		);
		parent::__construct( 'Hacc_Flashy_Insert', __( 'Flashy Insert' ), $widget_ops );
                register_sidebar( array(
                    'name'              => 'Fancy Widget Area',
                    'id'                => 'fancy-widget-area',
                    'before_widget'     => '<div>',
                    'after_widget'      => '</div>',
                    'before_title'      => '<h3 class="widget-title"',
                    'after_title'       => '</h3>',
                ));
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
	public function widget( $args, $instance ) {

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$widget_text = ! empty( $instance['text'] ) ? $instance['text'] : '';
                
		/**
		 * Filter the content of the Text widget.
		 *
		 * @since 2.3.0
		 * @since 4.4.0 Added the `$this` parameter.
		 *
		 * @param string         $widget_text The widget content.
		 * @param array          $instance    Array of settings for the current widget.
		 * @param WP_Widget_Text $this        Current Text widget instance.
		 */
		$text = apply_filters( 'widget_text', $widget_text, $instance, $this );
                
                $button_title = ! empty( $instance['button_title'] ) ? $instance['button_title'] : 'Find out More';
                $button_link = ! empty( $instance['button_link'] ) ? $instance['button_link'] : '#';
                //
                // Title
                //
		echo $args['before_widget'];
                echo '<div class="hacc-widget-container">';
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} 
                //
                // Widget Area
                //
                ?>
                <?php if ( is_active_sidebar( 'fancy-widget-area' ) ) : ?>
                    <ul id="fancy-widget-area">
                        <?php dynamic_sidebar( 'fancy-widget-area' ); ?>
                    </ul>
                 <?php endif; 
                //
                // Text
                //
                 ?>
		<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
                <?php
                //
                // Button
                //        
                echo '<a href="' . $button_link . '"><button class="hacc-action-button" type="button">' . esc_attr($button_title) . '</button></a>'; 
		echo '</div>';
		echo $args['after_widget'];
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
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}
		$instance['filter'] = ! empty( $new_instance['filter'] );
                $instance['button_title'] = sanitize_text_field( $new_instance['button_title'] );
                $instance['button_link'] = sanitize_text_field( $new_instance['button_link'] );
                
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
                    'text'  => '',
                    'button_title'  => 'Find out More',
                );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$filter = isset( $instance['filter'] ) ? $instance['filter'] : 
		$title = sanitize_text_field( $instance['title'] );
                $button_title = sanitize_text_field( $instance['button_title'] );
                $button_link = sanitize_text_field( $instance['button_link'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Content:' ); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox"<?php checked( $filter ); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
		
                <p><label for="<?php echo $this->get_field_id('button_title'); ?>"><?php _e('Button Title:'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_title')); ?>" name="<?php echo $this->get_field_name('button_title'); ?>" type="text" value="<?php echo esc_attr($button_title); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id('button_link'); ?>"><?php _e('Button Link:'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_link')); ?>" name="<?php echo $this->get_field_name('button_link'); ?>" type="text" value="<?php echo esc_attr($button_link); ?>" /></p>
                <?php
	}
}



