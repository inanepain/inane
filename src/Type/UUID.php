<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Type
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\Type;

use function strlen;
use function chr;
use function hexdec;
use function md5;
use function sprintf;
use function substr;
use function mt_rand;
use function str_replace;
use function sha1;
use function preg_match;

/**
 * UUID
 *
 * generates VALID RFC 4211 COMPLIANT Universally Unique IDentifiers (UUID) version 3, 4 and 5.
 *
 * @package Inane\Type
 * @version 1.0.0
 */
class UUID {

	/**
	 * Generates a named based uuid
	 *
	 * Given the same input the output will also be the same.
	 *
	 * @param string $namespace another uuid
	 * @param string $name text
	 *
	 * @return null|string
	 */
	public static function v3(string $namespace, string $name): ?string {
		if (! self::isValid($namespace)) return null;

		// Get hexadecimal components of namespace
		$nhex = str_replace([
			'-',
			'{',
			'}'
		], '', $namespace);

		// Binary Value
		$nstr = '';

		// Convert Namespace UUID to bits
		for($i = 0; $i < strlen($nhex); $i += 2) {
			$nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
		}

		// Calculate hash value
		$hash = md5($nstr . $name);

		return sprintf('%08s-%04s-%04x-%04x-%12s',

				// 32 bits for "time_low"
				substr($hash, 0, 8),

				// 16 bits for "time_mid"
				substr($hash, 8, 4),

				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 3
				(hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

				// 48 bits for "node"
				substr($hash, 20, 12));
	}

	/**
	 * Generates a pseudo-random uuid
	 *
	 * @return string
	 */
	public static function v4(): string {
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

				// 32 bits for "time_low"
				mt_rand(0, 0xffff), mt_rand(0, 0xffff),

				// 16 bits for "time_mid"
				mt_rand(0, 0xffff),

				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 4
				mt_rand(0, 0x0fff) | 0x4000,

				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				mt_rand(0, 0x3fff) | 0x8000,

				// 48 bits for "node"
				mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
	}

	/**
	 * Generates a named based uuid
	 *
	 * Given the same input the output will also be the same.
	 *
	 * @param string $namespace another uuid
	 * @param string $name text
	 *
	 * @return null|string
	 */
	public static function v5(string $namespace, string $name): ?string {
		if (! self::isValid($namespace)) return null;

		// Get hexadecimal components of namespace
		$nhex = str_replace([
			'-',
			'{',
			'}'
		], '', $namespace);

		// Binary Value
		$nstr = '';

		// Convert Namespace UUID to bits
		for($i = 0; $i < strlen($nhex); $i += 2) {
			$nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
		}

		// Calculate hash value
		$hash = sha1($nstr . $name);

		return sprintf('%08s-%04s-%04x-%04x-%12s',

				// 32 bits for "time_low"
				substr($hash, 0, 8),

				// 16 bits for "time_mid"
				substr($hash, 8, 4),

				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 5
				(hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

				// 48 bits for "node"
				substr($hash, 20, 12));
	}

	/**
	 * Test if a uuid is valid
	 *
	 * @param string $uuid
	 * @return boolean
	 */
	public static function isValid(string $uuid): bool {
		return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' . '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
	}
}
