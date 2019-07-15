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

class Ai1wmie_Settings_Controller {

	public static function index() {
		$model = new Ai1wmie_Settings;

		$digitalocean_backup_schedules = get_option( 'ai1wmie_digitalocean_cron', array() );
		$digitalocean_cron_timestamp   = get_option( 'ai1wmie_digitalocean_cron_timestamp', time() );
		$last_backup_timestamp         = get_option( 'ai1wmie_digitalocean_timestamp', false );

		$last_backup_date = $model->get_last_backup_date( $last_backup_timestamp );
		$next_backup_date = $model->get_next_backup_date( $digitalocean_backup_schedules );

		$regions = $model->get_regions();

		try {
			if ( ( $buckets = $model->get_buckets() ) ) {
				if ( ! in_array( $model->get_bucket_name(), $buckets ) ) {
					$buckets[] = $model->get_bucket_name();
				}
			}
		} catch ( Ai1wmie_Error_Exception $e ) {
			$buckets = false;
		}

		Ai1wm_Template::render(
			'settings/index',
			array(
				'backups'                       => get_option( 'ai1wmie_digitalocean_backups', false ),
				'digitalocean_backup_schedules' => $digitalocean_backup_schedules,
				'digitalocean_cron_timestamp'   => $digitalocean_cron_timestamp,
				'notify_ok_toggle'              => get_option( 'ai1wmie_digitalocean_notify_toggle', false ),
				'notify_error_toggle'           => get_option( 'ai1wmie_digitalocean_notify_error_toggle', false ),
				'notify_email'                  => get_option( 'ai1wmie_digitalocean_notify_email', get_option( 'admin_email', false ) ),
				'last_backup_date'              => $last_backup_date,
				'next_backup_date'              => $next_backup_date,
				'access_key'                    => get_option( 'ai1wmie_digitalocean_access_key', false ),
				'secret_key'                    => get_option( 'ai1wmie_digitalocean_secret_key', false ),
				'bucket_name'                   => get_option( 'ai1wmie_digitalocean_bucket_name', ai1wm_archive_bucket() ),
				'region_name'                   => get_option( 'ai1wmie_digitalocean_region_name', AI1WMIE_DIGITALOCEAN_DEFAULT_REGION_NAME ),
				'folder_name'                   => get_option( 'ai1wmie_digitalocean_folder_name', '' ),
				'storage_class'                 => get_option( 'ai1wmie_digitalocean_storage_class', AI1WMIE_DIGITALOCEAN_DEFAULT_STORAGE_CLASS ),
				'file_chunk_size'               => get_option( 'ai1wmie_digitalocean_file_chunk_size', AI1WMIE_DEFAULT_FILE_CHUNK_SIZE ),
				'encryption'                    => get_option( 'ai1wmie_digitalocean_encryption', false ),
				'total'                         => get_option( 'ai1wmie_digitalocean_total', false ),
				'regions'                       => $regions,
				'buckets'                       => $buckets,
			),
			AI1WMIE_TEMPLATES_PATH
		);
	}

	public static function connection( $params = array() ) {
		ai1wm_setup_environment();

		// Set params
		if ( empty( $params ) ) {
			$params = stripslashes_deep( $_POST );
		}

		// DigitalOcean Spaces update
		if ( isset( $params['ai1wmie_digitalocean_update'] ) ) {
			$model = new Ai1wmie_Settings;

			// Access key
			if ( isset( $params['ai1wmie_digitalocean_access_key'] ) ) {
				$model->set_access_key( trim( $params['ai1wmie_digitalocean_access_key'] ) );
			}

			// Secret key
			if ( ! empty( $params['ai1wmie_digitalocean_secret_key'] ) ) {
				$model->set_secret_key( trim( $params['ai1wmie_digitalocean_secret_key'] ) );
			}

			try {
				// Get buckets
				$model->get_buckets();

				// Set message
				Ai1wm_Message::flash( 'success', __( 'DigitalOcean Spaces connection is successfully established.', AI1WMIE_PLUGIN_NAME ) );
			} catch ( Ai1wmie_Error_Exception $e ) {
				Ai1wm_Message::flash( 'error', $e->getMessage() );
			}
		}

		// Redirect to settings page
		wp_redirect( network_admin_url( 'admin.php?page=ai1wmie_settings' ) );
		exit;
	}

