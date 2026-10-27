<?php
//---------------------------------------------------------------------
//
// QR Code Generator for PHP / JavaScript
//
// Copyright (c) 2009 Kazuhiko Arase
//
// URL: http://www.d-project.com/
//
// Licensed under the MIT license:
//   http://www.opensource.org/licenses/mit-license.php
//
// The word "QR Code" is registered trademark of
// DENSO WAVE INCORPORATED
//   http://www.denso-wave.com/qrcode/faqpatent-e.html
//
//---------------------------------------------------------------------
//
// Full MIT licence text: qrcode-LICENSE.txt in this folder.
//
// Source: github.com/kazuhikoarase/qrcode-generator (php/qrcode.php and
// js/dist/qrcode.js, branch master). Ported for Sylvia's Dream Core 0.4.1
// by Pimofy Digital. Changes from the original PHP file, all following the
// JavaScript version of the same library line by line:
// - namespaced (SylviasDreamCore\QR) and constants moved into classes, so it
//   cannot clash with another plugin that bundles the original QRCode class;
// - byte mode only (the only mode the plugin uses);
// - automatic version selection 1-40 exactly as the JavaScript make()
//   (the original PHP length table stops at version 10);
// - timing pattern set exactly as the JavaScript version;
// - exceptions instead of trigger_error( E_USER_ERROR ) (deprecated in PHP 8.4);
// - integer casts / intdiv where the original relied on floats as array keys.
// The tables, mask functions, penalty scoring and Reed-Solomon code are
// unchanged.
//
//---------------------------------------------------------------------

namespace SylviasDreamCore\QR;

defined( 'ABSPATH' ) || exit;

/**
 * QR code (byte mode).
 */
final class QRCode {

	const PAD0 = 0xEC;
	const PAD1 = 0x11;

	// Error correction levels (values as in the QR specification).
	const ECL_L = 1;
	const ECL_M = 0;
	const ECL_Q = 3;
	const ECL_H = 2;

	const MODE_8BIT_BYTE = 4;

	/** @var int */
	private $typeNumber;
	/** @var int */
	private $errorCorrectionLevel;
	/** @var array */
	private $modules = array();
	/** @var int */
	private $moduleCount = 0;
	/** @var array|null */
	private $dataCache = null;
	/** @var QR8BitByte[] */
	private $dataList = array();

	/**
	 * @param int $typeNumber           1 to 40, or 0 for automatic.
	 * @param int $errorCorrectionLevel One of the ECL_* constants.
	 * @throws \InvalidArgumentException On bad arguments.
	 */
	public function __construct( $typeNumber, $errorCorrectionLevel ) {
		$typeNumber           = (int) $typeNumber;
		$errorCorrectionLevel = (int) $errorCorrectionLevel;
		if ( $typeNumber < 0 || $typeNumber > 40 ) {
			throw new \InvalidArgumentException( 'bad typeNumber' );
		}
		if ( ! in_array( $errorCorrectionLevel, array( self::ECL_L, self::ECL_M, self::ECL_Q, self::ECL_H ), true ) ) {
			throw new \InvalidArgumentException( 'bad errorCorrectionLevel' );
		}
		$this->typeNumber           = $typeNumber;
		$this->errorCorrectionLevel = $errorCorrectionLevel;
	}

	/**
	 * @param string $data Bytes.
	 */
	public function addData( $data ) {
		$this->dataList[] = new QR8BitByte( (string) $data );
		$this->dataCache  = null;
	}

	/**
	 * @param int $row Row.
	 * @param int $col Column.
	 * @return bool
	 * @throws \OutOfRangeException Outside the symbol.
	 */
	public function isDark( $row, $col ) {
		if ( $row < 0 || $this->moduleCount <= $row || $col < 0 || $this->moduleCount <= $col ) {
			throw new \OutOfRangeException( $row . ',' . $col );
		}
		return true === $this->modules[ $row ][ $col ];
	}

	/**
	 * @return int
	 */
	public function getModuleCount() {
		return $this->moduleCount;
	}

	/**
	 * @return int
	 */
	public function getTypeNumber() {
		return $this->typeNumber;
	}

	/**
	 * Matrix of booleans (true = dark), rows then columns.
	 *
	 * @return bool[][]
	 */
	public function getMatrix() {
		$out = array();
		for ( $r = 0; $r < $this->moduleCount; $r++ ) {
			$line = array();
			for ( $c = 0; $c < $this->moduleCount; $c++ ) {
				$line[] = true === $this->modules[ $r ][ $c ];
			}
			$out[] = $line;
		}
		return $out;
	}

	/**
	 * Build the symbol (JavaScript make()).
	 *
	 * @throws \LengthException When the data does not fit.
	 */
	public function make() {
		if ( $this->typeNumber < 1 ) {
			$typeNumber = 1;

			for ( ; $typeNumber < 40; $typeNumber++ ) {
				$rsBlocks = QRRSBlock::getRSBlocks( $typeNumber, $this->errorCorrectionLevel );
				$buffer   = new QRBitBuffer();

				$dataListCount = count( $this->dataList );
				for ( $i = 0; $i < $dataListCount; $i++ ) {
					$data = $this->dataList[ $i ];
					$buffer->put( $data->getMode(), 4 );
					$buffer->put( $data->getLength(), QRUtil::getLengthInBits( $data->getMode(), $typeNumber ) );
					$data->write( $buffer );
				}

				$totalDataCount = 0;
				$rsBlockCount   = count( $rsBlocks );
				for ( $i = 0; $i < $rsBlockCount; $i++ ) {
					$totalDataCount += $rsBlocks[ $i ]->dataCount;
				}

				if ( $buffer->getLengthInBits() <= $totalDataCount * 8 ) {
					break;
				}
			}

			$this->typeNumber = $typeNumber;
		}

		$this->makeImpl( false, $this->getBestMaskPattern() );
	}

