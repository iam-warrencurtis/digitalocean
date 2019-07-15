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
?>

<div class="ai1wm-container">
	<div class="ai1wm-row">
		<div class="ai1wm-left">
			<div class="ai1wm-holder">
				<h1><i class="ai1wm-icon-gear"></i> <?php _e( 'DigitalOcean Spaces Settings', AI1WMIE_PLUGIN_NAME ); ?></h1>
				<br />
				<br />

				<?php if ( Ai1wm_Message::has( 'error' ) ) : ?>
					<div class="ai1wm-message ai1wm-error-message">
						<p><?php echo Ai1wm_Message::get( 'error' ); ?></p>
					</div>
				<?php elseif ( Ai1wm_Message::has( 'success' ) ) : ?>
					<div class="ai1wm-message ai1wm-success-message">
						<p><?php echo Ai1wm_Message::get( 'success' ); ?></p>
					</div>
				<?php endif; ?>

				<div id="ai1wmie-credentials">
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=ai1wmie_digitalocean_connection' ) ); ?>">
						<div class="ai1wm-field">
							<label for="ai1wmie-digitalocean-access-key">
								<?php _e( 'Access Key', AI1WMIE_PLUGIN_NAME ); ?>
								<br />
								<input type="text" placeholder="<?php _e( 'Enter DigitalOcean Spaces Access Key', AI1WMIE_PLUGIN_NAME ); ?>" id="ai1wmie-digitalocean-access-key" class="ai1wmie-settings-key" name="ai1wmie_digitalocean_access_key" value="<?php echo esc_attr( $access_key ); ?>" />
							</label>
							<a
								href="https://www.digitalocean.com/community/tutorials/how-to-create-a-digitalocean-space-and-api-key#creating-an-access-key"
								target="_blank">
								<?php _e( 'How to find your Access Key', AI1WMIE_PLUGIN_NAME ); ?>
							</a>
						</div>

						<div class="ai1wm-field">
							<label for="ai1wmie-digitalocean-secret-key">
								<?php _e( 'Secret Key', AI1WMIE_PLUGIN_NAME ); ?>
								<br />
								<input type="text" placeholder="<?php ( $secret_key ) ? _e( 'Hidden', AI1WMIE_PLUGIN_NAME ) : _e( 'Enter DigitalOcean Spaces Secret Key', AI1WMIE_PLUGIN_NAME ); ?>" id="ai1wmie-digitalocean-secret-key" class="ai1wmie-settings-key" name="ai1wmie_digitalocean_secret_key" autocomplete="off" />
							</label>
							<a
								href="https://www.digitalocean.com/community/tutorials/how-to-create-a-digitalocean-space-and-api-key#creating-an-access-key"
								target="_blank">
								<?php _e( 'How to find your Secret Key', AI1WMIE_PLUGIN_NAME ); ?>
							</a>
						</div>

						<p>
							<button type="submit" class="ai1wm-button-blue" name="ai1wmie_digitalocean_update" id="ai1wmie-digitalocean-link">
								<i class="ai1wm-icon-enter"></i>
								<?php _e( 'Update', AI1WMIE_PLUGIN_NAME ); ?>
							</button>
						</p>
					</form>
				</div>
			</div>

			<?php if ( $buckets !== false ) : ?>
				<div id="ai1wmie-backups" class="ai1wm-holder">
					<h1><i class="ai1wm-icon-gear"></i> <?php _e( 'DigitalOcean Spaces Backups', AI1WMIE_PLUGIN_NAME ); ?></h1>
					<br />
					<br />

					<?php if ( Ai1wm_Message::has( 'bucket' ) ) : ?>
						<div class="ai1wm-message ai1wm-error-message">
							<p><?php echo Ai1wm_Message::get( 'bucket' ); ?></p>
						</div>
					<?php elseif ( Ai1wm_Message::has( 'settings' ) ) : ?>
						<div class="ai1wm-message ai1wm-success-message">
							<p><?php echo Ai1wm_Message::get( 'settings' ); ?></p>
						</div>
					<?php endif; ?>

					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=ai1wmie_digitalocean_settings' ) ); ?>">
						<article class="ai1wmie-article">
							<h3><?php _e( 'Region name', AI1WMIE_PLUGIN_NAME ); ?></h3>
							<p>
								<?php if ( count( $regions ) > 0 ) : ?>
									<select class="ai1wmie-region-name" id="ai1wmie-digitalocean-region-name" name="ai1wmie_digitalocean_region_name">
										<?php foreach ( $regions as $key => $value ) : ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php echo $region_name === $key ? 'selected' : null; ?>><?php echo esc_html( $value ); ?></option>
										<?php endforeach; ?>
									</select>
								<?php else : ?>
									<input type="text" placeholder="<?php _e( 'Enter DigitalOcean Spaces Region Name', AI1WMIE_PLUGIN_NAME ); ?>" class="ai1wmie-region-name" name="ai1wmie_digitalocean_region_name" id="ai1wmie-digitalocean-region-name" value="<?php echo esc_attr( $region_name ); ?>" />
								<?php endif; ?>
							</p>
						</article>

						<article class="ai1wmie-article">
							<h3><?php _e( 'Bucket name', AI1WMIE_PLUGIN_NAME ); ?></h3>
							<p>
								<input type="text" placeholder="<?php _e( 'Enter DigitalOcean Spaces Bucket Name', AI1WMIE_PLUGIN_NAME ); ?>" class="ai1wmie-bucket-name" name="ai1wmie_digitalocean_bucket_name" id="ai1wmie-digitalocean-bucket-name" value="<?php echo esc_attr( $bucket_name ); ?>" />
							</p>
						</article>

						<article class="ai1wmie-article">
							<h3><?php _e( 'Folder name', AI1WMIE_PLUGIN_NAME ); ?></h3>
							<p>
								<input type="text" placeholder="<?php _e( 'Enter Folder Name (optional)', AI1WMIE_PLUGIN_NAME ); ?>" class="ai1wmie-folder-name" name="ai1wmie_digitalocean_folder_name" id="ai1wmie-digitalocean-folder-name" value="<?php echo esc_attr( $folder_name ); ?>" />
							</p>
						</article>

						<article class="ai1wmie-article">
							<h3><?php _e( 'Configure your backup plan', AI1WMIE_PLUGIN_NAME ); ?></h3>

							<p>
								<label for="ai1wmie-digitalocean-cron-timestamp">
									<?php _e( 'Backup time:', AI1WMIE_PLUGIN_NAME ); ?>
									<input type="text" name="ai1wmie_digitalocean_cron_timestamp" id="ai1wmie-digitalocean-cron-timestamp" value="<?php echo esc_attr( get_date_from_gmt( date( 'Y-m-d H:i:s', $digitalocean_cron_timestamp ), 'g:i a' ) ); ?>" autocomplete="off" />
									<code><?php echo ai1wm_get_timezone_string(); ?></code>
								</label>
							</p>

							<ul id="ai1wmie-digitalocean-cron">
								<li>
									<label for="ai1wmie-digitalocean-cron-hourly">
										<input type="checkbox" name="ai1wmie_digitalocean_cron[]" id="ai1wmie-digitalocean-cron-hourly" value="hourly" <?php echo in_array( 'hourly', $digitalocean_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every hour', AI1WMIE_PLUGIN_NAME ); ?>
									</label>
								</li>
								<li>
									<label for="ai1wmie-digitalocean-cron-daily">
										<input type="checkbox" name="ai1wmie_digitalocean_cron[]" id="ai1wmie-digitalocean-cron-daily" value="daily" <?php echo in_array( 'daily', $digitalocean_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every day', AI1WMIE_PLUGIN_NAME ); ?>
									</label>
								</li>
								<li>
									<label for="ai1wmie-digitalocean-cron-weekly">
										<input type="checkbox" name="ai1wmie_digitalocean_cron[]" id="ai1wmie-digitalocean-cron-weekly" value="weekly" <?php echo in_array( 'weekly', $digitalocean_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every week', AI1WMIE_PLUGIN_NAME ); ?>
									</label>
								</li>
								<li>
									<label for="ai1wmie-digitalocean-cron-monthly">
										<input type="checkbox" name="ai1wmie_digitalocean_cron[]" id="ai1wmie-digitalocean-cron-monthly" value="monthly" <?php echo in_array( 'monthly', $digitalocean_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every month', AI1WMIE_PLUGIN_NAME ); ?>
									</label>
								</li>
							</ul>

							<p>
								<?php _e( 'Last backup date:', AI1WMIE_PLUGIN_NAME ); ?>
								<strong>
									<?php echo $last_backup_date; ?>
								</strong>
							</p>

							<p>
								<?php _e( 'Next backup date:', AI1WMIE_PLUGIN_NAME ); ?>
								<strong>
									<?php echo $next_backup_date; ?>
								</strong>
							</p>
						</article>

						<article class="ai1wmie-article">
							<h3><?php _e( 'Notification settings', AI1WMIE_PLUGIN_NAME ); ?></h3>
							<p>
								<label for="ai1wmie-digitalocean-notify-toggle">
									<input type="checkbox" id="ai1wmie-digitalocean-notify-toggle" name="ai1wmie_digitalocean_notify_toggle" <?php echo empty( $notify_ok_toggle ) ? null : 'checked'; ?> />
									<?php _e( 'Send an email when a backup is complete', AI1WMIE_PLUGIN_NAME ); ?>
								</label>
							</p>

							<p>
								<label for="ai1wmie-digitalocean-notify-error-toggle">
									<input type="checkbox" id="ai1wmie-digitalocean-notify-error-toggle" name="ai1wmie_digitalocean_notify_error_toggle" <?php echo empty( $notify_error_toggle ) ? null : 'checked'; ?> />
									<?php _e( 'Send an email if a backup fails', AI1WMIE_PLUGIN_NAME ); ?>
								</label>
							</p>

							<p>
								<label for="ai1wmie-digitalocean-notify-email">
									<?php _e( 'Email address', AI1WMIE_PLUGIN_NAME ); ?>
									<br />
									<input class="ai1wmie-email" type="email" id="ai1wmie-digitalocean-notify-email" name="ai1wmie_digitalocean_notify_email" value="<?php echo esc_attr( $notify_email ); ?>" />
								</label>
							</p>
						</article>

						<article class="ai1wmie-article">
							<h3><?php _e( 'Retention settings', AI1WMIE_PLUGIN_NAME ); ?></h3>
							<p>
								<div class="ai1wm-field">
									<label for="ai1wmie-digitalocean-backups">
										<?php _e( 'Keep the most recent', AI1WMIE_PLUGIN_NAME ); ?>
										<input style="width: 3em;" type="number" min="0" name="ai1wmie_digitalocean_backups" id="ai1wmie-digitalocean-backups" value="<?php echo intval( $backups ); ?>" />
									</label>
									<?php _e( 'backups. <small>Default: <strong>0</strong> unlimited</small>', AI1WMIE_PLUGIN_NAME ); ?>
								</div>

								<div class="ai1wm-field">
									<label for="ai1wmie-digitalocean-total">
										<?php _e( 'Limit the total size of backups to', AI1WMIE_PLUGIN_NAME ); ?>
										<input style="width: 4em;" type="number" min="0" name="ai1wmie_digitalocean_total" id="ai1wmie-digitalocean-total" value="<?php echo intval( $total ); ?>" />
									</label>
									<select style="margin-top: -2px;" name="ai1wmie_digitalocean_total_unit" id="ai1wmie-digitalocean-total-unit">
										<option value="MB" <?php echo strpos( $total, 'MB' ) !== false ? 'selected="selected"' : null; ?>><?php _e( 'MB', AI1WMIE_PLUGIN_NAME ); ?></option>
										<option value="GB" <?php echo strpos( $total, 'GB' ) !== false ? 'selected="selected"' : null; ?>><?php _e( 'GB', AI1WMIE_PLUGIN_NAME ); ?></option>
									</select>
									<?php _e( '<small>Default: <strong>0</strong> unlimited</small>', AI1WMIE_PLUGIN_NAME ); ?>
								</div>
							</p>
						</article>

						<article class="ai1wmie-article">
							<h3><?php _e( 'Transfer settings', AI1WMIE_PLUGIN_NAME ); ?></h3>
							<div class="ai1wm-field">
								<label><?php _e( 'Slow Internet (Home)', AI1WMIE_PLUGIN_NAME ); ?></label>
								<input name="ai1wmie_digitalocean_file_chunk_size" min="5242880" max="20971520" step="5242880" type="range" value="<?php echo $file_chunk_size; ?>" id="ai1wmie-digitalocean-file-chunk-size" />
								<label><?php _e( 'Fast Internet (Internet Servers)', AI1WMIE_PLUGIN_NAME ); ?></label>
							</div>
						</article>

						<p>
							<button type="submit" class="ai1wm-button-blue" name="ai1wmie_digitalocean_update" id="ai1wmie-digitalocean-update">
								<i class="ai1wm-icon-database"></i>
								<?php _e( 'Update', AI1WMIE_PLUGIN_NAME ); ?>
							</button>
						</p>
					</form>
				</div>
			<?php endif; ?>
		</div>
		<div class="ai1wm-right">
			<div class="ai1wm-sidebar">
				<div class="ai1wm-segment">
					<?php if ( ! AI1WM_DEBUG ) : ?>
						<?php include AI1WM_TEMPLATES_PATH . '/common/share-buttons.php'; ?>
					<?php endif; ?>

					<h2><?php _e( 'Leave Feedback', AI1WMIE_PLUGIN_NAME ); ?></h2>

					<?php include AI1WM_TEMPLATES_PATH . '/common/leave-feedback.php'; ?>
				</div>
			</div>
		</div>
	</div>
</div>