	public static function settings( $params = array() ) {
		ai1wm_setup_environment();

		// Set params
		if ( empty( $params ) ) {
			$params = stripslashes_deep( $_POST );
		}

		// DigitalOcean Spaces update
		if ( isset( $params['ai1wmie_digitalocean_update'] ) ) {
			$model = new Ai1wmie_Settings;

			// Cron timestamp update
			if ( ! empty( $params['ai1wmie_digitalocean_cron_timestamp'] ) &&
				( $cron_timestamp = strtotime( $params['ai1wmie_digitalocean_cron_timestamp'] ) ) ) {
				$model->set_cron_timestamp( strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $cron_timestamp ) ) ) );
			} else {
				$model->set_cron_timestamp( time() );
			}

			// Region name
			if ( isset( $params['ai1wmie_digitalocean_region_name'] ) ) {
				$model->set_region_name( trim( $params['ai1wmie_digitalocean_region_name'] ) );
			}

			// Bucket name
			if ( isset( $params['ai1wmie_digitalocean_bucket_name'] ) ) {
				try {
					// Create bucket
					$model->create_bucket( trim( $params['ai1wmie_digitalocean_bucket_name'] ) );

					// Set bucket name
					$model->set_bucket_name( trim( $params['ai1wmie_digitalocean_bucket_name'] ) );

					// Set message
					Ai1wm_Message::flash( 'settings', __( 'Your changes have been saved.', AI1WMIE_PLUGIN_NAME ) );
				} catch ( Ai1wmie_Error_Exception $e ) {
					Ai1wm_Message::flash( 'bucket', $e->getMessage() );
				}
			}

			// Folder name
			if ( isset( $params['ai1wmie_digitalocean_folder_name'] ) ) {
				$model->set_folder_name( trim( $params['ai1wmie_digitalocean_folder_name'], " \t\n\r\0\x0B/" ) );
			}

			// Storage class
			if ( isset( $params['ai1wmie_digitalocean_storage_class'] ) ) {
				$model->set_storage_class( trim( $params['ai1wmie_digitalocean_storage_class'] ) );
			}

			// Bucket encryption
			if ( isset( $params['ai1wmie_digitalocean_encryption'] ) ) {
				$model->set_encryption( trim( $params['ai1wmie_digitalocean_encryption'] ) );
			} else {
				$model->set_encryption( false );
			}

			// Cron update
			if ( ! empty( $params['ai1wmie_digitalocean_cron'] ) ) {
				$model->set_cron( (array) $params['ai1wmie_digitalocean_cron'] );
			} else {
				$model->set_cron( array() );
			}

			// Set number of backups
			if ( ! empty( $params['ai1wmie_digitalocean_backups'] ) ) {
				$model->set_backups( (int) $params['ai1wmie_digitalocean_backups'] );
			} else {
				$model->set_backups( 0 );
			}

			// Set size of backups
			if ( ! empty( $params['ai1wmie_digitalocean_total'] ) && ! empty( $params['ai1wmie_digitalocean_total_unit'] ) ) {
				$model->set_total( (int) $params['ai1wmie_digitalocean_total'] . trim( $params['ai1wmie_digitalocean_total_unit'] ) );
			} else {
				$model->set_total( 0 );
			}

			// Set file chunk size
			if ( ! empty( $params['ai1wmie_digitalocean_file_chunk_size'] ) ) {
				$model->set_file_chunk_size( $params['ai1wmie_digitalocean_file_chunk_size'] );
			} else {
				$model->set_file_chunk_size( AI1WMIE_DEFAULT_FILE_CHUNK_SIZE );
			}

			// Set notify ok toggle
			$model->set_notify_ok_toggle( isset( $params['ai1wmie_digitalocean_notify_toggle'] ) );

			// Set notify error toggle
			$model->set_notify_error_toggle( isset( $params['ai1wmie_digitalocean_notify_error_toggle'] ) );

			// Set notification email
			$model->set_notify_email( trim( $params['ai1wmie_digitalocean_notify_email'] ) );
		}

		// Redirect to settings page
		wp_redirect( network_admin_url( 'admin.php?page=ai1wmie_settings' ) );
		exit;
	}

	public static function notify_ok_toggle() {
		$model = new Ai1wmie_Settings;
		if ( ( $notify_ok_toggle = $model->get_notify_ok_toggle() ) ) {
			return $notify_ok_toggle;
		}
	}

	public static function notify_error_toggle() {
		$model = new Ai1wmie_Settings;
		if ( ( $notify_error_toggle = $model->get_notify_error_toggle() ) ) {
			return $notify_error_toggle;
		}
	}

	public static function notify_error_subject() {
		$model = new Ai1wmie_Settings;
		if ( ( $notify_error_subject = $model->get_notify_error_subject() ) ) {
			return $notify_error_subject;
		}
	}

	public static function notify_email() {
		$model = new Ai1wmie_Settings;
		if ( ( $notify_email = $model->get_notify_email() ) ) {
			return $notify_email;
		}
	}
}
