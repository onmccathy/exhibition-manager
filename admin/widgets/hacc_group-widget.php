<?php

/**
 * Core class used to implement a Text widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class Hacc_Group_Widget extends WP_Widget {

	/**
	 * Sets up a Workshop widget instance.
         * 
         * used to display information for one workshop post type
	 *
	 * @since 1.0
	 * @access public
	 */
    
        
        const START_TIME        = 'hacc_StartTime';
        const END_TIME          = 'hacc_EndTime';        
        const VENUE_POST_TYPE   = 'hacc_venue';
        const VENUE             = 'hacc_venue'; // field
        const DAY_OF_WEEK       = 'hacc_DayOfWeek';
        const FREQUENCY         = 'hacc_Frequency';
        const CONVENOR          = 'hacc_Convenor';
        const CONVENOR_PHONE_NUMBER = 'hacc_phone_number';
        const VENUE_TITLE       = 'hacc_venue_title';
    
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hacc-group-widget hacc-flashy-container',
			'description' => __( 'Group' ),
		);
		parent::__construct( 'Hacc_Group_Widget', __( 'Group' ), $widget_ops );
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
                $text = ! empty( $instance['text'] ) ? $instance['text'] : '';
		
		$venueTitle = ! empty( $instance[self::VENUE_TITLE] ) ? $instance[self::VENUE_TITLE] : '';
                $startTime = ! empty( $instance[self::START_TIME] ) ? new DateTime($instance[self::START_TIME]) : '';
                $endTime = ! empty( $instance[self::END_TIME] ) ? new DateTime($instance[self::END_TIME]) : '';
                
                $dayOfWeek = ! empty( $instance[self::DAY_OF_WEEK] ) ? hacc_get_day($instance[self::DAY_OF_WEEK]) : '';
                $frequency = ! empty( $instance[self::FREQUENCY] ) ? $instance[self::FREQUENCY] : '';
                
                $convenor = ! empty( $instance[self::CONVENOR] ) ? $instance[self::CONVENOR] : '';
                $phone = ! empty( $instance[self::CONVENOR_PHONE_NUMBER] ) ? $instance[self::CONVENOR_PHONE_NUMBER] : '';
               
                $button_title = ! empty( $instance['button_title'] ) ? $instance['button_title'] : 'Find out More';
                $button_link = ! empty( $instance['button_link'] ) ? $instance['button_link'] : '#';
		echo $args['before_widget'];
                echo '<div class="hacc-widget-container">';
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title;
                        
		}
                
                echo $args['after_title'];
               
                ?>
			<div class="textwidget"><?php echo $text; ?></div>
                <?php
                
                if (!empty($venueTitle) ) {
                    echo '<p class="hacc-widget-field">' .sprintf('%s %s', "Venue : ", $venueTitle) . '</p>';
                }
                
                
                if (!empty($startTime) && !empty($endTime) ) {
                    echo '<p class="hacc-widget-field">' .$startTime->format('g:i a') . ' to ' . $endTime->format('g:i a') . '</p>';
                }
                
                if (!empty($dayOfWeek)) {
                    echo '<p class="hacc-widget-field">' .sprintf('%s %s', "Day : ", $dayOfWeek) . '</p>';
                }
          
                if (!empty($frequency)) {
                    echo '<p class="hacc-widget-field">' .sprintf('%s %s', "Frequency : ", $frequency) . '</p>';
                }
                
                if (!empty($convenor)) {
                    echo '<p class="hacc-widget-field">' .sprintf('%s %s', "Convenor : ", $convenor) . '</p>';
                }
                
                if (!empty($phone)) {
                    echo '<p class="hacc-widget-field">' .sprintf('%s %s', "Phone Number : ", $phone) . '</p>';
                }
                
                // todo check file exists before requiring it.
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
                
                $instance[self::VENUE_TITLE] = sanitize_text_field( $new_instance[self::VENUE_TITLE] );
                
                $instance[self::END_TIME] = sanitize_text_field( $new_instance[self::END_TIME] );
                $instance[self::START_TIME] = sanitize_text_field( $new_instance[self::START_TIME] );
                
                $instance[self::DAY_OF_WEEK] = sanitize_text_field( $new_instance[self::DAY_OF_WEEK] );
                $instance[self::FREQUENCY] = sanitize_text_field( $new_instance[self::FREQUENCY] );
                
                $instance[self::CONVENOR] = sanitize_text_field( $new_instance[self::CONVENOR] );
                $instance[self::CONVENOR_PHONE_NUMBER] = sanitize_text_field( $new_instance[self::CONVENOR_PHONE_NUMBER] );

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
                    'title'             => '',
                    'text'              => '',
                    'button_title'      => 'Find out More',
                    self::VENUE_TITLE   => '', 
                    self::START_TIME    => '',
                    self::END_TIME      => '',
                    self::DAY_OF_WEEK   => '',
                    self::FREQUENCY     => '',
                    self::CONVENOR      => '',
                    self::CONVENOR_PHONE_NUMBER    => '',
                    
                    
                );
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = sanitize_text_field( $instance['title'] );
                
                $venueTitle = sanitize_text_field( $instance[self::VENUE_TITLE] );
           
                $startTime = sanitize_text_field( $instance[self::START_TIME] );
                $endTime = sanitize_text_field( $instance[self::END_TIME] );
                
                $dayOfWeek = sanitize_text_field( $instance[self::DAY_OF_WEEK] );
                $frequency = sanitize_text_field( $instance[self::FREQUENCY] );
                
                $convenor = sanitize_text_field( $instance[self::CONVENOR] );
                $phone = sanitize_text_field( $instance[self::CONVENOR_PHONE_NUMBER] );
                
                $button_title = sanitize_text_field( $instance['button_title'] );
                $button_link = sanitize_text_field( $instance['button_link'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Content:' ); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>
		
                p><label for="<?php echo $this->get_field_id(self::VENUE_TITLE); ?>"><?php _e('Venue Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::VENUE_TITLE); ?>" name="<?php echo $this->get_field_name(self::VENUE_TITLE); ?>" type="text" value="<?php echo esc_attr($venueTitle); ?>" /></p>
                               
                <p><label for="<?php echo $this->get_field_id(self::START_TIME); ?>"><?php _e('Start Time:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::START_TIME); ?>" name="<?php echo $this->get_field_name(self::START_TIME); ?>" type="text" value="<?php echo esc_attr($startTime); ?>" /></p>
                
                                
                <p><label for="<?php echo $this->get_field_id(self::END_TIME); ?>"><?php _e('End Time:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('self::END_TIME'); ?>" name="<?php echo $this->get_field_name(self::END_TIME); ?>" type="text" value="<?php echo esc_attr($endTime); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::DAY_OF_WEEK); ?>"><?php _e('Day :'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::DAY_OF_WEEK); ?>" name="<?php echo $this->get_field_name(self::DAY_OF_WEEK); ?>" type="text" value="<?php echo esc_attr($dayOfWeek); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::FREQUENCY); ?>"><?php _e('Frequency: '); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::FREQUENCY); ?>" name="<?php echo $this->get_field_name(self::FREQUENCY); ?>" type="text" value="<?php echo esc_attr($frequency); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::CONVENOR); ?>"><?php _e('Convenor: '); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::CONVENOR); ?>" name="<?php echo $this->get_field_name(self::CONVENOR); ?>" type="text" value="<?php echo esc_attr($convenor); ?>" /></p>           
                
                <p><label for="<?php echo $this->get_field_id(self::CONVENOR_PHONE_NUMBER); ?>"><?php _e('Frequency: '); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::CONVENOR_PHONE_NUMBER); ?>" name="<?php echo $this->get_field_name(self::CONVENOR_PHONE_NUMBER); ?>" type="text" value="<?php echo esc_attr($phone); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id('button_title'); ?>"><?php _e('Button Title:'); ?></label>
 		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_title')); ?>" name="<?php echo $this->get_field_name('button_title'); ?>" type="text" value="<?php echo esc_attr($button_title); ?>" /></p>
 
                <p><label for="<?php echo $this->get_field_id('button_link'); ?>"><?php _e('Button Link:'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_link')); ?>" name="<?php echo $this->get_field_name('button_link'); ?>" type="text" value="<?php echo esc_url($button_link); ?>" /></p>
                    
                <?php
	}
}



