<?php
/**
 * Uninstall functions WordPress сhangelog
 *
 * @file
 * @package		WordPress changelog
 * @author		Andrew Skochelias
 */

defined( 'ABSPATH' ) || die();

function webolatory_changelog_remove_plugin() {

	global $wpdb;

	// Remove webolatory сhangelog table
	$wpdb->query( 'DROP TABLE `' . $wpdb->prefix . 'webolatory_changelog`;' );

	// Remove webolatory сhangelog option
	delete_option( 'wordpress_changelog_version' );
}

webolatory_changelog_remove_plugin();
