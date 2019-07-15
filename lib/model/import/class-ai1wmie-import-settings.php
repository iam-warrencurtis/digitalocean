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

class Ai1wmie_Import_Settings {

	public static function execute( $params ) {

		// Set progress
		Ai1wm_Status::info( __( 'Getting DigitalOcean Spaces settings...', AI1WMIE_PLUGIN_NAME ) );

		$settings = array(
			'ai1wmie_digitalocean_cron_timestamp'       => get_option( 'ai1wmie_digitalocean_cron_timestamp', time() ),
			'ai1wmie_digitalocean_cron'                 => get_option( 'ai1wmie_digitalocean_cron', array() ),
			'ai1wmie_digitalocean_access_key'           => get_option( 'ai1wmie_digitalocean_access_key', false ),
			'ai1wmie_digitalocean_secret_key'           => get_option( 'ai1wmie_digitalocean_secret_key', false ),
			'ai1wmie_digitalocean_bucket_name'          => get_option( 'ai1wmie_digitalocean_bucket_name', ai1wm_archive_bucket() ),
			'ai1wmie_digitalocean_region_name'          => get_option( 'ai1wmie_digitalocean_region_name', AI1WMIE_DIGITALOCEAN_DEFAULT_REGION_NAME ),
			'ai1wmie_digitalocean_storage_class'        => get_option( 'ai1wmie_digitalocean_storage_class', AI1WMIE_DIGITALOCEAN_DEFAULT_STORAGE_CLASS ),
			'ai1wmie_digitalocean_encryption'           => get_option( 'ai1wmie_digitalocean_encryption', false ),
			'ai1wmie_digitalocean_backups'              => get_option( 'ai1wmie_digitalocean_backups', false ),
			'ai1wmie_digitalocean_folder_name'          => get_option( 'ai1wmie_digitalocean_folder_name', '' ),
			'ai1wmie_digitalocean_total'                => get_option( 'ai1wmie_digitalocean_total', false ),
			'ai1wmie_digitalocean_file_chunk_size'      => get_option( 'ai1wmie_digitalocean_file_chunk_size', AI1WMIE_DEFAULT_FILE_CHUNK_SIZE ),
			'ai1wmie_digitalocean_notify_toggle'        => get_option( 'ai1wmie_digitalocean_notify_toggle', false ),
			'ai1wmie_digitalocean_notify_error_toggle'  => get_option( 'ai1wmie_digitalocean_notify_error_toggle', false ),
			'ai1wmie_digitalocean_notify_error_subject' => get_option( 'ai1wmie_digitalocean_notify_error_subject', false ),
			'ai1wmie_digitalocean_notify_email'         => get_option( 'ai1wmie_digitalocean_notify_email', false ),
		);

		// Save settings.json file
		$handle = ai1wm_open( ai1wm_settings_path( $params ), 'w' );
		ai1wm_write( $handle, json_encode( $settings ) );
		ai1wm_close( $handle );

		// Set progress
		Ai1wm_Status::info( __( 'Done getting DigitalOcean Spaces settings.', AI1WMIE_PLUGIN_NAME ) );

		return $params;
	}
}
