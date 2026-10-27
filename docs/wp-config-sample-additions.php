<?php
/**
 * Optional wp-config.php constants for Sylvia's Dream (child theme 1.17.0+,
 * core plugin 0.4.0). Copy the lines you need into wp-config.php ABOVE
 * "That's all, stop editing!". This file is documentation; it is not loaded.
 *
 * No keys, passwords or tokens belong in the theme, plugin or this repo.
 * Any future secret must be read from a wp-config constant or getenv(), e.g.
 *   $key = defined( 'SD_SOME_KEY' ) ? SD_SOME_KEY : (string) getenv( 'SD_SOME_KEY' );
 *
 * @package SylviasDreamCore
 */

// DEV SITE ONLY: signed-in reviewers (edit_posts or sd_reviewer) see Draft
// books as they will look at launch. Never set this on the live site.
define( 'SD_PREVIEW_MODE', true );

// Admin and login over HTTPS only (both dev and live; WordPress.com does this already).
define( 'FORCE_SSL_ADMIN', true );

// Switch off individual hardening features if a host conflicts with one of them.
// Keys: headers, csp, hsts, login_limit, form_limit, session_expiry, xmlrpc,
// rest_users, author_enum, version, cors, https, password_length, upload_mimes,
// login_errors, app_passwords, session_cleanup, two_factor, login_brand, admin_brand.
// define( 'SD_SECURITY_DISABLE', array( 'xmlrpc' ) );   // e.g. self-hosted Jetpack needing XML-RPC
// define( 'SD_SECURITY_DISABLE', 'all' );               // everything off

// EMERGENCY ONLY: two-factor authentication off for the whole site (for example
// if an administrator lost their phone and recovery codes). Users' settings are
// kept; remove the line again as soon as the account is recovered.
// define( 'SD_2FA_DISABLE', true );

// Recommended on any self-managed host.
define( 'DISALLOW_FILE_EDIT', true );   // no theme/plugin code editor in WP Admin
// define( 'SCRIPT_DEBUG', true );      // load readable CSS/JS instead of the .min files
