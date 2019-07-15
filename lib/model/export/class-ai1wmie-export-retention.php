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

class Ai1wmie_Export_Retention {

	public static function execute( $params, Ai1wmie_DigitalOcean_Client $digitalocean = null ) {

		// Set DigitalOcean Spaces client
		if ( is_null( $digitalocean ) ) {
			$digitalocean = new Ai1wmie_DigitalOcean_Client(
				get_option( 'ai1wmie_digitalocean_access_key', false ),
				get_option( 'ai1wmie_digitalocean_secret_key', false )
			);
		}

		// No bucket, no need to apply backup retention
		if ( ! $digitalocean->is_bucket_available( $params['bucket_name'], $params['region_name'] ) ) {
			return $params;
		}

		// Get bucket items
		$prefix = ! empty( $params['folder_name'] ) ? sprintf( '%s/', $params['folder_name'] ) : null;
		$items  = $digitalocean->get_objects_by_bucket( $params['bucket_name'], $params['region_name'], array( 'delimiter' => '/', 'prefix' => $prefix ) );

		$backups = array();
		foreach ( $items as $item ) {
			if ( $item['type'] === 'file' && pathinfo( $item['name'], PATHINFO_EXTENSION ) === 'wpress' ) {
				$backups[] = $item;
			}
		}
		// No backups, no need to apply backup retention
		if ( count( $backups ) === 0 ) {
			return $params;
		}

		usort( $backups, 'Ai1wmie_Export_Retention::sort_by_date_asc' );

		// Number of backups
		if ( ( $backups_limit = get_option( 'ai1wmie_digitalocean_backups', 0 ) ) ) {
			if ( ( $backups_to_remove = count( $backups ) - intval( $backups_limit ) ) > 0 ) {
				for ( $i = 0; $i < $backups_to_remove; $i++ ) {
					$digitalocean->remove_file( $backups[ $i ]['path'], $params['bucket_name'], $params['region_name'] );
				}
			}
		}

		// Sort backups by date desc
		$backups = array_reverse( $backups );

		// Get the size of the latest backup before we remove it
		$size_of_backups = $backups[0]['bytes'];

		// Remove the latest backup, the user should have at least one backup
		array_shift( $backups );

		// Size of backups
		if ( ( $retention_size = ai1wm_parse_size( get_option( 'ai1wmie_digitalocean_total', 0 ) ) ) > 0 ) {
			foreach ( $backups as $backup ) {
				$size_of_backups += $backup['bytes'];

				// Remove file if retention size is exceeded
				if ( $size_of_backups > $retention_size ) {
					$digitalocean->remove_file( $backup['path'], $params['bucket_name'], $params['region_name'] );
				}
			}
		}

		return $params;
	}

	public static function sort_by_date_asc( $first_backup, $second_backup ) {
		return intval( $first_backup['date'] ) - intval( $second_backup['date'] );
	}
}
