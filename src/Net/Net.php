<?php

/**
 * Inane
 *
 * Tools
 *
 * PHP version 8.1
 *
 * @package Owner\Project
 * @author Philip Michael Raab<peep@inane.co.za>
 *
 * @license MIT
 * @license https://raw.githubusercontent.com/CathedralCode/Builder/develop/LICENSE MIT License
 *
 * @copyright 2013-2022 Philip Michael Raab <peep@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Net;

use function array_filter;
use function fclose;
use function fsockopen;
use function is_null;
use const false;
use const null;
use const true;

/**
 * Net
 *
 * Some simple network helpers
 *
 * @package Inane\Tools
 *
 * @version 1.1.0
 */
class Net {
    /**
     * Test if a specified port is open
     *
     * If the target ip does not exist, it classifies as port not open.
     *
     * @param string        $ipV4   of machine to use
     * @param int           $port   to test
     * @param array|null    $error  if supplied, an occurring error's no and msg will be assigned to $error
     *
     * @return bool is open
     */
    public static function portOpen(string $ipV4, int $port, ?array &$error = null): bool {
        $fp = @fsockopen($ipV4, $port, $errno, $errstr, 0.1);
        if (!$fp) {
            if (!is_null($error)) {
                $error['ip'] = $ipV4;
                $error['port'] = $port;
                $error['error'] = [
                    'no' => $errno,
                    'message' => $errstr,
                ];
            }
            return false;
        }

        fclose($fp);
        return true;
    }

    /**
     * Test a range of ports for open status
     *
     * If the target ip does not exist, it classifies as ports not open.
     *
     * @since 1.1.0
     *
     * @param string        $ipV4   of machine to use
     * @param int[]         $ports  to test
     * @param array|null    $error  if supplied, an occurring error's no and msg will be assigned to $error
     *
     * @return array of open ports
     */
    public static function portsOpen(string $ipV4, array $ports, ?array &$error = null): array {
        return array_filter($ports, function($port) use ($ipV4, &$error) {
            $err = is_null($error) ? null : [];
            $open = static::portOpen($ipV4, $port, $err);

            $error[$port] = $err;

            return $open;
        });
    }
}
