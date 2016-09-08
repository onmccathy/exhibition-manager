<?php

/**
 * Core class used to implement a Text widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class Hacc_Workshop_Widget extends WP_Widget {

	/**
	 * Sets up a Workshop widget instance.
         * 
         * used to display information for one workshop post type
	 *
	 * @since 1.0
	 * @access public
	 */
    
        const START_DATE        = 'hacc_StartDate';
        const START_TIME        = 'hacc_StartTime';
        const END_DATE          = 'hacc_EndDate';
        const END_TIME          = 'hacc_EndTime';
        
        const PUBLIC_PRICE      = 'hacc_public_price';
        const MEMBER_PRICE      = 'hacc_member_price';
        const LEVEL             = 'hacc_level';
        const NOTES             = 'hacc_notes';
        const VENUE_POST_TYPE   = 'hacc_venue';
        const VENUE             = 'hacc_venue'; // field
        const TUTORNAME         = 'hacc_tutor_name';
        const MONEY_FORMAT      = '$%i';
    
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hacc-workshop-widget hacc-flashy-container',
			'description' => __( 'Workshop' ),
		);
		parent::__construct( 'Hacc_Workshop_Widget', __( 'Workshop' ), $widget_ops );
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
		$tutorName = ! empty( $instance[self::TUTORNAME] ) ? $instance[self::TUTORNAME] : '';
		$startDate = ! empty( $instance[self::START_DATE] ) ? new DateTime($instance[self::START_DATE]) : '';
                $endDate = ! empty( $instance[self::END_DATE] ) ? new DateTime($instance[self::END_DATE]) : '';
                $startTime = ! empty( $instance[self::START_TIME] ) ? new DateTime($instance[self::START_TIME]) : '';
                $endTime = ! empty( $instance[self::END_TIME] ) ? new DateTime($instance[self::END_TIME]) : '';
                $memberPrice = ! empty( $instance[self::MEMBER_PRICE] ) ? $instance[self::MEMBER_PRICE] : 0;
                $publicPrice = ! empty( $instance[self::PUBLIC_PRICE] ) ? $instance[self::PUBLIC_PRICE] : 0;
                $level = ! empty( $instance[self::LEVEL] ) ? $instance[self::LEVEL] : '';
                $notes = ! empty( $instance[self::NOTES] ) ? $instance[self::NOTES] : '';
               
                $button_title = ! empty( $instance['button_title'] ) ? $instance['button_title'] : 'Find out More';
                $button_link = ! empty( $instance['button_link'] ) ? $instance['button_link'] : '#';
		echo $args['before_widget'];
                echo '<div class="hacc-widget-container">';
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title;
                        
		}
                
                echo $args['after_title'];
                echo '<p class="hacc-widget-sub-title">' . $tutorName . '</p>';
                ?>
			<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
                <?php
                if (!empty($startDate) ) {
                    // Check if startdate is equal to enddate ie 1 day workshop so only dispaly startdate.
                    if (!empty($endDate) && ($startDate == $endDate)) {
                        echo '<p class="hacc-widget-field">' . $startDate->format('l F jS') . '</p>';
                    } else {
                        echo '<p class="hacc-widget-field">' . $startDate->format('l F jS') . '</p>';
                        echo '<p class="hacc-widget-field">' . $endDate->format('l F jS') .'</p>';
                    }
                }
                
                
                if (!empty($startTime) && !empty($endTime) ) {
                    echo '<p class="hacc-widget-field">' .$startTime->format('g:i a') . ' to ' . $endTime->format('g:i a') . '</p>';
                }
                
                if (!empty($level)) {
                    echo '<p class="hacc-widget-field">' .sprintf('%s %s', "Level: ", $level) . '</p>';
                }
                if ($memberPrice != 0) {
                    echo '<p class="hacc-widget-field">' . sprintf('%s $%01.2f', "Member Price: ", $memberPrice) . '</p>';
                }
                if ($publicPrice != 0) {
                    echo '<p class="hacc-widget-field">' . sprintf('%s $%01.2f', 'Public Price: ', $publicPrice);
                }
                if (!empty($notes)) {
                    echo '<p class="hacc-widget-field">' . $notes . '</p>';
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
                $instance[self::TUTORNAME] = sanitize_text_field( $new_instance[self::TUTORNAME] );
		$instance[self::START_DATE] = sanitize_text_field( $new_instance[self::START_DATE] );
                $instance[self::END_DATE] = sanitize_text_field( $new_instance[self::END_DATE] );
                $instance[self::END_TIME] = sanitize_text_field( $new_instance[self::END_TIME] );
                $instance[self::START_TIME] = sanitize_text_field( $new_instance[self::START_TIME] );
                $instance[self::MEMBER_PRICE] = sanitize_text_field( $new_instance[self::MEMBER_PRICE] );
                $instance[self::PUBLIC_PRICE] = sanitize_text_field( $new_instance[self::PUBLIC_PRICE] );
                $instance[self::LEVEL] = sanitize_text_field( $new_instance[self::LEVEL] );
                $instance[self::NOTES] = sanitize_text_field( $new_instance[self::NOTES] );

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
		$filter = isset( $instance['filter'] ) ? $instance['filter'] : 0;
		$title = sanitize_text_field( $instance['title'] );
                
                $tutorName = sanitize_text_field( $instance[self::TUTORNAME] );
                $startDate = sanitize_text_field( $instance[self::START_DATE] );
                $endDate = sanitize_text_field( $instance[self::END_DATE] );
                $startTime = sanitize_text_field( $instance[self::START_TIME] );
                $endTime = sanitize_text_field( $instance[self::END_TIME] );
                $memberPrice = sanitize_text_field( $instance[self::MEMBER_PRICE] );
                $publicPrice = sanitize_text_field( $instance[self::PUBLIC_PRICE] );
                $level = sanitize_text_field( $instance[self::LEVEL] );
                $notes = sanitize_text_field( $instance[self::NOTES] );
                
                $button_title = sanitize_text_field( $instance['button_title'] );
                $button_link = sanitize_text_field( $instance['button_link'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Content:' ); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>
				
                <p><label for="<?php echo $this->get_field_id(self::TUTORNAME); ?>"><?php _e('Tutors Name:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::TUTORNAME); ?>" name="<?php echo $this->get_field_name(self::TUTORNAME); ?>" type="text" value="<?php echo esc_attr($tutorName); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::START_DATE); ?>"><?php _e('Start Date:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::START_DATE); ?>" name="<?php echo $this->get_field_name(self::START_DATE); ?>" type="text" value="<?php echo esc_attr($startDate); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::START_TIME); ?>"><?php _e('Start Time:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::START_TIME); ?>" name="<?php echo $this->get_field_name(self::START_TIME); ?>" type="text" value="<?php echo esc_attr($startTime); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::END_DATE); ?>"><?php _e('Finsh Time:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::END_DATE); ?>" name="<?php echo $this->get_field_name(self::END_DATE); ?>" type="text" value="<?php echo esc_attr($endDate); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::END_TIME); ?>"><?php _e('End Time:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('self::END_TIME'); ?>" name="<?php echo $this->get_field_name(self::END_TIME); ?>" type="text" value="<?php echo esc_attr($endTime); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::LEVEL); ?>"><?php _e('Level:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::LEVEL); ?>" name="<?php echo $this->get_field_name(self::LEVEL); ?>" type="text" value="<?php echo esc_attr($level); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::NOTES); ?>"><?php _e('Notes:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::NOTES); ?>" name="<?php echo $this->get_field_name(self::NOTES); ?>" type="text" value="<?php echo esc_attr($notes); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::MEMBER_PRICE); ?>"><?php _e('Member Price:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::MEMBER_PRICE); ?>" name="<?php echo $this->get_field_name(self::MEMBER_PRICE); ?>" type="text" value="<?php echo esc_attr($memberPrice); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id(self::PUBLIC_PRICE); ?>"><?php _e('Public Price:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id(self::PUBLIC_PRICE); ?>" name="<?php echo $this->get_field_name(self::PUBLIC_PRICE); ?>" type="text" value="<?php echo esc_attr($publicPrice); ?>" /></p>
                
                <p><label for="<?php echo $this->get_field_id('button_title'); ?>"><?php _e('Button Title:'); ?></label>
 		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_title')); ?>" name="<?php echo $this->get_field_name('button_title'); ?>" type="text" value="<?php echo esc_attr($button_title); ?>" /></p>
 
                <p><label for="<?php echo $this->get_field_id('button_link'); ?>"><?php _e('Button Link:'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_link')); ?>" name="<?php echo $this->get_field_name('button_link'); ?>" type="text" value="<?php echo esc_url($button_link); ?>" /></p>
                    
                <?php
	}
}



