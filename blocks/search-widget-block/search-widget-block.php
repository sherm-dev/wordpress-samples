<?php
/**
 * Plugin Name:       Search Widget Block
 * Description:       Example static block scaffolded with Create Block tool.
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       search-widget-block
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_search_widget_block_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_search_widget_block_block_init' );

add_filter('render_block_jewishla-blocks/search-widget-block', function($block_content, $block, $instance){
	wp_enqueue_script('search-widget-script', get_template_directory_uri() . '/blocks/search-widget-block/dist/search-widget.js', array('wp-element', 'wp-data'), '', array('in_footer' => TRUE));
	
	return $block_content;
}, 99, 3);
