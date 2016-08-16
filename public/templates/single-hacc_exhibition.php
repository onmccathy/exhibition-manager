<?php

/* 
 * Template to display a single Exhibition.
 *
 * Notes:
 *  
 * 
 */

Global $post;

const START_DATE        = 'hacc_StartDate';
const END_DATE          = 'hacc_EndDate';

get_header(); 

$stored_metadata = get_metadata('post',$post->ID);

$start_date = '';
$end_date = '';

if (!empty($stored_metadata[START_DATE])) {
    
    $start_date = date("d/m/Y", strtotime(esc_attr($stored_metadata[START_DATE][0])));
}
if (!empty($stored_metadata[END_DATE])) {
    $end_date = date("d/m/Y", strtotime(esc_attr($stored_metadata[END_DATE][0])));
}


?><div id="prmary" class="content-area">
	<main id="main" class="site-main" role="main">
            <?php
            // Start the loop.

            while ( have_posts() ) : the_post();

             ?>    
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        </header><!-- .entry-header -->
                        <!-- Display the exhibition opening and closing dates-->
                        <div class='hacc-entry-dates'>
                            <span class='hacc-entry-label'>Opening Date</span>
                            <span class='hacc-entry-value'><?php print_r($start_date) ?> </span>
                            <span class='hacc-entry-label'>Closing Date</span>
                            <span class='hacc-entry-value'><?php print_r($end_date) ?> </span>
                        </div>
                        <!-- Display the thumbnail and post content-->
                        <?php if ( has_post_thumbnail()) {
                            the_post_thumbnail();
                        }
                        ?>
                        <div class="hacc-entry-content"><?php the_content() ?></div>


                </article> 
                <?php
            endwhile;
            ?>

	</main><!-- .site-main -->

	

</div><!-- .content-area -->


<?php get_footer(); 
