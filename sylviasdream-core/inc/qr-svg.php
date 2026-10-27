<?php
/**
 * 0.4.1: locally rendered QR codes as inline SVG.
 *
 * Used by the two-factor setup screen (inc/two-factor.php) so the otpauth://
 * address can be scanned with Google Authenticator, Microsoft Authenticator,
 * Authy or similar. Everything is computed on this server: no external
 * service, CDN or third-party request. The encoder is a namespaced port of the
 * MIT-licensed qrcode-generator by Kazuhiko Arase (inc/lib/qrcode.php, licence
 * in inc/lib/qrcode-LICENSE.txt): byte mode, automatic version 1-40, all 8
 * masks with penalty scoring.
 *
 * The library is loaded only when a QR code is actually requested. Any
 * failure (missing file, data too long, unexpected error) returns '' so the
 * caller simply shows the manual key instead.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load the QR encoder once.
 *
 * @return bool True when the encoder class is available.
 */
function sd_core_qr_load() {
	if ( class_exists( '\SylviasDreamCore\QR\QRCode', false ) ) {
		return true;
	}
	$file = __DIR__ . '/lib/qrcode.php';
	if ( ! is_readable( $file ) ) {
		return false;
	}
	require_once $file;
	return class_exists( '\SylviasDreamCore\QR\QRCode', false );
}

/**
 * QR code module matrix for a text (byte mode, error correction level M).
 *
 * @param string $text Text to encode (bytes as given; keep it ASCII or UTF-8).
 * @return bool[][]|null Rows of booleans (true = dark), or null on failure.
 */
function sd_core_qr_matrix( $text ) {
	$text = (string) $text;
	// Byte mode at level M holds at most 2331 bytes (version 40).
	if ( '' === $text || strlen( $text ) > 2331 || ! sd_core_qr_load() ) {
		return null;
	}
	try {
		$qr = new \SylviasDreamCore\QR\QRCode( 0, \SylviasDreamCore\QR\QRCode::ECL_M );
		$qr->addData( $text );
		$qr->make();
		$matrix = $qr->getMatrix();
	} catch ( \Throwable $e ) {
		return null;
	}
	$n = count( $matrix );
	// A valid symbol is 21 to 177 modules wide (versions 1-40, 4 per step).
	if ( $n < 21 || $n > 177 || 0 !== ( $n - 17 ) % 4 ) {
		return null;
	}
	return $matrix;
}

/**
 * Inline SVG QR code for a text.
 *
 * Output is built only from integers and fixed strings (never from the
 * encoded text), so it is safe markup by construction; callers still pass it
 * through sd_core_qr_kses() when printing.
 *
 * - One <path> of 1x1 module squares (horizontal runs merged), dark #2B2116
 *   on a white background, shape-rendering crispEdges.
 * - 4-module quiet zone on every side.
 * - role="img" with an aria-label and a <title>.
 *
 * @param string $text    Text to encode.
 * @param int    $size_px Rendered width and height in CSS pixels (96-600).
 * @return string SVG markup, or '' when no QR code could be made.
 */
function sd_core_qr_svg( $text, $size_px = 200 ) {
	$matrix = sd_core_qr_matrix( $text );
	if ( null === $matrix ) {
		return '';
	}
	$size  = min( 600, max( 96, (int) $size_px ) );
	$quiet = 4;
	$n     = count( $matrix );
	$full  = $n + 2 * $quiet;

	$d = '';
	for ( $y = 0; $y < $n; $y++ ) {
		$x = 0;
		while ( $x < $n ) {
			if ( empty( $matrix[ $y ][ $x ] ) ) {
				$x++;
				continue;
			}
			$start = $x;
			while ( $x < $n && ! empty( $matrix[ $y ][ $x ] ) ) {
				$x++;
			}
			$run = $x - $start;
			$d  .= 'M' . ( $start + $quiet ) . ' ' . ( $y + $quiet ) . 'h' . $run . 'v1h-' . $run . 'z';
		}
	}
	if ( '' === $d ) {
		return '';
	}

	$label = function_exists( '__' ) ? __( 'QR code for your authenticator app', 'sylviasdream-core' ) : 'QR code for your authenticator app';
	$label = function_exists( 'esc_attr' ) ? esc_attr( $label ) : htmlspecialchars( $label, ENT_QUOTES, 'UTF-8' );
	$title = function_exists( 'esc_html' ) ? esc_html( $label ) : $label;

	return '<svg xmlns="http://www.w3.org/2000/svg" class="sd-2fa-qr-svg" role="img" aria-label="' . $label . '"'
		. ' width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $full . ' ' . $full . '" shape-rendering="crispEdges">'
		. '<title>' . $title . '</title>'
		. '<rect x="0" y="0" width="' . $full . '" height="' . $full . '" fill="#ffffff"/>'
		. '<path fill="#2B2116" d="' . $d . '"/>'
		. '</svg>';
}

/**
 * Print-time filter for sd_core_qr_svg() output: only the elements and
 * attributes it produces are allowed.
 *
 * @param string $svg SVG markup.
 * @return string
 */
function sd_core_qr_kses( $svg ) {
	return wp_kses(
		(string) $svg,
		array(
			'svg'   => array(
				'xmlns'           => true,
				'class'           => true,
				'role'            => true,
				'aria-label'      => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true,
				'shape-rendering' => true,
			),
			'title' => array(),
			'rect'  => array(
				'x'      => true,
				'y'      => true,
				'width'  => true,
				'height' => true,
				'fill'   => true,
			),
			'path'  => array(
				'fill' => true,
				'd'    => true,
			),
		)
	);
}
