<?php
/**
 * Plugin Name: WordPress Changelog
 * Plugin URI: http://skoch.com.ua/
 * Description: WordPress changelog - logs any uploads, updates, installations/uninstallations, activations/deactivations of themes, plugins and WordPress core.
 * Author: Webolatory Team
 * Author URI: http://webolatory.com/
 * Text Domain: wordpress-changelog
 * Version: 1.0
 * Domain Path: /languages/
 * License: GPL v3
 */

/**
 * WordPress changelog Plugin
 * Copyright (C) 2016, Webolatory - a.skoch@webolatory.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined( 'ABSPATH' ) || die;

define( 'WP_CHANGELOG_PLUGIN', __FILE__ );

define( 'WP_CHANGELOG_BASENAME', plugin_basename( WP_CHANGELOG_PLUGIN ) );

define( 'WP_CHANGELOG_DOMAIN', trim( dirname( WP_CHANGELOG_BASENAME ), '/' ) );

define( 'WP_CHANGELOG_DIR', untrailingslashit( dirname( WP_CHANGELOG_PLUGIN ) ) );

define( 'WP_CHANGELOG_URL', plugins_url( WP_CHANGELOG_DOMAIN ) );

/**
 * Init
 * */
class WP_Changelog_Init {

	public static $version = '1.0';

	/**
	 * Constructor
	*/
	function __construct() {

		// Include classes
		include_once( 'classes/class.view-changelog.php' );
		include_once( 'classes/class.wordpress-changelog.php' );

		// Load translate
		add_action( 'plugins_loaded', array( &$this, 'load_plugin_data' ) );
	}

	/**
	 * Load plugin data.
	 *
	 * @return void.
	 */
	public function load_plugin_data() {

		// Load translate
		load_plugin_textdomain( 'wordpress-changelog', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Check update db
		$db_version = get_option( 'wordpress_changelog_version', '0.0.1' );

		if ( $db_version !== self::$version ) {

			// Update DB
			WordPress_Changelog_Class::setup();
		}
	}

	/**
	 * Plugin Activation.
	 *
	 * @return void.
	 */
	public static function activation() {

		// Setup WordPress Changelog module
		WordPress_Changelog_Class::setup();

		return null;
	}

	/**
	 * Plugin Uninstall.
	 *
	 * @return void.
	 */
	public static function uninstall() {

		global $wpdb;

		// Remove webolatory сhangelog table
		$wpdb->query( 'DROP TABLE `' . $wpdb->prefix . 'webolatory_changelog`;' );

		// Remove webolatory сhangelog option
		delete_option( 'wordpress_changelog_version' );

		return null;
	}
}

$wp_changelog = new WP_Changelog_Init();

// Activation hook
register_activation_hook( __FILE__, array( 'WP_Changelog_Init', 'activation' ) );

// Uninstall hook
register_uninstall_hook( __FILE__, array( 'WP_Changelog_Init', 'uninstall' ) );
