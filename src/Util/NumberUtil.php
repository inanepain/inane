<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 8
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Util
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Util;

use function array_sum;
use function count;
use function in_array;
use function str_split;

/**
 * NumberUtil
 *
 * @package Inane\Util
 * @version 0.1.0
 */
class NumberUtil {
    /**
     * Reduce number
     *
     * By adding the individual digits until required length reached or an exception occurs.
     *
     * @param int $number starting number
     * @param int $length limit
     * @param array $exceptions array of valid numbers that exceed limit
     *
     * @return int the number
     */
    public static function reduceNumber(int $number, int $length = 1, array $exceptions = []): int {
        while (count(str_split("$number")) > $length && !in_array($number, $exceptions)) $number = array_sum(str_split("$number"));
        return $number;
    }
}