	/**
	 * @param bool $test        Test run (type info left light).
	 * @param int  $maskPattern Mask 0-7.
	 */
	private function makeImpl( $test, $maskPattern ) {

		$this->moduleCount = $this->typeNumber * 4 + 17;
		$this->modules     = array();
		for ( $row = 0; $row < $this->moduleCount; $row++ ) {
			$this->modules[ $row ] = array_fill( 0, $this->moduleCount, null );
		}

		$this->setupPositionProbePattern( 0, 0 );
		$this->setupPositionProbePattern( $this->moduleCount - 7, 0 );
		$this->setupPositionProbePattern( 0, $this->moduleCount - 7 );
		$this->setupPositionAdjustPattern();
		$this->setupTimingPattern();
		$this->setupTypeInfo( $test, $maskPattern );

		if ( $this->typeNumber >= 7 ) {
			$this->setupTypeNumber( $test );
		}

		if ( null === $this->dataCache ) {
			$this->dataCache = self::createData( $this->typeNumber, $this->errorCorrectionLevel, $this->dataList );
		}

		$this->mapData( $this->dataCache, $maskPattern );
	}

	/**
	 * @param int $row Row.
	 * @param int $col Column.
	 */
	private function setupPositionProbePattern( $row, $col ) {

		for ( $r = -1; $r <= 7; $r++ ) {

			if ( $row + $r <= -1 || $this->moduleCount <= $row + $r ) {
				continue;
			}

			for ( $c = -1; $c <= 7; $c++ ) {

				if ( $col + $c <= -1 || $this->moduleCount <= $col + $c ) {
					continue;
				}

				if ( ( 0 <= $r && $r <= 6 && ( 0 === $c || 6 === $c ) )
						|| ( 0 <= $c && $c <= 6 && ( 0 === $r || 6 === $r ) )
						|| ( 2 <= $r && $r <= 4 && 2 <= $c && $c <= 4 ) ) {
					$this->modules[ $row + $r ][ $col + $c ] = true;
				} else {
					$this->modules[ $row + $r ][ $col + $c ] = false;
				}
			}
		}
	}

	/**
	 * Try all 8 masks, keep the one with the lowest penalty.
	 *
	 * @return int
	 */
	private function getBestMaskPattern() {

		$minLostPoint = 0;
		$pattern      = 0;

		for ( $i = 0; $i < 8; $i++ ) {

			$this->makeImpl( true, $i );

			$lostPoint = QRUtil::getLostPoint( $this );

			if ( 0 === $i || $minLostPoint > $lostPoint ) {
				$minLostPoint = $lostPoint;
				$pattern      = $i;
			}
		}

		return $pattern;
	}

	private function setupTimingPattern() {

		for ( $r = 8; $r < $this->moduleCount - 8; $r++ ) {
			if ( null !== $this->modules[ $r ][6] ) {
				continue;
			}
			$this->modules[ $r ][6] = ( 0 === $r % 2 );
		}

		for ( $c = 8; $c < $this->moduleCount - 8; $c++ ) {
			if ( null !== $this->modules[6][ $c ] ) {
				continue;
			}
			$this->modules[6][ $c ] = ( 0 === $c % 2 );
		}
	}

	private function setupPositionAdjustPattern() {

		$pos      = QRUtil::getPatternPosition( $this->typeNumber );
		$posCount = count( $pos );

		for ( $i = 0; $i < $posCount; $i++ ) {

			for ( $j = 0; $j < $posCount; $j++ ) {

				$row = $pos[ $i ];
				$col = $pos[ $j ];

				if ( null !== $this->modules[ $row ][ $col ] ) {
					continue;
				}

				for ( $r = -2; $r <= 2; $r++ ) {

					for ( $c = -2; $c <= 2; $c++ ) {

						if ( -2 === $r || 2 === $r || -2 === $c || 2 === $c
								|| ( 0 === $r && 0 === $c ) ) {
							$this->modules[ $row + $r ][ $col + $c ] = true;
						} else {
							$this->modules[ $row + $r ][ $col + $c ] = false;
						}
					}
				}
			}
		}
	}

	/**
	 * @param bool $test Test run.
	 */
	private function setupTypeNumber( $test ) {

		$bits = QRUtil::getBCHTypeNumber( $this->typeNumber );

		for ( $i = 0; $i < 18; $i++ ) {
			$mod = ( ! $test && 1 === ( ( $bits >> $i ) & 1 ) );
			$this->modules[ intdiv( $i, 3 ) ][ $i % 3 + $this->moduleCount - 8 - 3 ] = $mod;
		}

		for ( $i = 0; $i < 18; $i++ ) {
			$mod = ( ! $test && 1 === ( ( $bits >> $i ) & 1 ) );
			$this->modules[ $i % 3 + $this->moduleCount - 8 - 3 ][ intdiv( $i, 3 ) ] = $mod;
		}
	}

	/**
	 * @param bool $test        Test run.
	 * @param int  $maskPattern Mask.
	 */
	private function setupTypeInfo( $test, $maskPattern ) {

		$data = ( $this->errorCorrectionLevel << 3 ) | $maskPattern;
		$bits = QRUtil::getBCHTypeInfo( $data );

		// Vertical.
		for ( $i = 0; $i < 15; $i++ ) {

			$mod = ( ! $test && 1 === ( ( $bits >> $i ) & 1 ) );

			if ( $i < 6 ) {
				$this->modules[ $i ][8] = $mod;
			} elseif ( $i < 8 ) {
				$this->modules[ $i + 1 ][8] = $mod;
			} else {
				$this->modules[ $this->moduleCount - 15 + $i ][8] = $mod;
			}
		}

		// Horizontal.
		for ( $i = 0; $i < 15; $i++ ) {

			$mod = ( ! $test && 1 === ( ( $bits >> $i ) & 1 ) );

			if ( $i < 8 ) {
				$this->modules[8][ $this->moduleCount - $i - 1 ] = $mod;
			} elseif ( $i < 9 ) {
				$this->modules[8][ 15 - $i - 1 + 1 ] = $mod;
			} else {
				$this->modules[8][ 15 - $i - 1 ] = $mod;
			}
		}

		// Fixed module.
		$this->modules[ $this->moduleCount - 8 ][8] = ( ! $test );
	}

