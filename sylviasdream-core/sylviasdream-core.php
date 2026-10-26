<?php
/**
 * Plugin Name: Sylvia's Dream Core
 * Plugin URI:  https://sylviasdream.com
 * Description: Site functions that must survive a theme change: download-page protection, redirects, structured data and the Dream Partner progress bar.
 * Version:     0.1.2
 * Author:      Pimofy Digital
 * Author URI:  https://pimofydigital.com
 * License:     GPL-2.0-or-later
 * Text Domain: sylviasdream-core
 * Requires at least: 6.4
 * Requires PHP: 8.0
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

define( 'SD_CORE_VERSION', '0.1.2' );
define( 'SD_CORE_DIR', plugin_dir_path( __FILE__ ) );

require_once SD_CORE_DIR . 'inc/settings.php';
require_once SD_CORE_DIR . 'inc/protect-downloads.php';
require_once SD_CORE_DIR . 'inc/redirects.php';
require_once SD_CORE_DIR . 'inc/schema.php';
require_once SD_CORE_DIR . 'inc/dream-partners.php';
