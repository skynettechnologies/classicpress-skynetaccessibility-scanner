<?php
/**
 * Uninstall SkynetAccessibility Scanner.
 *
 * @package SkynetAccessibilityScanner
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options.
delete_option( 'skynetaccessibilityscanner_consent' );
delete_option( 'skynetaccessibilityscanner_token' );
delete_option( 'skynetaccessibilityscanner_settings' );

// If multisite support is added later, you may also
// need to loop through sites and remove options there.