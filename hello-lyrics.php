<?php
/*
 * Plugin Name:       Hello Lyrics
 * Plugin URI:        http://wordpress.org/plugins/hello-lyrics/
 * Description:       This is a plugin to display your favorite song/porem lyrics. Hello, Lyrics. When activated you will randomly see a lyric from your inserted lyric in the upper right of your admin screen on every page.
 * Version:           1.0.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Hakik Zaman
 * Author URI:        https://github.com/hakikz
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        http://wordpress.org/plugins/hello-lyrics/
 * Text Domain:       hello-lyrics
 * Domain Path:       /languages
 */

function hls_register_settings(){
	/* Settings field for Hello Lyrics */
	add_settings_section( "hls_section_block",__( "Hello Lyrics Section"), "hls_section_title_callback", "general" );
	add_settings_field( "hls_lyric_textarea_field", __( "Lyrics" ), "hls_lyric_field_callback", "general", "hls_section_block" );
	add_settings_field( "hls_lyric_text_title", __( "Title" ), "hls_lyric_title_callback", "general", "hls_section_block" );
	add_settings_field( "hls_lyric_text_by", __( "Lyric By" ), "hls_lyric_by_callback", "general", "hls_section_block" );

	/* Registering field for Hello Lyrics */
	register_setting( "general", "hls_lyric_textarea_field" , array("type" => "string", "sanitize_callback" => "sanitize_textarea_field", "default" => NULL) );
	register_setting( "general", "hls_lyric_text_title" , array("type" => "string", "sanitize_callback" => "sanitize_text_field", "default" => NULL) );
	register_setting( "general", "hls_lyric_text_by" , array("type" => "string", "sanitize_callback" => "sanitize_text_field", "default" => NULL) );
}

function hls_section_title_callback(){
	printf("<p>%s</p>", __( "Please set the lyric credintials here", "hello-lyrics" ) );
}

function hls_lyric_field_callback(){
    $hls_lyric_textarea_field = get_option( "hls_lyric_textarea_field" );
    printf("<textarea id='hls_lyric_textarea_field' name='hls_lyric_textarea_field' rows='15' cols='20' class='large-text code'>%s</textarea>", $hls_lyric_textarea_field);
}

function hls_lyric_title_callback(){
    $hls_lyric_text_title = get_option( "hls_lyric_text_title" );
    printf("<input type='text' id='hls_lyric_text_title' name='hls_lyric_text_title' class='regular-text' value='%s' />", $hls_lyric_text_title);
}

function hls_lyric_by_callback(){
    $hls_lyric_text_by = get_option( "hls_lyric_text_by" );
    printf("<input type='text' id='hls_lyric_text_by' name='hls_lyric_text_by' class='regular-text' value='%s' />", $hls_lyric_text_by);
}

add_action( "admin_init", "hls_register_settings" );

function hls_get_the_lyric() {
	/** Getting Users Lyrics */
	$lyrics = get_option( "hls_lyric_textarea_field", __( 'Please Set The Lyrics', 'hello-lyrics' ) );
	// Here we split it into lines.
	$lyrics = explode( "\n", $lyrics );

	// And then randomly choose a line.
	return wptexturize( $lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later.
function hls_chosen_lyric_line() {
	$chosen = hls_get_the_lyric();
	$lang   = '';
	$title = get_option( "hls_lyric_text_title" );
	$by = get_option( "hls_lyric_text_by" );
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		'<p id="hls_lyrics"><span class="screen-reader-text">%s %s %s %s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Quote from' ),
		$title,
		__( 'song, by' ),
		$by,
		$lang,
		$chosen


	);
}

// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'hls_chosen_lyric_line' );

// We need some CSS to position the paragraph.
function hls_chosen_lyrics_css() {
	echo "
	<style type='text/css'>
	#hls_lyrics {
		float: right;
		padding: 5px 10px;
		margin: 0;
		font-size: 12px;
		line-height: 1.6666;
	}
	.rtl #hls_lyrics {
		float: left;
	}
	.block-editor-page #hls_lyrics {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#hls_lyrics,
		.rtl #hls_lyrics {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
}

add_action( 'admin_head', 'hls_chosen_lyrics_css' );

/*
========================================================================================
Introduce Hello Lyrics Block
========================================================================================
*/
require plugin_dir_path( __FILE__ ) . 'block/index.php' ;



