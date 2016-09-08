<?php
/*******************************************************************************
 * Group metabox fields.
 * 
 * This file contains the markup for displaying the group metabox markup.
 * 
 * included form metabox update.
 * 
 * Parameters.
 * 
 * $post                - this current post
 * 
 * Constants:
 * 
 * PARENT_POST_TYPE     - The venue / gallery where the group will meet.
 * VENUE                - The Venue post ID -  we display the venue title which contains the tutor's name
 * START_TIME           - The start time.
 * END_DATE             - End Timee
 * DAY_OF_WEEK          - The class fee for the public.
 * FREQUENCY            - Frequency the group meets 'Weekly,Fortnightly,Monthly'.
 * CONVENOR             - Group Convenor.
 * CONVENOR_PHONE_NUMBER- Group Convenor Phone Number.
 * 
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
    
    
    $stored_metadata = get_metadata('post',$post->ID);
    
    $dayofweek = "0";
    if (!empty($stored_metadata[self::DAY_OF_WEEK])) {
        $dayofweek = $stored_metadata[self::DAY_OF_WEEK][0];
    } 
    $frequency = "W";
    if (!empty($stored_metadata[self::FREQUENCY])) {
        $dayofweek = $stored_metadata[self::FREQUENCY][0];
    } 
?>
    <div id="hacc_post" value="<?php $post->ID?>">
        <div class="hacc-meta-row">
            <div class="hacc-row-label">Venue</div> 
            <div class="hacc-row-field">
                <div id="hacc-programme-selection-list" name="hacc-venue-selection-list">
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
        <!-- Day of Week -->
        <div class="hacc-meta-row">
            <div id="hacc-dayofweek-selection-list" name="hacc-dayofweek-selection-list">
            <?php   $html = '<select name="'. self::DAY_OF_WEEK .'">';

                    if($dayofweek == "0") {$selected = ' selected="selected" ';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="0">Monday</option>';
                    if($dayofweek == "1") {$selected = ' selected="selected" ';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="1">Tuesday</option>';
                    if($dayofweek == "2") {$selected = ' selected="selected" ';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="2">Wednesday</option>';
                    if($dayofweek == "3") {$selected = ' selected="selected" ';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="3">Thursday</option>';
                    if($dayofweek == "4") {$selected = ' selected="selected" ';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="4">Friday</option>';
                    if($dayofweek == "5") {$selected = ' selected="selected" ';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="5">Saturday</option>';
                    if($dayofweek == "6") {$selected = ' selected="selected" ';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="6">Sunday</option>';
                    $html .= '</select>';
                    print_r($html);
            ?>
                </div>
        </div>
        <!-- Frequency -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::FREQUENCY) ?>" class="hacc-frequency-label">Frequency</label>
            </div>
            <div class="hacc-meta-field">
               <div id="hacc-frequency-selection-list" name="hacc-frequency-selection-list">
            <?php   $html = '<select name="'. self::FREQUENCY .'">';
                    $selected = '';
                    if($frequency == "W") {$selected = ' selected="selected"';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="W">Weekly</option>';
                    if($dayofweek == "F") {$selected = ' selected="selected"';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="F">Fortnightly</option>';
                    if($dayofweek == "M") {$selected = ' selected="selected"';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="2">Monthly</option>';
                    if($dayofweek == "O") {$selected = ' selected="selected"';} else {$selected = '';}
                    $html .= '<option ' . $selected . ' value="2">Other</option>';
                    $html .= '</select>';
                    print_r($html);
            ?>
                </div> 
            </div>
        </div>
        <!-- Convener -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::CONVENOR )?>" class="hacc-convenor-label">Convenor </label>
            </div>
            <div class="hacc-meta-field">
                <input class="hacc-convenor" name="<?php print_r(self::CONVENOR)?>" id="<?php print_r(self::CONVENOR) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::CONVENOR])) {
                                $convenor = esc_attr( $stored_metadata[self::CONVENOR][0]);
                                 printf($convenor);
                            } else {
                                $str = esc_attr('');
                                printf($str);
                            }

                          ?>"</input>
            </div>
        </div>
        <!-- Convenor Phone Number -->
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::CONVENOR_PHONE_NUMBER )?>" class="hacc-phone-label">Phone: </label>
            </div>
            <div class="hacc-meta-field">
                <input class="hacc-phone" name="<?php print_r(self::CONVENOR_PHONE_NUMBER)?>" id="<?php print_r(self::CONVENOR_PHONE_NUMBER) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::CONVENOR_PHONE_NUMBER])) {
                                $phone = esc_attr( $stored_metadata[self::CONVENOR_PHONE_NUMBER][0]);
                                 printf($phone);
                            } else {
                                $str = esc_attr('');
                                printf($str);
                            }
                          ?>"</input>
            </div>
        </div>
    </div>
<?php
/*  end of Exhibition metabox fields */
/*  do not remove this line - php needs a line or two after opening <?php */