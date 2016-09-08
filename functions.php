<?php

if (!function_exists('hacc_get_programme_title')) {
    
    function hacc_get_programme_title($post_ID) {
        
        $programme = new WP_Query(array( 'post_type' => 'hacc_programme', 'p' => $post_ID ));
        return $programme->post->post_title;
    }
}

if (!function_exists('hacc_get_venue_title')) {
    /**
     * Gets the venue title given post ID
     * @param type $post_ID
     * @return venue name
     */
    
    function hacc_get_venue_title($post_ID) {
        $venue = new WP_Query(array( 'post_type' => 'hacc_venue', 'p' => $post_ID ));
        return $venue->post->post_title;
    }
}

if (!function_exists('hacc_get_day')) {
    
    function hacc_get_day($day_of_week) {
        
        $day = '';
        switch ($day_of_week) {
            case 0:
                $day = 'Monday';
                break;
            case 1:
                $day = 'Tuesday';
                break;
            case 2:
                $day = 'Wednesday';
                break;
            case 3:
                $day = 'Thursday';
                break;
            case 4:
                $day = 'Friday';
                break;
            case 5:
                $day = 'Saturday';
                break;
            case 6:
                $day = 'Sunday';
                break;
        }
        
        return $day;
        
    }
}
if (!function_exists('om_get_post_type_template')) {
    /**
     * Get post type template
     * @param type $post_type
     * @param type $original_template
     * @return type
     */
    
    function hacc_get_post_type_template($post_type, $original_template) {
            
    
            if (is_archive() || is_search()) {
                if (file_exists(get_stylesheet_directory().'\archive-'. $post_type.'.php')) {
                    return get_stylesheet_directory().'\archive-'.$post_type.'.php';
                } else {
                    return plugin_dir_path(__FILE__).'public\templates\archive-'.$post_type.'.php';
                }
                
            } else {
                if (is_single()) {
                    if (file_exists(get_stylesheet_directory().'\single-'.$post_type.'.php')) {
                        return get_stylesheet_directory().'\single-'.$post_type.'.php';
                    } else {
                        return plugin_dir_path(__FILE__).'public\templates\single-'.$post_type.'.php';
                    }
                }
            }
            return $original_template;
    }
}
    
    if ( ! function_exists('om_log')) {
        function om_log ( $log)  {
      
      
            if ( is_array( $log ) || is_object( $log ) ) {
               error_log( print_r( $log, true ) );
            } else {
               error_log( $log );
            }
         }
      }
      
      if ( ! function_exists('dump_post_array')) {
        function dump_post_array ( $posts)  {
            
            if (is_array($posts)) {
                forEach($posts as $post) {
                    om_log($post);
                    $storedmeta = get_metadata('post',$post->ID);
                    om_log($storedmeta);
                    if (!empty($storedmeta['hacc_StartDate'])) {
                       
                        om_log($storedmeta['hacc_StartDate'][0]);
                        
                    }
                    if (!empty($storedmeta['hacc_EndDate'])) {
                        om_log($storedmeta['hacc_EndDate'][0]);
                        

                    }
               
                }
            } else {
                om_log($posts);
                $storedmeta = get_metadata('post',$posts->ID);
                if (!empty($storedmeta['hacc_StartDate'])) {
                    $date = new DateTime($storedmeta['hacc_StartDate'][0]);
                    om_log($date->format('Y-m-d H:i:s'));
                    
                }
                if (!empty($storedmeta['hacc_EndDate'])) {
                    $date = new DateTime($storedmeta['hacc_EndDate'][0]);
                    om_log($date->format('Y-m-d H:i:s'));
                }
                om_log('now');
                $nowDate = new DateTime('now');
                $nowDate->setTime(00, 00, 00);
                $now = $nowDate->getTimestamp();
                om_log($now->format('Y-m-d H:i:s'));
                om_log($now->format($now->getTimestamp()));
            }
      
        }
      }
    