	/**
	 * @param int[] $data        Codewords.
	 * @param int   $maskPattern Mask.
	 */
	private function mapData( $data, $maskPattern ) {

		$inc       = -1;
		$row       = $this->moduleCount - 1;
		$bitIndex  = 7;
		$byteIndex = 0;
		$dataCount = count( $data );

		for ( $col = $this->moduleCount - 1; $col > 0; $col -= 2 ) {

			if ( 6 === $col ) {
				$col -= 1;
			}

			while ( true ) {

				for ( $c = 0; $c < 2; $c++ ) {

					if ( null === $this->modules[ $row ][ $col - $c ] ) {

						$dark = false;

						if ( $byteIndex < $dataCount ) {
							$dark = ( 1 === ( ( $data[ $byteIndex ] >> $bitIndex ) & 1 ) );
						}

						$mask = QRUtil::getMask( $maskPattern, $row, $col - $c );

						if ( $mask ) {
							$dark = ! $dark;
						}

						$this->modules[ $row ][ $col - $c ] = $dark;
						$bitIndex -= 1;

						if ( -1 === $bitIndex ) {
							$byteIndex += 1;
							$bitIndex   = 7;
						}
					}
				}

				$row += $inc;

				if ( $row < 0 || $this->moduleCount <= $row ) {
					$row -= $inc;
					$inc  = -$inc;
					break;
				}
			}
		}
	}

	/**
	 * @param QRBitBuffer $buffer   Data bits.
	 * @param QRRSBlock[] $rsBlocks Blocks.
	 * @return int[]
	 */
	private static function createBytes( $buffer, $rsBlocks ) {

		$offset = 0;

		$maxDcCount = 0;
		$maxEcCount = 0;

		$rsBlockCount = count( $rsBlocks );
		$dcdata       = array_fill( 0, $rsBlockCount, null );
		$ecdata       = array_fill( 0, $rsBlockCount, null );
		$bytes        = $buffer->getBuffer();

		for ( $r = 0; $r < $rsBlockCount; $r++ ) {

			$dcCount = $rsBlocks[ $r ]->dataCount;
			$ecCount = $rsBlocks[ $r ]->totalCount - $dcCount;

			$maxDcCount = max( $maxDcCount, $dcCount );
			$maxEcCount = max( $maxEcCount, $ecCount );

			$dcdata[ $r ] = array_fill( 0, $dcCount, 0 );

			for ( $i = 0; $i < $dcCount; $i++ ) {
				$dcdata[ $r ][ $i ] = 0xff & $bytes[ $i + $offset ];
			}
			$offset += $dcCount;

			$rsPoly  = QRUtil::getErrorCorrectPolynomial( $ecCount );
			$rawPoly = new QRPolynomial( $dcdata[ $r ], $rsPoly->getLength() - 1 );

			$modPoly  = $rawPoly->mod( $rsPoly );
			$ecLength = $rsPoly->getLength() - 1;

			$ecdata[ $r ] = array_fill( 0, $ecLength, 0 );
			for ( $i = 0; $i < $ecLength; $i++ ) {
				$modIndex           = $i + $modPoly->getLength() - $ecLength;
				$ecdata[ $r ][ $i ] = ( $modIndex >= 0 ) ? $modPoly->getAt( $modIndex ) : 0;
			}
		}

		$totalCodeCount = 0;
		for ( $i = 0; $i < $rsBlockCount; $i++ ) {
			$totalCodeCount += $rsBlocks[ $i ]->totalCount;
		}

		$data  = array_fill( 0, $totalCodeCount, 0 );
		$index = 0;

		for ( $i = 0; $i < $maxDcCount; $i++ ) {
			for ( $r = 0; $r < $rsBlockCount; $r++ ) {
				if ( $i < count( $dcdata[ $r ] ) ) {
					$data[ $index ] = $dcdata[ $r ][ $i ];
					$index         += 1;
				}
			}
		}

		for ( $i = 0; $i < $maxEcCount; $i++ ) {
			for ( $r = 0; $r < $rsBlockCount; $r++ ) {
				if ( $i < count( $ecdata[ $r ] ) ) {
					$data[ $index ] = $ecdata[ $r ][ $i ];
					$index         += 1;
				}
			}
		}

		return $data;
	}

	/**
	 * @param int          $typeNumber           Version.
	 * @param int          $errorCorrectionLevel Level.
	 * @param QR8BitByte[] $dataList             Segments.
	 * @return int[]
	 * @throws \LengthException When the data does not fit.
	 */
	private static function createData( $typeNumber, $errorCorrectionLevel, $dataList ) {

		$rsBlocks = QRRSBlock::getRSBlocks( $typeNumber, $errorCorrectionLevel );

		$buffer = new QRBitBuffer();

		$dataListCount = count( $dataList );
		for ( $i = 0; $i < $dataListCount; $i++ ) {
			$data = $dataList[ $i ];
			$buffer->put( $data->getMode(), 4 );
			$buffer->put( $data->getLength(), QRUtil::getLengthInBits( $data->getMode(), $typeNumber ) );
			$data->write( $buffer );
		}

		// Calc num max data.
		$totalDataCount = 0;
		$rsBlockCount   = count( $rsBlocks );
		for ( $i = 0; $i < $rsBlockCount; $i++ ) {
			$totalDataCount += $rsBlocks[ $i ]->dataCount;
		}

		if ( $buffer->getLengthInBits() > $totalDataCount * 8 ) {
			throw new \LengthException( 'code length overflow. (' . $buffer->getLengthInBits() . '>' . ( $totalDataCount * 8 ) . ')' );
		}

		// End code.
		if ( $buffer->getLengthInBits() + 4 <= $totalDataCount * 8 ) {
			$buffer->put( 0, 4 );
		}

		// Padding.
		while ( 0 !== $buffer->getLengthInBits() % 8 ) {
			$buffer->putBit( false );
		}

		// Padding.
		while ( true ) {

			if ( $buffer->getLengthInBits() >= $totalDataCount * 8 ) {
				break;
			}
			$buffer->put( self::PAD0, 8 );

			if ( $buffer->getLengthInBits() >= $totalDataCount * 8 ) {
				break;
			}
			$buffer->put( self::PAD1, 8 );
		}

		return self::createBytes( $buffer, $rsBlocks );
	}
}

