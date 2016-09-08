<?php
/*******************************************************************************
 * Exhibition metabox fields.
 * 
 * This file contains the markup for displaying the exhibition metabox markup.
 * 
 * included form metabox update.
 * 
 * Parameters.
 * 
 * $post                - this current post
 * 
 * Constants:
 * 
 * PARENT_POST_TYPE     - The venue / gallery where the exhibition will be held.
 * TUTOR                - The Tutor post ID -  we display the post title which contains the tutor's name
 * VENUE                - The Venue post ID -  we display the venue title which contains the tutor's name
 * START_DATE           - The Date the exhibition starts.
 * START_TIME           - Start Time
 * END_DATE             - The date the exhibition closes.
 * END_TIME             - End Time
 * PUBLIC_PRICE         - The class fee for the public.
 * MEMBER_PRICE         - The class fee for members.
 * LEVEL                - The workshop expected competency level
 * NOTES                - Any additional notes ie Materials
 * TUTOR_POST_TYPE      - The tutor post type.
 * VENUE_POST_TYPE      - The Studio post type.
 *                      
 * 
 *******************************************************************************/

/**
 * get list of venues for venue selection list 
 */
    $args = array(
        'post_type'         => self::PARENT_POST_TYPE,
        'post_status'       => 'publish',
        'number'            => '-1',
        'order'             => 'ASC',
        'orderby'           => 'title',

    );
    $parents = new WP_Query($args);
    
 /**
  * Get a list of tutors
  */
    $tutorargs = array(
       'post_type'          => self::TUTOR_POST_TYPE,
        'post_status'       => 'publish',
        'number'            => '-1',
        'order'             => 'ASC',
        'orderby'           => 'title', 
    );
    
    $tutors = new WP_Query($tutorargs);
    
    /**
  * Get a list of venues
  */
    $venueargs = array(
        'post_type'         => self::VENUE_POST_TYPE,
        'post_status'       => 'publish',
        'number'            => '-1',
        'order'             => 'ASC',
        'orderby'           => 'title',
        
    );
    
    $venues = new WP_Query($venueargs);
    
    
    
    $stored_metadata = get_metadata('post',$post->ID); 
    $tutor_id = '';
    if ( ! empty($stored_metadata[self::TUTOR])) {
        $tutor_id = $stored_metadata[self::TUTOR][0];
    }
    $venue_id = '';
    if ( ! empty($stored_metadata[self::VENUE])) {
        $venue_id = $stored_metadata[self::VENUE][0];
    }
