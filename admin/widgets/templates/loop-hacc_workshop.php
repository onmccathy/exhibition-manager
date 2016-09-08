<?php
/**
 * The template for displaying current and future workshops in a grid layout.
 * Workshops will be displayed in start date order.
 * Maximum of 30 workshops will be displayyed.
 * TODO Create option to specify number of workshops to display.
 */
        const PARENT_POST_TYPE  = 'hacc_programme';
        const START_DATE        = 'hacc_StartDate';
        const START_TIME        = 'hacc_StartTime';
        const END_DATE          = 'hacc_EndDate';
        const END_TIME          = 'hacc_EndTime';
        
        const PUBLIC_PRICE      = 'hacc_public_price';
        const MEMBER_PRICE      = 'hacc_member_price';
        const TUTOR             = 'hacc_tutor';
        const TUTORNAME         = 'hacc_tutor_name';
        const TUTOR_POST_TYPE   = 'hacc_tutor';
        const VENUE_POST_TYPE   = 'hacc_venue';
        const VENUE             = 'hacc_venue'; // field
        const LEVEL             = 'hacc_level';
        const NOTES             = 'hacc_notes';

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
                
    'post_type'         => 'hacc_workshop',
    'post_status'       => 'publish',
    'post_parent'       => $post_parent_ID,
    'posts_per_page'     => 30,
    'meta_key'          => 'hacc_StartDate',
    'orderby'           => 'meta_value',
    'order'             => 'ASC',
    'meta_query'        => array(
        array (
                'key'       =>'hacc_EndDate',
                'value'     => $now,
                'compare'   => '>=',

            ),
    ),
);
/*
 * The parent of a Workshop post is a Programme Post Type.
 * 
 * The parent post type variable if set contains the title 
 * of the parent ie a Programme post type.
 * 
 * if the variable $post_parent is set then we are only
 * interested in selecting these workshops where the 
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


$workshops = new WP_Query($args);

/**
  * Get a list of tutors
  */
    $tutorargs = array(
       'post_type'          => TUTOR_POST_TYPE,
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
        'post_type'         => VENUE_POST_TYPE,
        'post_status'       => 'publish',
        'number'            => '-1',
        'order'             => 'ASC',
        'orderby'           => 'title',
        
    );
    
    $venues = new WP_Query($venueargs);


?>

	<div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

            <?php if ( $workshops->have_posts() ) : $i = 0;?>
                
                <div id="vantage-circleicon-loop hacc-flashycontainer-loop" class="vantage-circleicon-loop hacc-flashycontainer-loop">

		<?php
                    while( $workshops->have_posts() ){ ?>
                        <div class="hacc-flashy-widget-wrapper" index="<?php printf($i) ?>">
                        <?php    
                        $workshops->the_post();
                        
                        $sm = get_metadata('post',esc_attr(get_the_ID())); 
                        $tutor_id = '';
                        if ( ! empty($sm[TUTOR])) {
                            $tutor_id = $sm[TUTOR][0];
                        }
                        $venue_id = '';
                        if ( ! empty($sm[VENUE])) {
                            $venue_id = $sm[VENUE][0];
                        } 
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id() );
                        
                        $title = get_the_title();
                        $text = get_the_excerpt();
                        $button_link = get_permalink();

                        $startDate = ! empty( $sm[START_DATE] ) ? esc_attr($sm[START_DATE][0]) : '';
                        $endDate = ! empty( $sm[END_DATE] ) ? esc_attr($sm[END_DATE][0]) : '';
                        $startTime = ! empty( $sm[START_TIME] ) ? esc_attr($sm[START_TIME][0]) : '';
                        $endTime = ! empty( $sm[END_TIME] ) ? esc_attr($sm[END_TIME][0]) : '';
                        $memberPrice = ! empty( $sm[MEMBER_PRICE] ) ? esc_attr($sm[MEMBER_PRICE][0]) : '0.00';
                        $publicPrice = ! empty( $sm[PUBLIC_PRICE] ) ? esc_attr($sm[PUBLIC_PRICE][0]) : '0.00';
                        $level = ! empty( $sm[LEVEL] ) ? esc_attr($sm[LEVEL][0]) : '';
                        $notes = ! empty( $sm[NOTES] ) ? esc_attr($sm[NOTES][0]) : '';
                        
                        // get tutor
                        $tutorName = '';
                        if ( $tutors->have_posts() ) {
                            while ($tutors->have_posts()) {
                                $tutors->the_post();
                                if(!empty($tutor_id) && $tutor_id == esc_attr(get_the_ID())) {
                                    $tutorName = get_the_title();
                                }
                            }
                        }
                        the_widget(
                            'Hacc_Workshop_Widget',
                            array(
                                'title' => $title,
                                'text' => $text,
                                'button_link' => $button_link,
                                TUTORNAME => $tutorName,
                                START_DATE => $startDate,
                                END_DATE => $endDate,
                                START_TIME => $startTime,
                                END_TIME => $endTime,
                                MEMBER_PRICE =>$memberPrice,
                                PUBLIC_PRICE =>$publicPrice,
                                LEVEL =>$level,
                                NOTES =>$notes,
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
