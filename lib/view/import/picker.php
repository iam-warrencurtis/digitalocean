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

<div id="ai1wmie-import-modal" class="ai1wmie-modal-container" role="dialog" tabindex="-1">
	<div class="ai1wmie-modal-content" v-if="items !== false">
		<div class="ai1wmie-file-browser">
			<div class="ai1wmie-path-list">
				<template v-for="(item, index) in path">
					<span v-if="index !== path.length - 1">
						<span class="ai1wmie-path-item" v-on:click="browse(item, index)" v-html="item.label"></span>
						<i class="ai1wm-icon-chevron-right"></i>
					</span>
					<span v-else>
						<span class="ai1wmie-path-item" v-html="item.label"></span>
					</span>
				</template>
			</div>

			<ul class="ai1wmie-file-list">
				<li class="ai1wmie-file-title" v-if="items.length > 0">
					<span class="ai1wmie-file-label">
						<?php _e( 'Name', AI1WMIE_PLUGIN_NAME ); ?>
					</span>
					<span class="ai1wmie-file-date">
						<?php _e( 'Date', AI1WMIE_PLUGIN_NAME ); ?>
					</span>
					<span class="ai1wmie-file-size">
						<?php _e( 'Size', AI1WMIE_PLUGIN_NAME ); ?>
					</span>
				</li>
				<li class="ai1wmie-file-item" v-for="item in items" v-on:click="browse(item)">
					<span class="ai1wmie-file-label">
						<i v-bind:class="item.type | icon"></i>
						{{ item.label }}
					</span>
					<span class="ai1wmie-file-date">{{ item.date }}</span>
					<span class="ai1wmie-file-size">{{ item.size }}</span>
				</li>
				<li
					v-if="items !== false && items.length === 0"
					style="text-align: center; cursor: default;"
					class="ai1wmie-file-item">
					<strong><?php _e( 'No folders or files to list. Click on the navbar to go back.', AI1WMIE_PLUGIN_NAME ); ?></strong>
				</li>
				<li class="ai1wmie-file-info" v-if="num_hidden_files === 1">
					{{ num_hidden_files }}
					<?php _e( 'file is hidden', AI1WMIE_PLUGIN_NAME ); ?>
					<i class="ai1wm-icon-help" title="<?php _e( 'Only wpress backups are listed', AI1WMIE_PLUGIN_NAME ); ?>"></i>
				</li>
				<li class="ai1wmie-file-info" v-if="num_hidden_files > 1">
					{{ num_hidden_files }}
					<?php _e( 'files are hidden', AI1WMIE_PLUGIN_NAME ); ?>
					<i class="ai1wm-icon-help" title="<?php _e( 'Only wpress backups are listed', AI1WMIE_PLUGIN_NAME ); ?>"></i>
				</li>
			</ul>
		</div>
	</div>

	<div class="ai1wmie-modal-loader" v-if="items === false">
		<p>
			<span class="ai1wmie-modal-spinner spinner"></span>
		</p>
		<p>
			<span class="ai1wmie-contact-digitalocean">
				<?php _e( 'Connecting to DigitalOcean ...', AI1WMIE_PLUGIN_NAME ); ?>
			</span>
		</p>
	</div>

	<div class="ai1wmie-modal-action">
		<transition>
			<p class="ai1wmie-selected-file" v-if="file">
				<i class="ai1wm-icon-file-zip"></i>
				{{ file.label }}
			</p>
		</transition>

		<p>
			<button type="button" class="ai1wm-button-red" v-on:click="cancel">
				<?php _e( 'Close', AI1WMIE_PLUGIN_NAME ); ?>
			</button>
			<button type="button" class="ai1wm-button-green" v-if="file" v-on:click="restore(file)">
				<i class="ai1wm-icon-publish"></i>
				<?php _e( 'Import', AI1WMIE_PLUGIN_NAME ); ?>
			</button>
		</p>
	</div>
</div>

<div id="ai1wmie-import-overlay" class="ai1wmie-overlay"></div>
