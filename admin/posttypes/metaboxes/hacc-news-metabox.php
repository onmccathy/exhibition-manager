<?php
/*******************************************************************************
 * Exhibition metabox fields.
 * 
 * This file contains the markup for displaying the news metabox markup.
 * 
 * included form metabox update.
 * 
 * Parameters.
 * 
 * $post                - this current post
 * 
 * Constants:
 * 
 * START_DATE           - The Date the news will be displayed on the front page.
 * END_DATE             - The date the news will be removed from the front page.
 *                      
 * 
 *******************************************************************************/


    
    $stored_metadata = get_metadata('post',$post->ID); 
?>
    <div id="hacc_post" value="<?php $post->ID?>">
        
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::START_DATE )?>" class="hacc-start-date">Publish From</label>
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
        <div class="hacc-meta-row">
            <div class="hacc-meta-label">
                <label for="<?php print_r(self::END_DATE) ?>" class="hacc-end-date">Remove on</label>
            </div>
            <div class="hacc-meta-field">
                <input type="text" class="hacc-date hacc-end-date hacc-datepicker" name="<?php print_r(self::END_DATE) ?>" id="<?php print_r(self::END_DATE) ?>"
                       value="<?php
                            if(!empty($stored_metadata[self::END_DATE])) {
                                
                                $time = esc_attr( $stored_metadata[self::END_DATE][0]);
                                 printf($time);
                            } else {
                                $str = esc_attr( date('Y-m-d'));
                                printf($str);
                            }
                          ?>"</input>
            </div>
        </div>
    </div>
<?php
/*  end of News metabox fields */
/*  do not remove this line - php needs a line or two after opening <?php */