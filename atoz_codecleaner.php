<?php
/*
Plugin Name: Atoz Sites Code Cleaner
Plugin URI: https://atozsites.com/cleaner/
Description: The Code Cleaner plugin cleans and optimizes WordPress code for improved website performance and faster page load times.
Version: 1.0
Author: Atoz Sites
Author URI: https://atozsites.com/cleaner/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: atozsites
Domain Path: /languages
*/

/*****************************************************************************************
* EDD License
*****************************************************************************************/
define('atoz_codecleaner_STORE_URL', 'https://atozsites.com/cleaner/');
define('atoz_codecleaner_ITEM_NAME', 'atoz_codecleaner');
define('atoz_codecleaner_VERSION', '2.0.1');

//load translations
function atoz_codecleaner_load_textdomain() {
	load_plugin_textdomain('atoz_codecleaner', false, dirname(plugin_basename( __FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'atoz_codecleaner_load_textdomain');

// admin menus loading
if(is_admin()) {
	add_action('admin_menu', 'atoz_codecleaner_menu', 9);
}

//admin menu
function atoz_codecleaner_menu() {
	if(atoz_codecleaner_network_access()) {
		$pages = add_options_page('Code Cleaner', 'Code Cleaner', 'manage_options', 'atoz_codecleaner', 'atoz_codecleaner_admin');
	}
}

//admin page settings
function atoz_codecleaner_admin() {
	include plugin_dir_path(__FILE__) . '/inc/admin.php';
}

//load EDD updater class
if(!class_exists('EDD_SL_Plugin_Updater')) {
	include(dirname( __FILE__ ) . '/inc/EDD_SL_Plugin_Updater.php');
}

//load plugin scripts (.css/.js)
function atoz_codecleaner_admin_scripts() {
	if(atoz_codecleaner_network_access()) {
		wp_register_style('atoz_codecleaner-styles', plugins_url('/css/style.css', __FILE__), array(), atoz_codecleaner_VERSION);
		wp_enqueue_style('atoz_codecleaner-styles');
	}
}
add_action('admin_enqueue_scripts', 'atoz_codecleaner_admin_scripts');

//verify access and identify problems
function atoz_codecleaner_network_access() {
	if(is_multisite()) {
		$atoz_codecleaner_network = get_site_option('atoz_codecleaner_network');
		if((!empty($atoz_codecleaner_network['access']) && $atoz_codecleaner_network['access'] == 'super') && !is_super_admin()) {
			return false;
		}
	}
	return true;
}

//Optimization Notice
function atoz_codecleaner_guide_notice() {
    if(get_current_screen()->base == 'settings_page_atoz_codecleaner') {
        echo "<div class='notice notice-info'>";
        	echo "<p>";
        		_e("Have a look at our <a href='https://atozsites.com/speed-up-wordpress/' title='WordPress Optimization Guide' target='_blank'>WordPress Optimization Guide</a> for even more ways to make WordPress faster!", 'atoz_codecleaner');
        	echo "</p>";
        echo "</div>";
    }
}
add_action('admin_notices', 'atoz_codecleaner_guide_notice');

//uninstall\remove plugin + delete options
function atoz_codecleaner_uninstall() {

	//plugin options
	$atoz_codecleaner_options = array(
		'atoz_codecleaner_options',
		'atoz_codecleaner_woocommerce',
		'atoz_codecleaner_ga',
		'atoz_codecleaner_extras',
		'atoz_codecleaner_deep_cleaning',
		'atoz_codecleaner_deep_cleaning_settings',
		'atoz_codecleaner_edd_license_key',
		'atoz_codecleaner_edd_license_status'
	);

	if(is_multisite()) {
		$atoz_codecleaner_network = get_site_option('atoz_codecleaner_network');
		if(!empty($atoz_codecleaner_network['clean_uninstall']) && $atoz_codecleaner_network['clean_uninstall'] == 1) {
			delete_site_option('atoz_codecleaner_network');

			$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
			if(is_array($sites) && $sites !== array()) {
				foreach($sites as $site) {
					foreach($atoz_codecleaner_options as $option) {
						delete_blog_option($site['blog_id'], $option);
					}
				}
			}
		}
	}
	else {
		$atoz_codecleaner_extras = get_option('atoz_codecleaner_extras');
		if(!empty($atoz_codecleaner_extras['clean_uninstall']) && $atoz_codecleaner_extras['clean_uninstall'] == 1) {
			foreach($atoz_codecleaner_options as $option) {
				delete_option($option);
			}
		}
	}
}
register_uninstall_hook(__FILE__, 'atoz_codecleaner_uninstall');

//files include in plugin
include plugin_dir_path(__FILE__) . '/inc/atoz_codecleaner_settings.php';
include plugin_dir_path(__FILE__) . '/inc/atoz_codecleaner_functions.php';
include plugin_dir_path(__FILE__) . '/inc/atoz_codecleaner_network.php';