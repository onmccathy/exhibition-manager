<?php

/* 
 * Template to display a single Venue.
 *
 * Notes:
 *  
 * 
 */

get_header(); 

?><div id="prmary" class="content-area">
	<main id="main" class="site-main" role="main">
            <?php
            // Start the loop.

            while ( have_posts() ) : the_post();

             ?>    
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        <!-- -->
                        </header><!-- .entry-header -->

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

