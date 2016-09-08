<?php

/* 
 * Template to display a single Exhibition.
 *
 * Notes:
 *  
 * 
 */

Global $post;

const POST_TYPE         = 'hacc_workshop';
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

get_header(); 

$sm = get_metadata('post',$post->ID);

$startDate = ! empty( $sm[START_DATE] ) ? new DateTime($sm[ START_DATE][0]) : '';
$endDate = ! empty( $sm[ END_DATE] ) ? new DateTime($sm[END_DATE][0]) : '';
$startTime = ! empty( $sm[ START_TIME] ) ? new DateTime($sm[ START_TIME][0]) : '';
$endTime = ! empty( $sm[ END_TIME] ) ? new DateTime($sm[ END_TIME][0]) : '';
$memberPrice = ! empty( $sm[ MEMBER_PRICE] ) ? $sm[ MEMBER_PRICE][0] : 0;
$publicPrice = ! empty( $sm[ PUBLIC_PRICE] ) ? $sm[ PUBLIC_PRICE][0] : 0;
$level = ! empty( $sm[ LEVEL] ) ? $sm[ LEVEL][0] : '';
$notes = ! empty( $sm[ NOTES] ) ? $sm[ NOTES][0] : '';

$programme_title = hacc_get_programme_title($post->post_parent);

$venue_title = ! empty( $sm[VENUE] ) ? hacc_get_venue_title($sm[VENUE][0]) : '';

?><div id="prmary" class="content-area">
	<main id="main" class="site-main" role="main">
            <?php
            // Start the loop.

            while ( have_posts() ) : the_post();

             ?>    
                <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>

                        <div class="entry-main">

                                <?php do_action('vantage_entry_main_top') ?>

                                <header class="entry-header">

                                        <?php if( has_post_thumbnail() && siteorigin_setting('blog_featured_image') ): ?>
                                                <div class="entry-thumbnail"><?php the_post_thumbnail( is_active_sidebar('sidebar-1') ? 'post-thumbnail' : 'vantage-thumbnail-no-sidebar' ) ?></div>
                                        <?php endif; ?>

                                        <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'vantage' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

                                        <?php if ( siteorigin_setting( 'blog_post_metadata' ) && get_post_type() == POST_TYPE ) : ?>
                                                <div class="entry-meta">
                                                        <?php vantage_posted_on(); ?>
                                                </div><!-- .entry-meta -->
                                        <?php endif; ?>

                                </header><!-- .entry-header -->
                                <div>
                                <?php 
                                if (!empty($programme_title) ) {
                                    echo '<p class="hacc-widget-field">Programme: ' .$programme_title .'</p>';
                                }    
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
                                ?></div>
                                <div class="entry-content">
                                        <?php the_content(); ?>
                                        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'vantage' ), 'after' => '</div>' ) ); ?>
                                </div><!-- .entry-content -->

                                <?php if(vantage_get_post_categories()) : ?>
                                        <div class="entry-categories">
                                                <?php echo vantage_get_post_categories() ?>
                                        </div>
                                <?php endif; ?>

                                <?php if( is_singular() && siteorigin_setting('blog_author_box') ) : ?>
                                        <div class="author-box">
                                                <div class="avatar-box">
                                                        <div class="avatar-wrapper"><?php echo get_avatar( get_the_author_meta('user_email'), 70 ) ?></div>
                                                </div>
                                                <div class="box-content entry-content">
                                                        <h3 class="box-title"><?php echo esc_html( get_the_author_meta('display_name') ) ?></h3>
                                                        <div class="box-description">
                                                                <?php echo wp_kses_post( wpautop( get_the_author_meta('description') ) ) ?>
                                                        </div>
                                                </div>
                                        </div>
                                <?php endif; ?>


                                <?php do_action('vantage_entry_main_bottom') ?>

                        </div>

                </article><!-- #post-<?php the_ID(); ?> -->
                <?php
            endwhile;
            ?>

	</main><!-- .site-main -->

	

</div><!-- .content-area -->


<?php get_footer(); 
