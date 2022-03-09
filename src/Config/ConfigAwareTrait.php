<?php

/**
 * Inane Tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 8.1
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Config
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2022 Philip Michael Raab <philip@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Config;

use function is_array;

/**
 * ConfigAwareTrait
 *
 * @package Inane\Config
 * @version 1.0.0
 */
trait ConfigAwareTrait {
	/**
	 * Configuration
	 *
	 * @var array|Options
	 */
	protected array|Options $config;

	/**
	 * {@inheritDoc}
	 * @see \Inane\Config\ConfigAwareInterface::setConfig()
	 */
	public function setConfig(array|Options $config): void {
		if (is_array($config)) $this->config = new Options($config);
		else $this->config = $config;
	}
}
