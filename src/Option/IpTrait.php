<?php
/**
 * This file is part of the InaneClasses package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Forms\Options
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Option;

use function array_key_exists;

/**
 * IpTrait
 *
 * Client IP address
 *
 * @package Inane\Option
 * @version 1.0.1
 */
trait IpTrait {

    /**
     * IP Address
     */
    protected string $ipAddress;

    /**
     * Get IP Address
     *
     * @return string the client ip
     */
    public function getIp(): ?string {
        if (!isset($this->ipAddress)) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) $this->ipAddress = $_SERVER['HTTP_CLIENT_IP']; // ip from share internet
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $this->ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR']; // ip pass from proxy
            else if (array_key_exists('REMOTE_ADDR', $_SERVER)) $this->ipAddress = $_SERVER['REMOTE_ADDR'];
            else $this->ipAddress = '127.0.0.1';
        }
        return $this->ipAddress;
    }
}
