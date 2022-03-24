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
 * @version 1.0.0
 */
class Net {
    /**
     * Test if a specified port is open
     *
     * If the target ip does not exist, it classifies as port not open.
     *
     * @param string $ip of machine to use
     * @param int $port to test
     * @param array|null $error if supplied, an occurring error's no and msg will be assigned to $error
     *
     * @return bool is open
     */
    public static function portOpen(string $ip, int $port, ? array &$error = null): bool {
        $fp = @fsockopen($ip, $port, $errno, $errstr, 0.1);
        if (!$fp) {
            if (!is_null($error)) {
                $error['ip'] = $ip;
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

}
