<?php
//options default values
function atoz_codecleaner_default_options() {	
		$defaults = array(
		'disable_post_revisions' => "0",
		'disable_emojis' => "0",
		'disable_embeds' => "0",
		'remove_query_strings' => "0",
        //'query_string_parameters' => "",
		'disable_xmlrpc' => "0",
		'remove_jquery_migrate' => "0",
		'hide_wp_version' => "0",
		'remove_wlwmanifest_link' => "0",
		'remove_rsd_link' => "0",
		'remove_shortlink' => "0",
		'disable_rss_feeds' => "0",
		'remove_feed_links' => "0",
		'disable_self_pingbacks' => "0",
		'remove_rest_api_links' => "0",
        'disable_dashicons' => "0",
        'disable_google_maps' => "0",
        'disable_password_strength_meter' => "0",
		'disable_heartbeat' => "",
		'heartbeat_frequency' => "",
		'limit_post_revisions' => "",
		'autosave_interval' => "",
        'login_url' => ""
	);
    atoz_codecleaner_network_defaults($defaults, 'atoz_codecleaner_options');
	return apply_filters('atoz_codecleaner_default_options', $defaults);
}

//woocommerce default values
function atoz_codecleaner_default_woocommerce() {
    $defaults = array(
        'disable_woocommerce_scripts' => "0",
        'disable_woocommerce_cart_fragmentation' => "0",
        'disable_woocommerce_status' => "0",
        'disable_woocommerce_widgets' => "0"
    );
    atoz_codecleaner_network_defaults($defaults, 'atoz_codecleaner_woocommerce');
    return apply_filters('atoz_codecleaner_default_woocommerce', $defaults);
}

//extras default values
function atoz_codecleaner_default_extras() {
    $defaults = array(
        'deep_cleaning' => "0",
        'accessibility_mode' => "0"
    );
    atoz_codecleaner_network_defaults($defaults, 'atoz_codecleaner_extras');
    return apply_filters( 'atoz_codecleaner_default_extras', $defaults );
}

function atoz_codecleaner_network_defaults(&$defaults, $option) {
    if(is_multisite()) {
        $atoz_codecleaner_network = get_site_option('atoz_codecleaner_network');
        if(!empty($atoz_codecleaner_network['default'])) {
            $networkDefaultOptions = get_blog_option($atoz_codecleaner_network['default'], $option);
            if(!empty($networkDefaultOptions)) {
                foreach($networkDefaultOptions as $key => $val) {
                    $defaults[$key] = $val;
                }
            }
        }
    }
}