/**
 * Tables and helpers.
 */
final class QRUtil {

	const PATTERN_POSITION_TABLE = array(
		array(),
		array( 6, 18 ),
		array( 6, 22 ),
		array( 6, 26 ),
		array( 6, 30 ),
		array( 6, 34 ),
		array( 6, 22, 38 ),
		array( 6, 24, 42 ),
		array( 6, 26, 46 ),
		array( 6, 28, 50 ),
		array( 6, 30, 54 ),
		array( 6, 32, 58 ),
		array( 6, 34, 62 ),
		array( 6, 26, 46, 66 ),
		array( 6, 26, 48, 70 ),
		array( 6, 26, 50, 74 ),
		array( 6, 30, 54, 78 ),
		array( 6, 30, 56, 82 ),
		array( 6, 30, 58, 86 ),
		array( 6, 34, 62, 90 ),
		array( 6, 28, 50, 72, 94 ),
		array( 6, 26, 50, 74, 98 ),
		array( 6, 30, 54, 78, 102 ),
		array( 6, 28, 54, 80, 106 ),
		array( 6, 32, 58, 84, 110 ),
		array( 6, 30, 58, 86, 114 ),
		array( 6, 34, 62, 90, 118 ),
		array( 6, 26, 50, 74, 98, 122 ),
		array( 6, 30, 54, 78, 102, 126 ),
		array( 6, 26, 52, 78, 104, 130 ),
		array( 6, 30, 56, 82, 108, 134 ),
		array( 6, 34, 60, 86, 112, 138 ),
		array( 6, 30, 58, 86, 114, 142 ),
		array( 6, 34, 62, 90, 118, 146 ),
		array( 6, 30, 54, 78, 102, 126, 150 ),
		array( 6, 24, 50, 76, 102, 128, 154 ),
		array( 6, 28, 54, 80, 106, 132, 158 ),
		array( 6, 32, 58, 84, 110, 136, 162 ),
		array( 6, 26, 54, 82, 110, 138, 166 ),
		array( 6, 30, 58, 86, 114, 142, 170 ),
	);

	const G15      = ( 1 << 10 ) | ( 1 << 8 ) | ( 1 << 5 ) | ( 1 << 4 ) | ( 1 << 2 ) | ( 1 << 1 ) | ( 1 << 0 );
	const G18      = ( 1 << 12 ) | ( 1 << 11 ) | ( 1 << 10 ) | ( 1 << 9 ) | ( 1 << 8 ) | ( 1 << 5 ) | ( 1 << 2 ) | ( 1 << 0 );
	const G15_MASK = ( 1 << 14 ) | ( 1 << 12 ) | ( 1 << 10 ) | ( 1 << 4 ) | ( 1 << 1 );

	/**
	 * @param int $data Value.
	 * @return int
	 */
	public static function getBCHDigit( $data ) {
		$digit = 0;
		while ( 0 !== $data ) {
			$digit += 1;
			$data >>= 1;
		}
		return $digit;
	}

	/**
	 * @param int $data Level and mask (5 bits).
	 * @return int
	 */
	public static function getBCHTypeInfo( $data ) {
		$d = $data << 10;
		while ( self::getBCHDigit( $d ) - self::getBCHDigit( self::G15 ) >= 0 ) {
			$d ^= ( self::G15 << ( self::getBCHDigit( $d ) - self::getBCHDigit( self::G15 ) ) );
		}
		return ( ( $data << 10 ) | $d ) ^ self::G15_MASK;
	}

	/**
	 * @param int $data Version.
	 * @return int
	 */
	public static function getBCHTypeNumber( $data ) {
		$d = $data << 12;
		while ( self::getBCHDigit( $d ) - self::getBCHDigit( self::G18 ) >= 0 ) {
			$d ^= ( self::G18 << ( self::getBCHDigit( $d ) - self::getBCHDigit( self::G18 ) ) );
		}
		return ( $data << 12 ) | $d;
	}

	/**
	 * @param int $typeNumber Version.
	 * @return int[]
	 */
	public static function getPatternPosition( $typeNumber ) {
		return self::PATTERN_POSITION_TABLE[ $typeNumber - 1 ];
	}

	/**
	 * Mask function (JavaScript getMaskFunction()).
	 *
	 * @param int $maskPattern Mask 0-7.
	 * @param int $i           Row.
	 * @param int $j           Column.
	 * @return bool
	 * @throws \InvalidArgumentException Bad mask.
	 */
	public static function getMask( $maskPattern, $i, $j ) {
		switch ( $maskPattern ) {
			case 0:
				return 0 === ( $i + $j ) % 2;
			case 1:
				return 0 === $i % 2;
			case 2:
				return 0 === $j % 3;
			case 3:
				return 0 === ( $i + $j ) % 3;
			case 4:
				return 0 === ( intdiv( $i, 2 ) + intdiv( $j, 3 ) ) % 2;
			case 5:
				return 0 === ( $i * $j ) % 2 + ( $i * $j ) % 3;
			case 6:
				return 0 === ( ( $i * $j ) % 2 + ( $i * $j ) % 3 ) % 2;
			case 7:
				return 0 === ( ( $i * $j ) % 3 + ( $i + $j ) % 2 ) % 2;
			default:
				throw new \InvalidArgumentException( 'bad maskPattern:' . $maskPattern );
		}
	}

