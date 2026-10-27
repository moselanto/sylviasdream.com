<?php
/**
 * Plugin Name: Sylvia's Dream Core
 * Plugin URI:  https://sylviasdream.com
 * Description: Site functions that must survive a theme change: download-page protection, redirects, structured data, social sharing tags, security hardening, branded sign-in screens, two-factor authentication, light admin branding, reviewer preview mode and the Dream Partner progress bar.
 * Version:     0.4.1
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

define( 'SD_CORE_VERSION', '0.4.1' );
define( 'SD_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'SD_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once SD_CORE_DIR . 'inc/settings.php';
require_once SD_CORE_DIR . 'inc/protect-downloads.php';
require_once SD_CORE_DIR . 'inc/redirects.php';
require_once SD_CORE_DIR . 'inc/schema.php';
require_once SD_CORE_DIR . 'inc/dream-partners.php';
require_once SD_CORE_DIR . 'inc/social.php';
require_once SD_CORE_DIR . 'inc/security.php';
require_once SD_CORE_DIR . 'inc/auth-hardening.php';
require_once SD_CORE_DIR . 'inc/qr-svg.php';
require_once SD_CORE_DIR . 'inc/two-factor.php';
require_once SD_CORE_DIR . 'inc/login.php';
require_once SD_CORE_DIR . 'inc/admin-brand.php';
require_once SD_CORE_DIR . 'inc/preview.php';
