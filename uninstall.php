<?php
/**
 * Copyright (C) 2014-2019 ServMask Inc.
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
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}

// Include plugin bootstrap file
require_once dirname( __FILE__ ) .
	DIRECTORY_SEPARATOR .
	'all-in-one-wp-migration-digitalocean-extension.php';

/**
 * Trigger Uninstall process only if WP_UNINSTALL_PLUGIN is defined
 */
if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	global $wpdb, $wp_filesystem;

	// Delete any options or other data stored in the database here
	delete_option( 'ai1wmie_digitalocean_cron_timestamp' );
	delete_option( 'ai1wmie_digitalocean_cron' );
	delete_option( 'ai1wmie_digitalocean_access_key' );
	delete_option( 'ai1wmie_digitalocean_secret_key' );
	delete_option( 'ai1wmie_digitalocean_bucket_name' );
	delete_option( 'ai1wmie_digitalocean_region_name' );
	delete_option( 'ai1wmie_digitalocean_folder_name' );
	delete_option( 'ai1wmie_digitalocean_storage_class' );
	delete_option( 'ai1wmie_digitalocean_encryption' );
	delete_option( 'ai1wmie_digitalocean_backups' );
	delete_option( 'ai1wmie_digitalocean_total' );
	delete_option( 'ai1wmie_digitalocean_file_chunk_size' );
	delete_option( 'ai1wmie_digitalocean_notify_toggle' );
	delete_option( 'ai1wmie_digitalocean_notify_error_toggle' );
	delete_option( 'ai1wmie_digitalocean_notify_error_subject' );
	delete_option( 'ai1wmie_digitalocean_notify_email' );
}
