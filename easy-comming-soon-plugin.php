<?php
/*
 * Plugin Name: Easy Coming Soon Mode Plugin
 * Plugin URI: https://wordpress.org/plugins/ecsm
 * Description: Coming soon page for anyone not logged in with no bad status codes
 * Version: 2.0
 * Author: dnightroad
 * Author URI: https://profiles.wordpress.org/dnightroad
 * License: GPLv2 or later
 *
 * @package maintenance-mode
 * @copyright Copyright (c) 2022, Diana Edreva
 * @license GPLv2 or later
*/

/**
 * Easy Coming Soon Mode Plugin
 *
 * The simplest coming soon plugin is here.
 * Cool coming soon page that let's you log in and edit your site , but keeps everyone else's eyes away.
 *
 * @return void
 */
 define('ECSM_PLUGIN_PATH', plugin_dir_path(__FILE__));
 define('ECSM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Wordpress Toolbar Menu
function ecsm_custom_toolbar()
{
    global $wp_admin_bar;

    $args = array(
        'id'     => 'coming_soon_maintenance_plugin',
        'title'  => __('Coming Soon Mode', 'text_domain'),
        'href'   => '/wp-admin/admin.php?page=ecsm',
    );
    $wp_admin_bar->add_menu($args);
}
add_action('wp_before_admin_bar_render', 'ecsm_custom_toolbar', 999);

// Main function and variable declarations
function ecsm_main()
{
    global $pagenow;

    $ecsm_options_defaults = array(
          'ecsm_enable_coming_soon_mode_0' =>'Off',
          'ecsm_heading_0'  => "Site is Coming Soon",
          'ecsm_sub_heading_1' => 'New and exciting site is being developed now',
          'ecsm_email_address_2' => 'none',
          'ecsm_theme_3' => 'light-theme',
          'ecsm_background_image' => "<?php plugins_url('assets/background.jpg', __FILE__); ?>", );
    $ecsm_options = get_option('ecsm_option_name', $ecsm_options_defaults);
    $ecsm_enable_coming_soon_mode_0 = $ecsm_options['ecsm_enable_coming_soon_mode_0']; // Enable button
    $ecsm_heading_0 = $ecsm_options['ecsm_heading_0']; // Heading
    $ecsm_sub_heading_1 = $ecsm_options['ecsm_sub_heading_1']; // Sub Heading
    $ecsm_email_address_2 = $ecsm_options['ecsm_email_address_2']; // Email Address
    $ecsm_theme_3 = $ecsm_options['ecsm_theme_3']; // Theme
    $ecsm_stylesheet_file = $ecsm_theme_3 == 'dark-theme' ? 'assets/dark.css' : 'assets/light.css';
    $ecsm_stylesheet = plugins_url($ecsm_stylesheet_file, __FILE__);
    $ecsm_background_image = $ecsm_options['ecsm_background_image'];

    if ($ecsm_enable_coming_soon_mode_0 == "Off") {
        return;
    }

    if ($pagenow !== 'wp-login.php' && ! current_user_can('manage_options') && ! is_admin()) {
        require_once('views/soon.php');

       die();
    }
}

//Wordpress Plugin Options

class ComingSoonMode
{
    private $ecsm_options;

    public function __construct()
    {
        add_action('admin_menu', array( $this, 'ecsm_add_plugin_page' ));
        add_action('admin_init', array( $this, 'ecsm_page_init' ));
    }

    public function ecsm_add_plugin_page()
    {
        add_menu_page(
            'Coming Soon Mode', // page_title
            'Coming Soon Mode', // menu_title
            'manage_options', // capability
            'ecsm', // menu_slug
            array( $this, 'ecsm_create_admin_page' ), // function
            'dashicons-admin-generic', // icon_url
            2 // position
        );
    }

    public function ecsm_create_admin_page()
    {
        $this->ecsm_options = get_option('ecsm_option_name');

        include_once 'partials/settings.php';
    }

    public function ecsm_page_init()
    {
        register_setting(
            'ecsm_option_group', // option_group
            'ecsm_option_name', // option_name
            array( $this, 'ecsm_sanitize'  ) // sanitize_callback

        );


        add_settings_section(
            'ecsm_setting_section', // id
            'Settings', // title
            array( $this, 'ecsm_section_info' ), // callback
            'ecsm-admin' // page
        );
        add_settings_field(
            'ecsm_enable_coming_soon_mode_0', // id
            'Enable coming soon mode', // title
            array( $this, 'ecsm_enable_0_callback' ), // callback
            'ecsm-admin', // page
            'ecsm_setting_section' // section
        );
        add_settings_field(
            'ecsm_heading_0', // id
            'Heading', // title
            array( $this, 'ecsm_heading_0_callback' ), // callback
            'ecsm-admin', // page
            'ecsm_setting_section' // section
        );

        add_settings_field(
            'ecsm_sub_heading_1', // id
            'Sub Heading', // title
            array( $this, 'ecsm_sub_heading_1_callback' ), // callback
            'ecsm-admin', // page
            'ecsm_setting_section' // section
        );

        add_settings_field(
            'ecsm_email_address_2', // id
            'Email Address', // title
            array( $this, 'ecsm_email_address_2_callback' ), // callback
            'ecsm-admin', // page
            'ecsm_setting_section' // section
        );

        add_settings_field(
            'ecsm_theme_3', // id
            'Theme', // title
            array( $this, 'ecsm_ecsm_theme_3_callback' ), // callback
            'ecsm-admin', // page
            'ecsm_setting_section' // section
        );
        add_settings_field(
            'ecsm_background_image', // id
            'Background Image', // title
            array( $this, 'ecsm_background_image_callback' ), // callback
            'ecsm-admin', // page
            'ecsm_setting_section' // section
        );
        add_settings_field(
            'ecsm_preview_mode', // id
            'Preview ', // title
            array( $this, 'ecsm_preview_mode_callback' ), // callback
            'ecsm-admin', // page
            'ecsm_setting_section' // section
        );
    }

    public function ecsm_sanitize($input)
    {
        $sanitary_values = array();
        foreach ($input as $field_name => $field_value) {
            $sanitary_values[$field_name] = sanitize_text_field($field_value);
        }

        return $sanitary_values;
    }

    public function ecsm_section_info()
    {
    }
    public function ecsm_enable_0_callback()
    {
        ?> <select name="ecsm_option_name[ecsm_enable_coming_soon_mode_0]" id="ecsm_enable_coming_soon_mode_0">
			<?php $selected = $this->ecsm_options['ecsm_enable_coming_soon_mode_0'] === 'Off' ? 'selected' : '' ; ?>
			<option <?php echo esc_attr($selected);?>>Off</option>
			<?php $selected = $this->ecsm_options['ecsm_enable_coming_soon_mode_0'] === 'On' ? 'selected' : '' ; ?>
			<option <?php echo esc_attr($selected);?>>On</option>
		</select> <?php
    }

    public function ecsm_heading_0_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="ecsm_option_name[ecsm_heading_0]" id="ecsm_heading_0" value="%s">',
            esc_attr($this->ecsm_options['ecsm_heading_0'])
        );
    }

    public function ecsm_sub_heading_1_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="ecsm_option_name[ecsm_sub_heading_1]" id="ecsm_sub_heading_1" value="%s">',
            esc_attr($this->ecsm_options['ecsm_sub_heading_1'])
        );
    }

    public function ecsm_email_address_2_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="ecsm_option_name[ecsm_email_address_2]" id="ecsm_email_address_2" value="%s">',
            esc_attr($this->ecsm_options['ecsm_email_address_2'])
        );
    }

    public function ecsm_ecsm_theme_3_callback()
    {
        ?> <select name="ecsm_option_name[ecsm_theme_3]" id="ecsm_theme_3">
			<?php $selected =  $this->ecsm_options['ecsm_theme_3'] === 'light-theme' ? 'selected' : '' ; ?>
			<option value="light-theme" <?php echo esc_attr($selected);?>> Light</option>
			<?php $selected =  $this->ecsm_options['ecsm_theme_3'] === 'dark-theme' ? 'selected' : '' ; ?>
			<option value="dark-theme" <?php echo esc_attr($selected);?>> Dark</option>
		</select> <?php
    }
    public function ecsm_background_image_callback()
    {
        wp_enqueue_media();
        wp_register_script('media-uploader', plugins_url('./assets/media-uploader.js', __FILE__), array('jquery'));
        wp_enqueue_script('media-uploader');
        $ecsm_options = get_option('ecsm_option_name');
        $ecsm_background_image = plugins_url('assets/background.jpg', __FILE__); // This is the default image - it changes once the user selects a new one
        $ecsm_background_image = $ecsm_options['ecsm_background_image']; ?>

        <input id="ecsm_background_image" type="text" name="ecsm_option_name[ecsm_background_image]" value="<?php echo esc_attr($ecsm_background_image);?>" /> <input id="upload_image_button" type="button" class="button-primary" value="Select an Image" />


        <?php

     $ecsm_background_image = ($this->ecsm_options['ecsm_background_image']);
    }
    public function ecsm_preview_mode_callback()
    {
        $ecsm_options = get_option('ecsm_option_name'); // Array of All Options
        $ecsm_background_image = plugins_url('assets/background.jpg', __FILE__); // This is the default image - it changes once the user selects a new one
        $ecsm_background_image = $ecsm_options['ecsm_background_image']; ?>
        <img style="height:20%; width:25%;" src="<?php echo esc_url( $ecsm_background_image ); ?>">
        <?php
    }
}

if (is_admin()) {
    $coming_soon_mode = new ComingSoonMode();
}



add_action('wp_loaded', 'ecsm_main', 'media-uploader');
