<?php
/**
 * Plugin Name:       Hello Lyrics Block
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.3
 * Version:           1.0.1
 * Author:            hakik
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hello-lyrics-block
 *
 * @package           hello-lyrics
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_hello_lyrics_block_block_init() {
	register_block_type( __DIR__ . '/build', array(
        'render_callback' => 'create_block_hello_lyrics_block_dynamic_render_callback'
    ) );
}
add_action( 'init', 'create_block_hello_lyrics_block_block_init' );


function create_block_hello_lyrics_block_dynamic_render_callback( $attributes, $content, $block ){

    ob_start();

    require plugin_dir_path( __FILE__ ) . 'build/render.php' ;

    return ob_get_clean();

}

// This just echoes the chosen line, we'll position it later.
function hls_block_chosen_lyric_line_render() {
    $chosen = hls_get_the_lyric();
    $lang   = '';
    $title = get_option( "hls_lyric_text_title" );
    $by = get_option( "hls_lyric_text_by" );
    if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
        $lang = ' lang="en"';
    }

    return sprintf(
        '<span class="screen-reader-text">%s %s %s %s </span><span dir="ltr"%s>%s</span>',
        __( 'Quote from' ),
        $title,
        __( 'song, by' ),
        $by,
        $lang,
        $chosen


    );
}
