<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              kasha.co.ke
 * @since             1.0.0
 * @package           Kasha_sms
 *
 * @wordpress-plugin
 * Plugin Name:       kasha SMS Integration plugin
 * Plugin URI:        kasha.co.ke
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Varunkumar
 * Author URI:        kasha.co.ke
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kasha_sms
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
define( 'KASHA_SMS_VERSION', '1.0.0' );
define('KASHA_SMS_TEXT_DOMAIN', 'kasha_sms');
define('KASHA_SMS_TOKEN', '589023aa48e5b8294d159073b54cea9ce3ae9d2d');
define('KASHA_SMS_API_URL', 'https://sms-staging.kasha.co.ke/');
define('KASHA_SMS_INCLUDE_PATH', plugin_dir_path( __FILE__ ) . 'includes');
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kasha_sms-activator.php
 */
function activate_kasha_sms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kasha_sms-activator.php';
	Kasha_sms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kasha_sms-deactivator.php
 */
function deactivate_kasha_sms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kasha_sms-deactivator.php';
	Kasha_sms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kasha_sms' );
register_deactivation_hook( __FILE__, 'deactivate_kasha_sms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kasha_sms.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kasha_sms() {
	$plugin = new Kasha_sms();
	$plugin->run();
}
run_kasha_sms();
