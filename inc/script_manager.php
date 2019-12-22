<?php
//Security Check
if(!current_user_can('manage_options') || is_admin() || !isset($_GET['atoz_codecleaner']) || !atoz_codecleaner_network_access()) {
	return;
}

//Set Variables
global $atoz_codecleaner_extras;
global $wp;
global $wp_scripts;
global $wp_styles;
global $atoz_codecleaner_options;
global $currentID;
$currentID = get_queried_object_id();

//Load Script Options
global $atoz_codecleaner_script_manager_options;
$atoz_codecleaner_script_manager_options = get_option('atoz_codecleaner_script_manager');

//Load Styles
require_once('script_manager_css.php');

//Settings Process Form
if(isset($_POST['atoz_codecleaner_script_manager_settings'])) {

	//Settings Validate
	if(!isset($_POST['atoz_codecleaner_script_manager_settings_nonce']) || !wp_verify_nonce($_POST['atoz_codecleaner_script_manager_settings_nonce'], 'atoz_codecleaner_script_manager_save_settings')) {
		print 'Sorry, your nonce did not verify.';
	    exit;
	} else {

		//Update Settings
		update_option('atoz_codecleaner_script_manager_settings', $_POST['atoz_codecleaner_script_manager_settings']);
	}
}

//Load Script Settings
global $atoz_codecleaner_script_manager_settings;
$atoz_codecleaner_script_manager_settings = get_option('atoz_codecleaner_script_manager_settings');

//Setup Filters Array
global $atoz_codecleaner_filters;
$atoz_codecleaner_filters = array(
	"js" => array (
		"title" => "JS",
		"scripts" => $wp_scripts
	),
	"css" => array(
		"title" => "CSS",
		"scripts" => $wp_styles
	)
);

//Build Array of Existing Disables
global $atoz_codecleaner_disables;
$atoz_codecleaner_disables = array();
if(!empty($atoz_codecleaner_options['disable_google_maps']) && $atoz_codecleaner_options['disable_google_maps'] == "1") {
	$atoz_codecleaner_disables[] = 'maps.google.com';
	$atoz_codecleaner_disables[] = 'maps.googleapis.com';
	$atoz_codecleaner_disables[] = 'maps.gstatic.com';
}