	/**
	 * @param int $errorCorrectLength Number of EC codewords.
	 * @return QRPolynomial
	 */
	public static function getErrorCorrectPolynomial( $errorCorrectLength ) {
		$a = new QRPolynomial( array( 1 ), 0 );
		for ( $i = 0; $i < $errorCorrectLength; $i++ ) {
			$a = $a->multiply( new QRPolynomial( array( 1, QRMath::gexp( $i ) ), 0 ) );
		}
		return $a;
	}

	/**
	 * Character count indicator length (byte mode).
	 *
	 * @param int $mode Mode.
	 * @param int $type Version.
	 * @return int
	 * @throws \InvalidArgumentException Bad mode or version.
	 */
	public static function getLengthInBits( $mode, $type ) {
		if ( QRCode::MODE_8BIT_BYTE !== $mode ) {
			throw new \InvalidArgumentException( 'mode:' . $mode );
		}
		if ( 1 <= $type && $type < 10 ) {
			return 8;  // 1 - 9.
		} elseif ( $type < 27 ) {
			return 16; // 10 - 26.
		} elseif ( $type < 41 ) {
			return 16; // 27 - 40.
		}
		throw new \InvalidArgumentException( 'type:' . $type );
	}

	/**
	 * Penalty score of a symbol (lower is better).
	 *
	 * @param QRCode $qrcode Symbol.
	 * @return int|float
	 */
	public static function getLostPoint( $qrcode ) {

		$moduleCount = $qrcode->getModuleCount();

		$lostPoint = 0;

		// LEVEL1.

		for ( $row = 0; $row < $moduleCount; $row++ ) {
			for ( $col = 0; $col < $moduleCount; $col++ ) {

				$sameCount = 0;
				$dark      = $qrcode->isDark( $row, $col );

				for ( $r = -1; $r <= 1; $r++ ) {

					if ( $row + $r < 0 || $moduleCount <= $row + $r ) {
						continue;
					}

					for ( $c = -1; $c <= 1; $c++ ) {

						if ( $col + $c < 0 || $moduleCount <= $col + $c ) {
							continue;
						}

						if ( 0 === $r && 0 === $c ) {
							continue;
						}

						if ( $dark === $qrcode->isDark( $row + $r, $col + $c ) ) {
							$sameCount += 1;
						}
					}
				}

				if ( $sameCount > 5 ) {
					$lostPoint += ( 3 + $sameCount - 5 );
				}
			}
		}

		// LEVEL2.

		for ( $row = 0; $row < $moduleCount - 1; $row++ ) {
			for ( $col = 0; $col < $moduleCount - 1; $col++ ) {
				$count = 0;
				if ( $qrcode->isDark( $row, $col ) ) {
					$count += 1;
				}
				if ( $qrcode->isDark( $row + 1, $col ) ) {
					$count += 1;
				}
				if ( $qrcode->isDark( $row, $col + 1 ) ) {
					$count += 1;
				}
				if ( $qrcode->isDark( $row + 1, $col + 1 ) ) {
					$count += 1;
				}
				if ( 0 === $count || 4 === $count ) {
					$lostPoint += 3;
				}
			}
		}

		// LEVEL3.

		for ( $row = 0; $row < $moduleCount; $row++ ) {
			for ( $col = 0; $col < $moduleCount - 6; $col++ ) {
				if ( $qrcode->isDark( $row, $col )
						&& ! $qrcode->isDark( $row, $col + 1 )
						&& $qrcode->isDark( $row, $col + 2 )
						&& $qrcode->isDark( $row, $col + 3 )
						&& $qrcode->isDark( $row, $col + 4 )
						&& ! $qrcode->isDark( $row, $col + 5 )
						&& $qrcode->isDark( $row, $col + 6 ) ) {
					$lostPoint += 40;
				}
			}
		}

		for ( $col = 0; $col < $moduleCount; $col++ ) {
			for ( $row = 0; $row < $moduleCount - 6; $row++ ) {
				if ( $qrcode->isDark( $row, $col )
						&& ! $qrcode->isDark( $row + 1, $col )
						&& $qrcode->isDark( $row + 2, $col )
						&& $qrcode->isDark( $row + 3, $col )
						&& $qrcode->isDark( $row + 4, $col )
						&& ! $qrcode->isDark( $row + 5, $col )
						&& $qrcode->isDark( $row + 6, $col ) ) {
					$lostPoint += 40;
				}
			}
		}

		// LEVEL4.

		$darkCount = 0;

		for ( $col = 0; $col < $moduleCount; $col++ ) {
			for ( $row = 0; $row < $moduleCount; $row++ ) {
				if ( $qrcode->isDark( $row, $col ) ) {
					$darkCount += 1;
				}
			}
		}

		$ratio      = abs( 100 * $darkCount / $moduleCount / $moduleCount - 50 ) / 5;
		$lostPoint += $ratio * 10;

		return $lostPoint;
	}
}

/**
 * GF(256) arithmetic.
 */
final class QRMath {

	/** @var int[]|null */
	private static $expTable = null;
	/** @var int[]|null */
	private static $logTable = null;

	private static function init() {
		if ( null !== self::$expTable ) {
			return;
		}
		$exp = array_fill( 0, 256, 0 );
		$log = array_fill( 0, 256, 0 );
		for ( $i = 0; $i < 8; $i++ ) {
			$exp[ $i ] = 1 << $i;
		}
		for ( $i = 8; $i < 256; $i++ ) {
			$exp[ $i ] = $exp[ $i - 4 ]
				^ $exp[ $i - 5 ]
				^ $exp[ $i - 6 ]
				^ $exp[ $i - 8 ];
		}
		for ( $i = 0; $i < 255; $i++ ) {
			$log[ $exp[ $i ] ] = $i;
		}
		self::$expTable = $exp;
		self::$logTable = $log;
	}

