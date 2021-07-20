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

namespace Inane\Option;

/**
 * IpTrait
 *
 * Client IP address
 *
 * @package Inane\Option
 * @version 1.0.0
 */
trait IpTrait {

    /**
	 * @var \Laminas\Log\Logger the log service
	 */
    protected $ipaddress;

	/**
	 * Get IP Address
	 *
	 * @return string the client ip
	 */
	public function getIp(): ?string {
		if (null === $this->ipaddress) {
			if (! empty($_SERVER['HTTP_CLIENT_IP'])) $this->ipaddress = $_SERVER['HTTP_CLIENT_IP']; // ip from share internet
			elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $this->ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR']; // ip pass from proxy
			else $this->ipaddress = $_SERVER['REMOTE_ADDR'];
		}
		return $this->ipaddress;
	}
}