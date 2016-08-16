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
 * START_DATE           - The Date the exhibition starts.
 * END_DATE             - The date the exhibition closes.
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
?>
    <div id="hacc_post" value="<?php $post->ID?>">
        <div class="hacc-meta-row">
            <div class="hacc-row-label">Venue</div> 
            <div class="hacc-row-field">
                <div id="hacc-venue-selection-list" name="hacc-venue-selection-list">
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
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::START_DATE )?>" class="hacc-start-date">Start Date</label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-date hacc-start-date hacc-datepicker" name="<?php print_r(self::START_DATE)?>" id="<?php print_r(self::START_DATE) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::START_DATE])) {
                                $time = esc_attr( $stored_metadata[self::START_DATE][0]);
                                 printf(date('Y-m-d', $time));
                            } else {
                                $str = esc_attr( date('Y-m-d'));
                                printf($str);
                            }

                          ?>"</input>
            </div>
        </div>
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::END_DATE) ?>" class="hacc-end-date">End Date</label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-date hacc-end-date hacc-datepicker" name="<?php print_r(self::END_DATE) ?>" id="<?php print_r(self::END_DATE) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::END_DATE])) {
                                // We hold dates as time since Jan 1 1970 so convert and display
                                $time = esc_attr( $stored_metadata[self::END_DATE][0]);
                                 printf(date('Y-m-d', $time));
                            } else {
                                $str = esc_attr( date('Y-m-d'));
                                printf($str);
                            }
                          ?>"</input>
            </div>
        </div>
    </div>
<?php
/*  end of Exhibition metabox fields */
/*  do not remove this line - php needs a line or two after opening <?php */