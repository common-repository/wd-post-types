<?php
/**
 * Plugin Name: WD Post Types
 * Description: “WD Post Types” is s free WordPress plugin that can be used: to create custom post types;  to create custom taxonomies (eg: categories, tags, etc.); to create custom meta fields and custom groups to manage meta
 * Plugin URI:  
 * Version:     1.1
 * Author: <a href="https://business.fiverr.com/freelancers/webdnix">WDSeller</a>
 * Tags: custom post, post type, custom taxonomy, post taxonomy, add taxonomy, custom meta, post meta, add post meta, custom post type
 * Requires at least: 5.0
 * Tested up to: 6.0
 * Author URI: https://wdseller.com
 * Text Domain: wdpt-types
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'WDTP_POST_TYPE' ) ) define('WDTP_POST_TYPE', 'wdpt-types');
if ( ! defined( 'WDTP_DIR' ) ) define('WDTP_DIR', plugin_dir_path(__FILE__));
// initialize the plugin 
final class WDSeller_Post_Types {

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}
	//include plugins all required files
	public function init() {
		require_once( WDTP_DIR.'inc/functions.php' );
		require_once( WDTP_DIR.'inc/classes/wd-post.php' );
		require_once( WDTP_DIR.'inc/classes/wd-group.php' );
		require_once( WDTP_DIR.'inc/classes/wd-meta.php' );
		require_once( WDTP_DIR.'inc/classes/wd-taxonomy.php' );
		require_once( WDTP_DIR.'inc/classes/wd-types.php' );
		require_once( WDTP_DIR.'inc/classes/wd-shortcode.php' );
		wdpt_inc_pro();
	}
}
new WDSeller_Post_Types();
