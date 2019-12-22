<?php

function atoz_codecleaner_network_admin_menu() {

	//Add Network Settings Menu Item
    add_submenu_page('settings.php', 'atoz_codecleaner Network Settings', 'atoz_codecleaner', 'manage_network_options', 'atoz_codecleaner', 'atoz_codecleaner_network_page_callback');

    //Create Site Option if Not Found
    if(get_site_option('atoz_codecleaner_network') == false) {    
        add_site_option('atoz_codecleaner_network', true);
    }
 
 	//Add Settings Section
    add_settings_section('atoz_codecleaner_network', 'Network Setup', 'atoz_codecleaner_network_callback', 'atoz_codecleaner_network');
   
   	//Add Options Fields
	add_settings_field(
		'access', 
		'<label for=\'access\'>' . __('Network Access', 'atoz_codecleaner') . '</label>' . atoz_codecleaner_tooltip('https://atozsites.com/docs/wordpress-multisite/'),
		'atoz_codecleaner_network_access_callback', 
		'atoz_codecleaner_network', 
		'atoz_codecleaner_network'
	);
 
	add_settings_field(
		'default', 
		'<label for=\'default\'>' . __('Network Default', 'atoz_codecleaner') . '</label>' . atoz_codecleaner_tooltip('https://atozsites.com/docs/wordpress-multisite/'),
		'atoz_codecleaner_network_default_callback', 
		'atoz_codecleaner_network', 
		'atoz_codecleaner_network'
	);

	//Full Uninstall
    add_settings_field(
        'clean_uninstall', 
        atoz_codecleaner_title(__('Clean Uninstall', 'atoz_codecleaner'), 'clean_uninstall') . atoz_codecleaner_tooltip('https://atozsites.com/docs/clean-uninstall/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_network', 
        'atoz_codecleaner_network', 
        array( 
            'id' => 'clean_uninstall',
            'option' => 'atoz_codecleaner_network',
            'tooltip' => __('When enabled, this will cause all atoz_codecleaner options data to be removed from your database when the plugin is uninstalled.', 'atoz_codecleaner')
        )
    );

	//Register Setting
	register_setting('atoz_codecleaner_network', 'atoz_codecleaner_network');
}
add_filter('network_admin_menu', 'atoz_codecleaner_network_admin_menu');

//atoz_codecleaner Network Section Callback
function atoz_codecleaner_network_callback() {
	echo '<p class="atoz_codecleaner-subheading">' . __('Manage network access control and setup a network default site.', 'atoz_codecleaner') . '</p>';
} 

//atoz_codecleaner Network Settings Page
function atoz_codecleaner_network_page_callback() {
	if(isset($_POST['atoz_codecleaner_apply_defaults'])) {
		check_admin_referer('atoz_codecleaner-network-apply');
		if(isset($_POST['atoz_codecleaner_network_apply_blog']) && is_numeric($_POST['atoz_codecleaner_network_apply_blog'])) {
			$blog = get_blog_details($_POST['atoz_codecleaner_network_apply_blog']);
			if($blog) {
 
				//Reset all settings to select blog
				if(is_multisite()) {
					$atoz_codecleaner_network = get_site_option('atoz_codecleaner_network');
 
					if(!empty($atoz_codecleaner_network['default'])) {

						if($blog->blog_id != $atoz_codecleaner_network['default']) {

							$option_names = array(
								'atoz_codecleaner_options',
								'atoz_codecleaner_woocommerce',
								'atoz_codecleaner_extras'
							);

							foreach($option_names as $option_name) {

								//Remove\Clear previous option
								delete_blog_option($blog->blog_id, $option_name);

								//catch new option from default blog
								$new_option = get_blog_option($atoz_codecleaner_network['default'], $option_name);


								//update selected blog with default option
								update_blog_option($blog->blog_id, $option_name, $new_option);

							} 

							//Updated Notice About Default Settings 
							echo "<div class='notice updated is-dismissible'><p>" . __('Default settings applied!', 'atoz_codecleaner') . "</p></div>";
						} 
						else {
							//Can't Apply to Network Default
							echo "<div class='notice error is-dismissible'><p>" . __('Select a site that is not already the Network Default.', 'atoz_codecleaner') . "</p></div>";
						}
					}
					else {
						//Network Default Not Set
						echo "<div class='notice error is-dismissible'><p>" . __('Network Default not set.', 'atoz_codecleaner') . "</p></div>";
					}
				} 
			}
			else {
				//Not Found Message
				echo "<div class='notice error is-dismissible'><p>" . __('Error: Blog Not Found.', 'atoz_codecleaner') . "</p></div>";
			}
		}
	}

	//Options Updated
	if(isset($_GET['updated'])) {
		echo "<div class='notice updated is-dismissible'><p>" . __('Options saved.', 'atoz_codecleaner') . "</p></div>";
	}

	//if no tab is set, default to first/network tab
	if(empty($_GET['tab'])) {
		$_GET['tab'] = 'network';
	}  

	echo "<div class='wrap atoz_codecleaner-admin'>";

		//Admin Page Title
  		echo "<h1>" . __('atoz_codecleaner Network Settings', 'atoz_codecleaner') . "</h1>";
 
  		//Tab Navigation
		echo "<h2 class='nav-tab-wrapper'>";
			echo "<a href='?page=atoz_codecleaner&tab=network' class='nav-tab " . ($_GET['tab'] == 'network' ? 'nav-tab-active' : '') . "'>" . __('Network', 'atoz_codecleaner') . "</a>";
			echo "<a href='?page=atoz_codecleaner&tab=license' class='nav-tab " . ($_GET['tab'] == 'license' ? 'nav-tab-active' : '') . "'>" . __('License', 'atoz_codecleaner') . "</a>";
			echo "<a href='?page=atoz_codecleaner&tab=support' class='nav-tab " . ($_GET['tab'] == 'support' ? 'nav-tab-active' : '') . "'>" . __('Support', 'atoz_codecleaner') . "</a>";
		echo "</h2>";

		//Tab Content
		if($_GET['tab'] == 'network') {

	  		echo "<form method='POST' action='edit.php?action=atoz_codecleaner_update_network_options' style='overflow: hidden;'>";
			    settings_fields('atoz_codecleaner_network');
			    do_settings_sections('atoz_codecleaner_network');
			    submit_button();
	  		echo "</form>";
 
	  		echo "<form method='POST'>";
	  
	  			echo "<h2>" . __('Apply Default Settings', 'atoz_codecleaner') . "</h2>";
	  			echo '<p class="atoz_codecleaner-subheading">' . __('Choose a site to apply the settings from your network default site.', 'atoz_codecleaner') . '</p>';
 
				wp_nonce_field('atoz_codecleaner-network-apply', '_wpnonce', true, true);
				echo "<p>" . __('Select a site from the dropdown and click to apply the settings from your network default (above).', 'atoz_codecleaner') . "</p>";

				echo "<select name='atoz_codecleaner_network_apply_blog'>";
					$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
					if(is_array($sites) && $sites !== array()) {
						echo "<option value=''>" . __('Select a Site', 'atoz_codecleaner') . "</option>";
						foreach($sites as $site) {
							echo "<option value='" . $site['blog_id'] . "'>" . $site['blog_id'] . ": " . $site['domain'] . $site['path'] . "</option>";
						}
					}
				echo "<select>";

				echo "<input type='submit' name='atoz_codecleaner_apply_defaults' value='" . __('Apply Default Settings', 'atoz_codecleaner') . "' class='button' />";
			echo "</form>";
		}

		//Support Tab Content
		elseif($_GET['tab'] == 'support') {
			echo "<h2>" . __('Support', 'atoz_codecleaner') . "</h2>";
			echo "<p>" . __("For plugin support and documentation, please visit <a href='https://atozsites.com/cleaner/' title='atoz_codecleaner' target='_blank'>atozsites.com</a>.", 'atoz_codecleaner') . "</p>";
		}

		//Tooltip Legend
		if($_GET['tab'] != 'support' && $_GET['tab'] != 'license') {
			echo "<div id='atoz_codecleaner-legend'>";
				echo "<div id='atoz_codecleaner-tooltip-legend'>";
					echo "<span>?</span>" . __('Click on tooltip icons to view full documentation.', 'atoz_codecleaner');
				echo "</div>";
			echo "</div>";
		}

		//Tooltip Display Script (.css/.js)
		echo "<script>
			(function ($) {
				$('.atoz_codecleaner-tooltip').hover(function(){
				    $(this).closest('tr').find('.atoz_codecleaner-tooltip-text-container').show();
				},function(){
				    $(this).closest('tr').find('.atoz_codecleaner-tooltip-text-container').hide();
				});
			}(jQuery));
		</script>";

	echo "</div>";
}

//atoz_codecleaner Network Default
function atoz_codecleaner_network_default_callback() {
	$atoz_codecleaner_network = get_site_option('atoz_codecleaner_network');
	echo "<div style='display: table; width: 100%;'>";
		echo "<div class='atoz_codecleaner-input-wrapper'>";
			echo "<select name='atoz_codecleaner_network[default]' id='default'>";
				$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
				if(is_array($sites) && $sites !== array()) {
					echo "<option value=''>" . __('None', 'atoz_codecleaner') . "</option>";
					foreach($sites as $site) {
						echo "<option value='" . $site['blog_id'] . "' " . ((!empty($atoz_codecleaner_network['default']) && $atoz_codecleaner_network['default'] == $site['blog_id']) ? "selected" : "") . ">" . $site['blog_id'] . ": " . $site['domain'] . $site['path'] . "</option>";
					} 
				}
			echo "<select>";
		echo "</div>";
		echo "<div class='atoz_codecleaner-tooltip-text-wrapper'>";
			echo "<div class='atoz_codecleaner-tooltip-text-container' style='display: none;'>";
				echo "<div style='display: table; height: 100%; width: 100%;'>";
					echo "<div style='display: table-cell; vertical-align: middle;'>";
						echo "<span class='atoz_codecleaner-tooltip-text'>" . __('Choose a subsite that you want to pull default settings from.', 'atoz_codecleaner') . "</span>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}

//Update atoz_codecleaner Network Options
function atoz_codecleaner_update_network_options() {

	//Verify Post Referring Page
  	check_admin_referer('atoz_codecleaner_network-options');
 
	//Get Registered Options
	global $new_whitelist_options;
	$options = $new_whitelist_options['atoz_codecleaner_network'];

	//Loop Through Registered Options
	foreach($options as $option) {
		if(isset($_POST[$option])) {

			//Update Site Option
			update_site_option($option, $_POST[$option]);
		}
	}

	//Redirect to Network Settings Page
	wp_redirect(add_query_arg(array('page' => 'atoz_codecleaner', 'updated' => 'true'), network_admin_url('settings.php')));

	exit;
}
add_action('network_admin_edit_atoz_codecleaner_update_network_options',  'atoz_codecleaner_update_network_options');
    
//atoz_codecleaner Network Access
function atoz_codecleaner_network_access_callback() {
	$atoz_codecleaner_network = get_site_option('atoz_codecleaner_network');
	echo "<div style='display: table; width: 100%;'>";
		echo "<div class='atoz_codecleaner-input-wrapper'>";
			echo "<select name='atoz_codecleaner_network[access]' id='access'>";
				echo "<option value=''>" . __('Site Admins (Default)', 'atoz_codecleaner') . "</option>";
				echo "<option value='super' " . ((!empty($atoz_codecleaner_network['access']) && $atoz_codecleaner_network['access'] == 'super') ? "selected" : "") . ">" . __('Super Admins Only', 'atoz_codecleaner') . "</option>";
			echo "<select>";
		echo "</div>";
		echo "<div class='atoz_codecleaner-tooltip-text-wrapper'>";
			echo "<div class='atoz_codecleaner-tooltip-text-container' style='display: none;'>";
				echo "<div style='display: table; height: 100%; width: 100%;'>";
					echo "<div style='display: table-cell; vertical-align: middle;'>";
						echo "<span class='atoz_codecleaner-tooltip-text'>" . __('Choose who has access to manage atoz_codecleaner plugin settings.', 'atoz_codecleaner') . "</span>";  
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
} 

function atoz_codecleaner_edd_check_network_license() {

	global $wp_version;

	$license = trim(get_site_option('atoz_codecleaner_edd_license_key'));

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode(atoz_codecleaner_ITEM_NAME),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post(atoz_codecleaner_STORE_URL, array('timeout' => 15, 'sslverify' => true, 'body' => $api_params));

	if(is_wp_error($response)) {
		return false;
	}

	$license_data = json_decode(wp_remote_retrieve_body($response));

	if($license_data->license == 'valid') {
		update_site_option('atoz_codecleaner_edd_license_status', "valid");
	}
	else {
		update_site_option('atoz_codecleaner_edd_license_status', "invalid");
	}
	
	return($license_data);
}

function atoz_codecleaner_edd_deactivate_network_license() {

	// retrieve the license from the database
	$license = trim(get_site_option('atoz_codecleaner_edd_license_key'));

	// data to send in our API request
	$api_params = array(
		'edd_action'=> 'deactivate_license',
		'license' 	=> $license,
		'item_name' => urlencode(atoz_codecleaner_ITEM_NAME), // the name of our product in EDD
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post(atoz_codecleaner_STORE_URL, array('timeout' => 15, 'sslverify' => true, 'body' => $api_params));

	// make sure the response came back okay
	if(is_wp_error($response)) {
		return false;
	}

	// decode the license data
	$license_data = json_decode(wp_remote_retrieve_body($response));

	// $license_data->license will be either "deactivated" or "failed"
	if($license_data->license == 'deactivated') {
		delete_site_option('atoz_codecleaner_edd_license_status');
	}
}

function atoz_codecleaner_edd_activate_network_license() {

	//retrieve the license from the database
	$license = trim(get_site_option('atoz_codecleaner_edd_license_key'));

	//data to send in our API request
	$api_params = array(
		'edd_action'=> 'activate_license',
		'license' 	=> $license,
		'item_name' => urlencode(atoz_codecleaner_ITEM_NAME), // name of product in EDD
		'url'       => home_url()
	);

	//Call the custom API.
	$response = wp_remote_post(atoz_codecleaner_STORE_URL, array('timeout' => 15, 'sslverify' => true, 'body' => $api_params));

	//make sure the response came back okay
	if(is_wp_error($response)) {
		return false;
	}

	//decode the license data
	$license_data = json_decode(wp_remote_retrieve_body($response));

	//$license_data->license will be either "valid" or "invalid"
	update_site_option('atoz_codecleaner_edd_license_status', $license_data->license);
}