?>
    <div id="hacc_post" value="<?php $post->ID?>">
        <div class="hacc-meta-row">
            <div class="hacc-row-label">Programme</div> 
            <div class="hacc-row-field">
                <div id="hacc-programme-selection-list" name="hacc-programme-selection-list">
            <?php        $html = '<select name="'. self::PARENT_POST_TYPE .'">';
               if ( $parents->have_posts() ) {
                        while ($parents->have_posts()) {
                            $parents->the_post();
                                $selected = '';
                                
                                if ($post->post_parent == esc_attr(get_the_ID())) {
                                    $selected = ' selected="selected"';
                                }
                                $html .= '<option' . $selected . ' value="' . esc_html(get_the_ID()) . '">' . esc_html(get_the_title()) . '</option>';                            
                        }
                        $html .= '</select>';
                        print_r($html);
                        wp_reset_postdata();
                    } else { ?>
                        <p><?php _e( 'There are no ' . self::PARENT_POST_TYPE ); ?></p>
                    <?php }; ?>
                </div>
            </div>
        </div>
        <!-- Tutor -->
        <div class="hacc-meta-row">
            <div class="hacc-row-label">Tutor</div> 
            <div class="hacc-row-field">
                <div id="hacc-tutor-selection-list" name="hacc-tutors-selection-list">
            <?php        $html = '<select name="'. self::TUTOR .'">';
               if ( $tutors->have_posts() ) {
                        while ($tutors->have_posts()) {
                            $tutors->the_post();
                                $selected = '';
                                 if ($tutor_id == esc_attr(get_the_ID())) {
                                    $selected = ' selected="selected"';
                                }
                                $html .= '<option' . $selected . ' value="' . esc_html(get_the_ID()) . '">' . esc_html(get_the_title()) . '</option>';                            
                        }
                        $html .= '</select>';
                        print_r($html);
                        wp_reset_postdata();
                    } else { ?>
                        <p><?php _e( "You need to add Tutors before attempting to add classes"); '</p>'?>
                    <?php }; ?>
                </div>
            </div>
        </div>
        <!-- Venue -->
        <div class="hacc-meta-row">
            <div class="hacc-row-label">Venue</div> 
            <div class="hacc-row-field">
                <div id="hacc-venue-selection-list" name="hacc-venue-selection-list">
            <?php        $html = '<select name="'. self::VENUE .'">';
               if ( $venues->have_posts() ) {
                        while ($venues->have_posts()) {
                            $venues->the_post();
                                $selected = '';
                                 if ($venue_id == esc_attr(get_the_ID())) {
                                    $selected = ' selected="selected"';
                                }
                                $html .= '<option' . $selected . ' value="' . esc_html(get_the_ID()) . '">' . esc_html(get_the_title()) . '</option>';                            
                        }
                        $html .= '</select>';
                        print_r($html);
                        wp_reset_postdata();
                    } else { ?>
                        <p><?php _e( "You need to add Venues before attempting to add classes"); '</p>'?>
                    <?php }; ?>
                </div>
            </div>
        </div>
        <!-- Start Date -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::START_DATE )?>" class="hacc-start-date">Start Date</label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-date hacc-start-date hacc-datepicker" name="<?php print_r(self::START_DATE)?>" id="<?php print_r(self::START_DATE) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::START_DATE])) {
                                $time = esc_attr( $stored_metadata[self::START_DATE][0]);
                                 printf($time);
                            } else {
                                $str = esc_attr( date('Y-m-d'));
                                printf($str);
                            }

                          ?>"</input>
            </div>
        </div>
        <!-- Start Time -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::START_TIME )?>" class="hacc-start-time-label">Start Time</label>
            </div>
            <div class="hacc-meta-field">
                <input class="hacc-time hacc-timepicker" name="<?php print_r(self::START_TIME)?>" id="<?php print_r(self::START_TIME) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::START_TIME])) {
                                $time = esc_attr( $stored_metadata[self::START_TIME][0]);
                                 printf($time);
                            } else {
                                $str = esc_attr( date('H:i'));
                                printf($str);
                            }

                          ?>"</input>
            </div>
        </div>
        <!-- End Date -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::END_DATE) ?>" class="hacc-end-date">End Date</label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-date hacc-end-date hacc-date hacc-datepicker" name="<?php print_r(self::END_DATE) ?>" id="<?php print_r(self::END_DATE) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::END_DATE])) {
                                // We hold dates as time since Jan 1 1970 so convert and display
                                $time = esc_attr( $stored_metadata[self::END_DATE][0]);
                                 printf($time);
                            } else {
                                $str = esc_attr( date('Y-m-d'));
                                printf($str);
                            }
                          ?>"</input>
            </div>
        </div>
        <!-- End Time -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::END_TIME )?>" class="hacc-end-time-label">End Time</label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-time hacc-timepicker" data-enable-time=true data-no-calendar=true name="<?php print_r(self::END_TIME)?>" id="<?php print_r(self::END_TIME) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::END_TIME])) {
                                $time = esc_attr( $stored_metadata[self::END_TIME][0]);
                                 printf($time);
                            } else {
                                $str = esc_attr( date('H:i'));
                                printf($str);
                            }

                          ?>"</input>
            </div>
        </div>
        <!-- Expected student competency level -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::LEVEL )?>" class="hacc-level-label">Level: </label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-level" name="<?php print_r(self::LEVEL)?>" id="<?php print_r(self::LEVEL) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::LEVEL])) {
                                $level = esc_attr( $stored_metadata[self::LEVEL][0]);
                                 printf($level);
                            } else {
                                $str = '';
                                printf($str);
                            }

                          ?>"</input>
            </div>
        </div>
        <!-- Workshop notes -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::NOTES )?>" class="hacc-notes-label">Notes: </label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-level" name="<?php print_r(self::NOTES)?>" id="<?php print_r(self::NOTES) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::NOTES])) {
                                $level = esc_attr( $stored_metadata[self::NOTES][0]);
                                 printf($level);
                            } else {
                                $str = '';
                                printf($str);
                            }

                          ?>"</input>
            </div>
        </div>
        
        <!-- Member Price -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::MEMBER_PRICE) ?>" class="hacc-public-price-label">Member Price</label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-money " name="<?php print_r(self::MEMBER_PRICE) ?>" id="<?php print_r(self::MEMBER_PRICE) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::MEMBER_PRICE])) {
                                 $money = esc_attr( $stored_metadata[self::MEMBER_PRICE][0]);
                                 echo sprintf("%01.2f", $money);
                            } else {
                                 printf('0.00');
                            }
                          ?>"</input>
            </div>
        </div>
        <!-- Public Price -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::PUBLIC_PRICE) ?>" class="hacc-public-price-label">Public Price</label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-money " name="<?php print_r(self::PUBLIC_PRICE) ?>" id="<?php print_r(self::PUBLIC_PRICE) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::PUBLIC_PRICE])) {
                                 $money = esc_attr( $stored_metadata[self::PUBLIC_PRICE][0]);
                                 echo sprintf("%01.2f", $money);
                            } else {
                                 printf('0.00');
                            }
                          ?>"</input>
            </div>
        </div>
    </div>
<?php
/*  end of Exhibition metabox fields */
/*  do not remove this line - php needs a line or two after opening <?php */