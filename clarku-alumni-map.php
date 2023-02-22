<?php
/*
Plugin Name: ClarkU Alumni Map
Plugin URI: https://www.clarku.edu
Description: Display Alumni Map
Version: 1.0
Author: ClarkU Marcom
Author URI: 
*/

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

define( 'ALUMNI_MAP_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALUMNI_MAP_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load us up the js and css.
 * only when the map is displayed
 */
function clarku_alumni_map_enqueues() {

		// $version = date( 'dHis', strtotime('now') );
		$version = '1.0';

		wp_enqueue_style( 'clarku_alumni_map_leaflet_css', 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.css', [], null );
		wp_enqueue_script( 'clarku_alumni_map_leaflet_js', 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.js', [], null, FALSE );

		wp_enqueue_style( 'clarku_alumni_map_map_css', ALUMNI_MAP_URL . 'css/map.css', [], $version );

		wp_enqueue_script( 'clarku_alumni_countries_js', ALUMNI_MAP_URL . 'js/worldLow.js', [], $version, TRUE );
		wp_enqueue_script( 'clarku_alumni_counts_js', ALUMNI_MAP_URL . 'js/alumni.js', [], $version, TRUE );
		wp_enqueue_script( 'clarku_alumni_map_map_js', ALUMNI_MAP_URL . 'js/map.js', ['clarku_alumni_map_leaflet_js'], $version, TRUE );

}
//add_action( 'wp_enqueue_scripts', 'campus_map_enqueue' );

/**
 * Update the css <link> attributes for leaflet.
 */
function clarku_alumni_map_css_attributes( $html, $handle ) {
	if ( $handle === 'clarku_alumni_map_leaflet_css') {
		return str_replace( "media='all'", "media='all' integrity='sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=' crossorigin=''", $html );
	}
	return $html;
}
add_filter( 'style_loader_tag', 'clarku_alumni_map_css_attributes', 10, 2 );

/**
 * Update the js <script> attributes for leaflet.
 */
function clarku_alumni_map_js_attributes( $tag, $handle ) {
	if ( $handle === 'clarku_alumni_map_leaflet_js') {
		return str_replace( "src", "integrity='sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=' crossorigin='' src", $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'clarku_alumni_map_js_attributes', 10, 2 );



/**
 * Shortcode responder.
 */
function clarku_alumni_map_shortcode($attributes, $content, $shortcode) {
		clarku_alumni_map_enqueues();

    // normalize attribute keys, lowercase
    $attributes = array_change_key_case((array)$attributes, CASE_LOWER);
 
		// override default attributes with user attributes
		$a = shortcode_atts(array(
			'id' => 'alumni-map',
			'before' => '',
			'after' => ''
		), $attributes, $shortcode);
					
		$af = '<dl id="armed-forces">
						<dt>Armed Forces Africa</dt>
						<dd>9 Clark Alumni</dd>
						<dt>Armed Forces Americas (except Canada)</dt>
						<dd>1 Clark Alumnus</dd>
						<dt>Armed Forces Pacific</dt>
						<dd>3 Clark Alumni</dd>
					</dl>
				';

	return $a['before'] . '<div id="' . $a['id'] . '" class="alumni-map"></div>' . $a['after'] . $af;	

	
}
add_shortcode( 'clarku-alumni-map', 'clarku_alumni_map_shortcode' );