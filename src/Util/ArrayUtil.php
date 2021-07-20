<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Util
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */
/*
$data = [
    'people' => [
        'philip' => [
            'age' => 16,
            'firstName' => 'Philip',
            'lastName' => 'Raab',
        ],
    ],
];

echo ArrayUtil::readWithPath($data, 'people/philip/firstName') . PHP_EOL;
var_export(ArrayUtil::writeWithPath($data, 'people/philip/middleName=Michael'));

ArrayUtil::$pathAssignor=':';
ArrayUtil::$pathSeparator='.';

ArrayUtil::writeWithPath($data, 'people.philip.colour:Purple');

ArrayUtil::$pathSeparator='->';
echo ArrayUtil::readWithPath($data, 'people->philip->colour') . PHP_EOL;
*/

namespace Inane\Util;

use function array_key_exists;
use function array_pop;
use function array_shift;
use function count;
use function explode;
use function in_array;
use function is_array;

/**
 * ArrayUtil
 *
 * @package Inane\Util
 * @version 0.2.0
 */
class ArrayUtil {
    /**
     * Path Separator 
     */
    public static string $pathSeparator = '/';

    /**
     * Path Assignor
     */
    public static string $pathAssignor = '=';

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
					if (!array_key_exists($key, $merged) || in_array($merged[$key], [
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

    /**
     * get object path value
     * 
     * $data = ['people' => ['bob' => [ 'age' => 7, 'firstName' => 'Bob']]];
     * echo ArrayUtil::readWithPath($data, 'people/bob/firstName') . PHP_EOL;
     *  => Bob
     *
     * @param array $array array to query
     * @param string $path path to get
     * @param null|string $separator path separator char (default: /)
     * 
     * @return mixed path value
     */
    public static function readWithPath(array $array, string $path, ?string $separator = null): mixed {
        $explodedPath = explode($separator ?? static::$pathSeparator, $path);

        $temp = &$array;
        foreach ($explodedPath as $key) {
            if (array_key_exists($key, $temp)) $temp = &$temp[$key];
            else return null;
        }

        return $temp;
    }

    /**
     * Set value using path assignment
     * 
     * input: contacts/personal/bob/age=16
     * 
     * $data = ['people' => ['bob' => [ 'age' => 7, 'firstName' => 'Bob']]];
     * var_export(ArrayUtil::writeWithPath($data, 'people/bob/lastName=Tail'));
     *  => ['people' => ['bob' => [ 'age' => 7, 'firstName' => 'Bob', 'lastName' => 'Tail']]];
     * 
     * @param array $array array to update
     * @param string $input assignment string
     * @param null|string $separator path separator character (default: /)
     * @param null|string $assignor assignment character (default: =)
     * 
     * @return array updated array 
     */
    public static  function writeWithPath(array &$array, string $input, ?string $separator = null, ?string $assignor = null): array {
        list($path, $value) = explode($assignor ?? static::$pathAssignor, $input);

        $explodedPath = explode($separator ?? static::$pathSeparator, $path);

        $temp = &$array;
        foreach ($explodedPath as $key) $temp = &$temp[$key];
        $temp = $value;
        unset($temp);

        return $array;
    }
}
