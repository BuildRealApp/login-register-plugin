<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              buildrealapp.co.uk
 * @since             1.0.0
 * @package           Bra_Custom_Register_Login
 *
 * @wordpress-plugin
 * Plugin Name:       BRA Custom Register & Login
 * Plugin URI:        buildrealapp.co.uk
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Husam Abuhajjaj
 * Author URI:        buildrealapp.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bra-custom-register-login
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BRA_CUSTOM_REGISTER_LOGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bra-custom-register-login-activator.php
 */
function activate_bra_custom_register_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bra-custom-register-login-activator.php';
	Bra_Custom_Register_Login_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bra-custom-register-login-deactivator.php
 */
function deactivate_bra_custom_register_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bra-custom-register-login-deactivator.php';
	Bra_Custom_Register_Login_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bra_custom_register_login' );
register_deactivation_hook( __FILE__, 'deactivate_bra_custom_register_login' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bra-custom-register-login.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bra_custom_register_login() {

	$plugin = new Bra_Custom_Register_Login();
	$plugin->run();

}
run_bra_custom_register_login();
