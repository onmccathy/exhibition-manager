<?php
/**
 * The template for displaying current and future groups in a grid layout.
 * Workshops will be displayed in start date order.
 * Maximum of 30 groups will be displayyed.
 * TODO Create option to specify number of groups to display.
 */
 
        const START_TIME        = 'hacc_StartTime';
        
        const END_TIME          = 'hacc_EndTime';
        const PARENT_POST_TYPE   = 'hacc_venue';
        const VENUE             = 'hacc_venue'; // field
        const VENUE_TITLE       = 'hacc_venue_title';
        const DAY_OF_WEEK       = 'hacc_DayOfWeek';
        const FREQUENCY         = 'hacc_Frequency';
        const CONVENOR          = 'hacc_Convenor';
        const CONVENOR_PHONE_NUMBER = 'hacc_phone_number';

$nowDate = new DateTime('now');
$now =  $nowDate->format('Y-m-d');


$post_parent_ID = '';
if (isset($post_parent) && !empty($post_parent)) {
    $post_parent_args = array(
        'post_type' => PARENT_POST_TYPE,
        'title'     => $post_parent,
    );

    $parent = new WP_Query($post_parent_args);

    foreach((array)$parent->posts AS $post) { 
            $post_parent_ID = $post->ID;
    }
        
}

$args = array(
                
    'post_type'         => 'hacc_group',
    'post_status'       => 'publish',
    'post_parent'       => $post_parent_ID,
    'posts_per_page'     => 30,
    
);
/*
 * The parent of a Workshop post is a Programme Post Type.
 * 
 * The parent post type variable if set contains the title 
 * of the parent ie a Programme post type.
 * 
 * if the variable $post_parent is set then we are only
 * interested in selecting these groups where the 
 * workshop post_parent field is equal to the post ID
 * of the programme parent post type. 
 * 
 * TODO - Instead of passing the title we need to pass
 * the ID of the parent. This could be selected in the
 * widget set up. But we dont know what parent we are looking
 * for until we get to this place.
 * 
 * In the widget we need two drop downs, one to select the post_type
 * and then when we know the post_type of the parent to dynamically 
 * create a dropdown of the possible parent posts. We then select the
 * parent post in the widget which then in turn gives us the parent post 
 * ID  
 *  
 */


$groups = new WP_Query($args);



?>

	<div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

            <?php if ( $groups->have_posts() ) : $i = 0;?>
                
                <div id="vantage-circleicon-loop hacc-flashycontainer-loop" class="vantage-circleicon-loop hacc-flashycontainer-loop">

		<?php
                    while( $groups->have_posts() ){ ?>
                        <div class="hacc-flashy-widget-wrapper" index="<?php printf($i) ?>">
                        <?php    
                        $groups->the_post();
                        
                        $sm = get_metadata('post',esc_attr(get_the_ID())); 
                        
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id() );
                        
                        $title = get_the_title();
                        $text = get_the_excerpt();
                        $button_link = get_permalink();

                        $venueTitle = get_the_title(wp_get_post_parent_id( esc_attr(get_the_ID()) ));
                        
                        $startTime = ! empty( $sm[START_TIME] ) ? esc_attr($sm[START_TIME][0]) : '';
                        $endTime = ! empty( $sm[END_TIME] ) ? esc_attr($sm[END_TIME][0]) : '';
                        $dayofweek = ! empty( $sm[DAY_OF_WEEK] ) ? esc_attr($sm[DAY_OF_WEEK][0]) : '';
                        $frequency = ! empty( $sm[FREQUENCY] ) ? esc_attr($sm[FREQUENCY][0]) : '';
                        $convenor = ! empty( $sm[CONVENOR] ) ? esc_attr($sm[CONVENOR][0]) : '';
                        $phone =  ! empty( $sm[CONVENOR_PHONE_NUMBER] ) ? esc_attr($sm[CONVENOR_PHONE_NUMBER][0]) : '';
                        
                        
                        the_widget(
                            'Hacc_Group_Widget',
                            array(
                                'title' => $title,
                                'text' => $text,
                                'button_link' => $button_link,
                                VENUE_TITLE => $venueTitle,
                                START_TIME => $startTime,
                                END_TIME => $endTime,
                                DAY_OF_WEEK =>$dayofweek,
                                FREQUENCY =>$frequency,
                                CONVENOR =>$convenor,
                                CONVENOR_PHONE_NUMBER =>$phone,
                            )
                        );
                        ?></div> <!--.hacc-flashy-widget-wrapper -->
                        <?php 
                        $i++;
 //                       if($i % 4 == 0) : <div class="clear"></div><?php endif;
                    }
		?>
                </div> <!-- .hacc-flashycontainer-loop -->
                <?php vantage_content_nav( 'nav-below' ); ?>
            <?php endif;?>
            </main><!-- .site-main -->
	</div><!-- .content-area -->
<?php
// end of archive-workshop.php
