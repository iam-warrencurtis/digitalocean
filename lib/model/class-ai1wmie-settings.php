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

class Ai1wmie_Settings {

	public function get_last_backup_date( $last_backup_timestamp ) {
		if ( $last_backup_timestamp ) {
			$last_backup_date = get_date_from_gmt( date( 'Y-m-d H:i:s', $last_backup_timestamp ), 'F j, Y g:i a' );
		} else {
			$last_backup_date = __( 'None', AI1WMIE_PLUGIN_NAME );
		}

		return $last_backup_date;
	}

	public function get_next_backup_date( $schedules ) {
		$future_backup_timestamps = array();

		foreach ( $schedules as $schedule ) {
			$future_backup_timestamps[] = wp_next_scheduled( "ai1wmie_digitalocean_{$schedule}_export", array(
				array(
					'secret_key'   => get_option( AI1WM_SECRET_KEY ),
					'digitalocean' => 1,
				),
			) );
		}

		sort( $future_backup_timestamps );

		if ( isset( $future_backup_timestamps[0] ) ) {
			$next_backup_date = get_date_from_gmt( date( 'Y-m-d H:i:s', $future_backup_timestamps[0] ), 'F j, Y g:i a' );
		} else {
			$next_backup_date = __( 'None', AI1WMIE_PLUGIN_NAME );
		}

		return $next_backup_date;
	}

	/**
	 * Get region list
	 *
	 * @return array
	 */
	public function get_regions() {
		$digitalocean = new Ai1wmie_DigitalOcean_Client(
			get_option( 'ai1wmie_digitalocean_access_key', false ),
			get_option( 'ai1wmie_digitalocean_secret_key', false )
		);

		return $digitalocean->get_regions();
	}

	/**
	 * Get bucket list
	 *
	 * @return array
	 */
	public function get_buckets() {
		$digitalocean = new Ai1wmie_DigitalOcean_Client(
			get_option( 'ai1wmie_digitalocean_access_key', false ),
			get_option( 'ai1wmie_digitalocean_secret_key', false )
		);

		return $digitalocean->get_buckets( $this->get_region_name() );
	}

	/**
	 * Create bucket
	 *
	 * @param  string  $bucket_name Bucket name
	 * @return boolean
	 */
	public function create_bucket( $bucket_name ) {
		$digitalocean = new Ai1wmie_DigitalOcean_Client(
			get_option( 'ai1wmie_digitalocean_access_key', false ),
			get_option( 'ai1wmie_digitalocean_secret_key', false )
		);

		// Create bucket if does not exist
		if ( $digitalocean->is_bucket_available( $bucket_name, $this->get_region_name() ) ) {
			return false;
		}

		return $digitalocean->create_bucket( $bucket_name, $this->get_region_name() );
	}

	public function set_cron_timestamp( $timestamp ) {
		return update_option( 'ai1wmie_digitalocean_cron_timestamp', $timestamp );
	}

	public function get_cron_timestamp() {
		return get_option( 'ai1wmie_digitalocean_cron_timestamp', time() );
	}

	/**
	 * Set cron schedules
	 *
	 * @param  array   $schedules List of schedules
	 * @return boolean
	 */
	public function set_cron( $schedules ) {
		// Reset cron schedules
		Ai1wm_Cron::clear( 'ai1wmie_digitalocean_hourly_export' );
		Ai1wm_Cron::clear( 'ai1wmie_digitalocean_daily_export' );
		Ai1wm_Cron::clear( 'ai1wmie_digitalocean_weekly_export' );
		Ai1wm_Cron::clear( 'ai1wmie_digitalocean_monthly_export' );

		// Update cron schedules
		foreach ( $schedules as $schedule ) {
			Ai1wm_Cron::add( "ai1wmie_digitalocean_{$schedule}_export", $schedule, $this->get_cron_timestamp(), array(
				array(
					'secret_key'   => get_option( AI1WM_SECRET_KEY ),
					'digitalocean' => 1,
				),
			) );
		}

		return update_option( 'ai1wmie_digitalocean_cron', $schedules );
	}

