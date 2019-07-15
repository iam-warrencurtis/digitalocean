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

class Ai1wmie_Export_DigitalOcean {

	public static function execute( $params, Ai1wmie_DigitalOcean_Client $digitalocean = null ) {

		// Set progress
		Ai1wm_Status::info( __( 'Connecting to DigitalOcean Spaces...', AI1WMIE_PLUGIN_NAME ) );

		// Open the archive file for writing
		$archive = new Ai1wm_Compressor( ai1wm_archive_path( $params ) );

		// Append EOF block
		$archive->close( true );

		// Set DigitalOcean Spaces client
		if ( is_null( $digitalocean ) ) {
			$digitalocean = new Ai1wmie_DigitalOcean_Client(
				get_option( 'ai1wmie_digitalocean_access_key', false ),
				get_option( 'ai1wmie_digitalocean_secret_key', false )
			);
		}

		// Get storage class
		$params['storage_class'] = get_option( 'ai1wmie_digitalocean_storage_class', AI1WMIE_DIGITALOCEAN_DEFAULT_STORAGE_CLASS );

		// Get bucket encryption
		$params['encryption'] = get_option( 'ai1wmie_digitalocean_encryption', false );

		// Get bucket name
		$params['bucket_name'] = get_option( 'ai1wmie_digitalocean_bucket_name', ai1wm_archive_bucket() );

		// Get region name
		$params['region_name'] = get_option( 'ai1wmie_digitalocean_region_name', AI1WMIE_DIGITALOCEAN_DEFAULT_REGION_NAME );

		// Get region name
		$params['region_name'] = $digitalocean->get_bucket_region( $params['bucket_name'], $params['region_name'] );

		// Get folder name
		$params['folder_name'] = get_option( 'ai1wmie_digitalocean_folder_name', '' );

		// Create bucket if does not exist
		if ( ! $digitalocean->is_bucket_available( $params['bucket_name'], $params['region_name'] ) ) {
			$digitalocean->create_bucket( $params['bucket_name'], $params['region_name'] );
		}

		$file_path = ! empty( $params['folder_name'] ) ? ( $params['folder_name'] . '/' . ai1wm_archive_name( $params ) ) : ai1wm_archive_name( $params );

		// Get upload ID
		$params['upload_id'] = $digitalocean->upload_multipart( $file_path, $params['bucket_name'], $params['region_name'], $params['storage_class'], $params['encryption'] );

		// Set progress
		Ai1wm_Status::info( __( 'Done connecting to DigitalOcean Spaces.', AI1WMIE_PLUGIN_NAME ) );

		return $params;
	}
}