	/**
	 * @param int $n Value 1-255.
	 * @return int
	 * @throws \InvalidArgumentException For n < 1.
	 */
	public static function glog( $n ) {
		self::init();
		if ( $n < 1 ) {
			throw new \InvalidArgumentException( 'glog(' . $n . ')' );
		}
		return self::$logTable[ $n ];
	}

	/**
	 * @param int $n Exponent.
	 * @return int
	 */
	public static function gexp( $n ) {
		self::init();
		while ( $n < 0 ) {
			$n += 255;
		}
		while ( $n >= 256 ) {
			$n -= 255;
		}
		return self::$expTable[ $n ];
	}
}

/**
 * Polynomial over GF(256).
 */
final class QRPolynomial {

	/** @var int[] */
	private $num;

	/**
	 * @param int[] $num   Coefficients.
	 * @param int   $shift Extra zero terms.
	 */
	public function __construct( $num, $shift ) {
		$offset = 0;
		$len    = count( $num );
		while ( $offset < $len && 0 === $num[ $offset ] ) {
			$offset += 1;
		}
		$size      = $len - $offset + $shift;
		$this->num = $size > 0 ? array_fill( 0, $size, 0 ) : array();
		for ( $i = 0; $i < $len - $offset; $i++ ) {
			$this->num[ $i ] = $num[ $i + $offset ];
		}
	}

	/**
	 * @param int $index Index.
	 * @return int
	 */
	public function getAt( $index ) {
		return $this->num[ $index ];
	}

	/**
	 * @return int
	 */
	public function getLength() {
		return count( $this->num );
	}

	/**
	 * @param QRPolynomial $e Other.
	 * @return QRPolynomial
	 */
	public function multiply( $e ) {

		$num = array_fill( 0, $this->getLength() + $e->getLength() - 1, 0 );

		for ( $i = 0; $i < $this->getLength(); $i++ ) {
			for ( $j = 0; $j < $e->getLength(); $j++ ) {
				$num[ $i + $j ] ^= QRMath::gexp( QRMath::glog( $this->getAt( $i ) ) + QRMath::glog( $e->getAt( $j ) ) );
			}
		}

		return new QRPolynomial( $num, 0 );
	}

	/**
	 * @param QRPolynomial $e Divisor.
	 * @return QRPolynomial Remainder.
	 */
	public function mod( $e ) {

		// Iterative form of the original recursive call (same steps).
		$p = $this;
		while ( $p->getLength() - $e->getLength() >= 0 ) {

			$ratio = QRMath::glog( $p->getAt( 0 ) ) - QRMath::glog( $e->getAt( 0 ) );

			$num = array();
			for ( $i = 0; $i < $p->getLength(); $i++ ) {
				$num[ $i ] = $p->getAt( $i );
			}

			for ( $i = 0; $i < $e->getLength(); $i++ ) {
				$num[ $i ] ^= QRMath::gexp( QRMath::glog( $e->getAt( $i ) ) + $ratio );
			}

			$p = new QRPolynomial( $num, 0 );
		}
		return $p;
	}
}

/**
 * Reed-Solomon block layout.
 */
final class QRRSBlock {