//register settings + options
function atoz_codecleaner_settings() {
	if(get_option('atoz_codecleaner_options') == false) {	
		add_option('atoz_codecleaner_options', apply_filters('atoz_codecleaner_default_options', atoz_codecleaner_default_options()));
	}

    //Autosave Interval
    add_settings_field(
    	'autosave_interval', 
    	'<label for=\'autosave_interval\'>' . __('Autosave Interval', 'atoz_codecleaner') . '</label>' . atoz_codecleaner_tooltip('https://atozsites.com/docs/change-autosave-interval-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'autosave_interval',
    		'input' => 'select',
    		'options' => array(
    			''    => __('1 Minute', 'atoz_codecleaner') . ' (' . __('Default', 'atoz_codecleaner') . ')',
                '120' => sprintf(__('%s Minutes', 'atoz_codecleaner'), '2'),
                '180' => sprintf(__('%s Minutes', 'atoz_codecleaner'), '3'),
                '240' => sprintf(__('%s Minutes', 'atoz_codecleaner'), '4'),
                '300' => sprintf(__('%s Minutes', 'atoz_codecleaner'), '5')
    		),
    		'tooltip' => __('Controls how often WordPress will auto save posts and pages while editing.', 'atoz_codecleaner')
    	)
    );

    //Disable Dashicons
    add_settings_field(
        'disable_dashicons', 
        atoz_codecleaner_title(__('Disable Dashicons', 'atoz_codecleaner'), 'disable_dashicons') . atoz_codecleaner_tooltip('https://atozsites.com/docs/remove-dashicons-wordpress/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_options', 
        'atoz_codecleaner_options', 
        array(
            'id' => 'disable_dashicons',
            'tooltip' => __('Disables dashicons on the front end when not logged in.', 'atoz_codecleaner')
        )
    );

    //Disable Embeds
    add_settings_field(
    	'disable_embeds', 
    	atoz_codecleaner_title(__('Disable Embeds', 'atoz_codecleaner'), 'disable_embeds') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-embeds-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'disable_embeds',
    		'tooltip' => __('Removes WordPress Embed JavaScript file (wp-embed.min.js).', 'atoz_codecleaner')   		
    	)
    );
		
    //Disable Emojis
    add_settings_field(
    	'disable_emojis', 
    	atoz_codecleaner_title(__('Disable Emojis', 'atoz_codecleaner'), 'disable_emojis') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-emojis-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
            'id' => 'disable_emojis',
            'tooltip' => __('Removes WordPress Emojis JavaScript file (wp-emoji-release.min.js).', 'atoz_codecleaner')
        )
    );

    //Disable Heartbeat
    add_settings_field(
    	'disable_heartbeat', 
    	'<label for=\'disable_heartbeat\'>' . __('Disable Heartbeat', 'atoz_codecleaner') . '</label>' . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-wordpress-heartbeat-api/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'disable_heartbeat',
    		'input' => 'select',
    		'options' => array(
    			''                   => __('Default', 'atoz_codecleaner'),
    			'disable_everywhere' => __('Disable Everywhere', 'atoz_codecleaner'),
    			'allow_posts'        => __('Only Allow When Editing Posts/Pages', 'atoz_codecleaner')
    		),
    		'tooltip' => __('Disable WordPress Heartbeat everywhere or in certain areas (used for auto saving and revision tracking).', 'atoz_codecleaner')
    	)
    );
	
    //Heartbeat Frequency
    add_settings_field(
    	'heartbeat_frequency', 
    	'<label for=\'heartbeat_frequency\'>' . __('Heartbeat Frequency', 'atoz_codecleaner') . '</label>' . atoz_codecleaner_tooltip('https://atozsites.com/docs/change-heartbeat-frequency-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'heartbeat_frequency',
    		'input' => 'select',
    		'options' => array(
    			''   => sprintf(__('%s Seconds', 'atoz_codecleaner'), '15') . ' (' . __('Default', 'atoz_codecleaner') . ')',
                '30' => sprintf(__('%s Seconds', 'atoz_codecleaner'), '30'),
                '45' => sprintf(__('%s Seconds', 'atoz_codecleaner'), '45'),
                '60' => sprintf(__('%s Seconds', 'atoz_codecleaner'), '60')
    		),
    		'tooltip' => __('Controls how often the WordPress Heartbeat API is allowed to run.', 'atoz_codecleaner')
    	)
    );
	
	//Disable Post Revisions
    add_settings_field(
    	'disable_post_revisions', 
    	atoz_codecleaner_title(__('Disable Post Revisions', 'atoz_codecleaner'), 'disable_post_revisions') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-limit-post-revisions-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
            'id' => 'disable_post_revisions',
            'tooltip' => __('Limits the maximum amount of revisions that are allowed for posts and pages.', 'atoz_codecleaner')
        )
    );
	
    //Limit Post Revisions
	add_settings_field(
    	'limit_post_revisions', 
    	'<div id="label_limit_post_revisions"><label for=\'limit_post_revisions\'>' . __('Limit Post Revisions', 'atoz_codecleaner') . '</label>' . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-limit-post-revisions-wordpress/').'</div>', 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'limit_post_revisions',
    		'input' => 'select',
    		'options' => array(
    			''      => __('Default', 'atoz_codecleaner'),
    			'false' => __('Disable Post Revisions', 'atoz_codecleaner'),
    			'1'     => '1',
    			'2'     => '2',
    			'3'     => '3',
    			'4'     => '4',
    			'5'     => '5',
    			'10'    => '10',
    			'15'    => '15',
    			'20'    => '20',
    			'25'    => '25',
    			'30'    => '30'
    		),
    		'tooltip' => __('Limits the maximum amount of revisions that are allowed for posts and pages.', 'atoz_codecleaner')
    	)
    );
	
    //Disable RSS Feeds
    add_settings_field(
    	'disable_rss_feeds', 
    	atoz_codecleaner_title(__('Disable RSS Feeds', 'atoz_codecleaner'), 'disable_rss_feeds') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-rss-feeds-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'disable_rss_feeds',
    		'tooltip' => __('Disable WordPress generated RSS feeds and 301 redirect URL to parent.', 'atoz_codecleaner')
    	)
    );

    //Disable Self Pingbacks
    add_settings_field(
    	'disable_self_pingbacks', 
    	atoz_codecleaner_title(__('Disable Self Pingbacks', 'atoz_codecleaner'), 'disable_self_pingbacks') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-self-pingbacks-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'disable_self_pingbacks',
    		'tooltip' => __('Disable Self Pingbacks (generated when linking to an article on your own blog).', 'atoz_codecleaner')
    	)
    );

	//Disable XML-RPC
    add_settings_field(
    	'disable_xmlrpc', 
    	atoz_codecleaner_title(__('Disable XML-RPC', 'atoz_codecleaner'), 'disable_xmlrpc') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-xml-rpc-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'disable_xmlrpc',
    		'tooltip' => __('Disables WordPress XML-RPC functionality.', 'atoz_codecleaner')
    	)
    );

	//Remove jQuery Migrate
    add_settings_field(
    	'remove_jquery_migrate', 
    	atoz_codecleaner_title(__('Remove jQuery Migrate', 'atoz_codecleaner'), 'remove_jquery_migrate') . atoz_codecleaner_tooltip('https://atozsites.com/docs/remove-jquery-migrate-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'remove_jquery_migrate',
    		'tooltip' => __('Removes jQuery Migrate JavaScript file (jquery-migrate.min.js).', 'atoz_codecleaner')
    	)
    );

    //Remove Password Strength Meter
    add_settings_field(
        'disable_password_strength_meter', 
        atoz_codecleaner_title(__('Remove Password Strength Meter', 'atoz_codecleaner'), 'disable_password_strength_meter') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-password-meter-strength/'),
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_options', 
        'atoz_codecleaner_options', 
        array(
            'id' => 'disable_password_strength_meter',
            'tooltip' => __('Removes WordPress and WooCommerce Password Strength Meter scripts from non essential pages.', 'atoz_codecleaner')
        )
    );

    //Remove Query Strings
    add_settings_field(
    	'remove_query_strings', 
    	atoz_codecleaner_title(__('Remove Query Strings', 'atoz_codecleaner'), 'remove_query_strings') . atoz_codecleaner_tooltip('https://atozsites.com/docs/remove-query-strings-from-static-resources/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'remove_query_strings',
    		'tooltip' => __('Remove query strings from static resources (CSS, JS).', 'atoz_codecleaner')
    	)
    );
	
    //Remove REST API Links
    add_settings_field(
    	'remove_rest_api_links', 
    	atoz_codecleaner_title(__('Remove REST API Links', 'atoz_codecleaner'), 'remove_rest_api_links') . atoz_codecleaner_tooltip('https://atozsites.com/docs/remove-wordpress-rest-api-links/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'remove_rest_api_links',
    		'tooltip' => __('Removes REST API link tag from the front end and the REST API header link from page requests.', 'atoz_codecleaner')
    	)
    );

    //Remove RSD Link
    add_settings_field(
    	'remove_rsd_link', 
    	atoz_codecleaner_title(__('Remove RSD Link', 'atoz_codecleaner'), 'remove_rsd_link') . atoz_codecleaner_tooltip('https://atozsites.com/docs/remove-rsd-link-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'remove_rsd_link',
    		'tooltip' => __('Remove RSD (Real Simple Discovery) link tag.', 'atoz_codecleaner')
    	)
    );

    //Remove RSS Feed Links
    add_settings_field(
    	'remove_feed_links', 
    	atoz_codecleaner_title(__('Remove RSS Feed Links', 'atoz_codecleaner'), 'remove_feed_links') . atoz_codecleaner_tooltip('https://atozsites.com/docs/remove-rss-feed-links-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'remove_feed_links',
    		'tooltip' => __('Disable WordPress generated RSS feed link tags.', 'atoz_codecleaner')
    	)
    );

    //Remove Shortlink
    add_settings_field(
    	'remove_shortlink', 
    	atoz_codecleaner_title(__('Remove Shortlink', 'atoz_codecleaner'), 'remove_shortlink') . atoz_codecleaner_tooltip('https://atozsites.com/docs/remove-shortlink-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'remove_shortlink',
    		'tooltip' => __('Remove Shortlink link tag.', 'atoz_codecleaner')
    	)
    );

    //Remove wlmanifest Link
    add_settings_field(
    	'remove_wlwmanifest_link', 
    	atoz_codecleaner_title(__('Remove wlwmanifest Link', 'atoz_codecleaner'), 'remove_wlwmanifest_link') . atoz_codecleaner_tooltip('https://atozsites.com/docs/remove-wlwmanifest-link-wordpress/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options',
        array(
        	'id' => 'remove_wlwmanifest_link',
        	'tooltip' => __('Remove wlwmanifest (Windows Live Writer) link tag.', 'atoz_codecleaner')
        )
    );

    //Options Primary Section
    add_settings_section('atoz_codecleaner_options', __('Default', 'atoz_codecleaner'), 'atoz_codecleaner_options_callback', 'atoz_codecleaner_options');

    //Query String Parameters
    /*add_settings_field(
        'query_string_parameters', 
        atoz_codecleaner_title(__('Additional Parameters', 'atoz_codecleaner'), 'query_string_parameters') . atoz_codecleaner_tooltip(''), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_options', 
        'atoz_codecleaner_options', 
        array(
            'id' => 'query_string_parameters',
            'input' => 'text',
            'placeholder' => 'v,id',
            'tooltip' => __('', 'atoz_codecleaner')
        )
    );*/

    //Hide WP Version
    add_settings_field(
    	'hide_wp_version', 
    	atoz_codecleaner_title(__('Remove WP Version Meta Tag', 'atoz_codecleaner'), 'hide_wp_version') . atoz_codecleaner_tooltip('https://atozsites.com/docs/remove-wordpress-version-number/'), 
    	'atoz_codecleaner_print_input', 
    	'atoz_codecleaner_options', 
    	'atoz_codecleaner_options', 
    	array(
    		'id' => 'hide_wp_version',
    		'tooltip' => __('Removes WordPress version meta tag.', 'atoz_codecleaner')
    	)
    );

    //Disable Google Maps
/*    add_settings_field(
        'disable_google_maps', 
        atoz_codecleaner_title(__('Disable Google Maps', 'atoz_codecleaner'), 'disable_google_maps') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-google-maps-api-wordpress/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_options', 
        'atoz_codecleaner_options', 
        array(
            'id' => 'disable_google_maps',
            'tooltip' => __('Removes any instances of Google Maps being loaded across your entire site.', 'atoz_codecleaner')
        )
    );*/

    //Change Login URL
/*    add_settings_field(
        'login_url', 
        atoz_codecleaner_title(__('Change Login URL', 'atoz_codecleaner'), 'login_url') . atoz_codecleaner_tooltip('https://atozsites.com/docs/change-wordpress-login-url/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_options', 
        'atoz_codecleaner_options', 
        array(
            'id' => 'login_url',
            'input' => 'text',
            'placeholder' => 'hideme',
            'tooltip' => __('When set, this will change your WordPress login URL (slug) to the provided string and will block wp-admin and wp-login endpoints from being directly accessed.', 'atoz_codecleaner')
        )
    );*/

	    register_setting('atoz_codecleaner_options', 'atoz_codecleaner_options');

    //Google Analytics Option
    if(get_option('atoz_codecleaner_woocommerce') == false) {    
    add_option('atoz_codecleaner_woocommerce', apply_filters('atoz_codecleaner_default_woocommerce', atoz_codecleaner_default_woocommerce()));
    }

    //WooCommerce Options Section
    add_settings_section('atoz_codecleaner_woocommerce', 'WooCommerce', 'atoz_codecleaner_woocommerce_callback', 'atoz_codecleaner_woocommerce');

    //Disable WooCommerce Scripts
    add_settings_field(
        'disable_woocommerce_scripts', 
        atoz_codecleaner_title(__('Disable Scripts', 'atoz_codecleaner'), 'disable_woocommerce_scripts') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-woocommerce-scripts-and-styles/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_woocommerce', 
        'atoz_codecleaner_woocommerce', 
        array(
            'id' => 'disable_woocommerce_scripts',
            'option' => 'atoz_codecleaner_woocommerce',
            'tooltip' => __('Disables WooCommerce scripts and styles except on product, cart, and checkout pages.', 'atoz_codecleaner')
        )
    );

    //Disable WooCommerce Cart Fragmentation
    add_settings_field(
        'disable_woocommerce_cart_fragmentation', 
        atoz_codecleaner_title(__('Disable Cart Fragmentation', 'atoz_codecleaner'), 'disable_woocommerce_cart_fragmentation') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-woocommerce-cart-fragments-ajax/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_woocommerce', 
        'atoz_codecleaner_woocommerce', 
        array(
            'id' => 'disable_woocommerce_cart_fragmentation',
            'option' => 'atoz_codecleaner_woocommerce',
            'tooltip' => __('Completely disables WooCommerce cart fragmentation script.', 'atoz_codecleaner')
        )
    );

    //Disable WooCommerce Status Meta Box
    add_settings_field(
        'disable_woocommerce_status', 
        atoz_codecleaner_title(__('Disable Status Meta Box', 'atoz_codecleaner'), 'disable_woocommerce_status') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-woocommerce-status-meta-box/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_woocommerce', 
        'atoz_codecleaner_woocommerce', 
        array(
            'id' => 'disable_woocommerce_status',
            'option' => 'atoz_codecleaner_woocommerce',
            'tooltip' => __('Disables WooCommerce status meta box from the WP Admin Dashboard.', 'atoz_codecleaner')
        )
    );

    //Disable WooCommerce Widgets
    add_settings_field(
        'disable_woocommerce_widgets', 
        atoz_codecleaner_title(__('Disable Widgets', 'atoz_codecleaner'), 'disable_woocommerce_widgets') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-woocommerce-widgets/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_woocommerce', 
        'atoz_codecleaner_woocommerce', 
        array(
            'id' => 'disable_woocommerce_widgets',
            'option' => 'atoz_codecleaner_woocommerce',
            'tooltip' => __('Disables all WooCommerce widgets.', 'atoz_codecleaner')
        )
    );

    register_setting('atoz_codecleaner_woocommerce', 'atoz_codecleaner_woocommerce');

    //Google Analytics Option
    if(get_option('atoz_codecleaner_ga') == false) {    
        add_option('atoz_codecleaner_ga', apply_filters('atoz_codecleaner_default_ga', atoz_codecleaner_default_ga()));
    }

    //Google Analytics Section
    add_settings_section('atoz_codecleaner_ga', __('Google Analytics', 'atoz_codecleaner'), 'atoz_codecleaner_ga_callback', 'atoz_codecleaner_ga');

    //Enable Local GA
    add_settings_field(
        'enable_local_ga', 
        atoz_codecleaner_title(__('Enable Local Analytics', 'atoz_codecleaner'), 'enable_local_ga') . atoz_codecleaner_tooltip('https://atozsites.com/docs/local-analytics/'),
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_ga', 
        'atoz_codecleaner_ga', 
        array(
            'id' => 'enable_local_ga',
            'option' => 'atoz_codecleaner_ga',
            'tooltip' => __('Enable syncing of the Google Analytics script to your own server.', 'atoz_codecleaner')
        )
    );

    //Tracking Code Position
    add_settings_field(
        'tracking_code_position', 
        atoz_codecleaner_title(__('Tracking Code Position', 'atoz_codecleaner'), 'tracking_code_position') . atoz_codecleaner_tooltip('https://atozsites.com/docs/local-analytics/#trackingcodeposition'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_ga', 
        'atoz_codecleaner_ga', 
        array(
            'id' => 'tracking_code_position',
            'option' => 'atoz_codecleaner_ga',
            'input' => 'select',
            'options' => array(
            	"" => __('Header', 'atoz_codecleaner') . ' (' . __('Default', 'atoz_codecleaner') . ')',
            	"footer" => __('Footer', 'atoz_codecleaner')
            	),
            'tooltip' => __('Load your analytics script in the header (default) or footer of your site. Default: Header', 'atoz_codecleaner')
        )
    );
	
	//Disable Google Maps
    add_settings_field(
        'disable_google_maps', 
        atoz_codecleaner_title(__('Disable Google Maps', 'atoz_codecleaner'), 'disable_google_maps') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-google-maps-api-wordpress/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_ga', 
        'atoz_codecleaner_ga', 
        array(
            'id' => 'disable_google_maps',
            'option' => 'atoz_codecleaner_ga',
            'tooltip' => __('Removes any instances of Google Maps being loaded across your entire site.', 'atoz_codecleaner')
        )
    );

    //Disable Display Features
    add_settings_field(
        'disable_display_features', 
        atoz_codecleaner_title(__('Disable Display Features', 'atoz_codecleaner'), 'disable_display_features') . atoz_codecleaner_tooltip('https://atozsites.com/docs/local-analytics/#disabledisplayfeatures'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_ga', 
        'atoz_codecleaner_ga', 
        array(
            'id' => 'disable_display_features',
            'option' => 'atoz_codecleaner_ga',
            'tooltip' => __('Disable remarketing and advertising which generates a 2nd HTTP request.', 'atoz_codecleaner')
        )
    );

    //Anonymize IP
    add_settings_field(
        'anonymize_ip', 
        atoz_codecleaner_title(__('Anonymize IP', 'atoz_codecleaner'), 'anonymize_ip') . atoz_codecleaner_tooltip('https://atozsites.com/docs/local-analytics/#anonymize-ip'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_ga', 
        'atoz_codecleaner_ga', 
        array(
            'id' => 'anonymize_ip',
            'option' => 'atoz_codecleaner_ga',
            'tooltip' => __('Shorten visitor IP to comply with privacy restrictions in some countries.', 'atoz_codecleaner')
        )
    );

    //Google Analytics ID
    add_settings_field(
        'tracking_id', 
        atoz_codecleaner_title(__('Tracking ID', 'atoz_codecleaner'), 'tracking_id') . atoz_codecleaner_tooltip('https://atozsites.com/docs/local-analytics/#trackingid'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_ga', 
        'atoz_codecleaner_ga', 
        array(
            'id' => 'tracking_id',
            'option' => 'atoz_codecleaner_ga',
            'input' => 'text',
            'tooltip' => __('Input your Google Analytics tracking ID.', 'atoz_codecleaner')
        )
    );

    //Track Logged In Admins
    add_settings_field(
        'track_admins', 
        atoz_codecleaner_title(__('Track Logged In Admins', 'atoz_codecleaner'), 'track_admins') . atoz_codecleaner_tooltip('https://atozsites.com/docs/local-analytics/#track-logged-in-admins'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_ga', 
        'atoz_codecleaner_ga', 
        array(
            'id' => 'track_admins',
            'option' => 'atoz_codecleaner_ga',
            'tooltip' => __('Include logged-in WordPress admins in your Google Analytics reports.', 'atoz_codecleaner')
        )
    );

    //Adjusted Bounce Rate
    add_settings_field(
        'adjusted_bounce_rate', 
        atoz_codecleaner_title(__('Adjusted Bounce Rate', 'atoz_codecleaner'), 'adjusted_bounce_rate') . atoz_codecleaner_tooltip('https://atozsites.com/docs/local-analytics/#adjusted-bounce-rate'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_ga', 
        'atoz_codecleaner_ga', 
        array(
            'id' => 'adjusted_bounce_rate',
            'option' => 'atoz_codecleaner_ga',
            'input' => 'text',
            'tooltip' => __('Set a timeout limit in seconds to better evaluate the quality of your traffic. (1-100)', 'atoz_codecleaner')
        )
    );

    //Use MonsterInsights
    add_settings_field(
        'use_monster_insights', 
        atoz_codecleaner_title(__('Use MonsterInsights', 'atoz_codecleaner'), 'use_monster_insights') . atoz_codecleaner_tooltip('https://atozsites.com/docs/local-analytics/#monster-insights'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_ga', 
        'atoz_codecleaner_ga', 
        array(
            'id' => 'use_monster_insights',
            'option' => 'atoz_codecleaner_ga',
            'tooltip' => __('Allows MonsterInsights to manage your Google Analaytics while still using the locally hosted analytics.js file generated by atoz_codecleaner.', 'atoz_codecleaner')
        )
    );

    register_setting('atoz_codecleaner_ga', 'atoz_codecleaner_ga');

    if(get_option('atoz_codecleaner_extras') == false) {    
        add_option('atoz_codecleaner_extras', apply_filters('atoz_codecleaner_default_extras', atoz_codecleaner_default_extras()));
    }
    add_settings_section('atoz_codecleaner_extras', __('More', 'atoz_codecleaner'), 'atoz_codecleaner_extras_callback', 'atoz_codecleaner_extras');

    //Deep Cleaning
    add_settings_field(
        'deep_cleaning', 
        atoz_codecleaner_title(__('Deep Cleaning', 'atoz_codecleaner'), 'deep_cleaning') . atoz_codecleaner_tooltip('https://atozsites.com/docs/disable-scripts-per-post-page/'), 
        'atoz_codecleaner_print_input', 
        'atoz_codecleaner_extras', 
        'atoz_codecleaner_extras', 
        array(
        	'id' => 'deep_cleaning',
        	'option' => 'atoz_codecleaner_extras',
        	'tooltip' => __('Enables the atoz_codecleaner Deep Cleaning, which gives you the ability to disable CSS and JS files on a page by page basis.', 'atoz_codecleaner')
        )
    );

    //Preconnect
    add_settings_field(
        'preconnect', 
        atoz_codecleaner_title(__('Preconnect', 'atoz_codecleaner'), 'preconnect') . atoz_codecleaner_tooltip('https://atozsites.com/docs/preconnect/'), 
        'atoz_codecleaner_print_preconnect', 
        'atoz_codecleaner_extras', 
        'atoz_codecleaner_extras', 
        array(
            'id' => 'preconnect',
            'option' => 'atoz_codecleaner_extras',
            'tooltip' => __('Preconnect allows the browser to set up early connections before an HTTP request, eliminating roundtrip latency and saving time for users. Format: scheme://domain.tld (one per line)', 'atoz_codecleaner')
        )
    );

    //DNS Prefetch
    add_settings_field(
        'dns_prefetch', 
        atoz_codecleaner_title(__('DNS Prefetch', 'atoz_codecleaner'), 'dns_prefetch') . atoz_codecleaner_tooltip('https://atozsites.com/docs/dns-prefetching/'), 
        'atoz_codecleaner_print_dns_prefetch', 
        'atoz_codecleaner_extras', 
        'atoz_codecleaner_extras', 
        array(
            'id' => 'dns_prefetch',
            'option' => 'atoz_codecleaner_extras',
            'tooltip' => __('Resolve domain names before a user clicks. Format: //domain.tld (one per line)', 'atoz_codecleaner')
        )
    );

    if(!is_multisite()) {

        //Clean Uninstall
        add_settings_field(
            'clean_uninstall', 
            atoz_codecleaner_title(__('Clean Uninstall', 'atoz_codecleaner'), 'clean_uninstall') . atoz_codecleaner_tooltip('https://atozsites.com/docs/clean-uninstall/'), 
            'atoz_codecleaner_print_input', 
            'atoz_codecleaner_extras', 
            'atoz_codecleaner_extras', 
            array(
                'id' => 'clean_uninstall',
                'option' => 'atoz_codecleaner_extras',
                'tooltip' => __('When enabled, this will cause all atoz_codecleaner options data to be removed from your database when the plugin is uninstalled.', 'atoz_codecleaner')
            )
        );

    }

    //Accessibility Mode
    add_settings_field(
        'accessibility_mode', 
        atoz_codecleaner_title(__('Accessibility Mode', 'atoz_codecleaner'), 'accessibility_mode', true), 
        'atoz_codecleaner_print_input',
        'atoz_codecleaner_extras', 
        'atoz_codecleaner_extras', 
        array(
        	'id' => 'accessibility_mode',
        	'input' => 'checkbox',
        	'option' => 'atoz_codecleaner_extras',
        	'tooltip' => __('Disable the use of visual UI elements in the plugin settings such as checkbox toggles and hovering tooltips.', 'atoz_codecleaner')
        )
    );

    register_setting('atoz_codecleaner_extras', 'atoz_codecleaner_extras', 'atoz_codecleaner_sanitize_extras');

}
add_action('admin_init', 'atoz_codecleaner_settings');



//google analytics group callback
function atoz_codecleaner_ga_callback() {
    echo '<p class="atoz_codecleaner-subheading">' . __('Optimization options for Google Analytics.', 'atoz_codecleaner') . '</p>';
}

//main options group callback
function atoz_codecleaner_options_callback() {
	echo '<p class="atoz_codecleaner-subheading">' . __('Select which performance default you would like to enable.', 'atoz_codecleaner') . '</p>';
}

//print form inputs
function atoz_codecleaner_print_input($args) {
    if(!empty($args['option'])) {
        $option = $args['option'];
        if($args['option'] == 'atoz_codecleaner_network') {
            $options = get_site_option($args['option']);
        }
        else {
            $options = get_option($args['option']);
        }
    }
    else {
        $option = 'atoz_codecleaner_options';
        $options = get_option('atoz_codecleaner_options');
    }
    if(!empty($args['option']) && $args['option'] == 'atoz_codecleaner_extras') {
        $extras = $options;
    }
    else {
        $extras = get_option('atoz_codecleaner_extras');
    }

    echo "<div style='display: table; width: 100%;'>";
        echo "<div class='atoz_codecleaner-input-wrapper'>";

            //Text
            if(!empty($args['input']) && ($args['input'] == 'text' || $args['input'] == 'color')) {
                echo "<input type='text' id='" . $args['id'] . "' name='" . $option . "[" . $args['id'] . "]' value='" . (!empty($options[$args['id']]) ? $options[$args['id']] : '') . "' placeholder='" . (!empty($args['placeholder']) ? $args['placeholder'] : '') . "' />";
            }

            //Select
            elseif(!empty($args['input']) && $args['input'] == 'select') {
                echo "<select id='" . $args['id'] . "' name='" . $option . "[" . $args['id'] . "]'>";
                    foreach($args['options'] as $value => $title) {
                        echo "<option value='" . $value . "' "; 
                        if(!empty($options[$args['id']]) && $options[$args['id']] == $value) {
                            echo "selected";
                        } 
                        echo ">" . $title . "</option>";
                    }
                echo "</select>";
            }

            //Checkbox + Toggle
            else {
                if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && (empty($args['input']) || $args['input'] != 'checkbox')) {
                    echo "<label for='" . $args['id'] . "' class='switch'>";
                }
                    echo "<input type='checkbox' id='" . $args['id'] . "' name='" . $option . "[" . $args['id'] . "]' value='1' style='display: block; margin: 0px;' ";
                    if(!empty($options[$args['id']]) && $options[$args['id']] == "1") {
                        echo "checked";
                    }
                    echo ">";
                if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && (empty($args['input']) || $args['input'] != 'checkbox')) {
                       echo "<div class='slider'></div>";
                   echo "</label>";
                }
            }
            
        echo "</div>";

        if(!empty($args['tooltip'])) {
            if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && $args['id'] != 'accessibility_mode') {
                echo "<div class='atoz_codecleaner-tooltip-text-wrapper'>";
                    echo "<div class='atoz_codecleaner-tooltip-text-container'>";
                        echo "<div style='display: table; height: 100%; width: 100%;'>";
                            echo "<div style='display: table-cell; vertical-align: middle;'>";
                                echo "<span class='atoz_codecleaner-tooltip-text'>" . $args['tooltip'] . "</span>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
            else {
                echo "<p style='font-size: 12px; font-style: italic;'>" . $args['tooltip'] . "</p>";
            }
        }
    echo "</div>";
}

//extras group callback
function atoz_codecleaner_extras_callback() {
    echo '<p class="atoz_codecleaner-subheading">' . __('More options that pertain to atoz_codecleaner plugin functionality.', 'atoz_codecleaner') . '</p>';
}

//woocommerce options group callback
function atoz_codecleaner_woocommerce_callback() {
    echo '<p class="atoz_codecleaner-subheading">' . __('Disable specific elements of WooCommerce.', 'atoz_codecleaner') . '</p>';
}


//google analytics default values
function atoz_codecleaner_default_ga() {
    $defaults = array(
    	'enable_local_ga' => "0",
        'tracking_id' => "",
        'tracking_code_position' => "",
        'disable_display_features' => "0",
        'anonymize_ip' => "0",
        'track_admins' => "0",
        'adjusted_bounce_rate' => "",
        'use_monster_insights' => "0"
    );
    atoz_codecleaner_network_defaults($defaults, 'atoz_codecleaner_ga');
    return apply_filters('atoz_codecleaner_default_ga', $defaults);
}

//print checkbox toggle option
function atoz_codecleaner_print_toggle($args) {
    if(!empty($args['section'])) {
        $section = $args['section'];
        $options = get_option($args['section']);
    }
    else {
        $section = 'atoz_codecleaner_options';
        $options = get_option('atoz_codecleaner_options');
    }
    if(!empty($args['section']) && $args['section'] == 'atoz_codecleaner_extras') {
        $extras = $options;
    }
    else {
        $extras = get_option('atoz_codecleaner_extras');
    }
	//$options = get_option('atoz_codecleaner_options');
    //$extras = get_option('atoz_codecleaner_extras');
    if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && empty($args['checkbox'])) {
        echo "<label for='" . $args['id'] . "' class='switch' style='font-size: 1px;'>";
            echo $args['label'];
    }
    	echo "<input type='checkbox' id='" . $args['id'] . "' name='" . $section . "[" . $args['id'] . "]' value='1' style='display: block; margin: 0px;' ";
    	if(!empty($options[$args['id']]) && $options[$args['id']] == "1") {
    		echo "checked";
    	}
        echo ">";
    if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && empty($args['checkbox'])) {
	       echo "<div class='slider'></div>";
	   echo "</label>";
    }
}

//print tooltip
function atoz_codecleaner_tooltip($link) {
	$var = "<a ";
        if(!empty($link)) {
            $var.= "href='" . $link . "' title='" . __('View Documentation', 'atoz_codecleaner') . "' ";
        }
        $var.= "class='atoz_codecleaner-tooltip' target='_blank'>?";
    $var.= "</a>";
    return $var;
}

//print DNS Prefetch
function atoz_codecleaner_print_dns_prefetch($args) {
    $extras = get_option('atoz_codecleaner_extras');
     echo "<div style='display: table; width: 100%;'>";
        echo "<div class='atoz_codecleaner-input-wrapper'>";
            echo "<textarea id='" . $args['id'] . "' name='atoz_codecleaner_extras[" . $args['id'] . "]' placeholder='//example.com'>";
                if(!empty($extras['dns_prefetch'])) {
                    foreach($extras['dns_prefetch'] as $line) {
                        echo $line . "\n";
                    }
                }
            echo "</textarea>";
        echo "</div>";
        if(!empty($args['tooltip'])) {
            if(empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") {
                echo "<div class='atoz_codecleaner-tooltip-text-wrapper'>";
                    echo "<div class='atoz_codecleaner-tooltip-text-container'>";
                        echo "<div style='display: table; height: 100%; width: 100%;'>";
                            echo "<div style='display: table-cell; vertical-align: top;'>";
                                echo "<span class='atoz_codecleaner-tooltip-text'>" . $args['tooltip'] . "</span>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
            else {
                echo "<p style='font-size: 12px; font-style: italic;'>" . $args['tooltip'] . "</p>";
            }
        }
    echo "</div>";
}

//print Preconnect
function atoz_codecleaner_print_preconnect($args) {
    $extras = get_option('atoz_codecleaner_extras');
     echo "<div style='display: table; width: 100%;'>";
        echo "<div class='atoz_codecleaner-input-wrapper'>";
            echo "<textarea id='" . $args['id'] . "' name='atoz_codecleaner_extras[" . $args['id'] . "]' placeholder='https://example.com'>";
                if(!empty($extras['preconnect'])) {
                    foreach($extras['preconnect'] as $line) {
                        echo $line . "\n";
                    }
                }
            echo "</textarea>";
        echo "</div>";
        if(!empty($args['tooltip'])) {
            if(empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") {
                echo "<div class='atoz_codecleaner-tooltip-text-wrapper'>";
                    echo "<div class='atoz_codecleaner-tooltip-text-container'>";
                        echo "<div style='display: table; height: 100%; width: 100%;'>";
                            echo "<div style='display: table-cell; vertical-align: top;'>";
                                echo "<span class='atoz_codecleaner-tooltip-text'>" . $args['tooltip'] . "</span>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
            else {
                echo "<p style='font-size: 12px; font-style: italic;'>" . $args['tooltip'] . "</p>";
            }
        }
    echo "</div>";
}

//print title
function atoz_codecleaner_title($title, $id, $checkbox = false) {
    if(!empty($title)) {
        $var = $title;
        if(!empty($id)) {
            $extras = get_option('atoz_codecleaner_extras');
            if((!empty($extras['accessibility_mode']) && $extras['accessibility_mode'] == "1") || $checkbox == true) {
                $var = "<label for='" . $id . "'>" . $var . "</label>";
            }
        }
        return $var;
    }
}

//sanitize extras
function atoz_codecleaner_sanitize_extras($values) {
    if(!empty($values['dns_prefetch'])) {
        $text = trim($values['dns_prefetch']);
        $text_array = explode("\n", $text);
        $text_array = array_filter($text_array, 'trim');
        $values['dns_prefetch'] = $text_array;
    }
    if(!empty($values['preconnect'])) {
        $text = trim($values['preconnect']);
        $text_array = explode("\n", $text);
        $text_array = array_filter($text_array, 'trim');
        $values['preconnect'] = $text_array;
    }
    return $values;
}

//print select option
function atoz_codecleaner_print_select($args) {
	$options = get_option('atoz_codecleaner_options');
	echo "<select id='" . $args['id'] . "' name='atoz_codecleaner_options[" . $args['id'] . "]'>";
		foreach($args['options'] as $value => $title) {
			echo "<option value='" . $value . "' "; 
			if($options[$args['id']] == $value) {
				echo "selected";
			} 
			echo ">" . $title . "</option>";
		}
	echo "</select>";
}