	public function get_cron() {
		return get_option( 'ai1wmie_digitalocean_cron', array() );
	}

	public function set_access_key( $access_key ) {
		return update_option( 'ai1wmie_digitalocean_access_key', $access_key );
	}

	public function get_access_key() {
		return get_option( 'ai1wmie_digitalocean_access_key', false );
	}

	public function set_secret_key( $secret_key ) {
		return update_option( 'ai1wmie_digitalocean_secret_key', $secret_key );
	}

	public function get_secret_key() {
		return get_option( 'ai1wmie_digitalocean_secret_key', false );
	}

	public function set_bucket_name( $bucket_name ) {
		return update_option( 'ai1wmie_digitalocean_bucket_name', $bucket_name );
	}

	public function get_bucket_name() {
		return get_option( 'ai1wmie_digitalocean_bucket_name', ai1wm_archive_bucket() );
	}

	public function set_region_name( $region_name ) {
		return update_option( 'ai1wmie_digitalocean_region_name', $region_name );
	}

	public function get_region_name() {
		return get_option( 'ai1wmie_digitalocean_region_name', AI1WMIE_DIGITALOCEAN_DEFAULT_REGION_NAME );
	}

	public function set_folder_name( $folder_name ) {
		return update_option( 'ai1wmie_digitalocean_folder_name', $folder_name );
	}

	public function get_folder_name() {
		return get_option( 'ai1wmie_digitalocean_folder_name', '' );
	}

	public function set_storage_class( $storage_class ) {
		return update_option( 'ai1wmie_digitalocean_storage_class', $storage_class );
	}

	public function get_storage_class() {
		return get_option( 'ai1wmie_digitalocean_storage_class', AI1WMIE_DIGITALOCEAN_DEFAULT_STORAGE_CLASS );
	}

	public function set_encryption( $encryption ) {
		return update_option( 'ai1wmie_digitalocean_encryption', $encryption );
	}

	public function get_encryption() {
		return get_option( 'ai1wmie_digitalocean_encryption', false );
	}

	public function set_backups( $number ) {
		return update_option( 'ai1wmie_digitalocean_backups', $number );
	}

	public function get_backups() {
		return get_option( 'ai1wmie_digitalocean_backups', false );
	}

	public function set_total( $size ) {
		return update_option( 'ai1wmie_digitalocean_total', $size );
	}

	public function get_total() {
		return get_option( 'ai1wmie_digitalocean_total', false );
	}

	public function set_file_chunk_size( $file_chunk_size ) {
		return update_option( 'ai1wmie_digitalocean_file_chunk_size', $file_chunk_size );
	}

	public function get_file_chunk_size() {
		return get_option( 'ai1wmie_digitalocean_file_chunk_size', false );
	}

	public function set_notify_ok_toggle( $toggle ) {
		return update_option( 'ai1wmie_digitalocean_notify_toggle', $toggle );
	}

	public function get_notify_ok_toggle() {
		return get_option( 'ai1wmie_digitalocean_notify_toggle', false );
	}

	public function set_notify_error_toggle( $toggle ) {
		return update_option( 'ai1wmie_digitalocean_notify_error_toggle', $toggle );
	}

	public function get_notify_error_toggle() {
		return get_option( 'ai1wmie_digitalocean_notify_error_toggle', false );
	}

	public function set_notify_error_subject( $subject ) {
		return update_option( 'ai1wmie_digitalocean_notify_error_subject', $subject );
	}

	public function get_notify_error_subject() {
		return get_option( 'ai1wmie_digitalocean_notify_error_subject', sprintf( __( '❌ Backup to DigitalOcean Spaces has failed (%s)', AI1WMIE_PLUGIN_NAME ), parse_url( site_url(), PHP_URL_HOST ) . parse_url( site_url(), PHP_URL_PATH ) ) );
	}

	public function set_notify_email( $email ) {
		return update_option( 'ai1wmie_digitalocean_notify_email', $email );
	}

	public function get_notify_email() {
		return get_option( 'ai1wmie_digitalocean_notify_email', false );
	}
}