	const RS_BLOCK_TABLE = array(

		// L
		// M
		// Q
		// H

		// 1
		array( 1, 26, 19 ),
		array( 1, 26, 16 ),
		array( 1, 26, 13 ),
		array( 1, 26, 9 ),

		// 2
		array( 1, 44, 34 ),
		array( 1, 44, 28 ),
		array( 1, 44, 22 ),
		array( 1, 44, 16 ),

		// 3
		array( 1, 70, 55 ),
		array( 1, 70, 44 ),
		array( 2, 35, 17 ),
		array( 2, 35, 13 ),

		// 4
		array( 1, 100, 80 ),
		array( 2, 50, 32 ),
		array( 2, 50, 24 ),
		array( 4, 25, 9 ),

		// 5
		array( 1, 134, 108 ),
		array( 2, 67, 43 ),
		array( 2, 33, 15, 2, 34, 16 ),
		array( 2, 33, 11, 2, 34, 12 ),

		// 6
		array( 2, 86, 68 ),
		array( 4, 43, 27 ),
		array( 4, 43, 19 ),
		array( 4, 43, 15 ),

		// 7
		array( 2, 98, 78 ),
		array( 4, 49, 31 ),
		array( 2, 32, 14, 4, 33, 15 ),
		array( 4, 39, 13, 1, 40, 14 ),

		// 8
		array( 2, 121, 97 ),
		array( 2, 60, 38, 2, 61, 39 ),
		array( 4, 40, 18, 2, 41, 19 ),
		array( 4, 40, 14, 2, 41, 15 ),

		// 9
		array( 2, 146, 116 ),
		array( 3, 58, 36, 2, 59, 37 ),
		array( 4, 36, 16, 4, 37, 17 ),
		array( 4, 36, 12, 4, 37, 13 ),

		// 10
		array( 2, 86, 68, 2, 87, 69 ),
		array( 4, 69, 43, 1, 70, 44 ),
		array( 6, 43, 19, 2, 44, 20 ),
		array( 6, 43, 15, 2, 44, 16 ),

		// 11
		array( 4, 101, 81 ),
		array( 1, 80, 50, 4, 81, 51 ),
		array( 4, 50, 22, 4, 51, 23 ),
		array( 3, 36, 12, 8, 37, 13 ),

		// 12
		array( 2, 116, 92, 2, 117, 93 ),
		array( 6, 58, 36, 2, 59, 37 ),
		array( 4, 46, 20, 6, 47, 21 ),
		array( 7, 42, 14, 4, 43, 15 ),

		// 13
		array( 4, 133, 107 ),
		array( 8, 59, 37, 1, 60, 38 ),
		array( 8, 44, 20, 4, 45, 21 ),
		array( 12, 33, 11, 4, 34, 12 ),

		// 14
		array( 3, 145, 115, 1, 146, 116 ),
		array( 4, 64, 40, 5, 65, 41 ),
		array( 11, 36, 16, 5, 37, 17 ),
		array( 11, 36, 12, 5, 37, 13 ),

		// 15
		array( 5, 109, 87, 1, 110, 88 ),
		array( 5, 65, 41, 5, 66, 42 ),
		array( 5, 54, 24, 7, 55, 25 ),
		array( 11, 36, 12, 7, 37, 13 ),

		// 16
		array( 5, 122, 98, 1, 123, 99 ),
		array( 7, 73, 45, 3, 74, 46 ),
		array( 15, 43, 19, 2, 44, 20 ),
		array( 3, 45, 15, 13, 46, 16 ),

		// 17
		array( 1, 135, 107, 5, 136, 108 ),
		array( 10, 74, 46, 1, 75, 47 ),
		array( 1, 50, 22, 15, 51, 23 ),
		array( 2, 42, 14, 17, 43, 15 ),

		// 18
		array( 5, 150, 120, 1, 151, 121 ),
		array( 9, 69, 43, 4, 70, 44 ),
		array( 17, 50, 22, 1, 51, 23 ),
		array( 2, 42, 14, 19, 43, 15 ),

		// 19
		array( 3, 141, 113, 4, 142, 114 ),
		array( 3, 70, 44, 11, 71, 45 ),
		array( 17, 47, 21, 4, 48, 22 ),
		array( 9, 39, 13, 16, 40, 14 ),

		// 20
		array( 3, 135, 107, 5, 136, 108 ),
		array( 3, 67, 41, 13, 68, 42 ),
		array( 15, 54, 24, 5, 55, 25 ),
		array( 15, 43, 15, 10, 44, 16 ),

		// 21
		array( 4, 144, 116, 4, 145, 117 ),
		array( 17, 68, 42 ),
		array( 17, 50, 22, 6, 51, 23 ),
		array( 19, 46, 16, 6, 47, 17 ),

		// 22
		array( 2, 139, 111, 7, 140, 112 ),
		array( 17, 74, 46 ),
		array( 7, 54, 24, 16, 55, 25 ),
		array( 34, 37, 13 ),

		// 23
		array( 4, 151, 121, 5, 152, 122 ),
		array( 4, 75, 47, 14, 76, 48 ),
		array( 11, 54, 24, 14, 55, 25 ),
		array( 16, 45, 15, 14, 46, 16 ),

		// 24
		array( 6, 147, 117, 4, 148, 118 ),
		array( 6, 73, 45, 14, 74, 46 ),
		array( 11, 54, 24, 16, 55, 25 ),
		array( 30, 46, 16, 2, 47, 17 ),

		// 25
		array( 8, 132, 106, 4, 133, 107 ),
		array( 8, 75, 47, 13, 76, 48 ),
		array( 7, 54, 24, 22, 55, 25 ),
		array( 22, 45, 15, 13, 46, 16 ),

		// 26
		array( 10, 142, 114, 2, 143, 115 ),
		array( 19, 74, 46, 4, 75, 47 ),
		array( 28, 50, 22, 6, 51, 23 ),
		array( 33, 46, 16, 4, 47, 17 ),

		// 27
		array( 8, 152, 122, 4, 153, 123 ),
		array( 22, 73, 45, 3, 74, 46 ),
		array( 8, 53, 23, 26, 54, 24 ),
		array( 12, 45, 15, 28, 46, 16 ),

		// 28
		array( 3, 147, 117, 10, 148, 118 ),
		array( 3, 73, 45, 23, 74, 46 ),
		array( 4, 54, 24, 31, 55, 25 ),
		array( 11, 45, 15, 31, 46, 16 ),

		// 29
		array( 7, 146, 116, 7, 147, 117 ),
		array( 21, 73, 45, 7, 74, 46 ),
		array( 1, 53, 23, 37, 54, 24 ),
		array( 19, 45, 15, 26, 46, 16 ),

		// 30
		array( 5, 145, 115, 10, 146, 116 ),
		array( 19, 75, 47, 10, 76, 48 ),
		array( 15, 54, 24, 25, 55, 25 ),
		array( 23, 45, 15, 25, 46, 16 ),

		// 31
		array( 13, 145, 115, 3, 146, 116 ),
		array( 2, 74, 46, 29, 75, 47 ),
		array( 42, 54, 24, 1, 55, 25 ),
		array( 23, 45, 15, 28, 46, 16 ),

		// 32
		array( 17, 145, 115 ),
		array( 10, 74, 46, 23, 75, 47 ),
		array( 10, 54, 24, 35, 55, 25 ),
		array( 19, 45, 15, 35, 46, 16 ),

		// 33
		array( 17, 145, 115, 1, 146, 116 ),
		array( 14, 74, 46, 21, 75, 47 ),
		array( 29, 54, 24, 19, 55, 25 ),
		array( 11, 45, 15, 46, 46, 16 ),

		// 34
		array( 13, 145, 115, 6, 146, 116 ),
		array( 14, 74, 46, 23, 75, 47 ),
		array( 44, 54, 24, 7, 55, 25 ),
		array( 59, 46, 16, 1, 47, 17 ),

		// 35
		array( 12, 151, 121, 7, 152, 122 ),
		array( 12, 75, 47, 26, 76, 48 ),
		array( 39, 54, 24, 14, 55, 25 ),
		array( 22, 45, 15, 41, 46, 16 ),

		// 36
		array( 6, 151, 121, 14, 152, 122 ),
		array( 6, 75, 47, 34, 76, 48 ),
		array( 46, 54, 24, 10, 55, 25 ),
		array( 2, 45, 15, 64, 46, 16 ),

		// 37
		array( 17, 152, 122, 4, 153, 123 ),
		array( 29, 74, 46, 14, 75, 47 ),
		array( 49, 54, 24, 10, 55, 25 ),
		array( 24, 45, 15, 46, 46, 16 ),

		// 38
		array( 4, 152, 122, 18, 153, 123 ),
		array( 13, 74, 46, 32, 75, 47 ),
		array( 48, 54, 24, 14, 55, 25 ),
		array( 42, 45, 15, 32, 46, 16 ),

		// 39
		array( 20, 147, 117, 4, 148, 118 ),
		array( 40, 75, 47, 7, 76, 48 ),
		array( 43, 54, 24, 22, 55, 25 ),
		array( 10, 45, 15, 67, 46, 16 ),

		// 40
		array( 19, 148, 118, 6, 149, 119 ),
		array( 18, 75, 47, 31, 76, 48 ),
		array( 34, 54, 24, 34, 55, 25 ),
		array( 20, 45, 15, 61, 46, 16 ),
	);

