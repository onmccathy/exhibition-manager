<?php
/**
 * Exhibitions list shortcode
 * 
 * Shortcode [hacc-list-exhibitions]
 * 
 * Parameters:
 * 
 * 'title'                       => Section Title,
 * 'no-exhibitions-message'      => Message displayed if there are no exhibitions to list.
 * 'post-count'                  => Maximum number of exhibitions read,
 * 'period'                      => 'future' -  Lists Exhibitions with a start date greater than today's date.
 *                                  'past'   -  Lists Exhibitions where the end date is less than today's date.
 *                                  'present -  Lists Exhibitions where the start date is less or equal than today 
 *                                              and the end date is greater than or equal to today
 * 'show-upcomming-if-no-current'=> 'no'        If there are no exhibitions currently showing display 'no-exhibitions-message' message.
 *                                  'yes'       Show up and coming.         
 * 'pagination'                  => 'false'     
 * 
 */

            