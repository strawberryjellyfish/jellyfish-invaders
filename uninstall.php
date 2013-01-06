<?php
// Uninstall functions to clean up if the plugin is deleted
// First check this was called from WordPress with uninstallation constant

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
exit;

// Check if options exist and delete them if present
if ( get_option( 'jellyfish_invaders_options' ) != false ) {
    delete_option( 'jellyfish_invaders_options' );
}
?>