//Wrapper Deep Cleaning 
echo "<div id='atoz_codecleaner-script-manager-wrapper' " . (isset($_GET['atoz_codecleaner']) ? "style='display: block;'" : "") . ">";

	echo "<div id='atoz_codecleaner-script-manager'>";

		$master_array = atoz_codecleaner_script_manager_load_master_array();

		//Header
		echo "<div id='atoz_codecleaner-script-manager-header'>";

			//Logo
			echo "<img src='" . plugins_url('img/logo.svg', dirname(__FILE__)) . "' title='atoz_codecleaner' id='atoz_codecleaner-logo' />";
		
			//Main Navigation Form
			echo "<form method='POST'>";
				echo "<div id='atoz_codecleaner-script-manager-tabs'>";
					echo "<button name='tab' value='' class='"; if(empty($_POST['tab'])){echo "active";} echo "' title='" . __('Deep Cleaning', 'atoz_codecleaner') . "'>" . __('Deep Cleaning', 'atoz_codecleaner') . "</button>";
					echo "<button name='tab' value='global' class='"; if(!empty($_POST['tab']) && $_POST['tab'] == "global"){echo "active";} echo "' title='" . __('Global View', 'atoz_codecleaner') . "'>" . __('Global View', 'atoz_codecleaner') . "</button>";
					echo "<button name='tab' value='settings' class='"; if(!empty($_POST['tab']) && $_POST['tab'] == "settings"){echo "active";} echo "' title='" . __('Settings', 'atoz_codecleaner') . "'>" . __('Settings', 'atoz_codecleaner') . "</button>";
				echo "</div>";
			echo "</form>";

		echo "</div>";

		//Disclaimer
		if(empty($atoz_codecleaner_script_manager_settings['hide_disclaimer']) || $atoz_codecleaner_script_manager_settings['hide_disclaimer'] != "1") {
			echo "<div id='atoz_codecleaner-script-manager-disclaimer'>";
				echo "<p>";
					_e("Below you can disable/enable CSS and JS files on a per page/post basis, as well as by custom post types. We recommend testing this locally or on a staging site first, as you could break the appearance of your live site. If you aren't sure about a certain script, you can try clicking on it, as a lot of authors will mention their plugin or theme in the header of the source code.", 'atoz_codecleaner');
				echo "</p>";
				echo "<p>";
					_e("If for some reason you run into trouble, you can always enable everything again to reset the settings. Make sure to check out the <a href='https://atozsites.com/docs/' target='_blank' title='atoz_codecleaner Knowledge Base'>atoz_codecleaner knowledge base</a> for more information.", 'atoz_codecleaner');
				echo "</p>";
			echo "</div>";
		}

		echo "<div id='atoz_codecleaner-script-manager-container'>";

			//Default/Main Tab
			if(empty($_POST['tab'])) {

				echo "<div class='atoz_codecleaner-script-manager-title-bar'>";
					echo "<h1>" . __('Deep Cleaning', 'atoz_codecleaner') . "</h1>";
					echo "<p>" . __('Manage scripts loading on the current page.', 'atoz_codecleaner') . "</p>";
				echo "</div>";

				//Form
				echo "<form method='POST'>";

					foreach($master_array as $category => $groups) {
						if(!empty($groups)) {
							echo "<h3>" . $category . "</h3>";
							if($category != "misc") {
								echo "<div style='background: #ffffff; padding: 10px;'>";
								foreach($groups as $group => $details) {
									if(!empty($details['assets'])) {
										echo "<div class='atoz_codecleaner-script-manager-group'>";
											echo "<h4>" . $details['name'];

												//Status
												echo "<div class='atoz_codecleaner-script-manager-group-status' style='float: right;'>";
												    atoz_codecleaner_script_manager_print_status($category, $group);
												echo "</div>";

											echo "</h4>";

											atoz_codecleaner_script_manager_print_section($category, $group, $details['assets']);
										echo "</div>";
									}
								}
								echo "</div>";
							}
							else {
								if(!empty($groups)) {
									atoz_codecleaner_script_manager_print_section($category, $category, $groups);
								}
							}
						}
					}

					//Save Button
					echo "<input type='submit' name='atoz_codecleaner_script_manager' value='" . __('Save', 'atoz_codecleaner') . "' />";

				echo "</form>";

			}
			//Global View Tab
			elseif(!empty($_POST['tab']) && $_POST['tab'] == "global") {

				echo "<div class='atoz_codecleaner-script-manager-title-bar'>";
					echo "<h1>" . __('Global View', 'atoz_codecleaner') . "</h1>";
					echo "<p>" . __('This is a visual representation of the Deep Cleaning configuration across your entire site.', 'atoz_codecleaner') . "</p>";
				echo "</div>";
				
				if(!empty($atoz_codecleaner_script_manager_options)) {
					foreach($atoz_codecleaner_script_manager_options as $category => $types) {
						echo "<h3>" . $category . "</h3>";
						if(!empty($types)) {
							echo "<table>";
								echo "<thead>";
									echo "<tr>";
										echo "<th>" . __('Type', 'atoz_codecleaner') . "</th>";
										echo "<th>" . __('Script', 'atoz_codecleaner') . "</th>";
										echo "<th>" . __('Setting', 'atoz_codecleaner') . "</th>";
									echo "</tr>";
								echo "</thead>";
								echo "<tbody>";
									foreach($types as $type => $scripts) {
										if(!empty($scripts)) {
											foreach($scripts as $script => $details) {
												if(!empty($details)) {
													foreach($details as $detail => $values) {
														echo "<tr>";
															echo "<td><span style='font-weight: bold;'>" . $type . "</span></td>";
															echo "<td><span style='font-weight: bold;'>" . $script . "</span></td>";
															echo "<td>";
																echo "<span style='font-weight: bold;'>" . $detail . "</span>";
																if($detail == "current" || $detail == "post_types") {
																	if(!empty($values)) {
																		echo " (";
																		$valueString = "";
																		foreach($values as $key => $value) {
																			if($detail == "current") {
																				$valueString.= "<a href='" . get_page_link($value) . "' target='_blank'>" . $value . "</a>, ";
																			}
																			elseif($detail == "post_types") {
																				$valueString.= $value . ", ";
																			}
																		}
																		echo rtrim($valueString, ", ");
																		echo ")";
																	}
																}
															echo "</td>";
														echo "</tr>";
													}
												}
											}
										}
									}
								echo "</tbody>";
							echo "</table>";
						}
					}
				}
			}
			//Settings Tab
			elseif(!empty($_POST['tab']) && $_POST['tab'] == "settings") {

				echo "<div class='atoz_codecleaner-script-manager-title-bar'>";
					echo "<h1>" . __('Settings', 'atoz_codecleaner') . "</h1>";
					echo "<p>" . __('View and manage all of your Deep Cleaning settings.', 'atoz_codecleaner') . "</p>";
				echo "</div>";

				//Form
				echo "<form method='POST' id='script-manager-settings'>";
					echo "<input type='hidden' name='tab' value='settings' />";

					echo "<div class='atoz_codecleaner-script-manager-section'>";

						echo "<table>";
							echo "<tbody>";
								echo "<tr>";
									echo "<th>" . atoz_codecleaner_title(__('Hide Disclaimer', 'atoz_codecleaner'), 'hide_disclaimer') . "</th>";
									echo "<td>";
										echo "<input type='hidden' name='atoz_codecleaner_script_manager_settings[hide_disclaimer]' value='0' />";
										$args = array(
								            'id' => 'hide_disclaimer',
								            'option' => 'atoz_codecleaner_script_manager_settings',
								            'tooltip' => __('Hide the disclaimer message box across all Deep Cleaning views.', 'atoz_codecleaner')
								        );
										atoz_codecleaner_print_input($args);
									echo "</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>" . atoz_codecleaner_title(__('Display Archives', 'atoz_codecleaner'), 'separate_archives') . "</th>";
									echo "<td>";
									$args = array(
							            'id' => 'separate_archives',
							            'option' => 'atoz_codecleaner_script_manager_settings',
							            'tooltip' => __('Add WordPress archives to your Deep Cleaning selection options. Archive posts will no longer be grouped with their post type.', 'atoz_codecleaner')
							        );
									atoz_codecleaner_print_input($args);
									echo "</td>";
								echo "</tr>";
							echo "</tbody>";
						echo "</table>";

						//Nonce
						wp_nonce_field('atoz_codecleaner_script_manager_save_settings', 'atoz_codecleaner_script_manager_settings_nonce');

						//Save Button
						echo "<input type='submit' name='atoz_codecleaner_script_manager_settings_submit' value='" . __('Save', 'atoz_codecleaner') . "' />";

						
					echo "<div>";
				echo "</form>";
			}
		echo "</div>";
	echo "</div>";
echo "</div>";