	/** @var int */
	public $totalCount;
	/** @var int */
	public $dataCount;

	/**
	 * @param int $totalCount Codewords in the block.
	 * @param int $dataCount  Data codewords in the block.
	 */
	public function __construct( $totalCount, $dataCount ) {
		$this->totalCount = $totalCount;
		$this->dataCount  = $dataCount;
	}

	/**
	 * @param int $typeNumber           Version.
	 * @param int $errorCorrectionLevel Level.
	 * @return int[]
	 * @throws \InvalidArgumentException Bad version or level.
	 */
	private static function getRsBlockTable( $typeNumber, $errorCorrectionLevel ) {
		if ( $typeNumber < 1 || $typeNumber > 40 ) {
			throw new \InvalidArgumentException( 'bad rs block @ typeNumber:' . $typeNumber );
		}
		switch ( $errorCorrectionLevel ) {
			case QRCode::ECL_L:
				return self::RS_BLOCK_TABLE[ ( $typeNumber - 1 ) * 4 + 0 ];
			case QRCode::ECL_M:
				return self::RS_BLOCK_TABLE[ ( $typeNumber - 1 ) * 4 + 1 ];
			case QRCode::ECL_Q:
				return self::RS_BLOCK_TABLE[ ( $typeNumber - 1 ) * 4 + 2 ];
			case QRCode::ECL_H:
				return self::RS_BLOCK_TABLE[ ( $typeNumber - 1 ) * 4 + 3 ];
			default:
				throw new \InvalidArgumentException( 'bad rs block @ errorCorrectionLevel:' . $errorCorrectionLevel );
		}
	}

	/**
	 * @param int $typeNumber           Version.
	 * @param int $errorCorrectionLevel Level.
	 * @return QRRSBlock[]
	 */
	public static function getRSBlocks( $typeNumber, $errorCorrectionLevel ) {

		$rsBlock = self::getRsBlockTable( $typeNumber, $errorCorrectionLevel );
		$length  = intdiv( count( $rsBlock ), 3 );
		$list    = array();

		for ( $i = 0; $i < $length; $i++ ) {

			$count      = $rsBlock[ $i * 3 + 0 ];
			$totalCount = $rsBlock[ $i * 3 + 1 ];
			$dataCount  = $rsBlock[ $i * 3 + 2 ];

			for ( $j = 0; $j < $count; $j++ ) {
				$list[] = new QRRSBlock( $totalCount, $dataCount );
			}
		}

		return $list;
	}
}

/**
 * Bit buffer.
 */
final class QRBitBuffer {

	/** @var int[] */
	private $buffer = array();
	/** @var int */
	private $length = 0;

	/**
	 * @return int[]
	 */
	public function getBuffer() {
		return $this->buffer;
	}

	/**
	 * @param int $index Bit index.
	 * @return bool
	 */
	public function getAt( $index ) {
		$bufIndex = intdiv( $index, 8 );
		return 1 === ( ( $this->buffer[ $bufIndex ] >> ( 7 - $index % 8 ) ) & 1 );
	}

	/**
	 * @param int $num    Value.
	 * @param int $length Bits.
	 */
	public function put( $num, $length ) {
		for ( $i = 0; $i < $length; $i++ ) {
			$this->putBit( 1 === ( ( $num >> ( $length - $i - 1 ) ) & 1 ) );
		}
	}

	/**
	 * @return int
	 */
	public function getLengthInBits() {
		return $this->length;
	}

	/**
	 * @param bool $bit Bit.
	 */
	public function putBit( $bit ) {

		$bufIndex = intdiv( $this->length, 8 );
		if ( count( $this->buffer ) <= $bufIndex ) {
			$this->buffer[] = 0;
		}

		if ( $bit ) {
			$this->buffer[ $bufIndex ] |= ( 0x80 >> ( $this->length % 8 ) );
		}

		$this->length += 1;
	}
}

/**
 * 8-bit byte segment (the string's bytes as they are; UTF-8 stays UTF-8).
 */
final class QR8BitByte {

	/** @var int[] */
	private $bytes = array();

	/**
	 * @param string $data Bytes.
	 */
	public function __construct( $data ) {
		$len = strlen( $data );
		for ( $i = 0; $i < $len; $i++ ) {
			$this->bytes[] = ord( $data[ $i ] ) & 0xff;
		}
	}

	/**
	 * @return int
	 */
	public function getMode() {
		return QRCode::MODE_8BIT_BYTE;
	}

	/**
	 * @return int
	 */
	public function getLength() {
		return count( $this->bytes );
	}

	/**
	 * @param QRBitBuffer $buffer Buffer.
	 */
	public function write( $buffer ) {
		$count = count( $this->bytes );
		for ( $i = 0; $i < $count; $i++ ) {
			$buffer->put( $this->bytes[ $i ], 8 );
		}
	}
}
