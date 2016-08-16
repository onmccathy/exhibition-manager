<?php

if (!function_exists('om_get_post_type_template')) {
    /**
     * Get post type template
     * @param type $post_type
     * @param type $original_template
     * @return type
     */
    
    function hacc_get_post_type_template($post_type, $original_template) {
            om_log(__FUNCTION__);
            om_log($original_template);
    
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
    
    if ( ! function_exists('om_log')) {
        function om_log ( $log)  {
      
      
            if ( is_array( $log ) || is_object( $log ) ) {
               error_log( print_r( $log, true ) );
            } else {
               error_log( $log );
            }
         }
      }
    
}



