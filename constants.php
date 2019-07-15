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

// ==================
// = Plugin Version =
// ==================
define( 'AI1WMIE_VERSION', '1.27' );

// ===============
// = Plugin Name =
// ===============
define( 'AI1WMIE_PLUGIN_NAME', 'all-in-one-wp-migration-digitalocean-extension' );

// ============
// = Lib Path =
// ============
define( 'AI1WMIE_LIB_PATH', AI1WMIE_PATH . DIRECTORY_SEPARATOR . 'lib' );

// ===================
// = Controller Path =
// ===================
define( 'AI1WMIE_CONTROLLER_PATH', AI1WMIE_LIB_PATH . DIRECTORY_SEPARATOR . 'controller' );

// ==============
// = Model Path =
// ==============
define( 'AI1WMIE_MODEL_PATH', AI1WMIE_LIB_PATH . DIRECTORY_SEPARATOR . 'model' );

// ===============
// = Export Path =
// ===============
define( 'AI1WMIE_EXPORT_PATH', AI1WMIE_MODEL_PATH . DIRECTORY_SEPARATOR . 'export' );

// ===============
// = Import Path =
// ===============
define( 'AI1WMIE_IMPORT_PATH', AI1WMIE_MODEL_PATH . DIRECTORY_SEPARATOR . 'import' );

// =============
// = View Path =
// =============
define( 'AI1WMIE_TEMPLATES_PATH', AI1WMIE_LIB_PATH . DIRECTORY_SEPARATOR . 'view' );

// ===============
// = Vendor Path =
// ===============
define( 'AI1WMIE_VENDOR_PATH', AI1WMIE_LIB_PATH . DIRECTORY_SEPARATOR . 'vendor' );

// ===========================
// = ServMask Activation URL =
// ===========================
define( 'AI1WMIE_ACTIVATION_URL', 'https://servmask.com/purchase/activations' );

// ===========================================
// = DigitalOcean Spaces Default Region Name =
// ===========================================
define( 'AI1WMIE_DIGITALOCEAN_DEFAULT_REGION_NAME', 'nyc3' );

// =============================================
// = DigitalOcean Spaces Default Storage Class =
// =============================================
define( 'AI1WMIE_DIGITALOCEAN_DEFAULT_STORAGE_CLASS', 'STANDARD' );

// ===========================
// = Default File Chunk Size =
// ===========================
define( 'AI1WMIE_DEFAULT_FILE_CHUNK_SIZE', 5 * 1024 * 1024 );

// =================
// = Max File Size =
// =================
define( 'AI1WMIE_MAX_FILE_SIZE', 0 );

// ===============
// = Purchase ID =
// ===============
define( 'AI1WMIE_PURCHASE_ID', 'fa41a419-0067-4327-9d3a-8e133fbac55a' );
