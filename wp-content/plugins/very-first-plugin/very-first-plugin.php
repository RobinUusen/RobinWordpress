<?php
/**
 * Plugin Name: Very First Plugin
 * Plugin URI: http://robinuusen.ikt.khk.ee/
 * Description: This is the very firt plugin I ever created
 * Version: 1.0
 * Author: Robin uusen
 * Author URI: http://robinuusen.ikt.khk.ee/
 **/
 
 function dh_modify_read_more_link() {
 return '<a class="more-link" href="' . get_permalink() . '">Click to Read!</a>';
}
add_filter( 'the_content_more_link', 'dh_modify_read_more_link' );