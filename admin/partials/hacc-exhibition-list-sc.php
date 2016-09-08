<?php
            global $post;
                       
            $atts = shortcode_atts( array(
                'title'                             => 'Up Coming',
                'no-exhibitions-message'            => 'There are no up and coming exhibitions scheduled',
                'count'                             =>  20,
                'period'                            => 'future',
                'show-opening-soon-if-no-current'   => 'yes',
                'opening-soon-count'                => 2,
                'opening-soon-title'                => 'Opening Soon',
                'show-title'                        => 'yes',
                'show-featured-image'               => 'yes',
                'show-start-date'                   => 'yes',
                'show-end-date'                     => 'yes',
                'date-format'                       => 'Y-m-d',
                'start-date-title'                  => 'Opens',
                'end-date-title'                    => 'Closes',
                'include-action-button'             => 'yes',
                'action-button-text'                => 'Find Out More',
                'action-button-class'               => 'hacc-button',
                
            ),$atts);
            
            $paged = get_query_var( 'paged') ? get_query_var( 'paged') : 1;
            
            
            $nowDate = new DateTime('now');
            $now =  $nowDate->format('Y-m-d');
            
            $post_count = (int)$atts['count'];
            $opening_soon_count = (int)$atts['opening-soon-count'];
                
            $future_args = array(
                
                'post_type'         =>self::POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'     => $post_count,
                'meta_key'          => self::START_DATE,
                'orderby'           => 'meta_value',
                'order'             => 'ASC',                
                'meta_query'        => array(
                    array (
                        'key'       =>self::START_DATE,
                        'value'     => $now,
                        'compare'   => '>',
                    ),
                ),
                
            );
            
            $opening_soon_args = array(
                
                'post_type'         =>self::POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'     => $opening_soon_count,
                'meta_key'          => self::START_DATE,
                'orderby'           => 'meta_value',
                'order'             => 'ASC',                
                'meta_query'        => array(
                    array (
                        'key'       =>self::START_DATE,
                        'value'     => $now,
                        'compare'   => '>',
                    ),
                ),
                
            );
            
            $past_args = array(
                
                'post_type'         => self::POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'     => $post_count,
                'meta_key'          => self::END_DATE,
                'orderby'           => 'meta_value',
                'order'             => 'DESC',
                'meta_query'        => array(
                    array (
                            'key'       =>self::END_DATE,
                            'value'     => $now,
                            'compare'   => '<=',
                            
                        ),
                ),
            );
            
            $present_args = array(
                
                'post_type'         =>self::POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'     => $post_count,
                'meta_key'          => self::START_DATE,
                'orderby'           => 'meta_value',                
                'meta_query'        => array(
                    'relation'      => 'AND',
                    array (
                            'key'       =>self::START_DATE,
                            'compare'   => '<=',
                            'value'     => $now,
                    ),
                    array (
                            'key'       =>self::END_DATE,
                            'compare'   => '>=',
                            'value'     => $now,
                    ),
                ),
            );
            
            $args = $future_args;
            if ($atts['period'] == 'past') {
                $args = $past_args;
            }
            if ($atts['period'] == 'present') {
                $args = $present_args;
            }
            
            $events = new WP_Query($args);
            
            $no_current = '';
            if (!$events->have_posts() && 
                    $atts['period'] == 'present' &&
                    $atts['show-opening-soon-if-no-current'] == 'yes') {
                wp_reset_postdata();
                $no_current = 'No Exhibitions are currently showing';
                $args = $opening_soon_args;
                $atts['title'] = $atts['opening-soon-title'];
                $events = new WP_Query($args);
            }

            $html = '<div class="hacc-container">';
            // if required - show title
            if($atts['show-title'] == 'yes') {
                $html  .= '<div class="list-title"><h2>';
                    $html .= $atts['title'] . '</h2></div>';
            }
            // 
            $formatOddEven = '-odd';
            if ($events->have_posts()) {
                $html .= '<div class="hacc-exhibitions">';
                if ($events->have_posts()) {
                    while ($events->have_posts()) {
                        $events->the_post();
                        $stored_metadata = get_metadata('post',$post->ID);
                        $start_date = '';
                        $end_date = '';

                        if (!empty($stored_metadata[self::START_DATE])) {

                            $start_date = new DateTime($stored_metadata[self::START_DATE][0]);
                        }
                        if (!empty($stored_metadata[self::END_DATE])) {
                            $end_date = new DateTime($stored_metadata[self::END_DATE][0]);
                        }
                        // set css class name suffix alternativly to -odd and -even
                        $html .= '<div class= "hacc-exhibition' . $formatOddEven . '">';
                        if ($formatOddEven == '-odd') {
                            $formatOddEven = '-even';
                        } else {
                            $formatOddEven = '-odd';
                        }
                        $html .= '<h3>' . esc_html(get_the_title()) . '</h3>';
                        $html .= '<div class="hacc-entry-dates">';
                        if ($atts['show-start-date'] == 'yes') {
                            $html .='<span class="hacc-entry-label hacc-exhibition-date">'. $atts['start-date-title'] . ' </span>';
                            $html .= '<span class="hacc-entry-value  hacc-exhibition-date">' . $start_date->format($atts['date-format']) .' </span>';
                        }
                        if ($atts['show-end-date'] == 'yes') {
                            $html .= '<span class="hacc-entry-label  hacc-exhibition-date">'. $atts['end-date-title'] . ' </span>';
                            $html .= '<span class="hacc-entry-value  hacc-exhibition-date">' . $end_date->format($atts['date-format']) . ' </span>';
                        }
                        $html .= '</div>';
                        // show featured image
                        if ($atts['show-featured-image'] == 'yes') {
                            // Display the thumbnail and post content
                            if ( has_post_thumbnail()) {
                                $html .= get_the_post_thumbnail();
                            }
                        }
                        if($atts['include-action-button'] == 'yes') {
                            $html .= '<p><a href="';
                            $html .= esc_html(get_the_permalink());
                            $html .= '"><button class="hacc-item-button" type="button">';
                            $html .= esc_html($atts['action-button-text']) . '</button></a>';
                            
                        }
                        $html .= '</div>';
                    }
                }
                $html .= '</div>';
            } else {
                $html .= '<div class="hacc-message">'.$atts['no-exhibitions-message'];
            }
            $html .= '</div>'; // 
            wp_reset_postdata();
            return $html;
