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

class Ai1wmie_Import_Controller {

	public static function button() {
		return Ai1wm_Template::get_content(
			'import/button',
			array( 'access_key' => get_option( 'ai1wmie_digitalocean_access_key', false ) ),
			AI1WMIE_TEMPLATES_PATH
		);
	}

	public static function picker() {
		Ai1wm_Template::render(
			'import/picker',
			array(),
			AI1WMIE_TEMPLATES_PATH
		);
	}

	public static function region( $params = array() ) {
		ai1wm_setup_environment();

		// Set params
		if ( empty( $params ) ) {
			$params = stripslashes_deep( $_GET );
		}

		// Set region name
		$region_name = null;
		if ( isset( $params['region_name'] ) ) {
			$region_name = trim( $params['region_name'] );
		}

		// Set bucket name
		$bucket_name = null;
		if ( isset( $params['bucket_name'] ) ) {
			$bucket_name = trim( $params['bucket_name'] );
		}

		// Set folder path
		$folder_path = null;
		if ( isset( $params['folder_path'] ) ) {
			$folder_path = trim( $params['folder_path'] );
		}

		// Set DigitalOcean Spaces client
		$digitalocean = new Ai1wmie_DigitalOcean_Client(
			get_option( 'ai1wmie_digitalocean_access_key' ),
			get_option( 'ai1wmie_digitalocean_secret_key' )
		);

		// Get regions
		$regions = $digitalocean->get_regions();

		// Set region structure
		$response = array( 'items' => array(), 'num_hidden_files' => 0 );

		// Loop over items
		if ( $region_name ) {

			// Get buckets
			$buckets = $digitalocean->get_buckets( $region_name );

			// Set bucket name (ListAllMyBuckets)
			if ( count( $buckets ) === 0 ) {
				$bucket_name = get_option( 'ai1wmie_digitalocean_bucket_name', ai1wm_archive_bucket() );
			}

			// Loop over items
			if ( $bucket_name ) {

				// Get bucket items
				$items = $digitalocean->get_objects_by_bucket( $bucket_name, $region_name, array( 'delimiter' => '/', 'prefix' => $folder_path ) );

				// Loop over folders and files
				foreach ( $items as $item ) {
					if ( $item['type'] === 'folder' || pathinfo( $item['name'], PATHINFO_EXTENSION ) === 'wpress' ) {
						$response['items'][] = array(
							'name'        => isset( $item['name'] ) ? $item['name'] : null,
							'label'       => isset( $item['name'] ) ? $item['name'] : null,
							'path'        => isset( $item['path'] ) ? $item['path'] : null,
							'date'        => isset( $item['date'] ) ? human_time_diff( $item['date'] ) : null,
							'size'        => isset( $item['bytes'] ) ? size_format( $item['bytes'] ) : null,
							'bytes'       => isset( $item['bytes'] ) ? $item['bytes'] : null,
							'type'        => isset( $item['type'] ) ? $item['type'] : null,
							'bucket_name' => $bucket_name,
							'region_name' => $region_name,
						);
					} else {
						$response['num_hidden_files']++;
					}
				}

				// Sort items by type desc and name asc
				usort( $response['items'], 'Ai1wmie_Import_Controller::sort_by_type_desc_name_asc' );

			} else {

				// Loop over buckets
				foreach ( $buckets as $bucket_name ) {
					$response['items'][] = array(
						'name'        => $bucket_name,
						'label'       => $bucket_name,
						'region_name' => $region_name,
						'type'        => 'bucket',
					);
				}
			}
		} else {

			// Loop over regions
			foreach ( $regions as $region_name => $region_label ) {
				$response['items'][] = array(
					'name'  => $region_name,
					'label' => $region_label,
					'type'  => 'region',
				);
			}
		}

		echo json_encode( $response );
		exit;
	}

	public static function sort_by_type_desc_name_asc( $first_item, $second_item ) {
		$sorted_items = strcasecmp( $second_item['type'], $first_item['type'] );
		if ( $sorted_items !== 0 ) {
			return $sorted_items;
		}

		return strcasecmp( $first_item['name'], $second_item['name'] );
	}

	public static function pro() {
		return Ai1wm_Template::get_content( 'import/pro', array(), AI1WMIE_TEMPLATES_PATH );
	}
}
