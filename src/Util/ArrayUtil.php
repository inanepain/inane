<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Forms\Options
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\Util;

use function count;
use function array_shift;
use function array_pop;
use function is_array;
use function in_array;
use function array_key_exists;

/**
 * ArrayUtil
 *
 * @package Inane\Util
 * @version 0.1.0
 */
class ArrayUtil {
	/**
	 * merges array 2+ into first array with decreasing importance
	 * so only unset keys are assigned values
	 *
	 * 1 array in = same array out
	 * 0 array in = empty array out
	 *
	 * @param array ...$arrays
	 * @return array
	 */
	public static function merge(array ...$arrays): array {
		if (count($arrays) < 2) return array_shift($arrays) ?: $arrays;
		$merged = array_shift($arrays);

		while ($array = array_pop($arrays)) {
			foreach ($array as $key => &$value) {
				if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
					$merged[$key] = self::merge($merged[$key], $value);
				} else
					if (! array_key_exists($key, $merged) || in_array($merged[$key], [
						'',
						null,
						false
					])) {
						$merged[$key] = $value;
					}
			}
		}
		return $merged;
	}
}
